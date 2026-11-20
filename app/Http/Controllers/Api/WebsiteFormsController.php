<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseClass;
use App\Http\Controllers\Controller;
use App\Models\AppSettings;
use App\Models\City;
use App\Models\Country;
use App\Models\Location;
use App\Models\StorageUnit;
use App\Models\Warehouse;
use App\Repo\AddonClass;
use App\Repo\Interfaces\LeadInterface;
use App\Repo\StorageTypeClass;
use App\Repo\StorageUnitLevelClass;
use App\Repo\StorageUnitSizeClass;
use App\Repo\TermLengthClass;
use App\Services\InquiryService;
use App\Services\InsurancePricingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

/**
 * Public (no login) JSON API for the mobile app, mirroring the website forms:
 * the quote / contact form, the storage unit search and the reservation form.
 * See docs/WEBSITE-FORMS-API.md.
 */
class WebsiteFormsController extends Controller
{
    /**
     * Everything the app needs to draw the forms: dropdowns, checkboxes, terms.
     */
    public function formOptions()
    {
        $termsSetting = AppSettings::query()->orderBy('id')->get(['id', 'value'])->get(9);

        return ResponseClass::success([
            'inquiry_storage_types' => InquiryService::STORAGE_TYPES,
            'countries' => $this->countryRows(),
            'storage_levels' => (new StorageUnitLevelClass())->getStorageLevel()
                ->map(fn ($r) => ['id' => $r->id, 'name' => $r->name])->values(),
            'storage_types' => (new StorageTypeClass())->getStorageType()
                ->map(fn ($r) => ['id' => $r->id, 'name' => $r->name])->values(),
            'storage_sizes' => (new StorageUnitSizeClass())->getStorageSize()
                ->map(fn ($r) => [
                    'id' => $r->id,
                    'name' => $r->unit_type_name,
                    'width' => $r->width,
                    'length' => $r->length,
                    'height' => $r->height,
                ])->values(),
            'term_lengths' => (new TermLengthClass())->getAllTermLength()
                ->map(fn ($r) => [
                    'id' => $r->id,
                    'title' => $r->title,
                    'term_period' => $r->term_period,
                    'discount_percentage' => $r->discount_percentage,
                    'description' => $r->description,
                ])->values(),
            'addons' => (new AddonClass())->getStorageUnitAddon()
                ->map(fn ($r) => ['id' => $r->id, 'name' => $r->name, 'price' => $r->price])->values(),
            'insurance_packages' => app(InsurancePricingService::class)->activePackages()
                ->map(fn ($r) => [
                    'id' => $r->id,
                    'name' => $r->name,
                    'monthly_amount' => $r->monthly_amount,
                    'cover' => $r->cover,
                ])->values(),
            'reservation_terms_html' => $termsSetting->value ?? null,
        ]);
    }

    public function countries()
    {
        return ResponseClass::success($this->countryRows());
    }

    public function cities(Request $request)
    {
        $v = Validator::make($request->all(), ['country_id' => 'required|integer']);
        if ($v->fails()) {
            return ResponseClass::error('Validation failed.', 422, $v->errors());
        }

        $rows = City::query()
            ->where('country_id', $request->integer('country_id'))
            ->where('is_deleted', 0)
            ->orderBy('city_name')
            ->get(['id', 'city_name', 'country_id'])
            ->map(fn ($r) => ['id' => $r->id, 'name' => $r->city_name, 'country_id' => $r->country_id])
            ->values();

        return ResponseClass::success($rows);
    }

    public function locations(Request $request)
    {
        $v = Validator::make($request->all(), ['city_id' => 'required|integer']);
        if ($v->fails()) {
            return ResponseClass::error('Validation failed.', 422, $v->errors());
        }

        $rows = Location::query()
            ->where('city_id', $request->integer('city_id'))
            ->where('is_deleted', 0)
            ->orderBy('id', 'DESC')
            ->get()
            ->map(fn ($r) => [
                'id' => $r->id,
                'name' => $r->loc_name,
                'city_id' => $r->city_id,
                'lat' => $r->lat,
                'lng' => $r->lang,
            ])
            ->values();

        return ResponseClass::success($rows);
    }

    public function warehouses(Request $request)
    {
        $v = Validator::make($request->all(), ['location_id' => 'required|integer']);
        if ($v->fails()) {
            return ResponseClass::error('Validation failed.', 422, $v->errors());
        }

        $rows = Warehouse::query()
            ->where('loc_id', $request->integer('location_id'))
            ->where('is_deleted', 0)
            ->orderBy('id', 'DESC')
            ->get(['id', 'name', 'loc_id', 'description'])
            ->map(fn ($r) => [
                'id' => $r->id,
                'name' => $r->name,
                'location_id' => $r->loc_id,
                'description' => $r->description,
            ])
            ->values();

        return ResponseClass::success($rows);
    }

    /**
     * Vacant storage units, same rules as the website booking page.
     */
    public function storageUnits(Request $request)
    {
        $v = Validator::make($request->all(), [
            'country_id' => 'nullable|integer',
            'city_id' => 'nullable|integer',
            'location_id' => 'nullable|integer',
            'warehouse_id' => 'nullable|integer',
            'level_ids' => 'nullable|array',
            'level_ids.*' => 'integer',
            'type_ids' => 'nullable|array',
            'type_ids.*' => 'integer',
            'size_id' => 'nullable|integer',
        ]);
        if ($v->fails()) {
            return ResponseClass::error('Validation failed.', 422, $v->errors());
        }

        $qry = $this->unitQuery()->availableForBooking();

        if ($request->filled('warehouse_id')) {
            $qry->where('wh_id', $request->integer('warehouse_id'));
        }
        if ($request->filled('location_id')) {
            $qry->whereHas('warehouse', fn ($q) => $q->where('loc_id', $request->integer('location_id')));
        }
        if ($request->filled('city_id')) {
            $qry->whereHas('warehouse.loc', fn ($q) => $q->where('city_id', $request->integer('city_id')));
        }
        if ($request->filled('country_id')) {
            $qry->whereHas('warehouse.loc.city', fn ($q) => $q->where('country_id', $request->integer('country_id')));
        }
        if ($request->filled('level_ids')) {
            $qry->whereIn('slevel_id', array_map('intval', (array) $request->input('level_ids')));
        }
        if ($request->filled('type_ids')) {
            $qry->whereIn('stype_id', array_map('intval', (array) $request->input('type_ids')));
        }
        if ($request->filled('size_id')) {
            $qry->where('ssize_id', $request->integer('size_id'));
        }

        $rows = $qry->orderBy('id', 'DESC')->get()->map(fn ($u) => $this->unitRow($u))->values();

        return ResponseClass::success($rows);
    }

    public function storageUnit($id)
    {
        $unit = $this->unitQuery()->availableForBooking()->find((int) $id);
        if (!$unit) {
            return ResponseClass::error('Storage unit not found or not available.', 404);
        }

        return ResponseClass::success($this->unitRow($unit));
    }

    /**
     * "Get a Free Quote" / contact form (website: POST /inquiry/store).
     */
    public function storeInquiry(Request $request, InquiryService $inquiries)
    {
        $v = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:40',
            'storage_type' => 'required|string|max:255',
            'message' => 'nullable|string|max:5000',
            'company' => 'nullable|string|max:255',
            'size' => 'nullable|string|max:255',
            'storing' => 'nullable|string|max:255',
            'duration' => 'nullable|string|max:255',
            'volume' => 'nullable|string|max:255',
            'items' => 'nullable|string|max:2000',
            'source' => 'nullable|string|max:100',
            'page' => 'nullable|string|max:500',
        ]);
        if ($v->fails()) {
            return ResponseClass::error('Validation failed.', 422, $v->errors());
        }

        if (!$request->filled('source')) {
            $request->merge(['source' => 'mobile-app']);
        }

        // Same as the website: bots get a normal-looking answer and nothing is saved.
        if ($inquiries->isSpam($request)) {
            $inquiries->logSpam($request);

            return ResponseClass::success(['reference' => 'SK-00000'], 'Thank you. Our team will contact you soon.', 201);
        }

        try {
            $inquiry = $inquiries->create($request->only(['name', 'email', 'phone', 'storage_type', 'message']), $request);
        } catch (\Throwable $e) {
            Log::error('API inquiry submission failed: '.$e->getMessage());

            return ResponseClass::error('Something went wrong while submitting your inquiry. Please try again later.', 500);
        }

        return ResponseClass::success([
            'inquiry_id' => $inquiry->id,
            'reference' => $inquiries->reference($inquiry),
        ], 'Thank you. Our team will contact you soon.', 201);
    }

    /**
     * Storage unit reservation form (website: /reservation/{id} → /save-lead).
     */
    public function storeReservation(Request $request, LeadInterface $leads)
    {
        $v = Validator::make($request->all(), [
            'storage_unit_ids' => 'required|array|min:1|max:20',
            'storage_unit_ids.*' => 'integer|distinct',
            'type' => 'required|in:individual,company',
            'company_name' => 'required_if:type,company|nullable|string|max:255',
            'move_in_date' => 'required|date|after_or_equal:today',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:40',
            'mobile1' => 'nullable|string|max:40',
            'mobile2' => 'nullable|string|max:40',
            'term_length_id' => 'nullable|integer|exists:term_lengths,id',
            'addon_ids' => 'nullable|array',
            'addon_ids.*' => 'integer|exists:addons,id',
            'insurance_id' => 'nullable|integer',
            'goods_value' => 'nullable|numeric|min:0',
            'accept_terms' => 'accepted',
        ]);
        if ($v->fails()) {
            return ResponseClass::error('Validation failed.', 422, $v->errors());
        }

        // Only the fields the website form sends; the app cannot set
        // customer, assignee, source or status on the lead.
        $leadRequest = new Request([
            'su_ids' => array_map('intval', $request->input('storage_unit_ids')),
            'type' => $request->input('type'),
            'company_name' => $request->input('type') === 'company' ? $request->input('company_name') : null,
            'r_date' => $request->input('move_in_date'),
            'f_name' => $request->input('first_name'),
            'l_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            // leads.mobile1 / mobile2 are NOT NULL.
            'mobile1' => (string) ($request->input('mobile1') ?? ''),
            'mobile2' => (string) ($request->input('mobile2') ?? ''),
            'price' => $request->input('term_length_id'),
            'addon' => array_map('intval', (array) $request->input('addon_ids', [])),
            'insurance_id' => (string) ($request->input('insurance_id') ?? ''),
            'goodsval' => $request->input('goods_value'),
            'terms' => 'agree',
        ]);

        $response = $leads->saveLead($leadRequest);
        $body = $response->getData(true);

        if (empty($body['success'])) {
            $status = $response->getStatusCode();
            if ($status >= 500) {
                Log::error('API reservation failed: '.($body['message'] ?? ''));

                return ResponseClass::error('Could not save your reservation. Please try again later.', 500);
            }

            return ResponseClass::error($body['message'] ?? 'Could not save your reservation.', $status);
        }

        $leadId = $body['data']['id'] ?? null;

        return ResponseClass::success([
            'lead_id' => $leadId,
            'reference' => 'SK-L' . str_pad((string) $leadId, 5, '0', STR_PAD_LEFT),
        ], 'Thanks for selecting Storage Keys. Our team will contact you soon.', 201);
    }

    private function countryRows()
    {
        return Country::query()
            ->where('is_deleted', 0)
            ->orderBy('id', 'DESC')
            ->get()
            ->map(fn ($r) => [
                'id' => $r->id,
                'name' => $r->name,
                'is_default' => (bool) $r->getRawOriginal('is_default'),
            ])
            ->values();
    }

    private function unitQuery()
    {
        return StorageUnit::query()->with([
            'warehouse.loc.city.country',
            'storagetype',
            'storagelevel',
            'storagesize',
        ]);
    }

    private function unitRow(StorageUnit $u): array
    {
        $loc = $u->warehouse->loc ?? null;
        $city = $loc->city ?? null;
        $country = $city->country ?? null;

        return [
            'id' => $u->id,
            'name' => $u->storage_unit_name,
            'width' => $u->width,
            'length' => $u->length,
            'height' => $u->height,
            'price' => $u->price,
            'status' => $u->status,
            'type' => $u->storagetype ? ['id' => $u->storagetype->id, 'name' => $u->storagetype->name] : null,
            'level' => $u->storagelevel ? ['id' => $u->storagelevel->id, 'name' => $u->storagelevel->name] : null,
            'size' => $u->storagesize ? ['id' => $u->storagesize->id, 'name' => $u->storagesize->unit_type_name] : null,
            'warehouse' => $u->warehouse ? ['id' => $u->warehouse->id, 'name' => $u->warehouse->name] : null,
            'location' => $loc ? ['id' => $loc->id, 'name' => $loc->loc_name] : null,
            'city' => $city ? ['id' => $city->id, 'name' => $city->city_name] : null,
            'country' => $country ? ['id' => $country->id, 'name' => $country->name] : null,
        ];
    }
}
