@extends('backend.layouts.app')
@section('title', '| Create Customer')
@section('content')
    <div class="components-preview wide-md mx-auto">
        <div class="nk-block nk-block-lg">
            <div class="nk-block-head">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h4 class="title nk-block-title">Create New Customer</h4>
                    </div>
                    <a href="{{url("admin/customer")}}" class="btn btn-primary btn-sm d-none d-md-inline-flex"><em class="icon ni ni-arrow-left"></em><span>Back</span></a>
                </div>
            </div>
            <div class="card">
                <div class="card-inner">
                    <form class="mx-5" action="{{url("admin/save-customer")}}"  method="post" id="CountryForm" enctype="multipart/form-data">
                        @csrf
                        <div class="row ">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label> Company Name <span class="text-danger">*</span></label>
                                    <div class="form-control-wrap">
                                        <input class="form-control" type="text" name="company_name" placeholder="Company Name" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label> Phone <span class="text-danger"></span></label>
                                    <div class="form-control-wrap">
                                        <input class="form-control" type="text" name="phone" placeholder="Phone" >
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label> Address <span class="text-danger"></span></label>
                                    <div class="form-control-wrap">
                                        <textarea rows="5" cols="4" class="form-control" type="text" name="address" placeholder="Address" ></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label> City <span class="text-danger"></span></label>
                                    <div class="form-control-wrap">
                                        <input class="form-control" type="text" name="city" placeholder="City" >
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label> State <span class="text-danger"></span></label>
                                    <div class="form-control-wrap">
                                        <input class="form-control" type="text" name="state" placeholder="State" >
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label> Country <span class="text-danger"></span></label>
                                    <div class="form-control-wrap">
                                        <input class="form-control" type="text" name="country" placeholder="Country" >
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label" for="status">Status</label>
                                    <select class="form-control form-select " id="status" name="status" required>
                                        <option value="0">Active</option>
                                        <option value="1">In-Active</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="float-right m-2">
                                    <button class="btn btn-primary mt-2 btn-submit" type="submit">Save</button>
                                </div>
{{--                                <div class="float-right m-2">--}}
{{--                                    <button class="btn btn-primary mt-2 btn-submit" type="submit">Save and create contect</button>--}}
{{--                                </div>--}}

                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script>
        $(document).ready(function() {

            $('#CountryForm').on('submit', function(e) {

                e.preventDefault();
                var formData=$('#CountryForm').serialize()
                $.ajax({
                    type: "get",
                    url: '{{ url('admin/save-customer') }}',
                    data: formData,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('.btn-submit').text('Saving...');
                        $(".btn-submit").prop("disabled", true);
                    },
                    success: function(data) {

                        if (data.success) {
                            $('#CountryForm')[0].reset();
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
                        window.location.href = "{{ route('customer.index')}}";
                    },

                    error: function() {;
                        toastr.error('any technical error');
                        $('.btn-submit').text('Save');
                        $(".btn-submit").prop("disabled", false);
                    }
                });


            });


        });
    </script>
@endsection



