@extends('backend.layouts.app')
@section('title', '| Edit Estimate')
@section('content')
    <div class="components-preview wide-md mx-auto">
        <div class="nk-block nk-block-lg">
            <div class="nk-block-head">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h4 class="title nk-block-title">Edit Estimate</h4>
                    </div>
                    <a href="{{url("admin/estimate")}}" class="btn btn-primary btn-sm d-none d-md-inline-flex"><em class="icon ni ni-arrow-left"></em><span>Back</span></a>
                </div>
            </div>
            <div class="card">
                <div class="card-inner">
                    @isset($data)
                    <div class="container">
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12 details-section">
                                    <form method="post" action="{{ url('admin/update-estimate') }}" id="EstimateUpdateForm">
                                        @csrf
                                        <input type="hidden" name="estimate_id" value="{{$data['estimate'][0]->id}}">
                                        <div class="row reservations-sections">
                                            <div class="offset-sm-2 offset-md-2 offset-lg-2 offset-1 col-8 col-sm-6 col-md-4 col-lg-4 term-section-header">
                                                Estimate Information</div>
                                            <div class="offset-md-1 offset-lg-1 col-12 col-sm-12 col-md-10 col-lg-10 term-section-body">
                                                <div class="row">
                                                    <div class="offset-lg-1 offset-md-1 col-12 col-sm-12 col-md-10 col-lg-10">

                                                        <div class="row mt-3">

                                                            <div class=" col-6">
                                                                <label class="lbl">Customer</label>
                                                                <select class="selectpicker form-control select2" name="customer_id" id="customer_id">
                                                                    <option value="" selected >Choose One</option>
                                                                    @isset($data)
                                                                        @foreach ($data['customer'] as $customer)
                                                                            <option value="{{ $customer->id }}" {{ (($customer->id == $data['estimate'][0]->customer_id)? 'selected':'') }} >{{$customer->customer_name }}</option>
                                                                        @endforeach
                                                                    @endisset
                                                                </select>
                                                            </div>
                                                            <div class="col-6">
                                                                <label class="">Requested date</label>
                                                                <input type="date" class="form-control" name="r_date" value="{{$data['estimate'][0]->r_date}}" style="height:35px;" required>
                                                            </div>
                                                            <div class="col-6">
                                                                <label class="">Estimate date</label>
                                                                <input type="date" class="form-control" name="estimate_date" value="{{$data['estimate'][0]->estimate_date}}" style="height:35px;" required>
                                                            </div>
                                                            <div class="col-6">
                                                                <label class="">Expiry date</label>
                                                                <input type="date" class="form-control" name="expiry_date" value="{{$data['estimate'][0]->expiry_date}}" style="height:35px;" required>
                                                            </div>
                                                        </div>

                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="row reservations-sections">
                                            <div class="offset-sm-2 offset-md-2 offset-lg-2 offset-1 col-8 col-sm-6 col-md-4 col-lg-4 term-section-header">
                                                Alternative Contact Information</div>
                                            <div class="offset-md-1 offset-lg-1 col-12 col-sm-12 col-md-10 col-lg-10 term-section-body">
                                                <div class="row">
                                                    <div class="offset-lg-1 offset-md-1 col-12 col-sm-12 col-md-10 col-lg-10">
                                                        <div class="row mt-3">
                                                            <div class="col-6">
                                                                <label class="">Name</label>
                                                                <input type="text" class="form-control" name="alt_contact_name" value="{{$data['estimate'][0]->alt_contact_name}}" style="height:35px;">
                                                            </div>
                                                            <div class="col-6">
                                                                <label class="">Email</label>
                                                                <input type="email" class="form-control" name="alt_contact_email" value="{{$data['estimate'][0]->alt_contact_email}}" style="height:35px;">
                                                            </div>
                                                        </div>
                                                        <div class="row mt-3">
                                                            <div class="col-6">
                                                                <label class="">Mobile number</label>
                                                                <input type="text" class="form-control" name="alt_contact_mobile" value="{{$data['estimate'][0]->alt_contact_mobile}}" style="height:35px;">
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
                                                                <select class="form-control " data-live-search="true" name="country_id" id="country_id">
                                                                    <option value="" selected >Choose One</option>
                                                                    @isset($data)
                                                                        @foreach ($data['loc'] as $country)
                                                                            <option value="{{ $country->id }}" {{ (isset($data['estimate'][0]->storageunit->warehouse->loc->city->country_id) && $country->id == $data['estimate'][0]->storageunit->warehouse->loc->city->country_id ? 'selected':'') }} >{{$country->name }}</option>
                                                                        @endforeach
                                                                    @endisset
                                                                </select>
                                                            </div>
                                                            <div class="col-6">
                                                                <label  class="lbl" >City</label>
                                                                <select name="city_id" class=" form-control  citySection " data-live-search="true" id="citySection" >
                                                                    <option value="">Choose One</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-6">
                                                                <label  class="lbl" >Location</label>
                                                                <select class="form-control loc_id " data-live-search="true" name="loc_id" id="loc_id">
                                                                    <option value="">Choose One</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-6">
                                                                <label  class="lbl" >Warehouse</label>
                                                                <select class=" form-control warehouse_id " data-live-search="true" name="warehouse_id" id="warehouse_id">
                                                                    <option value="">Choose One</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-12">
                                                                <label class="lbl">Selected Units & Pricing (AED/mo)</label>
                                                                <table class="table table-sm table-bordered" id="estimate_units_main_table">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Unit</th>
                                                                            <th style="width:180px;">Price</th>
                                                                            <th style="width:80px;">Action</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody id="estimate_units_main_tbody">
                                                                        @php
                                                                            $estUnits = $data['estimate'][0]->estimateStorageUnits ?? collect([]);
                                                                            if ($estUnits->isEmpty() && $data['estimate'][0]->storageunit) {
                                                                                $estUnits = collect([(object)[
                                                                                    'storage_unit_id' => $data['estimate'][0]->su_id,
                                                                                    'unit_price' => $data['estimate'][0]->unit_price,
                                                                                    'storageunit' => $data['estimate'][0]->storageunit,
                                                                                ]]);
                                                                            }
                                                                        @endphp
                                                                        @foreach($estUnits as $estUnit)
                                                                        <tr data-id="{{ $estUnit->storage_unit_id }}">
                                                                            <td>
                                                                                {{ optional($estUnit->storageunit)->storage_unit_name ?? ('Unit #'.$estUnit->storage_unit_id) }}
                                                                                <input type="hidden" name="su_ids[]" value="{{ $estUnit->storage_unit_id }}" class="est-su-id">
                                                                            </td>
                                                                            <td>
                                                                                <input type="number" step="any" class="form-control" name="unit_prices[]" value="{{ $estUnit->unit_price }}" required style="height:35px;">
                                                                            </td>
                                                                            <td>
                                                                                <button type="button" class="btn btn-sm btn-danger btn-remove-est-unit" data-id="{{ $estUnit->storage_unit_id }}">X</button>
                                                                            </td>
                                                                        </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                                <p class="text-muted mb-0" id="estimate_units_empty" style="{{ $estUnits->isEmpty() ? '' : 'display:none;' }}">No units selected yet.</p>
                                                            </div>
                                                            <div class="col-6 mt-3">
                                                                <label  class="lbl" >Add Unit</label>
                                                                <input type="hidden" name="old_su_id" id="old_su_id" value="{{$data['estimate'][0]->su_id}}">
                                                                <div class="input-group">
                                                                    <select class="form-control su_id" name="su_picker" id="su_id">
                                                                        <option value="">Choose One</option>
                                                                    </select>
                                                                    <div class="input-group-append">
                                                                        <button type="button" class="btn btn-primary" id="btn_add_est_unit" style="height:35px;">Add</button>
                                                                    </div>
                                                                </div>
                                                                <small class="text-muted">Pick warehouse cascade above, then Add units here.</small>
                                                            </div>
                                                            <div class="col-6 mt-3">
                                                                <label  class="status" >Estimate Status</label>
                                                                <select  class="form-control select2" data-live-search="true" name="status" id="status" required>
                                                                    <option value="" selected>Select Estimate Status</option>
                                                                    <option value="3" {{ ($data['estimate'][0]->status == 'Approved' ? 'selected':'') }}>Approved</option>
                                                                    <option value="2" {{ ($data['estimate'][0]->status == 'Approved Level 2' ? 'selected':'') }}>Approved Level 2</option>
                                                                    <option value="1" {{ ($data['estimate'][0]->status == 'Approved Level 1' ? 'selected':'') }}>Approved Level 1</option>
                                                                    <option value="0" {{ ($data['estimate'][0]->status == 'Not Approved' ? 'selected':'') }}>Not Approved</option>
                                                                    <option value="4" {{ ($data['estimate'][0]->status == 'Declined' ? 'selected':'') }}>Declined</option>
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
                                                                <div class="col-9">
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" name="term_length" type="radio" value="{{$term_length->id}}" {{($term_length->id == $data['estimate'][0]->term_length ? 'checked' : '')}} id="term_{{$term_length->id}}" />
                                                                        <label class="check-container" for="term_{{$term_length->id}}">{{$term_length->title}}</label>
                                                                    </div>
                                                                </div>
                                                                @if($term_length->term_period == 1 )
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
                                                        @php
                                                            $selectedAddons = !empty($data['estimate'][0]->addon) ? explode(',', $data['estimate'][0]->addon) : [];
                                                            $addonPrices = $data['estimate'][0]->estimateAddon->pluck('price', 'addon_id')->toArray();
                                                        @endphp
                                                        @foreach ($data['addon'] as $addon)
                                                            <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-checkbox">
                                                                <div class="form-check">
                                                                    <input class="form-check-input addon" name="addon[]" id="addon_{{$addon->id}}" type="checkbox" value="{{$addon->id}}" {{ (in_array($addon->id, $selectedAddons) ? 'checked' : '') }} />
                                                                    <label class="check-container" for="addon_{{$addon->id}}">{{$addon->name}}</label>
                                                                    <input type="text" class="no-bottom-margin addon-price form-control" placeholder="Price" name="addonprice[]" value="{{ $addonPrices[$addon->id] ?? $addon->price }}" style="height:35px;width:100px;padding: 0px 8px">
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
                                            <div class="offset-sm-2 offset-md-2 offset-lg-2 offset-1 col-8 col-sm-6 col-md-4 col-lg-4 padlock-section-header">
                                                Insurance</div>
                                            <div class="offset-md-1 offset-lg-1 col-12 col-sm-12 col-md-10 col-lg-10 insurance-section-body">
                                                @include('partials.insurance-selector', [
                                                    'insurances' => $data['insurances'] ?? collect([]),
                                                    'selectedInsuranceId' => $data['estimate'][0]->insurance_id ?? null,
                                                    'goodsValue' => $data['estimate'][0]->goods ?? null,
                                                    'readonly' => false,
                                                ])
                                            </div>
                                        </div>
                                        <div class="row reservations-sections">
                                            <div class="offset-sm-2 offset-md-2 offset-lg-2 offset-1 col-8 col-sm-6 col-md-4 col-lg-4 term-section-header">
                                                Email Template</div>
                                            <div class="offset-md-1 offset-lg-1 col-12 col-sm-12 col-md-10 col-lg-10 term-section-body">
                                                <div class="row">
                                                    <div class="offset-lg-1 offset-md-1 col-12 col-sm-12 col-md-10 col-lg-10">
                                                        <p>Select email template!</p>
                                                        @isset($data['email_temp'])
                                                            @foreach( $data['email_temp'] as $email_temp)
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" name="email_template" type="radio" value="{{$email_temp->id}}" {{($email_temp->id == $data['estimate'][0]->email_template ? 'checked' : '')}} id="temp_{{$email_temp->id}}" />
                                                                    <label class="check-container" for="temp_{{$email_temp->id}}">{{$email_temp->temp_name}}</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                            @endforeach
                                                        @endisset

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row reservations-sections">
                                            <div class="offset-sm-2 offset-md-2 offset-lg-2 offset-1 col-8 col-sm-6 col-md-4 col-lg-4 term-section-header">
                                                Require Documents</div>
                                            <div class="offset-md-1 offset-lg-1 col-12 col-sm-12 col-md-10 col-lg-10 term-section-body">
                                                <div class="row">
                                                    <div class="offset-lg-1 offset-md-1 col-12 col-sm-12 col-md-10 col-lg-10">
                                                        <p>Select require documents for estimation!</p>
                                                        @isset($data['req_docs'])
                                                            @php
                                                                $selectedDocs = !empty($data['estimate'][0]->require_documents) ? explode(',', $data['estimate'][0]->require_documents) : [];
                                                            @endphp
                                                            @foreach( $data['req_docs'] as $req_docs)
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" name="require_document[]" type="checkbox" value="{{$req_docs->id}}" {{ (in_array($req_docs->id, $selectedDocs) ? 'checked' : '') }} id="doc_{{$req_docs->id}}" />
                                                                            <label class="check-container" for="doc_{{$req_docs->id}}">{{$req_docs->title}}</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        @endisset
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row submission-sections">
                                            <div class="offset-md-1 offset-lg-1 col-12 col-sm-12 col-md-10 col-lg-10 mt-3">
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-md btn-submit btn-primary" data-button="submit">Update Estimate</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                    </div>
                    @endisset
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            var old_country_id = "{{ $data['estimate'][0]->storageunit->warehouse->loc->city->country_id ?? '' }}";
            var old_city_id = "{{ $data['estimate'][0]->storageunit->warehouse->loc->city_id ?? '' }}";
            var old_loc_id = "{{ $data['estimate'][0]->storageunit->warehouse->loc_id ?? '' }}";
            var old_warehouse_id = "{{ $data['estimate'][0]->storageunit->warehouse_id ?? '' }}";
            var old_su_id = "{{ $data['estimate'][0]->su_id ?? '' }}";

            if(old_country_id) {
                getCities(old_country_id, old_city_id);
            }
            if(old_city_id) {
                getLocations(old_city_id, old_loc_id);
            }
            if(old_loc_id) {
                getWharehouse(old_loc_id, old_warehouse_id);
            }
            if(old_warehouse_id) {
                getStorageUnit(old_warehouse_id, old_su_id);
            }


            $("#country_id").on('change', function() {
                var country_id = $(this).val();
                getCities(country_id);
            });

            function getCities(country_id, selected_id = null) {
                $.ajax({
                    url: '{{ url('get-cities') }}',
                    type: 'get',
                    async: false,
                    dataType: 'json',
                    data: { country_id: country_id },
                    success: function(data) {
                        $('.citySection').empty();
                        getLocations(0); // Clear downstream
                        var html3 = '';
                        $('.citySection').html('<option value="">Select City</option>');
                        if (data.length > 0) {
                            for (var i = 0; i < data.length; i++) {
                                html3 += '<option value="' + data[i].id + '" ' + (selected_id == data[i].id ? 'selected' : '') + '>' + data[i].city_name + '</option>';
                            }
                        } else {
                            html3 = '<option value="">No Cities Found</option>';
                        }
                        $('.citySection').append(html3);
                    }
                });
            }

            $(".citySection").on('change', function() {
                var city_id = $(this).val();
                getLocations(city_id);
            });

            function getLocations(city_id, selected_id = null) {
                $.ajax({
                    url: '{{ url('get-locations') }}',
                    type: 'get',
                    async: false,
                    dataType: 'json',
                    data: { city_id: city_id },
                    success: function(data) {
                        $('.loc_id').empty();
                        getWharehouse(0); // Clear downstream
                        var html3 = '';
                        $('.loc_id').html('<option value="">Select Location</option>');
                        if (data.length > 0) {
                            for (var i = 0; i < data.length; i++) {
                                html3 += ' <option value='+data[i].id+' ' + (selected_id == data[i].id ? 'selected' : '') + '> '+data[i].loc_name+'</option>';
                            }
                        } else {
                            html3 = '<option value="">No Location Found</option>';
                        }
                        $('.loc_id').append(html3);
                    }
                });
            }

            $(".loc_id").on('change', function() {
                var loc_id = $(this).val();
                getWharehouse(loc_id);
            });

            function getWharehouse(loc_id, selected_id = null) {
                $.ajax({
                    url: '{{ url('get-warehouse') }}',
                    type: 'get',
                    async: false,
                    dataType: 'json',
                    data: { loc_id: loc_id },
                    success: function(data) {
                        $('.warehouse_id').empty();
                        getStorageUnit(0); // Clear downstream
                        var html3 = '';
                        $('.warehouse_id').html('<option value="">Select Warehouse</option>');
                        if (data.length > 0) {
                            for (var i = 0; i < data.length; i++) {
                                html3 += ' <option value='+data[i].id+' ' + (selected_id == data[i].id ? 'selected' : '') + '> '+data[i].name+'</option>';
                            }
                        } else {
                            html3 = '<option value="">No Warehouse Found</option>';
                        }
                        $('.warehouse_id').append(html3);
                    }
                });
            }

            $(".warehouse_id").on('change', function() {
                var warehouse_id = $(this).val();
                getStorageUnit(warehouse_id);
            });

            function getStorageUnit(warehouse_id, selected_id = null) {
                $.ajax({
                    url: '{{ url('get-storageunit') }}',
                    type: 'get',
                    async: false,
                    dataType: 'json',
                    data: { warehouse_id: warehouse_id },
                    success: function(data) {
                        var html = '<option value="">Choose One</option>';
                        if (data.length > 0) {
                            for (var i = 0; i < data.length; i++) {
                                html += '<option value="'+data[i].id+'" data-name="'+data[i].storage_unit_name+'" data-price="'+(data[i].price || 0)+'" '+(selected_id == data[i].id ? 'selected' : '')+'>'+data[i].storage_unit_name+'</option>';
                            }
                        } else if (warehouse_id) {
                            html = '<option value="">No Storage Unit Found</option>';
                        }
                        $('.su_id').html(html);
                    }
                });
            }

            function toggleEstimateUnitsEmpty() {
                var hasRows = $('#estimate_units_main_tbody tr').length > 0;
                if (hasRows) {
                    $('#estimate_units_empty').hide();
                } else {
                    $('#estimate_units_empty').show();
                }
            }

            $('#btn_add_est_unit').on('click', function() {
                var $opt = $('#su_id option:selected');
                var id = $opt.val();
                var name = ($opt.data('name') || $opt.text() || '').toString().trim();
                var price = $opt.data('price') || 0;
                if (!id) {
                    toastr.error('Please select a storage unit first.');
                    return;
                }
                if ($('#estimate_units_main_tbody tr[data-id="'+id+'"]').length) {
                    toastr.error('This unit is already added.');
                    return;
                }
                $('#estimate_units_main_tbody').append(
                    '<tr data-id="'+id+'">'+
                    '<td>'+name+'<input type="hidden" name="su_ids[]" value="'+id+'" class="est-su-id"></td>'+
                    '<td><input type="number" step="any" class="form-control" name="unit_prices[]" value="'+price+'" required style="height:35px;"></td>'+
                    '<td><button type="button" class="btn btn-sm btn-danger btn-remove-est-unit" data-id="'+id+'">X</button></td>'+
                    '</tr>'
                );
                toggleEstimateUnitsEmpty();
                $('#su_id').val('');
            });

            $(document).on('click', '.btn-remove-est-unit', function() {
                $(this).closest('tr').remove();
                toggleEstimateUnitsEmpty();
            });

            $('#EstimateUpdateForm').on('submit', function(e) {
                e.preventDefault();
                if ($('#estimate_units_main_tbody tr').length === 0) {
                    toastr.error('Please select at least one storage unit.');
                    return;
                }
                var formData=$(this).serialize();
                $.ajax({
                    type: "post",
                    url: '{{ url('admin/update-estimate') }}',
                    data: formData,
                    beforeSend: function() {
                        $('.btn-submit').text('Updating...');
                        $(".btn-submit").prop("disabled", true);
                    },
                    success: function(data) {
                        if (data.success) {
                            toastr.success(data.success);
                            window.location.href = "{{ url('admin/estimate')}}";
                        }
                        if (data.errors) {
                            toastr.error(data.errors);
                            $('.btn-submit').text('Update Estimate');
                            $(".btn-submit").prop("disabled", false);
                        }
                    },
                    complete: function() {
                        $(".btn-submit").html("Update Estimate");
                        $(".btn-submit").prop("disabled", false);
                    },
                    error: function(xhr) {
                        var msg = (xhr.responseJSON && (xhr.responseJSON.errors || xhr.responseJSON.error)) || 'Technical error occurred';
                        toastr.error(msg);
                        $('.btn-submit').text('Update Estimate');
                        $(".btn-submit").prop("disabled", false);
                    }
                });
            });
        });
    </script>
@endsection
