@extends('ui.layouts.frontend')
@section('title', '| Bookings')
@section('content')
    @isset($data)

    <div class="justify-content-center checkout-page">

        <div class="container">
            @foreach ($data['su'] as $su)
            <div class="row">
                <div class="offset-lg-1 offset-md-1 col-12 col-sm-12 col-md-11 col-lg-11 greeting-user">
                    <h3 class="animated fadeIn" style="color:#FF8820;" >{{$su->warehouse->loc->city->city_name}} <span class="area-name">-{{$su->warehouse->loc->loc_name}}- {{$su->warehouse->name}} - {{$su->storage_unit_name}}</span> </h3>
                </div>
{{--                <div class="offset-lg-1 offset-md-1 col-12 col-sm-12 col-md-11 col-lg-11 selected-plot-message"> <p> {{$su->warehouse->loc->loc_name}}- {{$su->warehouse->name}} - {{$su->storage_unit_name}}</p></div>--}}
            </div>
            @endforeach
                @foreach ($data['lead'] as $lead)
            <div class="row">
                <div class="col-12 col-sm-12 col-md-12 col-lg-12 details-section">
                    <form method="post" action="{{ url('admin/save-estimate') }}" id="EstimateForm">
                        @csrf
                        <input type="hidden" name="lead_id" value="{{$lead->id}}">
                        <input type="hidden" name="su_id" value="{{$lead->su_id}}">
                    <div class="row reservations-sections">
                        <div class="offset-sm-2 offset-md-2 offset-lg-2 offset-1 col-8 col-sm-6 col-md-4 col-lg-4 term-section-header">
                            Contact Information</div>
                        <div class="offset-md-1 offset-lg-1 col-12 col-sm-12 col-md-10 col-lg-10 term-section-body">
                            <div class="row">
                                <div class="offset-lg-1 offset-md-1 col-12 col-sm-12 col-md-10 col-lg-10">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-check">
                                                <input class="form-check-input" name="type" type="radio" value="individual" {{ ($lead->lead_type=="individual")? "checked" : "" }}  id="ind" />
                                                <label class="check-container" for="flexCheckDefault">For Individual?</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-check">
                                                <input class="form-check-input" name="type" type="radio" value="company" {{ ($lead->lead_type=="company")? "checked" : "" }}  id="com" />
                                                <label class="check-container" for="flexCheckDefault">For Company?</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-6">
                                            <label class="">Requested date</label>
                                            <input type="date" class="form-control" name="r_date" value="{{$lead->r_date}}" style="height:35px;" required>
                                        </div>
                                        <div class="col-6" id="companyfeild">
                                            <label class="">Company Name</label>
                                            <input type="text" class="form-control" name="company_name" value="{{$lead->company_name}}" style="height:35px;" >
                                        </div>

                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-6">
                                            <label class="">First Name</label>
                                            <input type="text" class="form-control" name="f_name" value="{{$lead->f_name}}" style="height:35px;" required>
                                        </div>
                                        <div class="col-6">
                                            <label class="">Last Name</label>
                                            <input type="text" class="form-control" name="l_name" value="{{$lead->l_name}}" style="height:35px;" required>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-6">
                                            <label class="">Email</label>
                                            <input type="email" class="form-control" name="email" value="{{$lead->email}}" style="height:35px;" required>
                                        </div>
                                        <div class="col-6">
                                            <label class="">Phone</label>
                                            <input type="text" class="form-control" name="phone" value="{{$lead->phone}}" style="height:35px;" required>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-6">
                                            <label class="">Mobile 1</label>
                                            <input type="text" class="form-control" name="mobile1" value="{{$lead->mobile1}}" style="height:35px;" required>
                                        </div>
                                        <div class="col-6">
                                            <label class="">Mobile 2</label>
                                            <input type="text" class="form-control" name="mobile2" value="{{$lead->mobile2}}"  style="height:35px;" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row reservations-sections">
                        <div class="offset-sm-2 offset-md-2 offset-lg-2 offset-1 col-8 col-sm-6 col-md-4 col-lg-4 term-section-header">
                            Select term length</div>
                        <div class="offset-md-1 offset-lg-1 col-12 col-sm-12 col-md-10 col-lg-10 term-section-body">
                            <div class="row">
                                <div class="offset-lg-1 offset-md-1 col-12 col-sm-12 col-md-10 col-lg-10">
                                    <p>Select longer periods to enjoy massive savings!</p>
                                    <div class="row">
                                        <div class="col-9">
                                            <div class="form-check">
                                                <input class="form-check-input" name="term_length" type="radio" value="monthly" {{ ($lead->price=="monthly")? "checked" : "" }}  id="flexCheckDefault" />
                                                <label class="check-container" for="flexCheckDefault">Monthly</label>
                                            </div>
                                        </div>
                                        <div class="col-3 d-flex">
                                             <span class="no-bottom-margin mt-1 text-right">AED</span>
                                            <input type="text" class=" no-bottom-margin form-control" placeholder="Price" name="unit_price" value="{{$lead->storageunit->price}}" style="height:35px;width:100px;padding: 0px 8px">
                                            <span class="no-bottom-margin mt-1 text-right">/mo</span>
                                        </div>
                                    </div>
                                    <div class="separator"></div>
                                    <div class="row">
                                        <div class="col-6">
                                                <div class="form-check">
                                                    <input class="form-check-input" name="term_length" type="radio" value="quarterly" {{ ($lead->price=="quarterly")? "checked" : "" }}   id="flexCheckDefault" />
                                                    <label class="check-container" for="flexCheckDefault">Quarterly</label>
                                                </div>
                                        </div>
                                        <div class="col-6">
{{--                                            <p class="no-bottom-margin text-right">AED {{$su->price}}/mo</p>--}}
{{--                                            <p class="no-bottom-margin text-right"><del>AED {{$su->price}}/mo</del></p>--}}
                                            <p class="no-bottom-margin text-right on-sale-text">On Sale (Save 4.00%)</p>
                                        </div>
                                    </div>
                                    <div class="separator"></div>
                                    <div class="row">
                                        <div class="col-6">
                                                <div class="form-check">
                                                    <input class="form-check-input" name="term_length" type="radio" value="bi-annual" {{ ($lead->price=="bi-annual")? "checked" : "" }}  id="flexCheckDefault" />
                                                    <label class="check-container" for="flexCheckDefault">Bi-Annual</label>
                                                </div>
                                        </div>
                                        <div class="col-6">
{{--                                            <p class="no-bottom-margin text-right">AED {{$su->price}}/mo</p>--}}
{{--                                            <p class="no-bottom-margin text-right"><del>AED {{$su->price}}/mo</del></p>--}}
                                            <p class="no-bottom-margin text-right on-sale-text">On Sale (Save 8.00%)</p>
                                        </div>
                                    </div>
                                    <div class="separator"></div>
                                    <div class="row">
                                        <div class="col-6">
                                                <div class="form-check">
                                                    <input class="form-check-input" name="term_length" type="radio" value="annual" {{ ($lead->price=="annual")? "checked" : "" }}  id="flexCheckDefault" />
                                                    <label class="check-container" for="flexCheckDefault">Annual</label>
                                                </div>
                                        </div>
                                        <div class="col-6">
{{--                                            <p class="no-bottom-margin text-right">AED {{$su->price}}/mo</p>--}}
{{--                                            <p class="no-bottom-margin text-right"><del>AED {{$su->price}}/mo</del></p>--}}
                                            <p class="no-bottom-margin text-right on-sale-text">On Sale (Save 15.00%)</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row padlock-sections">
                        <div class="offset-sm-2 offset-md-2 offset-lg-2 offset-1 col-8 col-sm-6 col-md-4 col-lg-4 padlock-section-header">
                            Addons</div>
                        <div class="offset-md-1 offset-lg-1 col-12 col-sm-12 col-md-10 col-lg-10 padlock-section-body">
                            <div class="row">
                                @isset($data['addon'])
                                    @foreach ($data['addon'] as $addon)
                                        <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-checkbox">
                                            <div class="form-check">
                                                <input class="form-check-input addon" name="addon[]" id="cctv_for_customer_check" type="checkbox" value="{{$addon->id}}" @foreach($data['leadaddon'] as $selected) {{ ($selected == $addon->id) ? "checked" : "" }} @endforeach id="flexCheckDefault" />
                                                <label class="check-container" for="flexCheckDefault">{{$addon->name}}</label>
                                                <input type="text" class="no-bottom-margin addon-price form-control" placeholder="Price" name="addonprice[]" value="{{$addon->price}}" style="height:35px;width:100px;padding: 0px 8px">
                                            </div>
                                        </div>
                                    @endforeach
                                @endisset
                            </div>
                        </div>
                    </div>
                    <div class="row insurance-sections">
                        <div class="offset-sm-2 offset-md-2 offset-lg-2 offset-1 col-8 col-sm-6 col-md-4 col-lg-4 insurance-section-header">
                            Insurance</div>
                        <div class="offset-md-1 offset-lg-1 col-12 col-sm-12 col-md-10 col-lg-10 insurance-section-body">
                            <div class="row">
                                <div class="offset-lg-1 offset-md-1 col-12 col-sm-12 col-md-10 col-lg-10">
                                    <p>Insure your goods</p>
                                    <div class="separator"></div>
                                    <div class="row">
                                        <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                            <div class="form-check">
                                                <input class="form-check-input" name="insurance" type="radio" value="cover" {{ ($lead->insurence == "cover")? "checked" : "" }}   id="flexCheckDefault" />
                                                <label class="check-container" for="flexCheckDefault">Choose your own cover (100 AED per 100,000 AED cover)</label>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                            <p class="text-right">AED 25.00/mo</p>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-6 col-lg-6">

                                            <input type="text" class="form-control" placeholder="Enter value of your goods" name="goodsval" value="{{$lead->goods}}" style="height:35px;">

                                        </div>
                                        <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                            <p class="text-right">Cover AED 25000.00</p>
                                        </div>
                                    </div>
                                    <div class="separator"></div>

                                    <div class="form-check">
                                        <input class="form-check-input" name="insurance" type="radio" value="nothanks" {{ ($lead->insurence == "nothanks")? "checked" : "" }}  id="flexCheckDefault" />
                                        <label class="check-container" for="flexCheckDefault">No Thanks</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row submission-sections">
                        <button class="offset-md-1 offset-lg-1 btn btn-qoutation btn-sm active btn-submit float-end" type="submit">Save Estimate</button>
                    </div>
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
            $("#companyfeild").hide();

            $("input[name='type']").click(function() {
                if ($("#com").is(":checked")) {
                    $("#companyfeild").show();
                } else {
                    $("#companyfeild").hide();
                }
            });

            $('#EstimateForm').on('submit', function(e) {
                e.preventDefault();
               var formData=$('#EstimateForm').serialize()
                $.ajax({
                    type: "get",
                    url: '{{ url('admin/save-estimate') }}',
                    data: formData,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('.btn-submit').text('Saving...');
                        $(".btn-submit").prop("disabled", true);
                    },
                    success: function(data) {
                        if (data.success) {
                            // $('#EstimateForm')[0].reset();
                            toastr.success(data.success);
                            window.location.href = "{{ url('admin/estimate')}}";
                        }
                        if (data.errors) {
                            toastr.error(data.errors);
                            $('.btn-submit').text('Save');
                            $(".btn-submit").prop("disabled", false);
                        }
                    },
                    complete: function(data) {
                        $(".btn-submit").html("Save");
                        $(".btn-submit").prop("disabled", false);
                    },
                    error: function() {
                        toastr.error('any technical error');
                        $('.btn-submit').text('Save');
                        $(".btn-submit").prop("disabled", false);
                    }
                });
            });
        });
    </script>

@endsection