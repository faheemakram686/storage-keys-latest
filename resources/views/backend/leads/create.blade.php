@extends('backend.layouts.app')
@section('title', '| Leads')
@section('content')
    <div class="components-preview wide-md mx-auto">
        <div class="nk-block nk-block-lg">
            <div class="nk-block-head">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h4 class="title nk-block-title">Create New Lead</h4>
                    </div>
                    <a href="{{url("admin/leads")}}" class="btn btn-primary btn-sm d-none d-md-inline-flex"><em class="icon ni ni-arrow-left"></em><span>Back</span></a>
                </div>
            </div>
            <div class="card">
                <div class="card-inner">

                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 details-section">
                            <form method="post" action="{{ url('admin/save-lead') }}" id="LeadForm">
                                @csrf
                                @isset($id)
                                    <input type="hidden" name="customer_id" value="{{$id}}">
                                @endisset
                                <div class="row reservations-sections">
                                    <div class="offset-sm-2 offset-md-2 offset-lg-2 offset-1 col-8 col-sm-6 col-md-4 col-lg-4 term-section-header">
                                        Lead Information</div>
                                    <div class="offset-md-1 offset-lg-1 col-12 col-sm-12 col-md-10 col-lg-10 term-section-body">
                                        <div class="row">
                                            <div class="offset-lg-1 offset-md-1 col-12 col-sm-12 col-md-10 col-lg-10">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <div class="form-check">
                                                            <input class="form-check-input" name="type" type="radio" value="individual"  id="ind" />
                                                            <label class="check-container" for="flexCheckDefault">For Individual?</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-6">
                                                        <div class="form-check">
                                                            <input class="form-check-input" name="type" type="radio" value="company"  id="com" />
                                                            <label class="check-container" for="flexCheckDefault">For Company?</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mt-3">
                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            <label class="form-label">Requested date</label>
                                                            <div class="form-control-wrap">
                                                                <div class="form-icon form-icon-right">
                                                                    <em class="icon ni ni-calendar-alt"></em>
                                                                </div>
                                                                <input type="text" name="r_date" class="form-control date-picker">
                                                            </div>
                                                            <div class="form-note">Date format <code>mm/dd/yyyy</code></div>
                                                        </div>

                                                    </div>
                                                    <div class="col-6" id="companyfeild">
                                                        <label class="">Company Name</label>
                                                        <input type="text" class="form-control" name="company_name" style="height:35px;" >
                                                    </div>

                                                </div>

                                                <div class="row mt-3">
                                                    <div class="col-6">
                                                        <label class="">First Name</label>
                                                        <input type="text" class="form-control" name="f_name" style="height:35px;" required>
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="">Last Name</label>
                                                        <input type="text" class="form-control" name="l_name" style="height:35px;" required>
                                                    </div>
                                                </div>

                                                <div class="row mt-3">
                                                    <div class="col-6">
                                                        <label class="lbl" >Lead Status</label>
                                                        <select class="selectpicker form-control  form-select" name="lead_status" id="lead_status">
                                                            <option value="">Select Lead Status</option>
                                                            @isset($data)
                                                                @foreach ($data['status'] as $sl)
                                                                    <option value="{{ $sl->id }}" selected>{{ $sl->title }}</option>
                                                                @endforeach
                                                            @endisset

                                                        </select>
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="lbl" >User Responsible</label>
                                                        <select class="selectpicker form-control  form-select" name="user_res" id="user_res">
                                                            <option value="" selected >Select User Responsible</option>
                                                            @isset($data)
                                                                @foreach ($data['user'] as $user)
                                                                    <option value="{{ $user->id }}" {{ (auth()->user()->id == $user->id)? "selected" : "" }} >{{$user->first_name }} {{$user->last_name }}</option>
                                                                @endforeach
                                                            @endisset
                                                        </select>

                                                    </div>
                                                </div>

                                                <div class="row mt-3">
                                                    <div class="col-6">
                                                        <label class="">Lead Rating</label>
                                                        <input type="number" class="form-control" name="lead_rating" style="height:35px;" required>
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="lbl" >Lead Source</label>
                                                        <select class="selectpicker form-control  form-select" name="lead_source" id="lead_source">
                                                            <option value="">Select Lead Source</option>
                                                            @isset($data)
                                                                @foreach ($data['source'] as $sl)
                                                                    <option value="{{ $sl->id }}" selected>{{ $sl->title }}</option>
                                                                @endforeach
                                                            @endisset

                                                        </select>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row reservations-sections">
                                    <div class="offset-sm-2 offset-md-2 offset-lg-2 offset-1 col-8 col-sm-6 col-md-4 col-lg-4 term-section-header">
                                        Contact Information</div>
                                    <div class="offset-md-1 offset-lg-1 col-12 col-sm-12 col-md-10 col-lg-10 term-section-body">
                                        <div class="row">
                                            <div class="offset-lg-1 offset-md-1 col-12 col-sm-12 col-md-10 col-lg-10">
                                                <div class="row mt-3">
                                                    <div class="col-6">
                                                        <label class="">Email</label>
                                                        <input type="email" class="form-control" name="email" style="height:35px;" required>
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="">Phone</label>
                                                        <input type="text" class="form-control" name="phone" style="height:35px;" required>
                                                    </div>
                                                </div>

                                                <div class="row mt-3">
                                                    <div class="col-6">
                                                        <label class="">Mobile 1</label>
                                                        <input type="text" class="form-control" name="mobile1" style="height:35px;" required>
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="">Mobile 2</label>
                                                        <input type="text" class="form-control" name="mobile2" style="height:35px;" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row reservations-sections">
                                    <div class="offset-sm-2 offset-md-2 offset-lg-2 offset-1 col-8 col-sm-6 col-md-4 col-lg-4 term-section-header">
                                        Storage Unit Information </div>
                                    <div class="offset-md-1 offset-lg-1 col-12 col-sm-12 col-md-10 col-lg-10 term-section-body">
                                        <div class="row">
                                            <div class="offset-lg-1 offset-md-1 col-12 col-sm-12 col-md-10 col-lg-10">
{{--                                                <p>Select storage unit</p>--}}
                                                <div class="row">
                                                    <div class=" col-6">
                                                        <label class="lbl" >Country</label>
                                                            <select class="selectpicker form-control" name="country_id" id="country_id">
                                                                <option value="" selected >Choose One</option>
                                                                @isset($data)
                                                                    @foreach ($data['loc'] as $country)
                                                                        <option value="{{ $country->id }}">{{$country->name }}</option>
                                                                    @endforeach
                                                                @endisset
                                                            </select>
                                                    </div>
                                                    <div class="col-6">
                                                            <label  class="lbl" >City</label>
                                                            <select name="city_id" class=" selectpicker form-control  citySection" id="citySection" >
                                                                <option value="">Choose One</option>
                                                            </select>
                                                    </div>
                                                    <div class="col-6">
                                                            <label  class="lbl" >Location</label>
                                                            <select class="selectpicker form-control loc_id" name="loc_id" id="loc_id">
                                                                <option value="">Choose One</option>
                                                            </select>
                                                    </div>
                                                    <div class="col-6">
                                                        <label  class="lbl" >Warehouse</label>
                                                        <select class="selectpicker form-control warehouse_id" name="warehouse_id" id="warehouse_id">
                                                            <option value="">Choose One</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-6">
                                                        <label  class="lbl" >Storage Unit</label>
                                                        <select class="selectpicker form-control su_id" name="id" id="su_id">
                                                            <option value="">Choose One</option>
                                                        </select>
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
                                                @isset($data['term_length'])
                                                    @foreach($data['term_length'] as $term_length)
                                                        <div class="row">
                                                            <div class="col-6">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" name="term_length" type="radio" value="{{$term_length->id}}"  id="flexCheckDefault" />
                                                                    <label class="check-container" for="flexCheckDefault">{{$term_length->title}}</label>
                                                                </div>
                                                            </div>
                                                            @if($term_length->term_period == 1)
                                                                <div class="col-6 ">
                                                                    <p class="no-bottom-margin text-right">Fixed Price</p>
                                                                </div>
                                                            @else
                                                                <div class="col-6 ">
                                                                    <p class="no-bottom-margin text-right on-sale-text">On Sale (Save {{$term_length->discount_percentage}}%)</p>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="separator"></div>
                                                    @endforeach
                                                @endisset
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row padlock-sections">
                                    <div class="offset-sm-2 offset-md-2 offset-lg-2 offset-1 col-8 col-sm-6 col-md-4 col-lg-4 padlock-section-header">
                                        Addons</div>
                                    <div class="offset-md-1 offset-lg-1 col-12 col-sm-12 col-md-10 col-lg-10 padlock-section-body">
                                        <div class="row">
                                            <div class="offset-lg-1 offset-md-1 col-12 col-sm-12 col-md-10 col-lg-10">
                                                <div class="row">
                                            @isset($data['addon'])
                                                @foreach ($data['addon'] as $addon)
                                                    <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-checkbox">
                                                        <div class="form-check">
                                                            <input class="form-check-input" name="addon[]" id="addon_id" type="checkbox" value="{{$addon->id}}"  id="flexCheckDefault" />
                                                            <label class="check-container" for="flexCheckDefault">{{$addon->name}}</label>
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
                                    <div class="offset-md-1 offset-lg-1 col-12 col-sm-12 col-md-10 col-lg-10 insurance-section-body">
                                        <div class="row">
                                            <div class="offset-lg-1 offset-md-1 col-12 col-sm-12 col-md-10 col-lg-10">
                                                <p>Insure your goods</p>
                                                <div class="separator"></div>
                                                <div class="row">
                                                    <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                                        <div class="form-check">
                                                            <input class="form-check-input" name="insurance" type="radio" value="cover"  id="flexCheckDefault" />
                                                            <label class="check-container" for="flexCheckDefault">Choose your own cover (100 AED per 100,000 AED cover)</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                                        <p class="text-right">AED 25.00/mo</p>
                                                    </div>
                                                    <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                                        <input type="text" class="form-control" placeholder="Enter value of your goods" name="goodsval" style="height:35px;">
                                                    </div>
                                                    <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                                        <p class="text-right">Cover AED 25000.00</p>
                                                    </div>
                                                </div>
                                                <div class="separator"></div>

                                                <div class="form-check">
                                                    <input class="form-check-input" name="insurance" type="radio" value="nothanks"  id="flexCheckDefault" />
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
                                    <div class="offset-md-1 offset-lg-1 col-12 col-sm-12 col-md-10 col-lg-10 terms-conditions-section-body">
                                        <div class="row">
                                            <div class="offset-lg-1 offset-md-1 col-12 col-sm-12 col-md-10 col-lg-10">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="terms" value="agree"  id="flexCheckDefault" />
                                                    <label class="check-container" for="flexCheckDefault">I agree to the standard terms and conditions of storage
                                                        keys<a href="#" class="form-link"> (click here)</a></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row submission-sections">
                                    <div class="offset-md-1 offset-lg-1 col-12 col-sm-12 col-md-10 col-lg-10 mt-4">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-md btn-primary" data-button="submit">Save</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

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

            $("#country_id").on('change', function() {
                var country_id = $(this).val();
                getCities(country_id);

            });

            function getCities(country_id) {
                $.ajax({
                    url: '{{ url('get-cities') }}',
                    type: 'get',
                    async: false,
                    dataType: 'json',
                    data: { country_id: country_id },
                    success: function(data) {
                        $('.citySection').empty();
                         getLocations();
                        var html3 = '';
                        var i;
                        var c = 0;
                        $('.citySection').html('<option value="">Select City</option>');
                        if (data.length > 0) {

                            for (i = 0; i < data.length; i++) {
                                html3 += '<option  value="' + data[i].id + '">' + data[i].city_name + '</option>';
                            }
                        } else {
                            var html3 = '<option value="">No Cities Found</option>';
                        }
                        $('.citySection').append(html3);
                    },
                    error: function() {
                        toastr.error('any technical error');
                    }
                });
            }

            $(".citySection").on('change', function() {
                var city_id = $(this).val();
                getLocations(city_id);
            });

            function getLocations(city_id) {
                $.ajax({
                    url: '{{ url('get-locations') }}',
                    type: 'get',
                    async: false,
                    dataType: 'json',
                    data: { city_id: city_id },
                    success: function(data) {
                        $('.loc_id').empty();
                        getWharehouse();
                        var html3 = '';
                        var i;
                        var c = 0;
                        $('.loc_id').html('<option value="">Select Location</option>');
                        if (data.length > 0) {

                            for (i = 0; i < data.length; i++) {
                                c++;
                                html3 += ' <option value='+data[i].id+'> '+data[i].loc_name+'</option>';
                            }
                        } else {
                            var html3 = '<option value="">No Location Found</option>';
                        }
                        $('.loc_id').append(html3);
                    },
                    error: function() {
                        toastr.error('any technical error');
                    }
                });
            }

            $(".loc_id").on('change', function() {
                var loc_id = $(this).val();
                getWharehouse(loc_id);
            });

            function getWharehouse(loc_id) {
                $.ajax({
                    url: '{{ url('get-warehouse') }}',
                    type: 'get',
                    async: false,
                    dataType: 'json',
                    data: { loc_id: loc_id },
                    success: function(data) {
                        $('.warehouse_id').empty();
                        getStorageUnit();
                        var html3 = '';
                        var i;
                        var c = 0;
                        $('.warehouse_id').html('<option value="">Select Warehouse</option>');
                        if (data.length > 0) {

                            for (i = 0; i < data.length; i++) {
                                c++;
                                html3 += ' <option value='+data[i].id+'> '+data[i].name+'</option>';
                            }
                        } else {
                            var html3 = '<option value="">No Warehouse Found</option>';
                        }
                        $('.warehouse_id').append(html3);
                    },
                    error: function() {
                        toastr.error('any technical error');
                    }
                });
            }

            $(".warehouse_id").on('change', function() {
                var warehouse_id = $(this).val();
                getStorageUnit(warehouse_id);
            });
            function getStorageUnit(warehouse_id) {
                $.ajax({
                    url: '{{ url('get-storageunit') }}',
                    type: 'get',
                    async: false,
                    dataType: 'json',
                    data: { warehouse_id: warehouse_id },
                    success: function(data) {
                        $('.su_id').empty();
                        var html3 = '';
                        var i;
                        var c = 0;
                        $('.su_id').html('<option value="">Select Storage Unit</option>');
                        if (data.length > 0) {
                            for (i = 0; i < data.length; i++) {
                                c++;
                                html3 += ' <option value='+data[i].id+'> '+data[i].storage_unit_name+'</option>';
                            }
                        } else {
                            var html3 = '<option value="">No Storage Unit Found</option>';
                        }
                        $('.su_id').append(html3);
                    },
                    error: function() {
                        toastr.error('any technical error');
                    }
                });
            }


            $('#LeadForm').on('submit', function(e) {
                e.preventDefault();
                var formData=$('#LeadForm').serialize()
                $.ajax({
                    type: "get",
                    url: '{{ url('admin/save-lead') }}',
                    data: formData,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('.btn-submit').text('Saving...');
                        $(".btn-submit").prop("disabled", true);
                    },
                    success: function(data) {
                        if (data.success) {
                            $('#LeadForm')[0].reset();
                            toastr.success(data.success);
                            {{--window.location.href = "{{ url('booking')}}";--}}
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
                        window.location.href = "{{ url('admin/leads')}}";
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



