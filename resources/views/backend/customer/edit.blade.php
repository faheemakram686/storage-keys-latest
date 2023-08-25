@extends('backend.layouts.app')
@section('title', '| Edit Customer')
@section('content')
    <div class="components-preview wide-md mx-auto">
        <div class="nk-block nk-block-lg">
            <div class="nk-block-head">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h4 class="title nk-block-title">Edit Customer</h4>
                    </div>
                    <a href="{{url("admin/customer")}}" class="btn btn-primary btn-sm d-none d-md-inline-flex"><em class="icon ni ni-arrow-left"></em><span>Back</span></a>
                </div>
            </div>
            <div class="card">
                <div class="card-inner">
                    <form class="gy-3 form-validate is-alter" action="{{url("admin/update-customer")}}"  method="post" id="updateCountryForm" enctype="multipart/form-data">
                        @csrf
                        @isset($data)
                            <input type="hidden" name="id" value="{{$data['customer']->id}}">
                            <div class="row ">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label> Company Name <span class="text-danger">*</span></label>
                                        <div class="form-control-wrap">
                                            <input class="form-control" type="text" name="edit_company_name"  value="{{$data['customer']->company_name}}" placeholder="Company Name" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label> Phone <span class="text-danger"></span></label>
                                        <div class="form-control-wrap">
                                            <input class="form-control" type="text" name="edit_phone"  value="{{$data['customer']->phone}}" placeholder="Phone" >
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label> Address <span class="text-danger"></span></label>
                                        <div class="form-control-wrap">
                                            <textarea rows="5" cols="4" class="form-control" type="text" name="edit_address"  value="{{$data['customer']->address}}" placeholder="Address" >{{$data['customer']->address}}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label> City <span class="text-danger"></span></label>
                                        <div class="form-control-wrap">
                                            <input class="form-control" type="text" name="edit_city" value="{{$data['customer']->city}}" placeholder="City" >
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label> State <span class="text-danger"></span></label>
                                        <div class="form-control-wrap">
                                            <input class="form-control" type="text" name="edit_state" value="{{$data['customer']->state}}" placeholder="State" >
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label> Country <span class="text-danger"></span></label>
                                        <div class="form-control-wrap">
                                            <input class="form-control" type="text" name="edit_country" value="{{$data['customer']->country}}" placeholder="Country" >
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label" for="status">Status</label>
                                        <select class="form-control form-select " id="status" name="edit_status" required>
                                            <option value="1" {{$data['customer']->status == 1 ? 'selected' : ''}}>Active</option>
                                            <option value="0" {{$data['customer']->status== 0 ? 'selected' : ''}}>In-Active</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="float-right m-2">
                                        <button type="submit" class="btn btn-md btn-primary" data-button="submit">Save Changes</button>
                                    </div>
                                </div>
                            </div>
                        @endisset
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script>
        $(document).ready(function() {

            $('#updateCountryForm').on('submit', function(e) {
                e.preventDefault();
                var formData=$('#updateCountryForm').serialize()
                $.ajax({
                    type: "get",
                    url: '{{ url('admin/update-customer') }}',
                    data: formData,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('.btn-update').text('loading...');
                        $(".btn-update").prop("disabled", true);
                    },
                    success: function(data) {

                        if (data.success) {
                            $('#updateCountryForm')[0].reset();
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
                        window.location.href = "{{ route('customer.index')}}";
                    },

                    error: function() {;
                        toastr.error('any technical error');
                        $('.btn-update').text('Save Changes');
                        $(".btn-update").prop("disabled", false);
                    }
                });


            });

        });
    </script>
@endsection



