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
                                                <h4 class="nk-block-title">Contacts</h4>
                                                <div class="nk-block-des">

                                                </div>
                                            </div>
                                            <div class="d-flex align-center">
                                                <div class="nk-tab-actions me-n1">
                                                    <a href="#" class="btn btn-primary btn-sm"  data-toggle="modal" data-target="#addCountry"><em class="icon ni ni-plus"></em><span>New Contact</span></a>
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
                                            <table class=" table table-md datatable-init-export nk-tb-list nk-tb-ulist" data-auto-responsive="true"  id="" >
                                                <thead>
                                                <tr class="nk-tb-item nk-tb-head">
                                                    <th class="nk-tb-col text-left"><span class="sub-text">ID</span></th>
                                                    <th class="nk-tb-col"><span class="sub-text">Full Name</span></th>
                                                    <th class="nk-tb-col"><span class="sub-text">Email</span></th>
                                                    <th class="nk-tb-col"><span class="sub-text">Position</span></th>
                                                    <th class="nk-tb-col"><span class="sub-text">Status</span></th>
                                                    <th class="nk-tb-col"><span class="sub-text">Type</span></th>
                                                    <th class="nk-tb-col"><span class="sub-text">Action</span></th>
                                                </tr>
                                                </thead>
                                                <tbody id="countryTable">
                                                </tbody>
                                            </table>
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

            <div class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" id="addCountry" aria-hidden="true">
                <div class="modal-dialog modal-md" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title text-capitalize" id="ajax_model_title">New Contact</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" data-form="ajax-form-modal">
                            <form method="post" action="{{ url('admin/save-contact') }}" id="CountryForm">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label" for="status">Customer</label>
                                            <select class="form-control" disabled>
                                                @isset($data)
                                                    <option value="{{ $data['customer']->id }}" selected>{{ $data['customer']->full_display_name }}</option>
                                                @endisset
                                            </select>
                                            <input type="hidden" name="customer_id" id="contact_customer_id" value="{{ $data['customer']->id ?? '' }}">

                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>First Name <span class="text-danger">*</span></label>
                                            <input class="form-control" type="text" name="first_name" placeholder="First Name" required>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Last Name <span class="text-danger">*</span></label>
                                            <input class="form-control" type="text" name="last_name" placeholder="Last Name" required>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Position <span class="text-danger"></span></label>
                                            <input class="form-control" type="text" name="position" placeholder="Position" >
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Email <span class="text-danger">*</span></label>
                                            <input class="form-control" type="email" name="email" placeholder="Email" required>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Phone<span class="text-danger"></span></label>
                                            <input class="form-control" type="text" name="phone" placeholder="Phone" >
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Password <span class="text-danger"></span></label>
                                            <div class="form-control-wrap">
                                                <a href="#" class="form-icon form-icon-right passcode-switch" data-target="contact_create_password">
                                                    <em class="passcode-icon icon-show icon ni ni-eye"></em>
                                                    <em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
                                                </a>
                                                <input class="form-control" type="password" id="contact_create_password" name="password" placeholder="Password"  autocomplete="new-password" >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Contact Type <span class="text-danger"></span></label>
                                            <select name="contact_type" id="" class="form-control" >
                                                <option value="">Choose One</option>
                                                <option value="primary" selected>Primary</option>
                                                <option value="general">General</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Status <span class="text-danger"></span></label>
                                            <select name="status" id="" class="form-control" required>
                                                <option value="">Choose One</option>
                                                <option value="1">Active</option>
                                                <option value="0">In-Active</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mt-1">
                                        <div class="form-check">
                                            <input class="form-check-input" name="set_password" id="set_password" type="checkbox" value="1"  id="flexCheckDefault" />
                                            <label class="check-container" for="flexCheckDefault">Send SET password email</label>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mt-1">
                                        <div class="form-check">
                                            <input class="form-check-input" name="dont_welcome" id="dont_welcome" type="checkbox" value="1"  id="flexCheckDefault2" />
                                            <label class="check-container" for="flexCheckDefault2">Do not send welcome email</label>
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

            <div class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" id="editCountry" aria-hidden="true">
                <div class="modal-dialog modal-md" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title text-capitalize" id="ajax_model_title">Contact Information</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" data-form="ajax-form-modal">
                            <form method="post" action="{{ url('admin/update-contact') }}" id="updateCountryForm">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label" for="status">Customer</label>
                                            <select class="form-control" id="edit_customer_display" disabled>
                                                @isset($data)
                                                    <option value="{{ $data['customer']->id }}" selected>{{ $data['customer']->full_display_name }}</option>
                                                @endisset
                                            </select>
                                            <input type="hidden" name="edit_customer_id" id="edit_customer_id">

                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <input type="hidden" name="id">
                                            <label>First Name <span class="text-danger">*</span></label>
                                            <input class="form-control" type="text" name="edit_first_name" placeholder="First Name" required>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Last Name <span class="text-danger">*</span></label>
                                            <input class="form-control" type="text" name="edit_last_name" placeholder="Last Name" required>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Position <span class="text-danger"></span></label>
                                            <input class="form-control" type="text" name="edit_position" placeholder="Position" >
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Email <span class="text-danger">*</span></label>
                                            <input class="form-control" type="email" name="edit_email" placeholder="Email" required>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Phone<span class="text-danger"></span></label>
                                            <input class="form-control" type="text" name="edit_phone" placeholder="Phone" >
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Password</label>
                                            <div class="form-control-wrap">
                                                <a href="#" class="form-icon form-icon-right passcode-switch" data-target="contact_edit_password">
                                                    <em class="passcode-icon icon-show icon ni ni-eye"></em>
                                                    <em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
                                                </a>
                                                <input class="form-control" type="password" id="contact_edit_password" name="edit_password" placeholder="Leave blank to keep current password" >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Contact Type <span class="text-danger"></span></label>
                                            <select name="edit_contact_type" id="" class="form-control" >
                                                <option value="">Choose One</option>

                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Status <span class="text-danger"></span></label>
                                            <select name="edit_status" id="" class="form-control" required>
                                                <option value="">Choose One</option>

                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="float-right">
                                    <button class="btn btn-primary mt-2 btn-update" type="submit">Save Changes</button>
                                </div>
                            </form>

                        </div>
                    </div><!-- .modal-content -->
                </div><!-- .modla-dialog -->
            </div>

            <div class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" id="setPasswordModal" aria-hidden="true">
                <div class="modal-dialog modal-md" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title text-capitalize">Set Password</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form method="post" id="setPasswordForm">
                                @csrf
                                <input type="hidden" name="contact_id" id="set_password_contact_id">
                                <div class="form-group">
                                    <label>Contact</label>
                                    <input class="form-control" type="text" id="set_password_contact_name" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Email</label>
                                    <input class="form-control" type="email" id="set_password_contact_email" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Password <span class="text-danger">*</span></label>
                                    <div class="form-control-wrap">
                                        <a href="#" class="form-icon form-icon-right passcode-switch" data-target="set_password_password">
                                            <em class="passcode-icon icon-show icon ni ni-eye"></em>
                                            <em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
                                        </a>
                                        <input class="form-control" type="password" id="set_password_password" name="password" placeholder="Enter password" autocomplete="new-password" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Confirm Password <span class="text-danger">*</span></label>
                                    <div class="form-control-wrap">
                                        <a href="#" class="form-icon form-icon-right passcode-switch" data-target="set_password_confirmation">
                                            <em class="passcode-icon icon-show icon ni ni-eye"></em>
                                            <em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
                                        </a>
                                        <input class="form-control" type="password" id="set_password_confirmation" name="password_confirmation" placeholder="Confirm password" autocomplete="new-password" required>
                                    </div>
                                </div>
                                <div class="float-right">
                                    <button class="btn btn-primary mt-2 btn-set-password-submit" type="submit">Save Password</button>
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

            $('#CountryForm').on('submit', function(e) {

                e.preventDefault();
                var formData=$('#CountryForm').serialize()
                $.ajax({
                    type: "get",
                    url: '{{ url('admin/save-contact') }}',
                    data: formData,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('.btn-submit').text('Saving...');
                        $(".btn-submit").prop("disabled", true);
                    },
                    success: function(data) {

                        if (data.success) {
                            getAllCities();
                            $('#CountryForm')[0].reset();
                            $('.close').click();
                            toastr.success(data.success);

                        }
                        if (data.errors) {
                            console.log(data.errors);
                            toastr.error(data.errors.email);
                            $('.btn-submit').text('Save');
                            $(".btn-submit").prop("disabled", false);
                        }
                    },

                    complete: function(data) {
                        $(".btn-submit").html("Save");
                        $(".btn-submit").prop("disabled", false);
                    },

                    error: function() {;
                        toastr.error('any technical error');
                        $('.btn-submit').text('Save');
                        $(".btn-submit").prop("disabled", false);
                    }
                });


            });


            getAllCities();
            function getAllCities() {
                var customer_id=$('#contact_customer_id').val();

                $.ajax({

                    url: '{{ url('admin/get-contacts') }}',
                    type: 'get',
                    async: false,
                    dataType: 'json',
                    data: { customer_id:customer_id},

                    success: function(data) {
                        console.log(data);

                        var html = '';
                        var i;
                        var c = 0;

                        for (i = 0; i < data.length; i++) {
                            c++;
                            html += ' <tr class="nk-tb-item odd">'+
                                ' <td class="nk-tb-col nk-tb-col-tools sorting_1">'+c+'</td>'+
                                {{--' <td class="nk-tb-col nk-tb-col-tools"><a href={{url('admin/contact')}}/' + data[i].id + ' class="btn-edit" data='+data[i].id+'>' + data[i].first_name + ' ' + data[i].last_name + '</a></td>' +--}}
                                ' <td class="nk-tb-col nk-tb-col-tools"><a href="#" class="btn-edit" data='+data[i].id+' data-toggle="modal" data-target="#editCountry">' + data[i].first_name + ' ' + data[i].last_name + '</a></td>' +
                                // ' <td class="nk-tb-col nk-tb-col-tools"><a href="#">' + data[i].first_name + ' ' + data[i].last_name + '</a></td>' +
                                ' <td class="nk-tb-col nk-tb-col-tools">' + data[i].email + '</td>'+
                                ' <td class="nk-tb-col nk-tb-col-tools">' + data[i].position + '</td>'+
                                '<td class="nk-tb-col nk-tb-col-tools" >'+
                                ' <span class="badge badge-success">'+data[i].status+'</span>'+
                                '</td>'+
                                ' <td class="nk-tb-col nk-tb-col-tools">' + data[i].contact_type + '</td>'+
                                '  <td class="nk-tb-col nk-tb-col-tools">'+
                                ' <ul class="nk-tb-actions" gx-1 style="right: 2.5rem; !important">'+
                                '  <li>'+
                                ' <div class="drodown">'+
                                '  <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>'+
                                ' <div class="dropdown-menu dropdown-menu-right">'+
                                '<ul class="link-list-opt no-bdr">'+
                                '<li><a href="#" class="btn-set-password" data-id="'+data[i].id+'" data-name="'+data[i].first_name+' '+data[i].last_name+'" data-email="'+data[i].email+'" data-toggle="modal" data-target="#setPasswordModal"><em class="icon ni ni-lock"></em><span>Set Password</span></a></li>'+
                                 '<li><a href="#" class="btn-edit" data='+data[i].id+' data-toggle="modal" data-target="#editCountry"><em class="icon ni ni-edit"></em><span>Edit</span></a></li>'+
                                '<li><a href="#" class="btn-delete" data='+data[i].id+'><em class="icon ni ni-trash"></em><span>Delete</span></a></li>'+
                                '</ul>'+
                                ' </div>'+
                                '</div>'+
                                ' </li>'+
                                ' </ul>'+
                                '</td>'+


                                '</tr>';
                        }

                        $('#countryTable').html(html);

                    },
                    error: function() {
                        toastr.error('something went wrong');
                    }

                });
            }

            $('#countryTable').on('click', '.btn-delete', function() {
                var id = $(this).attr('data');
                $.ajax({
                    url: '{{ url('admin/delete-contact') }}',
                    type: 'get',
                    async: false,
                    dataType: 'json',
                    data: { id: id},
                    success: function(data) {
                        if (data.success) {
                            getAllCities();
                            $('.close').click();
                            toastr.success('Record deleted successfully');
                        }else{
                            toastr.success('Record not deleted');
                        }

                    },
                    error: function() {
                        toastr.error('something went wrong');
                    }

                });

            });


            $('#countryTable').on('click', '.btn-edit', function() {
                var id = $(this).attr('data');

                $.ajax({
                    url: '{{ url('admin/edit-contact') }}',
                    type: 'get',
                    async: false,
                    dataType: 'json',
                    data: { id: id },
                    success: function(res) {
                        console.log(res);

                        $('input[name=id]').val(id);
                        $('input[name=edit_first_name]').val(res.contact.first_name);
                        $('input[name=edit_last_name]').val(res.contact.last_name);
                        $('input[name=edit_position]').val(res.contact.position);
                        $('input[name=edit_email]').val(res.contact.email);
                        $('input[name=edit_phone]').val(res.contact.phone);
                        $('select[name="edit_contact_type"]')
                            .html(
                                `<option value="primary" ${res.contact.contact_type == 'primary' ? 'selected' : ''}>Primary</option>`+
                                `<option value="general" ${res.contact.contact_type== 'general' ? 'selected' : ''}>General</option>`
                            )
                        $('select[name="edit_status"]')
                            .html(
                                `<option value="1" ${res.contact.status == 'Active' ? 'selected' : ''}>Active</option>`+
                                `<option value="0" ${res.contact.status== 'In-Active' ? 'selected' : ''}>In-Active</option>`
                            )
                        $('#edit_customer_id').val(res.contact.customer_id);
                        $('#edit_customer_display').empty();

                        $.each(res.customer, function(key, customer) {
                            if (customer.id == res.contact.customer_id) {
                                $('#edit_customer_display').append(
                                    `<option value="${customer.id}" selected>${customer.full_display_name}</option>`
                                )
                            }
                        });
                    },
                    error: function() {
                        toastr.error('any technical error');
                    }
                });
            });

            $('#countryTable').on('click', '.btn-set-password', function() {
                var id = $(this).data('id');
                var name = $(this).data('name');
                var email = $(this).data('email');

                $('#set_password_contact_id').val(id);
                $('#set_password_contact_name').val(name);
                $('#set_password_contact_email').val(email);
                $('#setPasswordForm')[0].reset();
                $('#set_password_contact_id').val(id);
                $('#set_password_contact_name').val(name);
                $('#set_password_contact_email').val(email);
            });

            $('#setPasswordForm').on('submit', function(e) {
                e.preventDefault();
                var formData = $(this).serialize();

                $.ajax({
                    type: 'POST',
                    url: '{{ url('admin/contact-set-password') }}',
                    data: formData,
                    beforeSend: function() {
                        $('.btn-set-password-submit').text('Saving...');
                        $('.btn-set-password-submit').prop('disabled', true);
                    },
                    success: function(data) {
                        if (data.success) {
                            $('#setPasswordForm')[0].reset();
                            $('#setPasswordModal .close').click();
                            toastr.success(data.message);
                        } else {
                            toastr.error(data.message || 'Unable to set password.');
                        }
                    },
                    error: function(xhr) {
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            var messages = [];
                            $.each(xhr.responseJSON.errors, function(key, value) {
                                messages.push(value[0]);
                            });
                            toastr.error(messages.join('<br>'));
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            toastr.error(xhr.responseJSON.message);
                        } else {
                            toastr.error('any technical error');
                        }
                    },
                    complete: function() {
                        $('.btn-set-password-submit').text('Save Password');
                        $('.btn-set-password-submit').prop('disabled', false);
                    }
                });
            });

            $('#updateCountryForm').on('submit', function(e) {
                e.preventDefault();
                var formData=$('#updateCountryForm').serialize()
                $.ajax({
                    type: "get",
                    url: '{{ url('admin/update-contact') }}',
                    data: formData,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('.btn-update').text('loading...');
                        $(".btn-update").prop("disabled", true);
                    },
                    success: function(data) {

                        if (data.success) {
                            getAllCities();
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



