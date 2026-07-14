@extends('backend.layouts.app')
@section('title', '| Edit Lead')
@section('content')
    <div class="components-preview wide-md mx-auto">
        <div class="nk-block nk-block-lg">
            <div class="nk-block-head">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h4 class="title nk-block-title">Edit Lead</h4>
                    </div>
                    <a href="{{url("admin/leads")}}" class="btn btn-primary btn-sm d-none d-md-inline-flex"><em class="icon ni ni-arrow-left"></em><span>Back</span></a>
                </div>
            </div>
            <div class="card">
                <div class="card-inner">
                    @isset($data)
                    <div class="container">
                        @php
                            $selectedSu = isset($data['su'][0]) ? $data['su'][0] : null;
                            $selectedCountryId = optional(optional(optional($selectedSu)->warehouse)->loc)->city->country->id ?? '';
                            $selectedCityId = optional(optional(optional($selectedSu)->warehouse)->loc)->city->id ?? '';
                            $selectedLocationId = optional(optional($selectedSu)->warehouse)->loc->id ?? '';
                            $selectedWarehouseId = optional($selectedSu)->warehouse->id ?? '';
                        @endphp
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
                                    <form method="post" action="{{ url('admin/update-lead') }}" id="UpdateLeadForm">
                                        @csrf
                                        <input type="hidden" name="lead_id" value="{{$lead->id}}">
                                        <input type="hidden" name="u_su_id" value="{{$lead->su_id}}">
                                        <div class="row reservations-sections">
                                            <div class="offset-sm-2 offset-md-2 offset-lg-2 offset-1 col-8 col-sm-6 col-md-4 col-lg-4 term-section-header">
                                                Lead Information</div>
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
                                                                <label class="lbl" >Lead Status</label>
                                                                <select class="selectpicker form-control  form-select" name="lead_status" id="lead_status">
                                                                    <option value="">Select Lead Status</option>
                                                                    @isset($data)
                                                                        @foreach ($data['status'] as $sl)
                                                                            <option value="{{ $sl->id }}" {{ ( $sl->id == $lead->status)? "selected" : "" }}>{{ $sl->title }}</option>
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
                                                                            <option value="{{ $user->id }}" {{ ( $user->id == $lead->user_res_id)? "selected" : "" }} >{{$user->first_name }} {{$user->last_name }}</option>
                                                                        @endforeach
                                                                    @endisset
                                                                </select>

                                                            </div>
                                                        </div>

                                                        <div class="row mt-3">
                                                            <div class="col-6">
                                                                <label class="">Lead Rating</label>
                                                                <input type="number" step="any" class="form-control" name="lead_rating" value="{{$lead->lead_rating}}" style="height:35px;" required>
                                                            </div>
                                                            <div class="col-6">
                                                                <label class="lbl" >Lead Source</label>
                                                                <select class="selectpicker form-control  form-select" name="lead_source" id="lead_source">
                                                                    <option value="">Select Lead Source</option>
                                                                    @isset($data)
                                                                        @foreach ($data['source'] as $sl)
                                                                            <option value="{{ $sl->id }}" {{ ( $sl->id == $lead->lead_source)? "selected" : "" }} >{{ $sl->title }}</option>
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
                                                                <input type="text" class="form-control" name="mobile1" value="{{$lead->mobile1}}" style="height:35px;" >
                                                            </div>
                                                            <div class="col-6">
                                                                <label class="">Mobile 2</label>
                                                                <input type="text" class="form-control" name="mobile2" value="{{$lead->mobile2}}"  style="height:35px;" >
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
                                                        {{--<p>Select storage unit</p>--}}
                                                        <div class="row">
                                                            <div class=" col-6">
                                                                <label class="lbl" >Country</label>
                                                                <select class="selectpicker form-control" name="country_id" id="country_id">
                                                                    <option value="" selected >Choose One</option>
                                                                    @isset($data)
                                                                        @foreach ($data['loc'] as $country)
                                                                            <option value="{{ $country->id }}" {{ ($selectedCountryId == $country->id) ? "selected" : "" }} >{{$country->name }}</option>
                                                                        @endforeach
                                                                    @endisset
                                                                </select>
                                                            </div>
                                                            <div class="col-6">
                                                                <label  class="lbl" >City</label>
                                                                <select name="city_id" class=" selectpicker form-control  citySection" id="citySection" data="{{ $selectedCityId }}" >
                                                                    <option value="">Choose One</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-6">
                                                                <label  class="lbl" >Location</label>
                                                                <select class="selectpicker form-control loc_id" name="loc_id" id="loc_id" data="{{ $selectedLocationId }}">
                                                                    <option value="">Choose One</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-6">
                                                                <label  class="lbl" >Warehouse</label>
                                                                <select class="selectpicker form-control warehouse_id" name="warehouse_id" id="warehouse_id" data="{{ $selectedWarehouseId }}">
                                                                    <option value="">Choose One</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-6">
                                                                <label  class="lbl" >Storage Unit</label>
                                                                <div class="input-group">
                                                                    <select class="form-control su_id" name="su_picker" id="su_id">
                                                                        <option value="">Choose One</option>
                                                                    </select>
                                                                    <div class="input-group-append">
                                                                        <button type="button" class="btn btn-primary" id="btn_add_su_unit" style="height:35px;">Add</button>
                                                                    </div>
                                                                </div>
                                                                <small class="text-muted">Select a unit and click Add. You can add multiple units.</small>
                                                            </div>
                                                            <div class="col-12 mt-3">
                                                                <label class="lbl">Selected Units</label>
                                                                <div id="selected_units_container">
                                                                    <table class="table table-sm table-bordered" id="selected_units_table" style="display:none;">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>Unit</th>
                                                                                <th style="width:80px;">Action</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody></tbody>
                                                                    </table>
                                                                    <p class="text-muted mb-0" id="selected_units_empty">No units selected yet.</p>
                                                                </div>
                                                                <div id="su_ids_inputs"></div>
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
                                                            <div class="col-9">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" name="term_length" type="radio" value="{{$term_length->id}}" {{ ($lead->price== $term_length->id)? "checked" : "" }}  id="flexCheckDefault" />
                                                                    <label class="check-container" for="flexCheckDefault">{{$term_length->title}}</label>
                                                                </div>
                                                            </div>
                                                            @if($term_length->term_period == 1)
                                                            <div class="col-3 d-flex">
                                                                <p class="no-bottom-margin text-right">Fixed Price</p>
                                                            </div>
                                                            @else
                                                                <div class="col-3 d-flex">
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
                                                                    <input class="form-check-input addon" name="addon[]" id="cctv_for_customer_check" type="checkbox" value="{{$addon->id}}" @foreach($data['leadaddon'] as $selected) {{ ($selected == $addon->id) ? "checked" : "" }} @endforeach id="flexCheckDefault" />
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
                                                @include('partials.insurance-selector', [
                                                    'insurances' => $data['insurances'] ?? collect([]),
                                                    'selectedInsuranceId' => $lead->insurance_id ?? null,
                                                    'goodsValue' => $lead->goods ?? null,
                                                    'readonly' => false,
                                                ])
                                            </div>
                                        </div>
                                        <div class="row submission-sections">
                                            <div class="offset-md-1 offset-lg-1 col-12 col-sm-12 col-md-10 col-lg-10 mt-4">
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-md btn-primary btn-update" data-button="submit">Save Changes</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @endisset
                </div>
            </div>
        </div>
    </div>


    <script>
        $(document).ready(function() {

            // $("#companyfeild").hide();
            if ($("#com").is(":checked")) {
                $("#companyfeild").show();
            } else {
                $("#companyfeild").hide();
            }

            $("input[name='type']").click(function() {
                if ($("#com").is(":checked")) {
                    $("#companyfeild").show();
                } else {
                    $("#companyfeild").hide();
                }
            });

            function showReqError(xhr) {
                if (typeof window.showAjaxError === 'function') {
                    window.showAjaxError(xhr, 'any technical error');
                } else {
                    toastr.error('any technical error');
                }
            }

            function populateSelect($el, items, valueKey, labelKey, selectedValue, emptyLabel) {
                var html = '<option value="">' + emptyLabel + '</option>';
                if (items && items.length > 0) {
                    for (var i = 0; i < items.length; i++) {
                        var selected = (String(items[i][valueKey]) === String(selectedValue)) ? 'selected' : '';
                        html += '<option ' + selected + ' value="' + items[i][valueKey] + '">' + items[i][labelKey] + '</option>';
                    }
                }
                $el.html(html);
                if ($el.hasClass('selectpicker') && typeof $el.selectpicker === 'function') {
                    $el.selectpicker('refresh');
                }
            }

            function populateMultiSelect($el, items, valueKey, labelKey, selectedValues) {
                // Kept for cascade resets: always a single-select unit list
                var html = '<option value="">Choose One</option>';
                if (items && items.length > 0) {
                    for (var i = 0; i < items.length; i++) {
                        html += '<option value="' + items[i][valueKey] + '" data-name="' + items[i][labelKey] + '">' + items[i][labelKey] + '</option>';
                    }
                }
                $el.html(html);
            }

            var selectedUnitsMap = {};
            @isset($data['su'])
                @foreach($data['su'] as $preSu)
                    selectedUnitsMap[{{ $preSu->id }}] = @json($preSu->storage_unit_name);
                @endforeach
            @endisset

            function renderSelectedUnits() {
                var $tbody = $('#selected_units_table tbody');
                var $inputs = $('#su_ids_inputs');
                $tbody.empty();
                $inputs.empty();
                var ids = Object.keys(selectedUnitsMap);
                if (ids.length === 0) {
                    $('#selected_units_empty').show();
                    $('#selected_units_table').hide();
                    return;
                }
                $('#selected_units_empty').hide();
                $('#selected_units_table').show();
                ids.forEach(function(id) {
                    var name = selectedUnitsMap[id];
                    $tbody.append(
                        '<tr data-id="'+id+'"><td>'+name+'</td>'+
                        '<td><button type="button" class="btn btn-sm btn-danger btn-remove-unit" data-id="'+id+'"><em class="icon ni ni-trash"></em></button></td></tr>'
                    );
                    $inputs.append('<input type="hidden" name="su_ids[]" value="'+id+'">');
                });
            }

            function getCities(country_id, selectedCityId) {
                return $.ajax({
                    url: '{{ url('get-cities') }}',
                    type: 'get',
                    dataType: 'json',
                    data: { country_id: country_id }
                }).done(function(data) {
                    populateSelect($('.citySection'), data, 'id', 'city_name', selectedCityId, 'Select City');
                }).fail(showReqError);
            }

            function getLocations(city_id, selectedLocationId) {
                return $.ajax({
                    url: '{{ url('get-locations') }}',
                    type: 'get',
                    dataType: 'json',
                    data: { city_id: city_id }
                }).done(function(data) {
                    populateSelect($('.loc_id'), data, 'id', 'loc_name', selectedLocationId, 'Select Location');
                }).fail(showReqError);
            }

            function getWharehouse(loc_id, selectedWarehouseId) {
                return $.ajax({
                    url: '{{ url('get-warehouse') }}',
                    type: 'get',
                    dataType: 'json',
                    data: { loc_id: loc_id }
                }).done(function(data) {
                    populateSelect($('.warehouse_id'), data, 'id', 'name', selectedWarehouseId, 'Select Warehouse');
                }).fail(showReqError);
            }

            function getStorageUnit(warehouse_id) {
                return $.ajax({
                    url: '{{ url('get-storageunit') }}',
                    type: 'get',
                    dataType: 'json',
                    data: { warehouse_id: warehouse_id }
                }).done(function(data) {
                    populateMultiSelect($('.su_id'), data, 'id', 'storage_unit_name', []);
                }).fail(showReqError);
            }

            var country_id = $('select[name=country_id]').val();
            var selectedCityId = $('#citySection').attr("data") || '';
            var selectedLocationId = $('#loc_id').attr("data") || '';
            var selectedWarehouseId = $('#warehouse_id').attr("data") || '';

            renderSelectedUnits();

            if (country_id) {
                getCities(country_id, selectedCityId)
                    .then(function() { return getLocations(selectedCityId, selectedLocationId); })
                    .then(function() { return getWharehouse(selectedLocationId, selectedWarehouseId); })
                    .then(function() { return getStorageUnit(selectedWarehouseId); });
            }

            $("#country_id").on('change', function() {
                var cid = $(this).val();
                getCities(cid, '').then(function() {
                    populateSelect($('.loc_id'), [], 'id', 'loc_name', '', 'Select Location');
                    populateSelect($('.warehouse_id'), [], 'id', 'name', '', 'Select Warehouse');
                    populateMultiSelect($('.su_id'), [], 'id', 'storage_unit_name', []);
                });
            });

            $(".citySection").on('change', function() {
                var city_id = $(this).val();
                getLocations(city_id, '').then(function() {
                    populateSelect($('.warehouse_id'), [], 'id', 'name', '', 'Select Warehouse');
                    populateMultiSelect($('.su_id'), [], 'id', 'storage_unit_name', []);
                });
            });

            $(".loc_id").on('change', function() {
                var loc_id = $(this).val();
                getWharehouse(loc_id, '').then(function() {
                    populateMultiSelect($('.su_id'), [], 'id', 'storage_unit_name', []);
                });
            });

            $(".warehouse_id").on('change', function() {
                var warehouse_id = $(this).val();
                getStorageUnit(warehouse_id);
            });

            $('#btn_add_su_unit').on('click', function() {
                var $opt = $('#su_id option:selected');
                var id = $opt.val();
                var name = ($opt.data('name') || $opt.text() || '').toString().trim();
                if (!id) {
                    toastr.error('Please select a storage unit first.');
                    return;
                }
                if (selectedUnitsMap[id]) {
                    toastr.error('This unit is already added.');
                    return;
                }
                selectedUnitsMap[id] = name;
                renderSelectedUnits();
                $('#su_id').val('');
            });

            $(document).on('click', '.btn-remove-unit', function() {
                var id = $(this).data('id');
                delete selectedUnitsMap[id];
                renderSelectedUnits();
            });

            $('#UpdateLeadForm').on('submit', function(e) {
                e.preventDefault();
                if (Object.keys(selectedUnitsMap).length === 0) {
                    toastr.error('Please select at least one storage unit.');
                    return;
                }
                var formData=$('#UpdateLeadForm').serialize()
                $.ajax({
                    type: "get",
                    url: '{{ url('admin/update-lead') }}',
                    data: formData,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('.btn-update').text('loading...');
                        $(".btn-update").prop("disabled", true);
                    },
                    success: function(data) {

                        if (data.success) {
                            getCities();
                            $('#UpdateLeadForm')[0].reset();
                            $('.close').click();
                            toastr.success(data.success);

                        }
                        if (data.errors) {
                            toastr.error(data.errors);
                            $('.btn-update').text('Save Changes');
                            $(".btn-update").prop("disabled", false);
                        }
                    },

                    complete: function(data) {
                        $(".btn-update").html("Save Changes");
                        $(".btn-update").prop("disabled", false);
                        window.location.href = "{{ url('admin/leads')}}";
                    },

                    error: function(xhr) {
                        var msg = (xhr.responseJSON && (xhr.responseJSON.error || xhr.responseJSON.message)) ? (xhr.responseJSON.error || xhr.responseJSON.message) : 'any technical error';
                        toastr.error(msg);
                        $('.btn-update').text('Save Changes');
                        $(".btn-update").prop("disabled", false);
                    }
                });


            });

        });
    </script>


@endsection



