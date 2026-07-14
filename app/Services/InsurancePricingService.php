<?php

namespace App\Services;

use App\Models\Insurance;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class InsurancePricingService
{
    /**
     * Active, non-deleted insurance catalog packages.
     */
    public function activePackages(): Collection
    {
        return Insurance::query()
            ->where('is_deleted', 0)
            ->where('status', 1)
            ->orderBy('id')
            ->get();
    }

    /**
     * Parse a numeric percentage from a package name (e.g. "10%", "20 %").
     * Only matches an explicit percent sign — plain names are not treated as rates.
     */
    public function parsePercentFromName(?string $name): ?float
    {
        if ($name === null || $name === '') {
            return null;
        }

        if (preg_match('/(\d+(?:\.\d+)?)\s*%/', $name, $m)) {
            return (float) $m[1];
        }

        return null;
    }

    /**
     * Calculate cover + monthly fee for a catalog package and optional goods value.
     *
     * When goods > 0 and package name contains a percent:
     *   cover   = goods × (percent / 100)
     *   monthly = cover × (catalog_monthly / catalog_cover)  // preserve package fee-to-cover ratio
     *
     * When goods > 0 but no percent in name, scale catalog amounts by goods/catalog_cover.
     * When goods empty, return catalog defaults.
     *
     * @return array{monthly: float, cover: float, percent: float|null}
     */
    public function calculateForPackage(Insurance $package, $goodsValue): array
    {
        $attrs = $package->getAttributes();
        $baseMonthly = (float) ($attrs['monthly_amount'] ?? 0);
        $baseCover = (float) ($attrs['cover'] ?? 0);
        $percent = $this->parsePercentFromName($attrs['name'] ?? null);
        $goods = $this->normalizeGoods($goodsValue);

        if ($goods <= 0) {
            return [
                'monthly' => round($baseMonthly, 2),
                'cover' => round($baseCover, 2),
                'percent' => $percent,
            ];
        }

        if ($percent !== null && $percent > 0) {
            $cover = round($goods * ($percent / 100), 2);
            $ratio = $baseCover > 0 ? ($baseMonthly / $baseCover) : 0.0;
            $monthly = round($cover * $ratio, 2);

            return [
                'monthly' => $monthly,
                'cover' => $cover,
                'percent' => $percent,
            ];
        }

        if ($baseCover > 0) {
            $scale = $goods / $baseCover;
            return [
                'monthly' => round($baseMonthly * $scale, 2),
                'cover' => round($goods, 2),
                'percent' => $percent,
            ];
        }

        return [
            'monthly' => round($baseMonthly, 2),
            'cover' => round($goods, 2),
            'percent' => $percent,
        ];
    }

    /**
     * Resolve package selection from a request into a snapshot array.
     *
     * @return array{insurance_id: int|null, insurance_amount: float|null, insurance_cover: string|null, insurence: string, goods: string|null}
     */
    public function resolveFromRequest($request): array
    {
        $insuranceId = $request->input('insurance_id');
        $goods = $request->input('goodsval', $request->input('goods'));
        $goodsStr = $goods !== null && $goods !== '' ? (string) $this->normalizeGoods($goods) : null;
        if ($goodsStr === '0') {
            $goodsStr = null;
        }

        if ($insuranceId === '' || $insuranceId === null || $insuranceId === '0') {
            if ($request->input('insurance') === 'cover') {
                return [
                    'insurance_id' => null,
                    'insurance_amount' => null,
                    'insurance_cover' => null,
                    'insurence' => 'cover',
                    'goods' => $goodsStr,
                ];
            }

            return [
                'insurance_id' => null,
                'insurance_amount' => null,
                'insurance_cover' => null,
                'insurence' => 'nothanks',
                'goods' => null,
            ];
        }

        $package = Insurance::query()
            ->where('id', (int) $insuranceId)
            ->where('is_deleted', 0)
            ->first();

        if (!$package) {
            return [
                'insurance_id' => null,
                'insurance_amount' => null,
                'insurance_cover' => null,
                'insurence' => 'nothanks',
                'goods' => null,
            ];
        }

        $calc = $this->calculateForPackage($package, $goods);

        return [
            'insurance_id' => (int) $package->id,
            'insurance_amount' => $calc['monthly'],
            'insurance_cover' => (string) $calc['cover'],
            'insurence' => 'cover',
            'goods' => $goodsStr,
        ];
    }

    /**
     * Snapshot already stored on a related model (e.g. lead → estimate, estimate → contract).
     *
     * @return array{insurance_id: int|null, insurance_amount: float|null, insurance_cover: string|null, insurence: string, goods: string|null}
     */
    public function snapshotFromModel(?Model $model): array
    {
        if (!$model) {
            return [
                'insurance_id' => null,
                'insurance_amount' => null,
                'insurance_cover' => null,
                'insurence' => 'nothanks',
                'goods' => null,
            ];
        }

        $hasPackage = !empty($model->insurance_id) || (float) ($model->insurance_amount ?? 0) > 0;

        return [
            'insurance_id' => $model->insurance_id ? (int) $model->insurance_id : null,
            'insurance_amount' => $model->insurance_amount !== null ? (float) $model->insurance_amount : null,
            'insurance_cover' => $model->insurance_cover !== null ? (string) $model->insurance_cover : null,
            'insurence' => $hasPackage || ($model->insurence ?? '') === 'cover' ? 'cover' : 'nothanks',
            'goods' => isset($model->goods) && $model->goods !== '' ? (string) $model->goods : null,
        ];
    }

    /**
     * Apply a resolved snapshot onto a lead/estimate/contract model.
     *
     * @param  bool  $includeGoods  Set false for contracts (no goods column).
     */
    public function applySnapshot(Model $model, array $snapshot, bool $includeGoods = true): void
    {
        $model->insurance_id = $snapshot['insurance_id'];
        $model->insurance_amount = $snapshot['insurance_amount'];
        $model->insurance_cover = $snapshot['insurance_cover'];
        $model->insurence = $snapshot['insurence'];

        if ($includeGoods && array_key_exists('goods', $snapshot)) {
            $model->goods = $snapshot['goods'];
        }
    }

    /**
     * Apply insurance from request, optionally falling back to another model's snapshot.
     */
    public function applyFromRequest(Model $model, $request, ?Model $fallback = null, bool $includeGoods = true): void
    {
        $hasExplicitChoice = $request->has('insurance_id')
            || $request->has('insurance')
            || $request->filled('goodsval');

        if ($hasExplicitChoice) {
            $this->applySnapshot($model, $this->resolveFromRequest($request), $includeGoods);
            return;
        }

        if ($fallback) {
            $this->applySnapshot($model, $this->snapshotFromModel($fallback), $includeGoods);
            return;
        }

        $this->applySnapshot($model, $this->resolveFromRequest($request), $includeGoods);
    }

    public function termTotal(?float $monthlyAmount, $termPeriod): float
    {
        $monthly = (float) ($monthlyAmount ?? 0);
        $period = max(1, (float) $termPeriod);

        return round($monthly * $period, 2);
    }

    public function monthlyFee(?Model $model): float
    {
        if (!$model) {
            return 0.0;
        }

        return (float) ($model->insurance_amount ?? 0);
    }

    protected function normalizeGoods($goodsValue): float
    {
        if ($goodsValue === null || $goodsValue === '') {
            return 0.0;
        }

        if (is_string($goodsValue)) {
            $goodsValue = str_replace([',', ' '], '', $goodsValue);
        }

        return max(0.0, (float) $goodsValue);
    }
}
