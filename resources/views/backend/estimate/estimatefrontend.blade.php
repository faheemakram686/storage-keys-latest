@extends('ui.layouts.frontend')
@section('title', '| Bookings')
@section('content')
    @isset($data)

    <style>
        /* Page-scoped responsive tweaks for the customer estimate page */
        .estimate-view-page.checkout-page {
            padding-top: 200px;
            padding-bottom: 60px;
            overflow-x: hidden;
        }
        .estimate-view-page .estimate-content-col {
            padding-right: 20px;
        }
        .estimate-view-page .estimate-content-col .term-section-body,
        .estimate-view-page .estimate-content-col .padlock-section-body,
        .estimate-view-page .estimate-content-col .insurance-section-body,
        .estimate-view-page .estimate-content-col .terms-conditions-section-body {
            width: 100%;
            max-width: 100%;
            padding: 36px 28px 28px;
        }
        .estimate-view-page .order-summary {
            margin-left: 0;
            max-width: 100%;
        }
        .estimate-view-page .order-summary .locations-section {
            margin-left: 0;
            margin-right: 0;
            width: 100%;
        }
        .estimate-view-page .order-section-header {
            margin-left: auto;
            margin-right: auto;
            float: none;
            max-width: 70%;
        }
        .estimate-view-page .order-section-body {
            padding: 28px 16px 18px;
            overflow: hidden;
            width: 100%;
            box-sizing: border-box;
        }
        .estimate-view-page .order-section-body .form-check {
            padding-left: 0;
            margin-bottom: 0;
        }
        .estimate-view-page .order-section-body .row {
            margin-left: 0;
            margin-right: 0;
            align-items: center;
        }
        .estimate-view-page .order-section-body .row > [class*="col-"] {
            padding-left: 8px;
            padding-right: 8px;
        }
        .estimate-view-page .order-summary .d-flex,
        .estimate-view-page .order-summary .summary-row-value {
            justify-content: flex-end;
            text-align: right;
            flex-wrap: wrap;
            word-break: break-word;
        }
        .estimate-view-page .order-summary .summary-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 4px;
        }
        .estimate-view-page .order-summary .summary-actions .btn-qoutation {
            flex: 1 1 100%;
            min-width: 0;
            max-width: 100%;
            margin-top: 0;
            padding: 8px 10px;
            text-align: center;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            background-color: #F58320;
            color: #fff3e9;
            border-color: #F58320;
            box-sizing: border-box;
        }
        @media (min-width: 992px) {
            .estimate-view-page .order-summary-sticky {
                position: sticky;
                top: 100px;
            }
        }
        @media (max-width: 991.98px) {
            .estimate-view-page.checkout-page {
                padding-top: 140px;
            }
            .estimate-view-page .estimate-content-col {
                padding-right: 15px;
            }
            .estimate-view-page .order-summary {
                margin-top: 24px;
            }
            .estimate-view-page .estimate-content-col .term-section-body,
            .estimate-view-page .estimate-content-col .padlock-section-body,
            .estimate-view-page .estimate-content-col .insurance-section-body,
            .estimate-view-page .estimate-content-col .terms-conditions-section-body,
            .estimate-view-page .order-section-body {
                padding: 24px 16px 16px;
            }
        }
        @media (max-width: 575.98px) {
            .estimate-view-page.checkout-page {
                padding-top: 110px;
            }
            .estimate-view-page .greeting-user h3 {
                font-size: 1.3rem;
            }
            .estimate-view-page .greeting-user .area-name {
                font-size: 0.9rem;
            }
            .estimate-view-page .term-section-header,
            .estimate-view-page .padlock-section-header,
            .estimate-view-page .insurance-section-header,
            .estimate-view-page .terms-conditions-section-header,
            .estimate-view-page .order-section-header {
                font-size: 0.95rem;
                max-width: 80%;
            }
            .estimate-view-page .modal-body {
                padding: 20px !important;
            }
            .estimate-view-page ~ .modal .modal-body {
                padding: 20px !important;
            }
        }
    </style>
    <div class="justify-content-center checkout-page estimate-view-page">
        @foreach ($data['lead'] as $lead)
        @php
            $estimateUnits = collect($data['estimate_units'] ?? ($lead->estimateStorageUnits ?? []));
            if ($estimateUnits->isEmpty() && !empty($data['su'])) {
                $estimateUnits = collect($data['su'])->map(function ($su) use ($lead) {
                    return (object) [
                        'storage_unit_id' => $su->id,
                        'unit_price' => $lead->unit_price,
                        'storageunit' => $su,
                    ];
                });
            }
            $monthlySum = $estimateUnits->sum(function ($row) {
                return (float) (is_array($row) ? ($row['unit_price'] ?? 0) : ($row->unit_price ?? 0));
            });
            if ($monthlySum <= 0) {
                $monthlySum = (float) ($lead->unit_price ?? 0);
            }
            $discountPct = optional($lead->termLength)->discount_percentage ?? 0;
            $termPeriod = optional($lead->termLength)->term_period ?? 1;
            $storageTermTotal = $monthlySum * $termPeriod;
            $storageDiscounted = $storageTermTotal - ($storageTermTotal * $discountPct / 100);
            $addonSum = ($lead->estimateAddon ?? collect([]))->sum('price');
            $insuranceAmt = (float) ($lead->insurance_amount ?? 0);
            $subTotal = $storageDiscounted + $addonSum + $insuranceAmt;
        @endphp
        <div class="container">
            <div class="row">
                <div class="offset-lg-1 offset-md-1 col-12 col-sm-12 col-md-11 col-lg-11 greeting-user">
                    <h3 class="animated fadeIn" style="color:#FF8820;">Hi, {{$lead->f_name}}  {{$lead->l_name}}</h3>
                    <h3 class="animated fadeIn" style="color:#FF8820;">You have selected:</h3>
                    <ul class="list-unstyled mb-0" style="color:#FF8820;">
                        @forelse($estimateUnits as $eu)
                            @php $su = $eu->storageunit ?? null; @endphp
                            <li class="mb-1">
                                @if($su && optional(optional(optional($su->warehouse)->loc)->city)->city_name)
                                    <span class="area-name">
                                        {{ $su->warehouse->loc->city->city_name }}
                                        - {{ $su->warehouse->loc->loc_name }}
                                        - {{ $su->warehouse->name }}
                                        - {{ $su->storage_unit_name }}
                                    </span>
                                @else
                                    <span class="area-name">{{ optional($su)->storage_unit_name ?? ('Unit #'.($eu->storage_unit_id ?? '')) }}</span>
                                @endif
                                <small style="color:#666;">(AED {{ number_format((float)($eu->unit_price ?? 0), 2) }}/mo)</small>
                            </li>
                        @empty
                            <li>No storage units found on this estimate.</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-sm-12 col-md-12 col-lg-12 details-section">
                    <form method="post" action="#" id="EstimateForm1">
                        @csrf
                        <input type="hidden" name="lead_id" id="lead_id" value="{{$lead->id}}">
                        <input type="hidden" name="su_id" value="{{$lead->su_id}}">
                        @foreach($estimateUnits as $euHid)
                            <input type="hidden" name="su_ids[]" value="{{ is_array($euHid) ? ($euHid['storage_unit_id'] ?? '') : ($euHid->storage_unit_id ?? '') }}">
                        @endforeach

                    <div class="row reservations-sections align-items-start">
                        <div class="col-12 col-lg-9 estimate-content-col">
                            <div class="row">
                                <div class="offset-1 offset-sm-2 col-8 col-sm-5 col-md-4 col-lg-3 term-section-header">
                                    Select term length</div>
                                <div class="col-12 term-section-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <p>Select longer periods to enjoy massive savings!</p>
                                            @isset($data['term_lengths'])
                                                @foreach($data['term_lengths'] as $term_length)
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="form-check">
                                                        <input class="form-check-input" name="term_length" type="radio" value="{{$term_length->id}}"  {{ ($lead->term_length== $term_length->id)? "checked" : "" }}  id="flexCheckDefault" disabled />
                                                        <label class="check-container" for="flexCheckDefault">{{$term_length->title}}</label>
                                                        <p class="no-bottom-margin text-left on-sale-text"><small>({{$term_length->description}})</small></p>
                                                    </div>
                                                </div>
                                                    @php
                                                        $total2 = $monthlySum * $term_length->term_period;
                                                    @endphp
                                                    <div class="col-6">
                                                        <p class="no-bottom-margin text-right">AED {{$total2 - ($total2 * $term_length->discount_percentage/100)}}</p>
                                                        <p class="no-bottom-margin text-right"><del>AED {{$total2}}</del></p>
                                                        <p class="no-bottom-margin text-right on-sale-text">On Sale (Save {{$term_length->discount_percentage}}%)</p>
                                                    </div>
                                            </div>
                                            <div class="separator-item"></div>
                                                @endforeach
                                            @endisset

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row padlock-sections mt-3">
                                <div class="offset-1 offset-sm-2 col-8 col-sm-5 col-md-4 col-lg-3 padlock-section-header">
                                    Addons</div>
                                <div class="col-12 padlock-section-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="row">
                                        @isset($data['addon'])
                                            @foreach ($data['lead'][0]->estimateAddon as $addon)
                                                <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-checkbox">
                                                    <div class="row">
                                                        <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                                            <input class="form-check-input addon" name="addon[]" id="cctv_for_customer_check" readonly type="checkbox" value="{{$addon->id}}" @foreach($data['leadaddon'] as $selected) {{ ($selected == $addon->addon_id) ? "checked" : "" }} @endforeach id="flexCheckDefault" disabled />
                                                            <label class="check-container" for="flexCheckDefault">{{$addon->addon->name}}</label>
                                                        </div>
                                                        <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                                            <span class="no-bottom-margin addon-price">{{$addon->price}}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endisset
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row insurance-sections mt-3">
                                <div class="offset-1 offset-sm-2 col-8 col-sm-5 col-md-4 col-lg-3 insurance-section-header">
                                    Insurance</div>
                                <div class="col-12 insurance-section-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <p>Insure your goods</p>
                                            <div class="separator"></div>
                                            @if((float) ($lead->insurance_amount ?? 0) > 0 || ($lead->insurence ?? '') === 'cover')
                                                <div class="row">
                                                    <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" checked disabled />
                                                            <label class="check-container">
                                                                {{ optional($lead->insurance)->name ?? 'Insurance cover' }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                                        <p class="text-right">AED {{ number_format((float) ($lead->insurance_amount ?? 0), 2) }}/mo</p>
                                                    </div>
                                                    <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                                        <input type="text" class="form-control" readonly value="{{ $lead->goods }}" style="height:35px;" disabled>
                                                    </div>
                                                    <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                                        <p class="text-right">Cover AED {{ $lead->insurance_cover ?? '—' }}</p>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" checked disabled />
                                                    <label class="check-container">No Thanks</label>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row terms-conditions-sections mt-3">
                                <div class="offset-1 offset-sm-2 col-8 col-sm-5 col-md-4 col-lg-3 terms-conditions-section-header">
                                    TERMS &amp; CONDITIONS
                                </div>
                                <div class="col-12 terms-conditions-section-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox"  name="terms" value="agree"  id="flexCheckDefault" required />
                                                <label class="check-container" for="flexCheckDefault">I agree to the standard terms and conditions of storage
                                                    keys<a href="#" data-toggle="modal" data-target="#quick_view_modal" class="form-link"> (click here)</a></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-lg-3 order-summary">
                            <div class="order-summary-sticky">
                            <div class="row locations-section justify-content-center">
                                <div class="col-10 col-sm-8 col-md-6 col-lg-10 order-section-header">Order Summary</div>
                                <div class="col-12 order-section-body">
                                    <div class="row mb-2">
                                        <div class="col-12">
                                            <strong>Units</strong>
                                            <ul class="pl-3 mb-2">
                                                @foreach($estimateUnits as $eu)
                                                    <li>
                                                        {{ optional($eu->storageunit)->storage_unit_name ?? ('Unit #'.($eu->storage_unit_id ?? '')) }}
                                                        — AED {{ number_format((float)($eu->unit_price ?? 0), 2) }}/mo
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-check">
                                                <label class="check-container" for="flexCheckDefault">Storage</label>
                                            </div>
                                        </div>
                                        <div class="col-6 d-flex summary-row-value">
                                            <span class="no-bottom-margin ml-1 mt-1 text-right st_amount">{{ number_format($storageDiscounted, 2, '.', '') }}</span>
                                                <span class="no-bottom-margin mt-1 ml-1 text-right">AED </span>
                                        </div>
                                    </div>

                                    <div class="separator-item"></div>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-check">
                                                <label class="check-container" for="flexCheckDefault">PadLock</label>
                                            </div>
                                        </div>
                                        <div class="col-6 d-flex summary-row-value">

                                            @isset($data['lead'][0]->estimateAddon)

                                            <span class="no-bottom-margin mt-1 addon_amount ml-1 text-right">{{$data['lead'][0]->estimateAddon->sum('price')}}</span>
                                                <span class="no-bottom-margin mt-1 ml-1 text-right">AED </span>
                                            @endisset
                                        </div>
                                    </div>
                                    <div class="separator-item"></div>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-check">
                                                <label class="check-container" for="flexCheckDefault">Insurance</label>
                                            </div>
                                        </div>
                                        <div class="col-6 d-flex summary-row-value">

                                            @isset($data['lead'][0])
                                            <span class="no-bottom-margin inc_amount mt-1 ml-1 text-right">{{ number_format((float) ($data['lead'][0]->insurance_amount ?? 0), 2, '.', '') }}</span>
                                                <span class="no-bottom-margin mt-1 ml-1 text-right">AED </span>
                                            @endisset
                                        </div>
                                    </div>
                                    <div class="separator-item"></div>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-check">
                                                <label class="check-container" for="flexCheckDefault">Sub Total</label>
                                            </div>
                                        </div>
                                        <div class="col-6 d-flex summary-row-value">

                                            <span class="no-bottom-margin mt-1 sub_total text-right ml-1 "  id="subtotal">{{ number_format($subTotal, 2, '.', '') }}</span>
                                            <span class="no-bottom-margin mt-1 ml-1 text-right">AED </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 mt-2 order-section-body">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-check">
                                                <label class="check-container" for="flexCheckDefault">VAT</label>
                                            </div>
                                        </div>
                                        <div class="col-6 d-flex summary-row-value">


                                            <span class="no-bottom-margin mt-1 text-right vat_amount">0</span>
                                            <span class="no-bottom-margin mt-1 ml-1 text-right">AED </span>
                                        </div>
                                    </div>
                                    <div class="separator-item"></div>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-check">
                                                <label class="check-container" for="flexCheckDefault">Total</label>
                                            </div>
                                        </div>
                                        <div class="col-6 d-flex summary-row-value">

                                            <span class="no-bottom-margin mt-1 total_amount ml-1  text-right" id="total_amount"></span>
                                            <span class="no-bottom-margin mt-1 ml-1 text-right">AED</span>
                                        </div>
                                    </div>
                                    <div class="separator-item"></div>
                                    <div class="summary-actions">
                                        <a href="{{url('estimate-upload-document')}}/{{hashid_encode($data['lead'][0]->id)}}" class="btn btn-qoutation btn-sm active">Accept</a>
                                    </div>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
{{--                    <div class="row submission-sections">--}}
{{--                        <button class="offset-md-1 offset-lg-1 btn btn-qoutation btn-sm active btn-submit float-end" type="submit">Save Estimate</button>--}}
{{--                    </div>--}}
                    </form>
                </div>
            </div>
                @endforeach
        </div>

    </div>

    @endisset
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    <script>
        $(document).ready(function() {
            var total = 0;
            var total_amount = 0;
            var vat_amount = 0;
            getSubtotal();
            function getSubtotal() {
                    total = 0;
                    total += parseFloat($('.st_amount').text()) || 0;
                    total += parseFloat($('.addon_amount').text()) || 0;
                    total += parseFloat($('.inc_amount').text()) || 0;
                    $("#subtotal").text(total.toFixed(2));
                    vat_amount = parseFloat($('.vat_amount').text()) || 0;
                    total_amount = total - vat_amount;
                    $("#total_amount").text(total_amount.toFixed(2));
            }

            $('.btn-qoutation').on('click', function(e) {
                if ($(this).text().trim() === 'Accept') {
                    if (!$('input[name="terms"]').is(':checked')) {
                        e.preventDefault();
                        if (typeof toastr !== 'undefined') {
                            toastr.error('Please agree to the terms and conditions before accepting.');
                        } else {
                            alert('Please agree to the terms and conditions before accepting.');
                        }
                    }
                }
            });


        });
    </script>

    <!-- PREMIUM TERMS MODAL -->
    <div class="modal fade" id="quick_view_modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content" style="border: none; border-radius: 16px; box-shadow: 0 20px 40px rgba(0,0,0,0.2); overflow: hidden; font-family: 'Inter', sans-serif;">
                <div class="modal-header" style="background: linear-gradient(135deg, #FF8820 0%, #FF6B00 100%); border: none; padding: 25px 30px; position: relative;">
                    <h4 class="modal-title" style="color: #ffffff; font-weight: 700; margin: 0; font-size: 1.4rem; letter-spacing: -0.02em;">Terms & Conditions</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="position: absolute; right: 20px; top: 50%; transform: translateY(-50%); background: rgba(255,255,255,0.2); border: none; width: 36px; height: 36px; border-radius: 50%; color: white; display: flex; align-items: center; justify-content: center; opacity: 1; transition: all 0.3s ease; cursor: pointer;">
                        <span aria-hidden="true" style="font-size: 24px; line-height: 1;">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="padding: 40px; background: #ffffff;">
                    <div style="max-height: 450px; overflow-y: auto; padding-right: 15px; custom-scrollbar;">
                        <div style="font-size: 15px; line-height: 1.8; color: #4A5568; text-align: justify;">
                            @isset($data['terms_conditions'][9])
                                <div class="terms-content">
                                    {!! $data['terms_conditions'][9]->value !!}
                                </div>
                            @else
                                <div style="text-align: center; padding: 40px; color: #A0AEC0;">
                                    <em class="icon ni ni-info-fill" style="font-size: 48px; display: block; margin-bottom: 15px;"></em>
                                    <p>Terms and conditions content is currently unavailable.</p>
                                </div>
                            @endisset
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="background: #F7FAFC; border-top: 1px solid #EDF2F7; padding: 20px 40px; display: flex; justify-content: flex-end;">
                    <button type="button" class="btn" data-dismiss="modal" style="background: #2D3748; color: white; padding: 12px 30px; border-radius: 8px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; border: none; transition: background 0.3s ease;">Close Document</button>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Custom Scrollbar for Premium Feel */
        [custom-scrollbar]::-webkit-scrollbar {
            width: 6px;
        }
        [custom-scrollbar]::-webkit-scrollbar-track {
            background: #F1F5F9;
            border-radius: 10px;
        }
        [custom-scrollbar]::-webkit-scrollbar-thumb {
            background: #CBD5E0;
            border-radius: 10px;
        }
        [custom-scrollbar]::-webkit-scrollbar-thumb:hover {
            background: #A0AEC0;
        }
        .modal-content {
            animation: modalSlideUp 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }
        @keyframes modalSlideUp {
            from { transform: translateY(30px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .terms-content p { margin-bottom: 15px; }
        .terms-content ul, .terms-content ol { margin-bottom: 15px; padding-left: 20px; }
        .terms-content li { margin-bottom: 8px; }
        .terms-content h1, .terms-content h2, .terms-content h3 { margin-top: 20px; margin-bottom: 10px; color: #2D3748; }
    </style>
    <!-- MODAL AREA END -->
@endsection