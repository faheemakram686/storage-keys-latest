<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>How to Export HTML to Pdf in Laravel</title>
    <link rel="stylesheet" href="{{ asset('sk-assets/css/frontend/font-icons.css') }}">
    <!-- plugins css -->
    <link rel="stylesheet" href="{{ asset('sk-assets/css/frontend/plugins.css') }}">
    <link rel="stylesheet" href="{{ asset('sk-assets/css/frontend/booking.css') }}">
    <!-- Main Stylesheet -->
    <link rel="stylesheet" href="{{ asset('sk-assets/css/frontend/style.css') }}">
    <!-- Responsive css -->
    <link rel="stylesheet" href="{{ asset('sk-assets/css/frontend/responsive.css') }}">

    <link rel="stylesheet" href="{{ asset('sk-assets/css/custom.css') }}" />
    <link rel="stylesheet" href="{{ asset('sk-assets/css/toastr.css') }}"/>
</head>

<body>
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
                        <a href="javascript:void(0)" class="nav-link" onclick="export2Pdf()">Download PDF</a>
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
                                            <div class="row">
                                                <div class="col-9">
                                                    <div class="form-check">
                                                        <input class="form-check-input" name="term_length" type="radio" value="monthly"  {{ ($lead->term_length=="monthly")? "checked" : "" }}  id="flexCheckDefault" />
                                                        <label class="check-container" for="flexCheckDefault">Monthly</label>
                                                    </div>
                                                </div>
                                                <div class="col-3 d-flex">
                                                    <span class="no-bottom-margin mt-1 text-right">AED </span>
                                                    <span class="no-bottom-margin mt-1 text-right"> {{$data['lead'][0]->unit_price}}</span>
                                                    {{--                                            <input type="text" class=" no-bottom-margin form-control" placeholder="Price" name="unit_price" value="{{$lead->storageunit->price}}" style="height:35px;width:100px;padding: 0px 8px">--}}
                                                    <span class="no-bottom-margin mt-1 text-right">/mo</span>
                                                </div>
                                            </div>
                                            <div class="separator-item"></div>
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="form-check">
                                                        <input class="form-check-input" name="term_length" type="radio" value="quarterly"  {{ ($lead->term_length=="quarterly")? "checked" : "" }}   id="flexCheckDefault" />
                                                        <label class="check-container" for="flexCheckDefault">Quarterly</label>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <p class="no-bottom-margin text-right">AED {{($data['lead'][0]->unit_price * 3) - ($data['lead'][0]->unit_price * 4/100)}}/mo</p>
                                                    <p class="no-bottom-margin text-right"><del>AED {{$data['lead'][0]->unit_price * 3}}/mo</del></p>
                                                    <p class="no-bottom-margin text-right on-sale-text">On Sale (Save 4.00%)</p>
                                                </div>
                                            </div>
                                            <div class="separator-item"></div>
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="form-check">
                                                        <input class="form-check-input" name="term_length" type="radio" value="bi-annual"  {{ ($lead->term_length=="bi-annual")? "checked" : "" }}  id="flexCheckDefault" />
                                                        <label class="check-container" for="flexCheckDefault">Bi-Annual</label>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <p class="no-bottom-margin text-right">AED {{($data['lead'][0]->unit_price * 6) -($data['lead'][0]->unit_price * 8/100)}}/mo</p>
                                                    <p class="no-bottom-margin text-right"><del>AED {{$data['lead'][0]->unit_price * 6}}/mo</del></p>
                                                    <p class="no-bottom-margin text-right on-sale-text">On Sale (Save 8.00%)</p>
                                                </div>
                                            </div>
                                            <div class="separator-item"></div>
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="form-check">
                                                        <input class="form-check-input" name="term_length" type="radio" value="annual"  {{ ($lead->term_length=="annual")? "checked" : "" }}  id="flexCheckDefault" />
                                                        <label class="check-container" for="flexCheckDefault">Annual</label>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <p class="no-bottom-margin text-right">AED {{($data['lead'][0]->unit_price * 12) - ($data['lead'][0]->unit_price * 15/100)}}/mo</p>
                                                    <p class="no-bottom-margin text-right"><del>AED {{$data['lead'][0]->unit_price * 12 }}/mo</del></p>
                                                    <p class="no-bottom-margin text-right on-sale-text">On Sale (Save 15.00%)</p>
                                                </div>
                                            </div>
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
                                                    <span class="no-bottom-margin mt-1 text-right">AED </span>
                                                    @isset($data['su'])
                                                        <span class="no-bottom-margin mt-1 text-right st_amount"> {{($lead->term_length == 'annual') ? (($lead->unit_price * 12) - ($lead->unit_price * 15/100)) : (($lead->term_length == 'bi-annual') ? (($lead->unit_price * 6) - ($lead->unit_price * 8/100)) : (($lead->term_length == 'quarterly') ? (($lead->unit_price * 3) - ($lead->unit_price * 4/100)) : (($lead->term_length =='monthly')?  $lead->unit_price:0 )  )) }}</span>
                                                    @endisset
                                                    <span class="no-bottom-margin mt-1 text-right"> /mo</span>
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
                                                    <span class="no-bottom-margin mt-1 text-right">AED</span>
                                                    @isset($data['lead'][0]->estimateAddon)

                                                        <span class="no-bottom-margin mt-1 addon_amount text-right">{{$data['lead'][0]->estimateAddon->sum('price')}}</span>

                                                    @endisset
                                                    <span class="no-bottom-margin mt-1 text-right">/mo</span>
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
                                                    <span class="no-bottom-margin mt-1 text-right">AED</span>
                                                    @isset($data['lead'][0]->insurence)
                                                        <span class="no-bottom-margin inc_amount mt-1 text-right">{{(( $data['lead'][0]->insurence == 'nothanks')? 0 : 25)}}</span>
                                                    @endisset
                                                    <span class="no-bottom-margin mt-1 text-right">/mo</span>
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
                                                    <span class="no-bottom-margin mt-1 text-right">AED</span>
                                                    <span class="no-bottom-margin mt-1 sub_total text-right" id="subtotal">{{$data['lead'][0]->estimateAddon->sum('price') + $data['su'][0]->price +  (( $data['lead'][0]->insurence == 'nothanks')? 0 : 25) }} </span>
                                                    <span class="no-bottom-margin mt-1 text-right">/mo</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 mt-2 order-section-body ">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="form-check">
                                                        <label class="check-container" for="flexCheckDefault">VAT</label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 d-flex">
                                                    <span class="no-bottom-margin mt-1 text-right">AED </span>

                                                    <span class="no-bottom-margin mt-1 text-right vat_amount">0</span>

                                                    <span class="no-bottom-margin mt-1 text-right"> /mo</span>
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
                                                    <span class="no-bottom-margin mt-1 text-right">AED</span>
                                                    <span class="no-bottom-margin mt-1 total_amount  text-right" id="total_amount"></span>
                                                    <span class="no-bottom-margin mt-1 text-right">/mo</span>
                                                </div>
                                            </div>
                                            <div class="separator-item"></div>
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="form-check">
                                                        <a href="{{url('estimate-upload-document')}}/{{$data['lead'][0]->id}}" class="btn btn-qoutation btn-sm active mt-1 text-right" >Accept</a>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 d-flex">
                                                    <a href="#" class="btn btn-qoutation btn-sm active mt-1 text-right">Reject</a>
                                                </div>
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
                                                                    <input class="form-check-input addon" name="addon[]" id="cctv_for_customer_check" readonly type="checkbox" value="{{$addon->id}}" @foreach($data['leadaddon'] as $selected) {{ ($selected == $addon->addon_id) ? "checked" : "" }} @endforeach id="flexCheckDefault" />
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
                                                        <input class="form-check-input" readonly name="insurance" type="radio" value="cover" {{ ($lead->insurence == "cover")? "checked" : "" }}   id="flexCheckDefault" />
                                                        <label class="check-container" for="flexCheckDefault">Choose your own cover (100 AED per 100,000 AED cover)</label>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                                    <p class="text-right">AED 25.00/mo</p>
                                                </div>
                                                <div class="col-12 col-sm-12 col-md-6 col-lg-6">

                                                    <input type="text" class="form-control" readonly placeholder="Enter value of your goods" name="goodsval" value="{{$lead->goods}}" style="height:35px;">

                                                </div>
                                                <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                                    <p class="text-right">Cover AED 25000.00</p>
                                                </div>
                                            </div>
                                            <div class="separator"></div>

                                            <div class="form-check">
                                                <input class="form-check-input" name="insurance" type="radio" value="nothanks" disabled {{ ($lead->insurence == "nothanks")? "checked" : "" }}  id="flexCheckDefault" />
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
<script src="{{ asset('sk-assets/js/frontend/plugins.js') }}"></script>
<!-- Main JS -->
<script src="{{ asset('sk-assets/js/frontend/main.js') }}"></script>

<script src="{{ asset('sk-assets/js/common.js') }}"></script>
<script src="{{ asset('sk-assets/js/toastr.min.js') }}"></script>


<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script>
    const export2Pdf = async () => {

        let printHideClass = document.querySelectorAll('.print-hide');
        printHideClass.forEach(item => item.style.display = 'none');
        await fetch('{{url('/admin/export-pdf')}}', {
            method: 'GET'
        }).then(response => {
            if (response.ok) {
                response.json().then(response => {
                    var link = document.createElement('a');
                    link.href = response;
                    link.click();
                    printHideClass.forEach(item => item.style.display='');
                });
            }
        }).catch(error => console.log(error));
    }
</script>
</body>
</html>