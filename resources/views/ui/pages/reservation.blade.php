@extends('ui.layouts.frontend')
@section('title', '| Bookings')
@section('content')
    @isset($data)
    @php
        $primarySu = isset($data['su'][0]) ? $data['su'][0] : null;
    @endphp
    <div class="justify-content-center checkout-page">
        <div class="container">
            <div class="row" id="selected_units_header">
                @foreach ($data['su'] as $su)
                <div class="offset-lg-1 offset-md-1 col-12 col-sm-12 col-md-11 col-lg-11 greeting-user unit-header-row" data-su-id="{{ $su->id }}">
                    <h3 class="animated fadeIn" style="color:#FF8820;" >{{$su->warehouse->loc->city->city_name}} <span class="area-name">-{{$su->warehouse->loc->loc_name}}- {{$su->warehouse->name}} - {{$su->storage_unit_name}}</span> </h3>
                </div>
                @endforeach
            </div>

            <div class="row">
                <div class="col-12 col-sm-12 col-md-12 col-lg-12 details-section">
                    <form method="post" action="{{ url('save-lead') }}" id="LeadForm">
                        @csrf
                        @if($primarySu)
                        <input type="hidden" name="id" id="primary_su_id" value="{{$primarySu->id}}">
                        @endif
                        <div id="su_ids_inputs">
                            @foreach ($data['su'] as $su)
                            <input type="hidden" name="su_ids[]" value="{{ $su->id }}" class="su-id-input" data-name="{{ $su->storage_unit_name }}">
                            @endforeach
                        </div>

                    <div class="row reservations-sections">
                        <div class="offset-sm-2 offset-md-2 offset-lg-2 offset-1 col-8 col-sm-6 col-md-4 col-lg-4 term-section-header">
                            Storage Units</div>
                        <div class="offset-md-1 offset-lg-1 col-12 col-sm-12 col-md-10 col-lg-10 term-section-body">
                            <div class="row">
                                <div class="offset-lg-1 offset-md-1 col-12 col-sm-12 col-md-10 col-lg-10">
                                    <label class="lbl">Selected Units</label>
                                    <table class="table table-sm table-bordered" id="selected_units_table">
                                        <thead>
                                            <tr>
                                                <th>Unit</th>
                                                <th style="width:90px;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($data['su'] as $su)
                                            <tr data-id="{{ $su->id }}">
                                                <td>{{ $su->storage_unit_name }}</td>
                                                <td>
                                                    @if(!$loop->first)
                                                    <button type="button" class="btn btn-sm btn-danger btn-remove-unit" data-id="{{ $su->id }}">Remove</button>
                                                    @else
                                                    <span class="text-muted">Primary</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>

                                    <hr>
                                    <p class="mb-2"><strong>Add another unit</strong></p>
                                    <div class="row">
                                        <div class="col-6 mb-2">
                                            <label class="lbl">Country</label>
                                            <select class="form-control" name="country_id" id="country_id">
                                                <option value="">Choose One</option>
                                                @isset($data['loc'])
                                                    @foreach ($data['loc'] as $country)
                                                        <option value="{{ $country->id }}">{{$country->name }}</option>
                                                    @endforeach
                                                @endisset
                                            </select>
                                        </div>
                                        <div class="col-6 mb-2">
                                            <label class="lbl">City</label>
                                            <select name="city_id" class="form-control citySection" id="citySection">
                                                <option value="">Choose One</option>
                                            </select>
                                        </div>
                                        <div class="col-6 mb-2">
                                            <label class="lbl">Location</label>
                                            <select class="form-control loc_id" name="loc_id" id="loc_id">
                                                <option value="">Choose One</option>
                                            </select>
                                        </div>
                                        <div class="col-6 mb-2">
                                            <label class="lbl">Warehouse</label>
                                            <select class="form-control warehouse_id" name="warehouse_id" id="warehouse_id">
                                                <option value="">Choose One</option>
                                            </select>
                                        </div>
                                        <div class="col-8 mb-2">
                                            <label class="lbl">Storage Unit</label>
                                            <select class="form-control" id="extra_su_id">
                                                <option value="">Choose One</option>
                                            </select>
                                        </div>
                                        <div class="col-4 mb-2 d-flex align-items-end">
                                            <button type="button" class="btn btn-primary btn-sm" id="btn_add_unit" style="height:35px;">Add Unit</button>
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
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-check">
                                                <input class="form-check-input" name="type" type="radio" value="individual"  id="ind" />
                                                <label class="check-container" for="ind">For Individual?</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-check">
                                                <input class="form-check-input" name="type" type="radio" value="company"  id="com" />
                                                <label class="check-container" for="com">For Company?</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-3">

                                        <div class="col-6">
                                            <label class="">Requested date</label>
                                            <input type="date" class="form-control" name="r_date" style="height:35px;" required>
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
                                            <input type="text" class="form-control" name="mobile1" style="height:35px;">
                                        </div>
                                        <div class="col-6">
                                            <label class="">Mobile 2</label>
                                            <input type="text" class="form-control" name="mobile2" style="height:35px;">
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
                                                <input class="form-check-input" name="price" type="radio" value="{{$term_length->id}}"  id="flexCheckDefault" />
                                                <label class="check-container" for="flexCheckDefault">{{$term_length->title}}</label>
                                            </div>
                                        </div>
                                        @if($term_length->term_period == 1)
                                        <div class="col-6">
                                            <p class="no-bottom-margin text-right">Fixed Price</p>
                                        </div>
                                        @else
                                            <div class="col-6">
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
                            @include('partials.insurance-selector', [
                                'insurances' => $data['insurances'] ?? collect([]),
                                'selectedInsuranceId' => null,
                                'goodsValue' => null,
                                'readonly' => false,
                            ])
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
                                        <input class="form-check-input" type="checkbox" name="terms" value="agree"  id="flexCheckDefault" required />
                                        <label class="check-container" for="flexCheckDefault">I agree to the standard terms and conditions of storage
                                            keys<a href="#" data-toggle="modal" data-target="#quick_view_modal" class="form-link"> (click here)</a></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row submission-sections">
                        <button class="offset-md-1 offset-lg-1 btn btn-qoutation btn-sm active btn-submit float-end" type="submit">SUBMIT</button>
                    </div>
                    </form>
                </div>
            </div>

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

            var selectedUnitsMap = {};
            $('.su-id-input').each(function() {
                selectedUnitsMap[$(this).val()] = $(this).data('name') || $(this).val();
            });

            function syncInputsAndPrimary() {
                var $inputs = $('#su_ids_inputs');
                $inputs.empty();
                var ids = Object.keys(selectedUnitsMap);
                ids.forEach(function(id) {
                    $inputs.append('<input type="hidden" name="su_ids[]" value="'+id+'" class="su-id-input" data-name="'+selectedUnitsMap[id]+'">');
                });
                if (ids.length > 0) {
                    $('#primary_su_id').val(ids[0]);
                }
            }

            function renderSelectedUnitsTable() {
                var $tbody = $('#selected_units_table tbody');
                $tbody.empty();
                var ids = Object.keys(selectedUnitsMap);
                ids.forEach(function(id, idx) {
                    var action = idx === 0
                        ? '<span class="text-muted">Primary</span>'
                        : '<button type="button" class="btn btn-sm btn-danger btn-remove-unit" data-id="'+id+'">Remove</button>';
                    $tbody.append('<tr data-id="'+id+'"><td>'+selectedUnitsMap[id]+'</td><td>'+action+'</td></tr>');
                });
                syncInputsAndPrimary();
            }

            $("#country_id").on('change', function() {
                var country_id = $(this).val();
                $.ajax({
                    url: '{{ url('get-cities') }}',
                    type: 'get',
                    dataType: 'json',
                    data: { country_id: country_id },
                    success: function(data) {
                        var html = '<option value="">Select City</option>';
                        for (var i = 0; i < data.length; i++) {
                            html += '<option value="'+data[i].id+'">'+data[i].city_name+'</option>';
                        }
                        $('.citySection').html(html);
                        $('.loc_id').html('<option value="">Select Location</option>');
                        $('.warehouse_id').html('<option value="">Select Warehouse</option>');
                        $('#extra_su_id').html('<option value="">Choose One</option>');
                    }
                });
            });

            $(".citySection").on('change', function() {
                var city_id = $(this).val();
                $.ajax({
                    url: '{{ url('get-locations') }}',
                    type: 'get',
                    dataType: 'json',
                    data: { city_id: city_id },
                    success: function(data) {
                        var html = '<option value="">Select Location</option>';
                        for (var i = 0; i < data.length; i++) {
                            html += '<option value="'+data[i].id+'">'+data[i].loc_name+'</option>';
                        }
                        $('.loc_id').html(html);
                        $('.warehouse_id').html('<option value="">Select Warehouse</option>');
                        $('#extra_su_id').html('<option value="">Choose One</option>');
                    }
                });
            });

            $(".loc_id").on('change', function() {
                var loc_id = $(this).val();
                $.ajax({
                    url: '{{ url('get-warehouse') }}',
                    type: 'get',
                    dataType: 'json',
                    data: { loc_id: loc_id },
                    success: function(data) {
                        var html = '<option value="">Select Warehouse</option>';
                        for (var i = 0; i < data.length; i++) {
                            html += '<option value="'+data[i].id+'">'+data[i].name+'</option>';
                        }
                        $('.warehouse_id').html(html);
                        $('#extra_su_id').html('<option value="">Choose One</option>');
                    }
                });
            });

            $(".warehouse_id").on('change', function() {
                var warehouse_id = $(this).val();
                $.ajax({
                    url: '{{ url('get-storageunit') }}',
                    type: 'get',
                    dataType: 'json',
                    data: { warehouse_id: warehouse_id },
                    success: function(data) {
                        var html = '<option value="">Choose One</option>';
                        for (var i = 0; i < data.length; i++) {
                            if (data[i].status && data[i].status !== 'vacant') {
                                continue;
                            }
                            html += '<option value="'+data[i].id+'" data-name="'+data[i].storage_unit_name+'">'+data[i].storage_unit_name+'</option>';
                        }
                        $('#extra_su_id').html(html);
                    }
                });
            });

            $('#btn_add_unit').on('click', function() {
                var $opt = $('#extra_su_id option:selected');
                var id = $opt.val();
                var name = $opt.data('name') || $opt.text();
                if (!id) {
                    toastr.error('Please select a storage unit to add.');
                    return;
                }
                if (selectedUnitsMap[id]) {
                    toastr.error('This unit is already selected.');
                    return;
                }
                selectedUnitsMap[id] = name;
                renderSelectedUnitsTable();
                $('#selected_units_header').append(
                    '<div class="offset-lg-1 offset-md-1 col-12 col-sm-12 col-md-11 col-lg-11 greeting-user unit-header-row" data-su-id="'+id+'">'+
                    '<h3 class="animated fadeIn" style="color:#FF8820;"><span class="area-name">'+name+'</span></h3></div>'
                );
            });

            $(document).on('click', '.btn-remove-unit', function() {
                var id = String($(this).data('id'));
                delete selectedUnitsMap[id];
                $('.unit-header-row[data-su-id="'+id+'"]').remove();
                renderSelectedUnitsTable();
            });

            $('#LeadForm').on('submit', function(e) {
                e.preventDefault();

                if (!$('input[name="terms"]').is(':checked')) {
                    toastr.error('Please agree to the terms and conditions.');
                    return false;
                }

                if (Object.keys(selectedUnitsMap).length === 0) {
                    toastr.error('Please select at least one storage unit.');
                    return false;
                }

               var formData=$('#LeadForm').serialize()
                $.ajax({
                    type: "get",
                    url: '{{ url('/save-lead') }}',
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
                            toastr.success(data.message);
                            window.location.href = "{{ url('booking')}}";
                        }else {
                            toastr.error(data.message);
                        }
                        if (data.errors) {
                            $('.btn-submit').text('Save');
                            $(".btn-submit").prop("disabled", false);
                        }
                    },
                    complete: function(data) {
                        $(".btn-submit").html("Save");
                        $(".btn-submit").prop("disabled", false);
                    },
                    error: function(xhr) {
                        var msg = (xhr.responseJSON && (xhr.responseJSON.message || xhr.responseJSON.error)) ? (xhr.responseJSON.message || xhr.responseJSON.error) : 'any technical error';
                        toastr.error(msg);
                        $('.btn-submit').text('Save');
                        $(".btn-submit").prop("disabled", false);
                    }
                });
            });
        });
    </script>

    <!-- MODAL AREA START (Quick View Modal) -->
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
        [custom-scrollbar]::-webkit-scrollbar { width: 6px; }
        [custom-scrollbar]::-webkit-scrollbar-track { background: #F1F5F9; border-radius: 10px; }
        [custom-scrollbar]::-webkit-scrollbar-thumb { background: #CBD5E0; border-radius: 10px; }
        [custom-scrollbar]::-webkit-scrollbar-thumb:hover { background: #A0AEC0; }
        .modal-content { animation: modalSlideUp 0.4s cubic-bezier(0.16, 1, 0.3, 1); }
        @keyframes modalSlideUp {
            from { transform: translateY(30px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .terms-content p { margin-bottom: 15px; }
        .terms-content ul, .terms-content ol { margin-bottom: 15px; padding-left: 20px; }
        .terms-content li { margin-bottom: 8px; }
        .terms-content h1, .terms-content h2, .terms-content h3 { margin-top: 20px; margin-bottom: 10px; color: #2D3748; }
        .form-link { color: #FF8820; font-weight: 600; text-decoration: none; transition: color 0.3s ease; }
        .form-link:hover { color: #FF6B00; text-decoration: underline; }
    </style>
@endsection
