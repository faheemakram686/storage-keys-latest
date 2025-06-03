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

                        <div class="row reservations-sections">
                            <div class="offset-sm-2 offset-md-2 offset-lg-2 offset-1 col-8 col-sm-6 col-md-4 col-lg-4 term-section-header">
                                Customer Information</div>
                            <div class="offset-md-1 offset-lg-1 col-12 col-sm-12 col-md-10 col-lg-10 term-section-body">
                                <div class="row">
                                    <div class="offset-lg-1 offset-md-1 col-12 col-sm-12 col-md-10 col-lg-10">
                                        <div class="row mt-3">
                                            <div class="col-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" name="customer_type" type="radio" value="individual"  id="individual" required />
                                                    <label class="check-container" for="individual">For Individual?</label>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" name="customer_type" type="radio" value="company"  id="company" required />
                                                    <label class="check-container" for="company">For Company?</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-3" id="company_info">
                                            <div class="col-6" >
                                                <label class="">Company Name</label>
                                                <input type="text" class="form-control" placeholder="Company Name..." name="company_name" style="height:35px;" >
                                            </div>
                                            <div class="col-6" >
                                                <label class="">Trade License No.</label>
                                                <input type="text" class="form-control" placeholder="Trade License No." name="license_no" style="height:35px;" >
                                            </div>
                                            <div class="col-6 mt-2" >
                                                <label class="">VAT #</label>
                                                <input type="text" class="form-control" placeholder="VAT #" name="vat" style="height:35px;" >
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-6">
                                                <label class="">First Name</label>
                                                <input type="text" class="form-control" placeholder="First Name..." name="f_name" style="height:35px;" required>
                                            </div>
                                            <div class="col-6">
                                                <label class="">Last Name</label>
                                                <input type="text" class="form-control" placeholder="Last Name..." name="l_name" style="height:35px;" required>
                                            </div>
                                        </div>
                                        <div class="row mt-3 "  id="individual_info">
                                            <div class="col-6">
                                                <label class="">Customer ID</label>
                                                <input class="form-control" type="text" name="customer_id_card" placeholder="Customer ID" >
                                            </div>
                                            <div class="col-6">
                                                <label class="">Customer Passport No</label>
                                                <input type="text" class="form-control" placeholder="Customer Passport No" name="passport_no" style="height:35px;" >
                                            </div>
                                            <div class="col-6 mt-2">
                                                <div class="form-group">
                                                    <label class="form-label">Date of Birth</label>
                                                    <div class="form-control-wrap">
                                                        <div class="form-icon form-icon-right">
                                                            <em class="icon ni ni-calendar-alt"></em>
                                                        </div>
                                                        <input type="text" name="dob" class="form-control date-picker">
                                                    </div>
                                                    <div class="form-note">Date format <code>mm/dd/yyyy</code></div>
                                                </div>

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
                                                <input type="email" class="form-control" name="email" style="height:35px;" required>
                                            </div>
                                            <div class="col-6">
                                                <label class="">Phone</label>
                                                <input type="text" class="form-control" name="phone" style="height:35px;" required>
                                            </div>
                                        </div>

                                        <div class="row mt-3">
                                            <div class="col-6">
                                                <label class="">Home</label>
                                                <input type="text" class="form-control" name="home" style="height:35px;" required>
                                            </div>
                                            <div class="col-6">
                                                <label class="">Mobile</label>
                                                <input type="text" class="form-control" name="mobile" style="height:35px;" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row reservations-sections">
                            <div class="offset-sm-2 offset-md-2 offset-lg-2 offset-1 col-8 col-sm-6 col-md-4 col-lg-4 term-section-header">
                                Address Information</div>
                            <div class="offset-md-1 offset-lg-1 col-12 col-sm-12 col-md-10 col-lg-10 term-section-body">
                                <div class="row">
                                    <div class="offset-lg-1 offset-md-1 col-12 col-sm-12 col-md-10 col-lg-10">
                                        <div class="row mt-3">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label> City <span class="text-danger"></span></label>
                                                    <div class="form-control-wrap">
                                                        <input class="form-control" type="text" name="city" placeholder="City" >
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label> State <span class="text-danger"></span></label>
                                                    <div class="form-control-wrap">
                                                        <input class="form-control" type="text" name="state" placeholder="State" >
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label> Country <span class="text-danger"></span></label>
                                                    <div class="form-control-wrap">
                                                        <input class="form-control" type="text" name="country" placeholder="Country" >
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label  for="status">Status</label>
                                                    <select class="form-control form-select " id="status" name="status" required>
                                                        <option value="">Select Status:</option>
                                                        <option value="0">Active</option>
                                                        <option value="1">In-Active</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label> Address <span class="text-danger"></span></label>
                                                    <div class="form-control-wrap">
                                                        <textarea rows="5" cols="4" class="form-control" type="text" name="address" placeholder="Address" ></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="float-right m-2">
                                <button class="btn btn-primary mt-2 btn-submit" type="submit">Save</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>


    <script>
        $(document).ready(function() {

            $("#company_info").hide();
            $("#individual_info").hide();

            $("input[name='customer_type']").click(function() {
                if ($("#company").is(":checked")) {
                    $("#company_info").show();
                    $("#individual_info").hide();
                } else {
                    $("#individual_info").show();
                    $("#company_info").hide();
                }
            });
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



