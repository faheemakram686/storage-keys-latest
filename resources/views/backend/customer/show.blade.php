@extends('backend.layouts.app')
@section('title', '| Customer')
@section('content')
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-block-head">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h4 class="title nk-block-title">Customer Information</h4>
                    </div>
                    <a href="{{url("admin/customer")}}" class="btn btn-primary btn-sm d-none d-md-inline-flex"><em class="icon ni ni-arrow-left"></em><span>Back</span></a>
                </div>
            </div>
            <div class="nk-content-body">
                <div class="nk-block">
                    @isset($data)
                    <div class="card">
                        <div class="card-aside-wrap">
                            <div class="card-inner card-inner-lg">
                                <div class="nk-block-head">
                                    <div class="nk-block-between d-flex justify-content-between">
                                        <div class="nk-block-head-content">
                                            <h4 class="nk-block-title">Profile</h4>
                                            <div class="nk-block-des">
                                                <p>All information of the customer.</p>
                                            </div>
                                        </div>
                                        <div class="d-flex align-center">
                                            <div class="nk-tab-actions me-n1">
{{--                                                <a class="btn btn-icon btn-trigger" data-bs-toggle="modal" href="#profile-edit"><em class="icon ni ni-edit"></em></a>--}}
                                            </div>
                                            <div class="nk-block-head-content align-self-start d-lg-none">
                                                <a href="#" class="toggle btn btn-icon btn-trigger" data-target="userAside"><em class="icon ni ni-menu-alt-r"></em></a>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <!-- .nk-block-head -->
                                <!-- .nk-block -->
                                <div class="nk-block">
                                    <div class="card border border-light">
                                        <form class="gy-3 form-validate is-alter m-5" action="{{url("admin/update-customer")}}"  method="post" id="updateCountryForm" enctype="multipart/form-data">
                                            @csrf
                                            @isset($data)
                                                <input type="hidden" name="id" value="{{$data['customer']->id}}">

                                                <div class="row ">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label> Customer Type <span class="text-danger">*</span></label>
                                                            <div class="form-control-wrap">
                                                                <input class="form-control" type="text" name="customer_type"  value="{{$data['customer']->customer_type}}" placeholder="Company Type" readonly>
                                                            </div>
                                                        </div>
                                                    </div>

                                                        @if($data['customer']->company_name != null)
                                                        <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label> Company Name <span class="text-danger">*</span></label>
                                                            <div class="form-control-wrap">
                                                                <input class="form-control" type="text" name="company_name"  value="{{$data['customer']->company_name}}" placeholder="Company Name" >
                                                            </div>
                                                        </div>
                                                        </div>
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label> Trade License No. <span class="text-danger"></span></label>
                                                                    <div class="form-control-wrap">
                                                                        <input class="form-control" type="text" name="license_no"  value="{{$data['customer']->license_no}}" placeholder="Trade License No." >
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label> VAT# <span class="text-danger"></span></label>
                                                                    <div class="form-control-wrap">
                                                                        <input class="form-control" type="text" name="vat"  value="{{$data['customer']->vat}}" placeholder="VAT" >
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @else
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label> Customer ID <span class="text-danger">*</span></label>
                                                                <div class="form-control-wrap">
                                                                    <input class="form-control" type="text" name="customer_id_card"  value="{{$data['customer']->customer_id_card}}" placeholder="Customer Name" >
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label> Customer Passport No <span class="text-danger">*</span></label>
                                                                <div class="form-control-wrap">
                                                                    <input class="form-control" type="text" name="passport_no"  value="{{$data['customer']->passport_no}}" placeholder="Customer Passport No" >
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label> Date of Birth <span class="text-danger">*</span></label>
                                                                <div class="form-control-wrap">
                                                                    <input class="form-control" type="text" name="dob"  value="{{$data['customer']->dob}}" placeholder="Customer Date of Birth" >
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @endif

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
                                                                <textarea rows="5" cols="4" class="form-control" type="text" name="address"  value="{{$data['customer']->address}}" placeholder="Address" >{{$data['customer']->address}}</textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label> City <span class="text-danger"></span></label>
                                                            <div class="form-control-wrap">
                                                                <input class="form-control" type="text" name="city" value="{{$data['customer']->city}}" placeholder="City" >
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label> State <span class="text-danger"></span></label>
                                                            <div class="form-control-wrap">
                                                                <input class="form-control" type="text" name="state" value="{{$data['customer']->state}}" placeholder="State" >
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label> Country <span class="text-danger"></span></label>
                                                            <div class="form-control-wrap">
                                                                <input class="form-control" type="text" name="country" value="{{$data['customer']->country}}" placeholder="Country" >
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="form-label" for="status">Status</label>
                                                            <select class="form-control form-select " id="status" name="status" required>
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
                            @include('backend.customer.aside')
                            <!-- card-aside -->
                        </div>
                        <!-- .card-aside-wrap -->
                    </div>
                    @endisset
                    <!-- .card -->
                </div>
                <!-- .nk-block -->
            </div>
            <div class="modal fade" tabindex="-1" role="dialog" id="addCountry" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title text-capitalize" id="ajax_model_title">Add New Country</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" data-form="ajax-form-modal">
                            <form method="post" action="{{ url('admin/save-country') }}" id="CountryForm">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label> Name <span class="text-danger"></span></label>
                                            <input class="form-control" type="text" name="country_name" placeholder="Name" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Status <span class="text-danger"></span></label>
                                            <select name="status" id="" class="form-control" required>
                                                <option value="">Choose One</option>
                                                <option value="1">Active</option>
                                                <option value="0">In-Active</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="float-right">
                                    <button class="btn btn-primary mt-2 btn-submit" type="submit">Save</button>
                                </div>
                            </form>

                        </div>
                    </div><!-- .modal-content -->
                </div><!-- .modla-dialog -->
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {


            //
            //    alert(getInitials('Faheem Akram'));
            //
            // var getInitials = function (string) {
            //     var names = string.split(' '),
            //         initials = names[0].substring(0, 1).toUpperCase();
            //
            //     if (names.length > 1) {
            //         initials += names[names.length - 1].substring(0, 1).toUpperCase();
            //     }
            //     return initials;
            // };


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



