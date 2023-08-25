@extends('ui.layouts.frontend')
@section('title', '| Bookings')
@section('content')
    @isset($data)

    <div class="justify-content-center checkout-page">
        @foreach ($data['lead'] as $lead)
        <div class="container">
            @foreach ($data['su'] as $su)
            <div class="row">
                <div class="offset-lg-1 offset-md-1 col-12 col-sm-12 col-md-11 col-lg-11 greeting-user">
                    <h3 class="animated fadeIn" style="color:#FF8820;">Hi, {{$lead->f_name}}  {{$lead->l_name}}</h3>
                    <h3 class="animated fadeIn" style="color:#FF8820;" >You have selected {{$su->warehouse->loc->city->city_name}} <span class="area-name">-{{$su->warehouse->loc->loc_name}}- {{$su->warehouse->name}} - {{$su->storage_unit_name}}</span> </h3>
                </div>
{{--                <div class="offset-lg-1 offset-md-1 col-12 col-sm-12 col-md-11 col-lg-11 selected-plot-message"> <p> {{$su->warehouse->loc->loc_name}}- {{$su->warehouse->name}} - {{$su->storage_unit_name}}</p></div>--}}
            </div>
            @endforeach

            <div class="row">
                <div class="col-12 col-sm-12 col-md-12 col-lg-12 details-section">
                    <form method="post" action="#" id="EstimateForm1">
                        @csrf
                        <input type="hidden" name="lead_id" id="lead_id" value="{{$lead->id}}">
                        <input type="hidden" name="su_id" value="{{$lead->su_id}}">

                    <div class="row reservations-sections">
                        <div class="offset-sm-2 offset-md-2 offset-lg-2 offset-1 col-8 col-sm-6 col-md-4 col-lg-4 term-section-header">
                            Select term length</div>
                        <div class="offset-md-1 offset-lg-1 col-12 col-sm-12 col-md-9 col-lg-7 term-section-body">
                            <div class="row">
                                <div class="offset-lg-1 offset-md-1 col-12 col-sm-12 col-md-10 col-lg-10">
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
                                                $total2 = $data['lead'][0]->unit_price * $term_length->term_period;
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
                        <div class="col-12 col-sm-12 col-md-3 col-lg-3  order-summary">
                            <div class="row locations-section">
                                <div class="offset-2 offset-md-2 offset-lg-2 col-6 col-sm-6 col-md-8 col-lg-7 order-section-header">Order Summary</div>
                                <div class="col-12 order-section-body">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-check">
{{--                                                <input class="form-check-input" name="term_length" type="radio" value="monthly" {{ ($lead->price=="monthly")? "checked" : "" }}  id="flexCheckDefault" />--}}
                                                <label class="check-container" for="flexCheckDefault">Storage</label>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 d-flex">

                                            @isset($data['su'])
                                                @php
                                                $storagetotal = $lead->unit_price * $lead->termLength->term_period;
                                                 @endphp
                                            <span class="no-bottom-margin ml-1 mt-1 text-right st_amount"> {{$storagetotal - ($storagetotal * $lead->termLength->discount_percentage/100)}}</span>
{{--                                            <span class="no-bottom-margin mt-1 text-right st_amount"> {{($lead->term_length == 'annual') ? (($lead->unit_price * 12) - ($lead->unit_price * 15/100)) : (($lead->term_length == 'bi-annual') ? (($lead->unit_price * 6) - ($lead->unit_price * 8/100)) : (($lead->term_length == 'quarterly') ? (($lead->unit_price * 3) - ($lead->unit_price * 4/100)) : (($lead->term_length =='monthly')?  $lead->unit_price:0 )  )) }}</span>--}}
                                                <span class="no-bottom-margin mt-1 ml-1 text-right">AED </span>
                                            @endisset
{{--                                            <span class="no-bottom-margin mt-1 text-right"> /mo</span>--}}
                                        </div>
                                    </div>

                                    <div class="separator-item"></div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-check">
                                                <label class="check-container" for="flexCheckDefault">PadLock</label>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 d-flex">

                                            @isset($data['lead'][0]->estimateAddon)

                                            <span class="no-bottom-margin mt-1 addon_amount ml-1 text-right">{{$data['lead'][0]->estimateAddon->sum('price')}}</span>
                                                <span class="no-bottom-margin mt-1 ml-1 text-right">AED </span>
                                            @endisset
{{--                                            <span class="no-bottom-margin mt-1 text-right">/mo</span>--}}
                                        </div>
                                    </div>
                                    <div class="separator-item"></div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-check">
                                                <label class="check-container" for="flexCheckDefault">Insurance</label>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 d-flex">

                                            @isset($data['lead'][0]->insurence)
                                            <span class="no-bottom-margin inc_amount mt-1 ml-1 text-right">{{(( $data['lead'][0]->insurence == 'nothanks')? 0 : 25)}}</span>
                                                <span class="no-bottom-margin mt-1 ml-1 text-right">AED </span>
                                            @endisset
{{--                                            <span class="no-bottom-margin mt-1 text-right">/mo</span>--}}
                                        </div>
                                    </div>
                                    <div class="separator-item"></div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-check">
                                                <label class="check-container" for="flexCheckDefault">Sub Total</label>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 d-flex">

                                            <span class="no-bottom-margin mt-1 sub_total text-right ml-1 "  id="subtotal">{{$data['lead'][0]->estimateAddon->sum('price') + $data['su'][0]->price +  (( $data['lead'][0]->insurence == 'nothanks')? 0 : 25) }} </span>
{{--                                            <span class="no-bottom-margin mt-1 text-right">/mo</span>--}}
                                            <span class="no-bottom-margin mt-1 ml-1 text-right">AED </span>
                                        </div>
                                    </div>
{{--                                    <div class="separator-item"></div>--}}
{{--                                    <div class="row">--}}
{{--                                        <div class="col-lg-8">--}}
{{--                                            <div class="form-check">--}}
{{--                                                <input type="text" class="form-control" name="promo" placeholder="Promo Code" style="height:35px;">--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                        <div class="col-lg-4 d-flex">--}}
{{--                                            <a href="#" class=" btn btn-qoutation btn-sm active mt-1 text-right">APPLY</a>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
                                </div>
                                <div class="col-12 mt-2 order-section-body ">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-check">
{{--                                                <input class="form-check-input" name="term_length" type="radio" value="monthly" {{ ($lead->price=="monthly")? "checked" : "" }}  id="flexCheckDefault" />--}}
                                                <label class="check-container" for="flexCheckDefault">VAT</label>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 d-flex">


                                            <span class="no-bottom-margin mt-1 text-right vat_amount">0</span>
                                            <span class="no-bottom-margin mt-1 ml-1 text-right">AED </span>
{{--                                            <span class="no-bottom-margin mt-1 text-right"> /mo</span>--}}
                                        </div>
                                    </div>
                                    <div class="separator-item"></div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-check">
                                                <label class="check-container" for="flexCheckDefault">Total</label>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 d-flex">

                                            <span class="no-bottom-margin mt-1 total_amount ml-1  text-right" id="total_amount"></span>
{{--                                            <span class="no-bottom-margin mt-1 text-right">/mo</span>--}}
                                            <span class="no-bottom-margin mt-1 ml-1 text-right">AED</span>
                                        </div>
                                    </div>
                                    <div class="separator-item"></div>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-check">
                                                <a href="{{url('estimate-upload-document')}}/{{$data['lead'][0]->id}}" class="btn btn-qoutation btn-sm active mt-1 text-right" >Accept</a>
                                            </div>
                                        </div>
{{--                                        <div class="col-lg-6 d-flex">--}}
{{--                                            <a href="#" class="btn btn-qoutation btn-sm active mt-1 text-right">Reject</a>--}}
{{--                                        </div>--}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row padlock-sections">
                        <div class="offset-sm-2 offset-md-2 offset-lg-2 offset-1 col-8 col-sm-6 col-md-4 col-lg-4 padlock-section-header">
                            Addons</div>
                        <div class="offset-md-1 offset-lg-1 col-12 col-sm-12 col-md-9 col-lg-7 padlock-section-body">
                            <div class="row">
                                <div class="offset-lg-1 offset-md-1 col-12 col-sm-12 col-md-10 col-lg-10">
                                    <div class="row">

                                @isset($data['addon'])
                                    @foreach ($data['lead'][0]->estimateAddon as $addon)
                                        <div class="col-12 col-sm-12 col-md-6 col-lg-5 col-checkbox">
                                            <div class="row">
                                                <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                                    <input class="form-check-input addon" name="addon[]" id="cctv_for_customer_check" readonly type="checkbox" value="{{$addon->id}}" @foreach($data['leadaddon'] as $selected) {{ ($selected == $addon->addon_id) ? "checked" : "" }} @endforeach id="flexCheckDefault" disabled />
                                                    <label class="check-container" for="flexCheckDefault">{{$addon->addon->name}}</label>
                                                </div>
                                                <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                                    <span class="no-bottom-margin addon-price">{{$addon->price}}</span>
                                                </div>

{{--                                                <input type="text" class="no-bottom-margin addon-price form-control" placeholder="Price" name="addonprice[]" value="{{$addon->price}}" style="height:35px;width:100px;padding: 0px 8px" disabled>--}}
                                            </div>
                                        </div>
                                    @endforeach
                                @endisset
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                    <div class="row insurance-sections">
                        <div class="offset-sm-2 offset-md-2 offset-lg-2 offset-1 col-8 col-sm-6 col-md-4 col-lg-4 insurance-section-header">
                            Insurance</div>
                        <div class="offset-md-1 offset-lg-1 col-12 col-sm-12 col-md-9 col-lg-7 insurance-section-body">
                            <div class="row">
                                <div class="offset-lg-1 offset-md-1 col-12 col-sm-12 col-md-10 col-lg-10">
                                    <p>Insure your goods</p>
                                    <div class="separator"></div>
                                    <div class="row">
                                        <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                            <div class="form-check">
                                                <input class="form-check-input" readonly name="insurance" type="radio" value="cover" {{ ($lead->insurence == "cover")? "checked" : "" }}   id="flexCheckDefault" disabled />
                                                <label class="check-container" for="flexCheckDefault">Choose your own cover (100 AED per 100,000 AED cover)</label>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                            <p class="text-right">AED 25.00/mo</p>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-6 col-lg-6">

                                            <input type="text" class="form-control" readonly placeholder="Enter value of your goods" name="goodsval" value="{{$lead->goods}}" style="height:35px;" disabled>

                                        </div>
                                        <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                            <p class="text-right">Cover AED 25000.00</p>
                                        </div>
                                    </div>
                                    <div class="separator"></div>

                                    <div class="form-check">
                                        <input class="form-check-input" name="insurance" type="radio" value="nothanks" disabled {{ ($lead->insurence == "nothanks")? "checked" : "" }}  id="flexCheckDefault" disabled />
                                        <label class="check-container" for="flexCheckDefault">No Thanks</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                        <div class="row terms-conditions-sections">
                            <div class="offset-sm-2 offset-md-2 offset-lg-2 offset-1 col-8 col-sm-6 col-md-4 col-lg-4 terms-conditions-section-header">
                                TERMS &amp; CONDITIONS
                            </div>
                            <div class="offset-md-1 offset-lg-1 col-12 col-sm-12 col-md-10 col-lg-7 terms-conditions-section-body">
                                <div class="row">
                                    <div class="offset-lg-1 offset-md-1 col-12 col-sm-12 col-md-10 col-lg-10">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox"  name="terms" value="agree"  id="flexCheckDefault" />
                                            <label class="check-container" for="flexCheckDefault">I agree to the standard terms and conditions of storage
                                                keys<a href="#" class="form-link"> (click here)</a></label>
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
                    total += parseInt($('.st_amount').text());
                    total += parseInt($('.addon_amount').text());
                    total += parseInt($('.inc_amount').text());
                    $("#subtotal").text(total);
                    vat_amount = parseInt($('.vat_amount').text());
                    total_amount = total - vat_amount;
                    $("#total_amount").text(total_amount);
            console.log(total_amount);

            }


        });
    </script>

@endsection