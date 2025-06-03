@extends('backend.layouts.app')
@section('title', '| Contract')
@section('content')
    <div class="components-preview wide-md mx-auto">
        <div class="nk-block nk-block-lg">
            <div class="nk-block-head">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h4 class="title nk-block-title">Create New Contract</h4>
                    </div>
                    <a href="{{url("admin/contract")}}" class="btn btn-primary btn-sm d-none d-md-inline-flex"><em class="icon ni ni-arrow-left"></em><span>Back</span></a>
                </div>
            </div>
            @isset($data)
            <div class="card">
                <div class="card-inner">
                    <form class="gy-3 form-validate is-alter" action="{{url("admin/save-contract")}}"  method="post" enctype="multipart/form-data" id="ContractForm">
                        @csrf
                        <div class="row g-4">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>Customer<span class="text-danger">*</span></label>
                                    <select name="customer_id" id="customer_id" class="form-control select2" data-live-search="true" required>
                                        <option value="">Choose One</option>
                                        @foreach( $data['customers'] as $customer)
                                        <option value="{{$customer->id}}">{{$customer->customer_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>Estimate <span class="text-danger">*</span></label>
                                    <select name="estimate_id" id="" class="form-control select2 EstimateSection" data-live-search="true" required>
                                        <option value="">Choose One</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label> Subject <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="subject" placeholder="Subject" required>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>Contract Value <span class="text-danger">*</span></label>
                                    <input class="form-control" type="number" name="contract_value" placeholder="Contract Value" required>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>Contract Type <span class="text-danger"></span></label>
                                    <input class="form-control" type="text" name="contract_type" placeholder="Contract Type" >
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Start Date <span class="text-danger">*</span></label>
                                    <input class="form-control" type="date" name="s_date" placeholder="Start Date" >
                                </div>
                            </div>
                            <div  class="col-lg-6" >
                                <div class="form-group">
                                    <label>End Date <span class="text-danger">*</span></label>
                                    <input class="form-control" type="date" name="e_date" placeholder="End Date" >
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-label" for="reviewer">Contract Description</label>
                                    <div class="form-control-wrap">
                                        <textarea class="summernote-basic form-control"  id="editor" name="description"></textarea>
                                    </div>
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
                                    <button type="submit" class="btn btn-md btn-primary btn-submit" data-button="submit">Save</button>
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

            $('#ContractForm').on('submit', function(e) {

                e.preventDefault();
                var formData=$('#ContractForm').serialize()
                $.ajax({
                    type: "get",
                    url: '{{ url('admin/save-contract') }}',
                    data: formData,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('.btn-submit').text('Saving...');
                        $(".btn-submit").prop("disabled", true);
                    },
                    success: function(data) {

                        if (data.success) {
                            $('#ContractForm')[0].reset();
                            $('.close').click();
                            toastr.success(data.success);

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
                        window.location.href = "{{ url('admin/contract')}}";
                    },

                    error: function() {;
                        toastr.error('any technical error');
                        $('.btn-submit').text('Save');
                        $(".btn-submit").prop("disabled", false);
                    }
                });


            });


            $("#customer_id").on('change', function() {
                var customer_id = $(this).val();
                // alert(customer_id);
                getEstimates(customer_id);
            });
            function getEstimates(customer_id) {
                $.ajax({
                    url: '{{ url('admin/get-customer-estimates') }}',
                    type: 'get',
                    async: false,
                    dataType: 'json',
                    data: { customer_id: customer_id },
                    success: function(data) {
                        console.log(data);
                        $('.EstimateSection').empty();
                        var html3 = '';
                        var i;
                        var c = 0;
                        $('.EstimateSection').html('<option value="">Select Estimate</option>');
                        if (data.length > 0) {
                            for (i = 0; i < data.length; i++) {
                                html3 += '<option  value="' + data[i].id + '">' + data[i].id+' - '+ data[i].storageunit.storage_unit_name + ' / '+ data[i].term_length.title + ' </option>';
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


        });
    </script>
@endsection



