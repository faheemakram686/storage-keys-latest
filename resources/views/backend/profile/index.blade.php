@extends('backend.layouts.app')
@section('title', '| Profile')
@section('content')
    <div class="components-preview mx-auto">
        <div class="nk-block-head nk-block-head-lg pb-2">
            <div class="nk-block-between">
                <div class="nk-block-head-content">
                    <h3 class="nk-block-title fw-normal">Profile</h3>
                </div>
            </div>
        </div>
        <div class="nk-block nk-block-lg">
            <div class="card card-preview">
                <div class="card-inner">
                    <ul class="nav nav-tabs mt-n3">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#tabItem4"><em class="icon ni ni-user-fill"></em><span>Profile</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#tabItem9"><em class="icon ni ni-lock-alt-fill"></em><span>Change Password</span></a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tabItem4">
                            <div class="nk-block">
                                <div class="nk-block-head">
                                    <h5 class="title">Profile Settings</h5>

                                </div>
                                <form method="post" action="{{ url('admin/update-profile') }}" id="updateProfileForm"  enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="id" value="">
                                    <div class="row g-3 align-center">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label" for="avatar">Avatar</label>
                                            </div>
                                        </div>
                                        <div class="col-md-8">

                                            <div class="form-group">
                                                <div class=" logo">
                                                    <label for="logo-input">
                                                        <img id="logo" src="{{ asset('sk-assets/assets/images/no_image.png') }}" alt="store logo" class="" style="max-width:100px;max-height:120px" >
                                                        <input id="logo-input" preview="#logo" name="avatar" class="d-none" type="file" accept="image/*" onchange="loadFile(event)">
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-3 align-center">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label">Name</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control" name="first_name" value="" placeholder="First name" required="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control" name="last_name" value="" placeholder="Last name" required="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-3 align-center">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label" for="email">Email</label>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control" id="email" readonly="" name="email" value="" placeholder="Email" required="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-3 align-center">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label" for="phone">Phone Number</label>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control" id="phone" name="phone" value="" placeholder="Phone Number">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-3 align-center">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label" for="address">Address</label>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <div class="form-control-wrap">
                                                    <textarea type="text" class="form-control" id="address" name="address" placeholder="Your Address"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-8 offset-lg-4">
                                            <div class="form-group mt-2">
                                                <button class="btn btn-primary btn-lg btn-update" type="submit">Update</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="tab-pane" id="tabItem9">
                            <h5 class="title mb-4">Change Password</h5>
                            <form method="post" action="{{ url('admin/update-password') }}" id="updatePasswordForm"  enctype="multipart/form-data">
                                @csrf
                                <div class="row g-4">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-label" for="password">New Password</label>
                                            <div class="form-control-wrap">
                                                <input type="password" class="form-control" id="password" name="password" required="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-label" for="password_confirmation">Confirm New Password</label>
                                            <div class="form-control-wrap">
                                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required="">
                                                <div style="margin-top: 7px;" id="CheckPasswordMatch"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <button class="btn btn-primary btn-lg btn-update" type="submit">Save</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <script>
        $(document).ready(function() {

            getProfile();
            function getProfile() {
                var id = $(this).attr('data');

                $.ajax({
                    url: '{{ url('admin/edit-profile') }}',
                    type: 'get',
                    async: false,
                    dataType: 'json',
                    success: function (res) {
                        console.log(res);

                        $('input[name=id]').val(res.id);
                        $('input[name=first_name]').val(res.first_name);
                        $('input[name=last_name]').val(res.last_name);
                        $('input[name=email]').val(res.email);
                        $('input[name=phone]').val(res.phone);
                        $('textarea[name=address]').val(res.address);

                        var output = document.getElementById('logo');
                        output.src = '{{ asset('storage/uploads/user-images/') }}/'+res.avatar;
                        output.onload = function () {
                            URL.revokeObjectURL(output.src) // free memory
                        }


                    },
                    error: function () {
                        toastr.error('any technical error');
                    }
                });
            }
            $('#updateProfileForm').on('submit', function(e) {
                e.preventDefault();
                let formData = new FormData($('#updateProfileForm')[0])
                $.ajax({
                    type: "POST",
                    url: '{{ url('admin/update-profile') }}',
                    data: formData,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('.btn-update').text('loading...');
                        $(".btn-update").prop("disabled", true);
                    },
                    success: function(data) {

                        if (data.success) {
                            getProfile();
                            $('.close').click();
                            toastr.success(data.success);

                        }
                        if (data.errors) {
                            toastr.error(data.errors);
                            $('.btn-update').text('Update');
                            $(".btn-update").prop("disabled", false);
                        }
                    },

                    complete: function(data) {
                        $(".btn-update").html("Update");
                        $(".btn-update").prop("disabled", false);
                    },

                    error: function() {;
                        toastr.error('any technical error');
                        $('.btn-update').text('Update');
                        $(".btn-update").prop("disabled", false);
                    }
                });


            });

            $('#updatePasswordForm').on('submit', function(e) {
                e.preventDefault();

                    var password = $("#password").val();
                    var confirmPassword = $("#password_confirmation").val();
                    var pswlen = password.length;
                    if (password != confirmPassword){
                        $("#CheckPasswordMatch").html("Password does not match !").css("color","red");
                        toastr.error('Password does not match !');
                    } else if (pswlen < 8) {
                        $("#CheckPasswordMatch").html("Minimun 8 characters needed !").css("color","red");
                        toastr.error('Minimun 8 characters needed !');
                    }else {
                        $("#CheckPasswordMatch").html("Password match !").css("color","green");

                let formData = new FormData($('#updatePasswordForm')[0])

                $.ajax({
                    type: "POST",
                    url: '{{ url('admin/update-password') }}',
                    data: formData,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('.btn-update').text('loading...');
                        $(".btn-update").prop("disabled", true);
                    },
                    success: function(data) {

                        if (data.success) {
                            // getProfile();
                            $('#updatePasswordForm')[0].reset();
                            $("#CheckPasswordMatch").html("");
                            $('.close').click();
                            toastr.success(data.success);

                        }
                        if (data.errors) {
                            toastr.error(data.errors);
                            $('.btn-update').text('Save');
                            $(".btn-update").prop("disabled", false);
                        }
                    },

                    complete: function(data) {
                        $(".btn-update").html("Save");
                        $(".btn-update").prop("disabled", false);
                    },

                    error: function() {;
                        toastr.error('any technical error');
                        $('.btn-update').text('Save');
                        $(".btn-update").prop("disabled", false);
                    }
                });
                    }

            });

        });
    </script>
    <script>

        var loadFile = function(event) {
            var output = document.getElementById('logo');
            output.src = URL.createObjectURL(event.target.files[0]);
            output.onload = function () {
                URL.revokeObjectURL(output.src) // free memory
            }
        };



    </script>
@endsection



