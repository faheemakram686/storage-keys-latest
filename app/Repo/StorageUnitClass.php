<?php
namespace App\Repo;

use App\Enums\StorageUnitStatus;
use App\Models\Location;
use App\Models\StorageType;
use App\Models\StorageUnit;
use App\Models\Warehouse;
use App\Repo\Interfaces\StorageTypeInterface;
use App\Repo\Interfaces\StorageUnitInterface;
use App\Repo\Interfaces\WarehouseInterface;
use App\Services\StorageUnitStatusService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class StorageUnitClass implements StorageUnitInterface {

    /** @var StorageUnitStatusService */
    protected $unitStatus;

    public function __construct(?StorageUnitStatusService $unitStatus = null)
    {
        $this->unitStatus = $unitStatus ?: app(StorageUnitStatusService::class);
    }

  public function saveStorageUnit($request)
  {


      $sy =new StorageUnit();
      $sy->storage_unit_name=$request->su_name;
      $sy->wh_id=$request->wh_id;
      $sy->stype_id=$request->st_id;
      $sy->slevel_id =$request->sl_id;
      $sy->ssize_id =$request->ss_id;
      $sy->width = $request->filled('width') ? $request->width : null;
      $sy->length = $request->filled('length') ? $request->length : null;
      $sy->height = $request->filled('height') ? $request->height : null;
      $sy->price=$request->price;
      $sy->location=$request->location;
      $sy->status = StorageUnitStatus::VACANT;
      $sy->is_maintenance = false;
      if ($request->filled('is_maintenance') && (int) $request->is_maintenance === 1) {
          $sy->is_maintenance = true;
          $sy->status = StorageUnitStatus::UNDER_MAINTENANCE;
      }
      if($sy->save()){
          return response()->json(['success' => 'Record save successfully'], 200);
      }
  }


    public function getStorageUnit()
    {
        // TODO: Implement getStorageUnit() method.
        $qry=StorageUnit::with('warehouse','warehouse.loc.city.country','storagetype','storagelevel','storagesize','occupiedByCustomer','activeContract');
        $qry=$qry->where('is_deleted',0)->orderBy('id','DESC');
        $qry=$qry->get();
        return $qry;
    }

    public function deleteStorageUnit($id)
    {
        // TODO: Implement deleteStorageUnit() method.
        $country=StorageUnit::find($id);
        $country->is_deleted=1;
        $country->save();
        return 1;

    }

    public function editStorageUnit($id)
    {
        // TODO: Implement editStorageUnit() method.
        return $country=StorageUnit::with('occupiedByCustomer','activeContract')->find($id);
    }

    public function updateStorageUnit($request)
    {
        // TODO: Implement updateStorageUnit() method.
        $sy=StorageUnit::find($request->id);
        if (!$sy) {
            return 0;
        }
        $sy->storage_unit_name=$request->e_su_name;
        $sy->wh_id=$request->e_wh_id;
        $sy->stype_id=$request->e_st_id;
        $sy->slevel_id =$request->e_sl_id;
        $sy->ssize_id =$request->e_ss_id;
        $sy->width = $request->filled('e_width') ? $request->e_width : null;
        $sy->length = $request->filled('e_length') ? $request->e_length : null;
        $sy->height = $request->filled('e_height') ? $request->e_height : null;
        $sy->price=$request->e_price;
        $sy->location=$request->e_location;
        $sy->save();

        // Derived occupancy is read-only; only maintenance toggle is accepted from admin.
        if ($request->has('e_is_maintenance') || $request->has('is_maintenance')) {
            $enabled = (int) ($request->e_is_maintenance ?? $request->is_maintenance) === 1;
            try {
                $this->unitStatus->setMaintenance($sy->fresh(), $enabled);
            } catch (ValidationException $e) {
                return response()->json([
                    'error' => collect($e->errors())->flatten()->implode(' '),
                ], 422);
            }
        } else {
            $this->unitStatus->recalculate($sy->id, 'admin.unit_updated');
        }

        return 1;
    }

    public function setMaintenance($id, $enabled)
    {
        $unit = StorageUnit::find($id);
        if (!$unit) {
            return response()->json(['error' => 'Storage unit not found.'], 404);
        }
        try {
            $status = $this->unitStatus->setMaintenance($unit, (bool) $enabled);
            return response()->json([
                'success' => 'Maintenance updated successfully',
                'status' => $status,
                'is_maintenance' => (bool) $unit->fresh()->is_maintenance,
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => collect($e->errors())->flatten()->implode(' '),
            ], 422);
        }
    }

    public function searchStorageUnit($request){

        $qry=StorageUnit::with('warehouse.loc','warehouse.loc.city.country','storagetype','storagelevel','storagesize');

        $qry=$qry->availableForBooking();

        $qry=$qry->when($request->country_id, function ($query, $country_id) {
            return $query->whereRelation('warehouse.loc.city.country', 'id', $country_id);
        });
        $qry=$qry->when($request->city_id, function ($query, $city_id) {
            return $query->whereRelation('warehouse.loc.city', 'id', $city_id);
        });
        $qry=$qry->when($request->id, function ($query, $id) {
            return $query->whereRelation('warehouse.loc', 'loc_id', $id);
        });
        $qry=$qry->when($request->level, function ($query, $level) {
            return $query->whereRelationIn('storagelevel', 'id', $level);
        });
        $qry=$qry->when($request->sType, function ($query, $type) {
            return $query->whereRelationIn('storagetype', 'id', $type);
        });
        $qry=$qry->when($request->sSize, function ($query, $size) {
            return $query->whereRelation('storagesize', 'id', $size);
        });
        $qry=$qry->get();
        return $qry;

    }

    public  function  leadStorageUnit($id)
    {
        $qry = StorageUnit::with('warehouse.loc','warehouse.loc.city.country');
        $qry = $qry->where( 'id' , $id );
        $qry = $qry->get();
        return $qry;
    }

    public function leadStorageUnits($ids)
    {
        $ids = is_array($ids) ? $ids : [$ids];
        $ids = array_values(array_filter($ids));
        if (empty($ids)) {
            return collect([]);
        }
        return StorageUnit::with('warehouse.loc.city.country')
            ->whereIn('id', $ids)
            ->get();
    }

    public function getSunitWarehouseWise($request)
    {
        $qry = StorageUnit::query();
        $qry = $qry->where('wh_id', $request);
        $qry = $qry->availableForBooking();
        $qry = $qry->orderBy('id', 'DESC');
        return $qry->get();
    }
}
