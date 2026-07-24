@extends('backend.layouts.app')
@section('title', '| Contract')
@section('content')
    <div class="components-preview wide-md mx-auto">
        <div class="nk-block nk-block-lg">
            <div class="nk-block-head">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h4 class="title nk-block-title">Edit Contract</h4>
                    </div>
                    <a href="{{url("admin/contract")}}" class="btn btn-primary btn-sm d-none d-md-inline-flex"><em class="icon ni ni-arrow-left"></em><span>Back</span></a>
                </div>
            </div>
            @isset($data)
            <div class="card">
                <div class="card-inner">
                    <form class="gy-3 " action="{{url("admin/update-contract")}}"  method="post" enctype="multipart/form-data" id="UpdateContractForm">
                        @csrf
                            <input type="hidden" name="id" value="{{$data['contract'][0]->id}}">
                        <div class="row g-4">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>Customer<span class="text-danger">*</span></label>
                                    <select name="customer_id" id="customer_id" class="form-control select2" data-live-search="true" required>
                                        <option value="">Choose One</option>
                                        @foreach( $data['customers'] as $customer)
                                        <option value="{{$customer->id}}" {{ ($customer->id == $data['contract'][0]->customer_id) ? "selected" : "" }}>{{$customer->customer_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>Estimate <span class="text-danger">*</span></label>
                                    <input type="hidden" name="estimate_js" value="{{$data['contract'][0]->estimate_id}}" id="contract_id">
                                    <select name="estimate_id" id="" class="form-control select2 EstimateSection" data-live-search="true" required>
                                        <option value="">Choose One</option>
{{--                                        @foreach( $data['estimates'] as $estimate)--}}
{{--                                            <option value="{{$estimate->id}}"  {{ ($estimate->id == $data['contract'][0]->estimate_id) ? "selected" : "" }}>{{$estimate->id}} - {{$estimate->storageunit->storage_unit_name}} / {{$estimate->termLength->title}}</option>--}}
{{--                                        @endforeach--}}
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label> Subject <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="subject" value="{{$data['contract'][0]->subject}}" placeholder="Subject" required>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>Contract Value <span class="text-danger">*</span></label>
                                    <input class="form-control" type="number" step="any" name="contract_value"  value="{{$data['contract'][0]->contract_value}}" placeholder="Contract Value" required>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>Contract Type <span class="text-danger"></span></label>
                                    <input class="form-control" type="text" name="contract_type"  value="{{$data['contract'][0]->contract_type}}" placeholder="Contract Type" >
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Start Date <span class="text-danger">*</span></label>
                                    <input class="form-control" type="date" name="s_date"  value="{{$data['contract'][0]->start_date}}" placeholder="Start Date" >
                                </div>
                            </div>
                            <div  class="col-lg-6" >
                                <div class="form-group">
                                    <label>End Date <span class="text-danger">*</span></label>
                                    <input class="form-control" type="date" name="e_date"  value="{{$data['contract'][0]->end_date}}" placeholder="End Date" >
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-label" for="reviewer">Contract Content</label>
                                    <div class="form-control-wrap">
                                        <textarea class="summernote-basic form-control"  id="editor"  value="{{$data['contract'][0]->description}}" name="description"> {{$data['contract'][0]->description}}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <h6 class="title">Alternative Contact Information</h6>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Name</label>
                                    <input class="form-control" type="text" name="alt_contact_name" id="alt_contact_name" value="{{$data['contract'][0]->alt_contact_name}}" placeholder="Name">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Email</label>
                                    <input class="form-control" type="email" name="alt_contact_email" id="alt_contact_email" value="{{$data['contract'][0]->alt_contact_email}}" placeholder="Email">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Mobile number</label>
                                    <input class="form-control" type="text" name="alt_contact_mobile" id="alt_contact_mobile" value="{{$data['contract'][0]->alt_contact_mobile}}" placeholder="Mobile number">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-label" for="status">Status</label>
                                    <select class="form-control form-select " id="status" name="status" required>
                                        <option value="0">Active</option>
                                        <option value="1">In-Active</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-md btn-primary btn-update" data-button="submit">Save Changes</button>
                                </div>
                            </div>
                        </div>

                    </form>

                </div>
            </div>
            @endisset
        </div>
    </div>

    <script>
        ClassicEditor
            .create( document.querySelector( '#editor' ) )
            .catch( error => {
                console.error( error );
            } );
    </script>
    <script>
        $(document).ready(function() {

            $('#UpdateContractForm').on('submit', function(e) {
                e.preventDefault();
                var formData=$('#UpdateContractForm').serialize()
                $.ajax({
                    type: "get",
                    url: '{{ url('admin/update-contract') }}',
                    data: formData,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('.btn-update').text('loading...');
                        $(".btn-update").prop("disabled", true);
                    },
                    success: function(data) {

                        if (data.success) {
                            $('#UpdateContractForm')[0].reset();
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
                        window.location.href = "{{ url('admin/contract')}}";
                    },

                    error: function() {;
                        toastr.error('any technical error');
                        $('.btn-update').text('Save Changes');
                        $(".btn-update").prop("disabled", false);
                    }
                });


            });
            var customer_id=$('select[name=customer_id]').val();
            getEstimates(customer_id);
            $("#customer_id").on('change', function() {
                var customer_id = $(this).val();
                // alert(customer_id);
                getEstimates(customer_id);
            });
            function getEstimates(customer_id) {
                var estimate_id =  $('input[name=estimate_js]').val();

                $.ajax({
                    url: '{{ url('admin/get-customer-estimates') }}',
                    type: 'get',
                    async: false,
                    dataType: 'json',
                    data: { customer_id: customer_id },
                    success: function(data) {
                        console.log(data);
                        window._estimateAltContacts = {};
                        $('.EstimateSection').empty();
                        var html3 = '';
                        var i;
                        var c = 0;
                        $('.EstimateSection').html('<option value="">Select Estimate</option>');
                        if (data.length > 0) {
                            for (i = 0; i < data.length; i++) {
                                window._estimateAltContacts[data[i].id] = {
                                    name: data[i].alt_contact_name || '',
                                    email: data[i].alt_contact_email || '',
                                    mobile: data[i].alt_contact_mobile || ''
                                };
                                var termLengthTitle = (data[i].term_length && data[i].term_length.title) ? data[i].term_length.title : 'N/A';
                                var storageUnitName = 'N/A';
                                if (data[i].estimate_storage_units && data[i].estimate_storage_units.length > 0) {
                                    storageUnitName = data[i].estimate_storage_units.map(function(u) {
                                        return (u.storageunit && u.storageunit.storage_unit_name) ? u.storageunit.storage_unit_name : ('#'+u.storage_unit_id);
                                    }).join(', ');
                                } else if (data[i].storageunit && data[i].storageunit.storage_unit_name) {
                                    storageUnitName = data[i].storageunit.storage_unit_name;
                                }

                                html3 += '<option  value="' + data[i].id + ' " '+ ((data[i].id == estimate_id)? 'selected':'') +'>' + data[i].id+' - '+ storageUnitName + ' / '+ termLengthTitle + ' </option>';
                            }
                        } else {
                            var html3 = '<option value="">No Contract Found</option>';
                        }
                        $('.EstimateSection').append(html3);
                    },
                    error: function() {
                        toastr.error('any technical error');
                    }
                });
            }

            $(document).on('change', '.EstimateSection', function() {
                var id = $.trim($(this).val());
                var alt = (window._estimateAltContacts && window._estimateAltContacts[id]) ? window._estimateAltContacts[id] : null;
                if (alt) {
                    $('#alt_contact_name').val(alt.name);
                    $('#alt_contact_email').val(alt.email);
                    $('#alt_contact_mobile').val(alt.mobile);
                }
            });

        });


    </script>

@endsection



