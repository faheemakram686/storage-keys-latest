@extends('ui.layouts.frontend')
@section('title', '| Reserve')
@section('metaTitle', 'Reserve your unit | Storage Keys')
@section('metaDescription', 'Send an enquiry to reserve your StorageKeys unit. Share your contact details, preferred stay and any add-ons.')

@section('css')
<link rel="stylesheet" href="{{ asset('sk-assets/css/frontend/reservation-layout.css') }}">
@endsection

@section('content')
    @isset($data)
    @php
        $primarySu = isset($data['su'][0]) ? $data['su'][0] : null;
        $preferredDuration = trim((string) request('duration'));
        $matchedTermId = null;
        if ($preferredDuration !== '' && !empty($data['term_length'])) {
            foreach ($data['term_length'] as $termOption) {
                $termTitle = strtolower(trim((string) $termOption->title));
                $pref = strtolower($preferredDuration);
                if ($termTitle !== '' && ($termTitle === $pref || strpos($termTitle, $pref) !== false)) {
                    $matchedTermId = $termOption->id;
                    break;
                }
            }
        }
    @endphp
    <div class="sk-home rsv-page">
        <section class="ps-hero">
            <div class="sk-container">
                <div class="ps-crumb"><a href="{{ url('/') }}">Home</a> <i class="fas fa-chevron-right"></i> <a href="{{ url('booking') }}">Booking</a> <i class="fas fa-chevron-right"></i> <span>Reserve</span></div>
                <h1>Reserve your unit</h1>
                <p class="lead">Tell us how to reach you. We will confirm availability and the next step for the unit you selected.</p>
            </div>
        </section>

        <section class="rsv-body">
            <div class="sk-container">
                <div class="rsv-stack">
                    <div class="rsv-units" id="selected_units_header">
                        @foreach ($data['su'] as $su)
                        <article class="rsv-unit unit-header-row" data-su-id="{{ $su->id }}">
                            <p class="rsv-kicker">Selected unit</p>
                            <h2>{{ $su->storage_unit_name }}</h2>
                            <div class="rsv-meta">
                                @if(optional(optional(optional($su->warehouse)->loc)->city)->city_name)
                                <span><i class="fas fa-map-marker-alt"></i> {{ $su->warehouse->loc->city->city_name }}@if(optional(optional($su->warehouse)->loc)->loc_name) · {{ $su->warehouse->loc->loc_name }}@endif</span>
                                @endif
                                @if(optional($su->warehouse)->name)
                                <span><i class="fas fa-warehouse"></i> {{ $su->warehouse->name }}</span>
                                @endif
                                @if(optional($su->storagesize)->unit_type_name)
                                <span><i class="fas fa-vector-square"></i> {{ $su->storagesize->unit_type_name }}</span>
                                @endif
                                @if(optional($su->storagelevel)->name)
                                <span><i class="fas fa-layer-group"></i> {{ $su->storagelevel->name }}</span>
                                @endif
                                @if(optional($su->storagetype)->name)
                                <span><i class="fas fa-box"></i> {{ $su->storagetype->name }}</span>
                                @endif
                                @if($su->price)
                                <span><i class="fas fa-tag"></i> AED {{ number_format($su->price) }}</span>
                                @endif
                                @if($preferredDuration !== '')
                                <span class="is-duration"><i class="fas fa-clock"></i> {{ $preferredDuration }}</span>
                                @endif
                            </div>
                        </article>
                        @endforeach
                    </div>

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

                    <section class="rsv-card">
                        <h3 class="rsv-h">Storage units</h3>
                        <div class="rsv-card-b">
                            <table class="rsv-table" id="selected_units_table">
                                <thead>
                                    <tr>
                                        <th>Unit</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data['su'] as $su)
                                    <tr data-id="{{ $su->id }}">
                                        <td>{{ $su->storage_unit_name }}</td>
                                        <td>
                                            @if(!$loop->first)
                                            <button type="button" class="rsv-remove btn-remove-unit" data-id="{{ $su->id }}">Remove</button>
                                            @else
                                            <span class="rsv-primary">Primary</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <div class="rsv-addbox">
                                <h4>Add another unit</h4>
                                <div class="rsv-fields">
                                    <div class="rsv-field">
                                        <label class="lbl" for="country_id">Country</label>
                                        <select class="form-control" name="country_id" id="country_id">
                                            <option value="">Choose one</option>
                                            @isset($data['loc'])
                                                @foreach ($data['loc'] as $country)
                                                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                                                @endforeach
                                            @endisset
                                        </select>
                                    </div>
                                    <div class="rsv-field">
                                        <label class="lbl" for="citySection">City</label>
                                        <select name="city_id" class="form-control citySection" id="citySection">
                                            <option value="">Choose one</option>
                                        </select>
                                    </div>
                                    <div class="rsv-field">
                                        <label class="lbl" for="loc_id">Location</label>
                                        <select class="form-control loc_id" name="loc_id" id="loc_id">
                                            <option value="">Choose one</option>
                                        </select>
                                    </div>
                                    <div class="rsv-field">
                                        <label class="lbl" for="warehouse_id">Warehouse</label>
                                        <select class="form-control warehouse_id" name="warehouse_id" id="warehouse_id">
                                            <option value="">Choose one</option>
                                        </select>
                                    </div>
                                    <div class="rsv-field">
                                        <label class="lbl" for="extra_su_id">Storage unit</label>
                                        <select class="form-control" id="extra_su_id">
                                            <option value="">Choose one</option>
                                        </select>
                                    </div>
                                    <div class="rsv-field rsv-field-action">
                                        <button type="button" class="rsv-add" id="btn_add_unit">Add unit</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="rsv-card">
                        <h3 class="rsv-h">Contact information</h3>
                        <div class="rsv-card-b">
                            <div class="rsv-choices">
                                <label class="rsv-choice" for="ind">
                                    <input class="form-check-input" name="type" type="radio" value="individual" id="ind" checked>
                                    <span>Individual</span>
                                </label>
                                <label class="rsv-choice" for="com">
                                    <input class="form-check-input" name="type" type="radio" value="company" id="com">
                                    <span>Company</span>
                                </label>
                            </div>
                            <div class="rsv-fields rsv-date-row">
                                <div class="rsv-field">
                                    <label for="rsv_date">Requested date</label>
                                    <input type="date" class="form-control" id="rsv_date" name="r_date" required>
                                </div>
                                <div class="rsv-field" id="companyfeild">
                                    <label for="rsv_company">Company name</label>
                                    <input type="text" class="form-control" id="rsv_company" name="company_name">
                                </div>
                            </div>
                            <div class="rsv-fields">
                                <div class="rsv-field">
                                    <label for="rsv_fname">First name</label>
                                    <input type="text" class="form-control" id="rsv_fname" name="f_name" required>
                                </div>
                                <div class="rsv-field">
                                    <label for="rsv_lname">Last name</label>
                                    <input type="text" class="form-control" id="rsv_lname" name="l_name" required>
                                </div>
                                <div class="rsv-field">
                                    <label for="rsv_email">Email</label>
                                    <input type="email" class="form-control" id="rsv_email" name="email" required>
                                </div>
                                <div class="rsv-field">
                                    <label for="rsv_phone">Phone</label>
                                    <input type="text" class="form-control" id="rsv_phone" name="phone" required>
                                </div>
                                <div class="rsv-field">
                                    <label for="rsv_mobile1">Mobile 1</label>
                                    <input type="text" class="form-control" id="rsv_mobile1" name="mobile1">
                                </div>
                                <div class="rsv-field">
                                    <label for="rsv_mobile2">Mobile 2</label>
                                    <input type="text" class="form-control" id="rsv_mobile2" name="mobile2">
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="rsv-card">
                        <h3 class="rsv-h">Term length</h3>
                        <div class="rsv-card-b">
                            <p>Longer stays include a discount. Your preferred stay from booking is shown on the unit above.</p>
                            @forelse($data['term_length'] ?? [] as $term_length)
                            <label class="rsv-term" for="term_{{ $term_length->id }}">
                                <span class="rsv-term-main">
                                    <input class="form-check-input" name="price" type="radio" value="{{ $term_length->id }}" id="term_{{ $term_length->id }}" {{ (string) $matchedTermId === (string) $term_length->id ? 'checked' : '' }}>
                                    <span>{{ $term_length->title }}</span>
                                </span>
                                @if($term_length->term_period == 1)
                                <span class="rsv-term-note">Fixed price</span>
                                @else
                                <span class="rsv-term-note">Save {{ $term_length->discount_percentage }}%</span>
                                @endif
                            </label>
                            @empty
                            <p class="rsv-empty">Term options will be confirmed with you after this enquiry.</p>
                            @endforelse
                        </div>
                    </section>

                    <section class="rsv-card">
                        <h3 class="rsv-h">Addons</h3>
                        <div class="rsv-card-b">
                            <div class="rsv-addons">
                            @forelse($data['addon'] ?? [] as $addon)
                                <label class="rsv-choice" for="addon_{{ $addon->id }}">
                                    <input class="form-check-input" name="addon[]" id="addon_{{ $addon->id }}" type="checkbox" value="{{ $addon->id }}">
                                    <span>{{ $addon->name }}@if(!empty($addon->price)) · AED {{ number_format($addon->price) }}@endif</span>
                                </label>
                            @empty
                                <p class="rsv-empty">No add-ons are available right now.</p>
                            @endforelse
                            </div>
                        </div>
                    </section>

                    <section class="rsv-card">
                        <h3 class="rsv-h">Insurance</h3>
                        <div class="rsv-card-b">
                            @include('partials.insurance-selector', [
                                'insurances' => $data['insurances'] ?? collect([]),
                                'selectedInsuranceId' => null,
                                'goodsValue' => null,
                                'readonly' => false,
                            ])
                        </div>
                    </section>

                    <section class="rsv-card">
                        <h3 class="rsv-h">Terms and conditions</h3>
                        <div class="rsv-card-b">
                            <label class="rsv-choice" for="rsv_terms">
                                <input class="form-check-input" type="checkbox" name="terms" value="agree" id="rsv_terms" required>
                                <span>I agree to the StorageKeys terms and conditions</span>
                            </label>
                            <p class="rsv-terms-link"><a href="#" data-toggle="modal" data-target="#quick_view_modal" class="form-link">View terms</a></p>
                        </div>
                    </section>

                    <div class="rsv-actions">
                        <button class="btn btn-qoutation btn-submit" type="submit">Submit enquiry</button>
                    </div>
                    </form>
                </div>
            </div>
        </section>
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
                        ? '<span class="rsv-primary">Primary</span>'
                        : '<button type="button" class="rsv-remove btn-remove-unit" data-id="'+id+'">Remove</button>';
                    var safeName = $('<div>').text(selectedUnitsMap[id]).html();
                    $tbody.append('<tr data-id="'+id+'"><td>'+safeName+'</td><td>'+action+'</td></tr>');
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
                var safeName = $('<div>').text(name).html();
                $('#selected_units_header').append(
                    '<article class="rsv-unit unit-header-row" data-su-id="'+id+'">'+
                    '<p class="rsv-kicker">Added unit</p><h2>'+safeName+'</h2></article>'
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
                        $('.btn-submit').text('Saving');
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
                            $('.btn-submit').text('Submit enquiry');
                            $(".btn-submit").prop("disabled", false);
                        }
                    },
                    complete: function(data) {
                        $(".btn-submit").html("Submit enquiry");
                        $(".btn-submit").prop("disabled", false);
                    },
                    error: function(xhr) {
                        var msg = (xhr.responseJSON && (xhr.responseJSON.message || xhr.responseJSON.error)) ? (xhr.responseJSON.message || xhr.responseJSON.error) : 'any technical error';
                        toastr.error(msg);
                        $('.btn-submit').text('Submit enquiry');
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
                <div class="modal-header" style="background: #F48922; border: none; padding: 25px 30px; position: relative;">
                    <span class="h4 modal-title" style="color: #ffffff; font-weight: 700; margin: 0; font-size: 1.4rem; letter-spacing: -0.02em;">Terms & Conditions</span>
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
                    <button type="button" class="btn" data-dismiss="modal" style="background: #6C365B; color: white; padding: 12px 30px; border-radius: 8px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; border: none;">Close</button>
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
        .form-link { color: #F48922; font-weight: 600; text-decoration: none; }
        .form-link:hover { color: #EC5D2C; text-decoration: underline; }
    </style>
@endsection
