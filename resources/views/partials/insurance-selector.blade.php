{{--
  Dynamic insurance package selector with goods auto-calc.
  Expects:
    $insurances (collection)
    $selectedInsuranceId (int|null)
    $goodsValue (string|null)
    $readonly (bool, optional)
--}}
@php
    $insurances = $insurances ?? collect([]);
    $selectedInsuranceId = $selectedInsuranceId ?? null;
    $goodsValue = $goodsValue ?? null;
    $readonly = !empty($readonly);
    $nothingSelected = empty($selectedInsuranceId);
    $pricing = app(\App\Services\InsurancePricingService::class);
@endphp
<div class="row insurance-selector-root" data-readonly="{{ $readonly ? '1' : '0' }}">
    <div class="offset-lg-1 offset-md-1 col-12 col-sm-12 col-md-10 col-lg-10">
        <p>Insure your goods</p>
        <div class="separator"></div>

        @forelse($insurances as $pkg)
            @php
                $attrs = $pkg->getAttributes();
                $pkgId = (int) $pkg->id;
                $baseMonthly = (float) ($attrs['monthly_amount'] ?? 0);
                $baseCover = (float) ($attrs['cover'] ?? 0);
                $percent = $pricing->parsePercentFromName($attrs['name'] ?? null);
                $calc = $pricing->calculateForPackage($pkg, $goodsValue);
                $isChecked = (int) $selectedInsuranceId === $pkgId;
            @endphp
            <div class="row mb-2 insurance-package-row"
                 data-package-id="{{ $pkgId }}"
                 data-base-monthly="{{ $baseMonthly }}"
                 data-base-cover="{{ $baseCover }}"
                 data-percent="{{ $percent !== null ? $percent : '' }}">
                <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                    <div class="form-check">
                        <input class="form-check-input insurance-package-radio"
                               name="insurance_id"
                               type="radio"
                               value="{{ $pkgId }}"
                               id="insurance_pkg_{{ $pkgId }}{{ $readonly ? '_ro' : '' }}"
                               {{ $isChecked ? 'checked' : '' }}
                               {{ $readonly ? 'disabled' : '' }} />
                        <label class="check-container" for="insurance_pkg_{{ $pkgId }}{{ $readonly ? '_ro' : '' }}">
                            {{ $attrs['name'] ?? $pkg->name }}
                        </label>
                    </div>
                </div>
                <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                    <p class="text-right insurance-monthly-label">AED {{ number_format($calc['monthly'], 2) }}/mo</p>
                </div>
                <div class="col-12 col-sm-12 col-md-6 col-lg-6"></div>
                <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                    <p class="text-right insurance-cover-label">Cover AED {{ number_format($calc['cover'], 2) }}</p>
                </div>
            </div>
            <div class="separator"></div>
        @empty
            <p class="text-muted">No insurance packages available.</p>
            <div class="separator"></div>
        @endforelse

        <div class="row mb-2">
            <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                <input type="text"
                       class="form-control insurance-goods-input"
                       placeholder="Enter value of your goods"
                       name="{{ $readonly ? '' : 'goodsval' }}"
                       value="{{ $goodsValue }}"
                       inputmode="decimal"
                       style="height:35px;"
                       {{ $readonly ? 'readonly disabled' : '' }}>
            </div>
        </div>
        <div class="separator"></div>

        <div class="form-check">
            <input class="form-check-input insurance-package-radio"
                   name="insurance_id"
                   type="radio"
                   value=""
                   id="insurance_nothanks{{ $readonly ? '_ro' : '' }}"
                   {{ $nothingSelected ? 'checked' : '' }}
                   {{ $readonly ? 'disabled' : '' }} />
            <label class="check-container" for="insurance_nothanks{{ $readonly ? '_ro' : '' }}">No Thanks</label>
        </div>

        @if($readonly && !empty($selectedInsuranceId))
            <input type="hidden" name="insurance_id" value="{{ $selectedInsuranceId }}">
        @endif
    </div>
</div>

@once
<script>
(function () {
    function bootInsuranceCalc() {
        if (typeof jQuery === 'undefined') {
            setTimeout(bootInsuranceCalc, 50);
            return;
        }
        var $ = jQuery;

        function formatMoney(n) {
            var num = parseFloat(n) || 0;
            return num.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }

        function parseGoods(raw) {
            if (raw === null || raw === undefined) return 0;
            var cleaned = String(raw).replace(/,/g, '').replace(/\s/g, '');
            var n = parseFloat(cleaned);
            return isNaN(n) || n < 0 ? 0 : n;
        }

        function calcRow($row, goods) {
            var baseMonthly = parseFloat($row.data('base-monthly')) || 0;
            var baseCover = parseFloat($row.data('base-cover')) || 0;
            var percentRaw = $row.attr('data-percent');
            var percent = percentRaw !== '' && percentRaw !== undefined ? parseFloat(percentRaw) : null;

            if (goods <= 0) {
                return { monthly: baseMonthly, cover: baseCover };
            }

            if (percent !== null && !isNaN(percent) && percent > 0) {
                var cover = goods * (percent / 100);
                var ratio = baseCover > 0 ? (baseMonthly / baseCover) : 0;
                return { monthly: cover * ratio, cover: cover };
            }

            if (baseCover > 0) {
                var scale = goods / baseCover;
                return { monthly: baseMonthly * scale, cover: goods };
            }

            return { monthly: baseMonthly, cover: goods };
        }

        function refreshInsuranceSelector($root) {
            var goods = parseGoods($root.find('.insurance-goods-input').val());
            $root.find('.insurance-package-row').each(function () {
                var $row = $(this);
                var result = calcRow($row, goods);
                $row.find('.insurance-monthly-label').text('AED ' + formatMoney(result.monthly) + '/mo');
                $row.find('.insurance-cover-label').text('Cover AED ' + formatMoney(result.cover));
            });
        }

        $(document)
            .off('.insCalc')
            .on('input.insCalc change.insCalc keyup.insCalc', '.insurance-selector-root .insurance-goods-input', function () {
                refreshInsuranceSelector($(this).closest('.insurance-selector-root'));
            })
            .on('change.insCalc', '.insurance-selector-root .insurance-package-radio', function () {
                refreshInsuranceSelector($(this).closest('.insurance-selector-root'));
            });

        $('.insurance-selector-root').each(function () {
            refreshInsuranceSelector($(this));
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', bootInsuranceCalc);
    } else {
        bootInsuranceCalc();
    }
})();
</script>
@endonce
