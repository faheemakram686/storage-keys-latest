@extends('backend.layouts.app')
@section('title', '| Lead Estimate')
@section('content')
    <div class="components-preview wide-md mx-auto">
        <div class="nk-block nk-block-lg">
            <div class="nk-block-head">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h4 class="title nk-block-title">Create Estimate</h4>
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
                                    <form method="post" action="{{ url('admin/add-estimate') }}" id="EstimateForm">
                                        @csrf
                                        <input type="hidden" name="lead_id" value="1">
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
                                                                            <option value="{{ $customer->id }}" @isset($id) {{(($customer->id == $id)? 'selected':'')}} @endisset >{{$customer->customer_name }}</option>
                                                                        @endforeach
                                                                    @endisset
                                                                </select>
                                                            </div>
                                                            <div class="col-6">
                                                                <label class="">Requested date</label>
                                                                <input type="date" class="form-control" name="r_date" value="" style="height:35px;" required>
                                                            </div>
                                                            <div class="col-6">
                                                                <label class="">Estimate date</label>
                                                                <input type="date" class="form-control" name="estimate_date" value="" style="height:35px;" required>
                                                            </div>
                                                            <div class="col-6">
                                                                <label class="">Expiry date</label>
                                                                <input type="date" class="form-control" name="expiry_date" value="" style="height:35px;" required>
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
                                                                            <option value="{{ $country->id }}"  >{{$country->name }}</option>
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
                                                            <div class="col-6">
                                                                <label  class="lbl" >Storage Unit</label>
                                                                <div class="input-group">
                                                                    <select class="form-control su_id" name="su_picker" id="su_id">
                                                                        <option value="">Choose One</option>
                                                                    </select>
                                                                    <div class="input-group-append">
                                                                        <button type="button" class="btn btn-primary" id="btn_add_est_unit" style="height:35px;">Add</button>
                                                                    </div>
                                                                </div>
                                                                <small class="text-muted">Select a unit and click Add. You can add multiple units.</small>
                                                            </div>
                                                            <div class="col-6">
                                                                <label  class="status" >Estimate Status</label>
                                                                <select  class="form-control select2" data-live-search="true" name="status" id="status" required>
                                                                    <option value="" selected>Select Estimate Status</option>
                                                                    <option value="3" >Approved</option>
                                                                    <option value="2" >Approved Level 2</option>
                                                                    <option value="1">Approved Level 1</option>
                                                                    <option value="0">Not Approved</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-12 mt-3">
                                                                <label class="lbl">Unit Pricing (AED/mo)</label>
                                                                <table class="table table-sm table-bordered" id="estimate_units_price_table">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Unit</th>
                                                                            <th style="width:180px;">Price</th>
                                                                            <th style="width:80px;">Action</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody></tbody>
                                                                </table>
                                                                <div id="estimate_su_ids_inputs"></div>
                                                                <p class="text-muted" id="estimate_units_empty">No units selected yet. Use Add above.</p>
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
                                                                        <input class="form-check-input" name="term_length" type="radio" value="{{$term_length->id}}"  id="flexCheckDefault" />
                                                                        <label class="check-container" for="flexCheckDefault">{{$term_length->title}}</label>
                                                                    </div>
                                                                </div>
                                                                @if($term_length->term_period ==1 )
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
                                                                    <input class="form-check-input addon" name="addon[]" id="cctv_for_customer_check" type="checkbox" value="{{$addon->id}}"  id="flexCheckDefault" />
                                                                    <label class="check-container" for="flexCheckDefault">{{$addon->name}}</label>
                                                                    <input type="text" class="no-bottom-margin addon-price form-control" placeholder="Price" name="addonprice[]" value="{{$addon->price}}" style="height:35px;width:100px;padding: 0px 8px">
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
                                                    'selectedInsuranceId' => null,
                                                    'goodsValue' => null,
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
                                                                    <input class="form-check-input" name="email_template" type="radio" value="{{$email_temp->id}}"   id="flexCheckDefault" />
                                                                    <label class="check-container" for="flexCheckDefault">{{$email_temp->temp_name}}</label>
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
                                                            @foreach( $data['req_docs'] as $req_docs)
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" name="require_document[]" type="checkbox" value="{{$req_docs->id}}"   id="flexCheckDefault" />
                                                                            <label class="check-container" for="flexCheckDefault">{{$req_docs->title}}</label>
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
                                                    <button type="submit" class="btn btn-md btn-submit btn-primary" data-button="submit">Save Estimate</button>
{{--                                                    <button type="submit" class="btn btn-md btn-primary" data-button="submit">Save estimate and send</button>--}}
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

            var estimateUnitsMap = {};

            function renderEstimateUnitsTable() {
                var $tbody = $('#estimate_units_price_table tbody');
                var $inputs = $('#estimate_su_ids_inputs');
                $tbody.empty();
                $inputs.empty();
                var ids = Object.keys(estimateUnitsMap);
                if (ids.length === 0) {
                    $('#estimate_units_empty').show();
                    return;
                }
                $('#estimate_units_empty').hide();
                ids.forEach(function(id) {
                    var u = estimateUnitsMap[id];
                    $tbody.append(
                        '<tr data-id="'+id+'"><td>'+u.name+'</td>'+
                        '<td><input type="number" step="any" class="form-control" name="unit_prices[]" value="'+u.price+'" required style="height:35px;"></td>'+
                        '<td><button type="button" class="btn btn-sm btn-danger btn-remove-est-unit" data-id="'+id+'">X</button></td></tr>'
                    );
                    $inputs.append('<input type="hidden" name="su_ids[]" value="'+id+'">');
                });
            }

            function getStorageUnit(warehouse_id) {
                $.ajax({
                    url: '{{ url('get-storageunit') }}',
                    type: 'get',
                    async: false,
                    dataType: 'json',
                    data: { warehouse_id: warehouse_id },
                    success: function(data) {
                        var $su = $('.su_id');
                        var html = '<option value="">Choose One</option>';
                        if (data.length > 0) {
                            for (var i = 0; i < data.length; i++) {
                                html += '<option value="'+data[i].id+'" data-name="'+data[i].storage_unit_name+'" data-price="'+(data[i].price || 0)+'">'+data[i].storage_unit_name+'</option>';
                            }
                        } else {
                            html = '<option value="">No Storage Unit Found</option>';
                        }
                        $su.html(html);
                    },
                    error: function() {
                        toastr.error('any technical error');
                    }
                });
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
                if (estimateUnitsMap[id]) {
                    toastr.error('This unit is already added.');
                    return;
                }
                estimateUnitsMap[id] = { name: name, price: price };
                renderEstimateUnitsTable();
                $('#su_id').val('');
            });

            $(document).on('click', '.btn-remove-est-unit', function() {
                var id = $(this).data('id');
                delete estimateUnitsMap[id];
                renderEstimateUnitsTable();
            });

            $('#EstimateForm').on('submit', function(e) {
                e.preventDefault();
                if (Object.keys(estimateUnitsMap).length === 0) {
                    toastr.error('Please select at least one storage unit.');
                    return;
                }
                var formData=$('#EstimateForm').serialize()

                $.ajax({
                    type: "get",
                    url: '{{ url('admin/add-estimate') }}',
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
                    error: function(xhr) {
                        if (xhr) xhr._handledByPage = true;
                        var msg =
                            (xhr && xhr.responseJSON && (xhr.responseJSON.errors || xhr.responseJSON.error)) ||
                            'any technical error';
                        toastr.error(msg);
                        $('.btn-submit').text('Save');
                        $(".btn-submit").prop("disabled", false);
                    }
                });
            });
        });
    </script>

@endsection



