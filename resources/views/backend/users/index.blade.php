@extends('backend.layouts.app')
@section('title', '| Users')
@section('content')
    <div class="components-preview wide-md mx-auto">
        <div class="nk-block nk-block-lg">
            <div class="nk-block-head">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h3 class="nk-block-title page-title">Users List</h3>
                        <div class="nk-block-des text-soft">
                        </div>
                    </div>
                    <div class="nk-block-head-content">
                        <div class="toggle-wrap nk-block-tools-toggle">
                            <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-more-v"></em></a>
                            <div class="toggle-expand-content" data-content="pageMenu">
                                <ul class="nk-block-tools g-3">
                                    <li class="nk-block-tools-opt">
                                        <a href="#" class="btn btn-primary btn-sm"  data-toggle="modal" data-target="#addCountry"><em class="icon ni ni-plus"></em><span>Add User</span></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card card-preview">
                <div class="card-inner">
                    <table class="datatable-init-export nk-tb-list nk-tb-ulist" data-auto-responsive="true"  id="datatable" >
                        <thead>
                        <tr class="nk-tb-item nk-tb-head">
                            <th class="nk-tb-col text-left"><span class="sub-text">ID</span></th>
                            <th class="nk-tb-col"><span class="sub-text">First Name</span></th>
                            <th class="nk-tb-col"><span class="sub-text">Last Name</span></th>
                            <th class="nk-tb-col"><span class="sub-text">Email</span></th>
                            <th class="nk-tb-col"><span class="sub-text">Status</span></th>
                            <th class="nk-tb-col tb-col-mb text-right"><span class="sub-text">Actions</span></th>
                        </tr>
                        </thead>
                        <tbody id="countryTable">
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- .card-preview -->
        </div>
        <!-- nk-block -->
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="addCountry" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-capitalize" id="ajax_model_title">Add New User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" data-form="ajax-form-modal">

                    <form method="post" action="{{ url('admin/save-user') }}" id="CountryForm" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-3 align-center">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="form-label" for="dashboard_title">Avatar</label>
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="form-group">
                                    <div class=" logo">
                                        <label for="logo-input">
                                            <img id="logo" src="{{ asset('sk-assets/assets/images/no_avatar.png') }}" alt="store logo" class="" style="max-width:100px;max-height:120px">
                                            <input id="logo-input" preview="#logo" name="file" class="d-none" type="file" >
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-4">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label" for="first_name">First Name</label>
                                    <div class="form-control-wrap">
                                        <input type="text" class="form-control" id="first_name" name="first_name" required="" value="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label" for="last_name">Last Name</label>
                                    <div class="form-control-wrap">
                                        <input type="text" class="form-control" id="last_name" name="last_name" required="" value="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label" for="email">Email</label>
                                    <div class="form-control-wrap">
                                        <input type="email" class="form-control" id="email" name="email" required="" value="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label" for="phone">Phone</label>
                                    <div class="form-control-wrap">
                                        <input type="text" class="form-control" id="phone" name="phone" value="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label" for="create_password">Password</label>
                                    <div class="form-control-wrap">
                                        <a href="#" class="form-icon form-icon-right passcode-switch" data-target="create_password">
                                            <em class="passcode-icon icon-show icon ni ni-eye"></em>
                                            <em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
                                        </a>
                                        <input type="password" class="form-control" id="create_password" name="password" required="" value="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label" for="create_password_confirmation">Confirm Password</label>
                                    <div class="form-control-wrap">
                                        <a href="#" class="form-icon form-icon-right passcode-switch" data-target="create_password_confirmation">
                                            <em class="passcode-icon icon-show icon ni ni-eye"></em>
                                            <em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
                                        </a>
                                        <input type="password" class="form-control" id="create_password_confirmation" name="password_confirmation" required="" value="">
                                    </div>
                                </div>
                            </div>


                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-label" for="address">Address</label>
                                    <div class="form-control-wrap">
                                        <textarea type="text" class="form-control" id="address" name="address" value="" placeholder="Your Address"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-label">Gender</label>
                                    <div class="form-control-wrap">
                                        <ul class="custom-control-group">
                                            <li>
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" class="custom-control-input" name="gender" id="gender_male" value="male" required>
                                                    <label class="custom-control-label" for="gender_male">Male</label>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" class="custom-control-input" name="gender" id="gender_female" value="female">
                                                    <label class="custom-control-label" for="gender_female">Female</label>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" class="custom-control-input" name="gender" id="gender_other" value="other">
                                                    <label class="custom-control-label" for="gender_other">Other</label>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label" for="employee_id">Employee ID</label>
                                    <div class="form-control-wrap">
                                        <input type="text" class="form-control" id="employee_id" name="employee_id" required value="{{ $data['employee_id'] ?? '' }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label" for="department_id">Department</label>
                                    <select class="form-control" id="department_id" name="department_id" required>
                                        <option value="">Choose One</option>
                                        @isset($data['departments'])
                                            @foreach ($data['departments'] as $department)
                                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                                            @endforeach
                                        @endisset
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label" for="designation_id">Designation</label>
                                    <select class="form-control" id="designation_id" name="designation_id" required>
                                        <option value="">Choose One</option>
                                        @isset($data['designations'])
                                            @foreach ($data['designations'] as $designation)
                                                <option value="{{ $designation->id }}">{{ $designation->name }}</option>
                                            @endforeach
                                        @endisset
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label" for="employment_status_id">Employment Status</label>
                                    <select class="form-control" id="employment_status_id" name="employment_status_id" required>
                                        <option value="">Choose One</option>
                                        @isset($data['employment_statuses'])
                                            @foreach ($data['employment_statuses'] as $employmentStatus)
                                                <option value="{{ $employmentStatus->id }}">{{ $employmentStatus->name }}</option>
                                            @endforeach
                                        @endisset
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label" for="salary">Salary</label>
                                    <div class="form-control-wrap">
                                        <input type="number" class="form-control" id="salary" name="salary" min="0" step="0.01" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label" for="joining_date">Joining Date</label>
                                    <div class="form-control-wrap">
                                        <input type="date" class="form-control" id="joining_date" name="joining_date" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label" for="portal_role">Portal Role</label>
                                    <select class="form-control" id="portal_role" name="portal_role" data-select2-id="portal_role">
                                        <option value="">None (HRM only)</option>
                                        @isset($data['portal_roles'])
                                            @foreach ($data['portal_roles'] as $role)
                                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                                            @endforeach
                                        @endisset
                                    </select>
                                    <small class="text-soft">CRM / Admin Portal access</small>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label" for="hrm_role">HRM Role</label>
                                    <select class="form-control" id="hrm_role" name="hrm_role" data-select2-id="hrm_role">
                                        <option value="">Default (Employee)</option>
                                        @isset($data['hrm_roles'])
                                            @foreach ($data['hrm_roles'] as $role)
                                                <option value="{{ $role->id }}" @if(($role->alias ?? '') === 'employee') selected @endif>{{ $role->name }}</option>
                                            @endforeach
                                        @endisset
                                    </select>
                                    <small class="text-soft">Leave, attendance, payroll</small>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label" for="status">Status</label>
                                    <select class="form-control form-select" id="status" name="status" required="" data-select2-id="status" tabindex="-1" aria-hidden="true">
                                        <option value="1" data-select2-id="26">Active</option>
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

    <div class="modal fade" tabindex="-1" role="dialog" id="editCountry" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-capitalize" id="ajax_model_title">Edit User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" data-form="ajax-form-modal">

                    <form method="post" action="{{ url('admin/update-user') }}" id="updateCountryForm" enctype="multipart/form-data" >
                        @csrf

                        <div class="row g-3 align-center">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="form-label" for="dashboard_title">Avatar</label>
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="form-group">
                                    <div class=" logo">
                                        <label for="logo-input">
                                            <img id="e_file" src="{{ asset('sk-assets/assets/images/no_avatar.png') }}" alt="store logo" class="" style="max-width:100px;max-height:120px">
                                            <input id="logo-input" preview="#e_file" name="e_file"  type="file" onchange="loadFile(event)" >
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-4">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label" for="first_name">First Name</label>
                                    <div class="form-control-wrap">
                                        <input type="hidden" name="id">
                                        <input type="text" class="form-control" id="e_first_name" name="e_first_name" required="" value="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label" for="last_name">Last Name</label>
                                    <div class="form-control-wrap">
                                        <input type="text" class="form-control" id="e_last_name" name="e_last_name" required="" value="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label" for="email">Email</label>
                                    <div class="form-control-wrap">
                                        <input type="email" class="form-control" id="e_email" name="e_email" required="" value="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label" for="phone">Phone</label>
                                    <div class="form-control-wrap">
                                        <input type="text" class="form-control" id="phone" name="e_phone" value="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label" for="edit_password">Password</label>
                                    <div class="form-control-wrap">
                                        <a href="#" class="form-icon form-icon-right passcode-switch" data-target="edit_password">
                                            <em class="passcode-icon icon-show icon ni ni-eye"></em>
                                            <em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
                                        </a>
                                        <input type="password" class="form-control" id="edit_password" name="password" >
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label" for="edit_password_confirmation">Confirm Password</label>
                                    <div class="form-control-wrap">
                                        <a href="#" class="form-icon form-icon-right passcode-switch" data-target="edit_password_confirmation">
                                            <em class="passcode-icon icon-show icon ni ni-eye"></em>
                                            <em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
                                        </a>
                                        <input type="password" class="form-control" id="edit_password_confirmation" name="password_confirmation" >
                                    </div>
                                </div>
                            </div>


                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-label" for="address">Address</label>
                                    <div class="form-control-wrap">
                                        <textarea type="text" class="form-control" id="address" name="e_address" value="" placeholder="Your Address"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-label">Gender</label>
                                    <div class="form-control-wrap">
                                        <ul class="custom-control-group">
                                            <li>
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" class="custom-control-input" name="e_gender" id="e_gender_male" value="male" required>
                                                    <label class="custom-control-label" for="e_gender_male">Male</label>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" class="custom-control-input" name="e_gender" id="e_gender_female" value="female">
                                                    <label class="custom-control-label" for="e_gender_female">Female</label>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" class="custom-control-input" name="e_gender" id="e_gender_other" value="other">
                                                    <label class="custom-control-label" for="e_gender_other">Other</label>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label" for="e_employee_id">Employee ID</label>
                                    <div class="form-control-wrap">
                                        <input type="text" class="form-control" id="e_employee_id" name="e_employee_id" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label" for="e_department_id">Department</label>
                                    <select class="form-control" id="e_department_id" name="e_department_id" required>
                                        <option value="">Choose One</option>
                                        @isset($data['departments'])
                                            @foreach ($data['departments'] as $department)
                                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                                            @endforeach
                                        @endisset
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label" for="e_designation_id">Designation</label>
                                    <select class="form-control" id="e_designation_id" name="e_designation_id" required>
                                        <option value="">Choose One</option>
                                        @isset($data['designations'])
                                            @foreach ($data['designations'] as $designation)
                                                <option value="{{ $designation->id }}">{{ $designation->name }}</option>
                                            @endforeach
                                        @endisset
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label" for="e_employment_status_id">Employment Status</label>
                                    <select class="form-control" id="e_employment_status_id" name="e_employment_status_id" required>
                                        <option value="">Choose One</option>
                                        @isset($data['employment_statuses'])
                                            @foreach ($data['employment_statuses'] as $employmentStatus)
                                                <option value="{{ $employmentStatus->id }}">{{ $employmentStatus->name }}</option>
                                            @endforeach
                                        @endisset
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label" for="e_joining_date">Joining Date</label>
                                    <div class="form-control-wrap">
                                        <input type="date" class="form-control" id="e_joining_date" name="e_joining_date" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label" for="e_portal_role">Portal Role</label>
                                    <select class="form-control" id="e_portal_role" name="e_portal_role">
                                        <option value="">None (HRM only)</option>
                                    </select>
                                    <small class="text-soft">CRM / Admin Portal access</small>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label" for="e_hrm_role">HRM Role</label>
                                    <select class="form-control" id="e_hrm_role" name="e_hrm_role">
                                        <option value="">None</option>
                                    </select>
                                    <small class="text-soft">Leave, attendance, payroll</small>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label" for="status">Status</label>
                                    <select class="form-control form-select" id="status" name="e_status" required="" data-select2-id="status" tabindex="-1" aria-hidden="true">

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


    <script>
        $(document).ready(function() {

            $('#CountryForm').on('submit', function(e) {

                e.preventDefault();
                let formData = new FormData($('#CountryForm')[0])
                $.ajax({
                    type: "POST",
                    url: '{{ url('admin/save-user') }}',
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
                            toastr.error(data.errors);
                            $('.btn-submit').text('Save');
                            $(".btn-submit").prop("disabled", false);
                        }
                    },

                    complete: function(data) {
                        $(".btn-submit").html("Save");
                        $(".btn-submit").prop("disabled", false);
                    },

                    error: function(xhr, status, error) {
                        $('.btn-submit').text('Save');
                        $(".btn-submit").prop("disabled", false);

                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            // Show all validation errors
                            $.each(xhr.responseJSON.errors, function(key, value) {
                                toastr.error(value);
                            });
                        } else if (xhr.responseJSON && xhr.responseJSON.error) {
                            // Custom error from controller
                            toastr.error(xhr.responseJSON.error);
                        } else {
                            // Fallback
                            toastr.error("Something went wrong: " + xhr.status + " " + error);
                        }
                    }
                });


            });

            getAllCities();
            function getAllCities() {

                $.ajax({

                    url: '{{ url('admin/get-users') }}',
                    type: 'get',
                    async: false,
                    dataType: 'json',

                    success: function(data) {
                        console.log(data);

                        var html = '';
                        var i;
                        var c = 0;

                        for (i = 0; i < data.length; i++) {
                            c++;
                            html += ' <tr class="nk-tb-item odd">'+
                                ' <td class="nk-tb-col nk-tb-col-tools sorting_1">'+c+'</td>'+
                                ' <td class="nk-tb-col nk-tb-col-tools">'+data[i].first_name+'  </td>'+
                                ' <td class="nk-tb-col nk-tb-col-tools">'+data[i].last_name+'</td>'+
                                ' <td class="nk-tb-col nk-tb-col-tools">'+data[i].email+'</td>'+
                                '<td class="nk-tb-col nk-tb-col-tools" >'+
                                ' '+statusBadgeHtml(data[i].status)+
                                ' </td>'+
                                '  <td class="nk-tb-col nk-tb-col-tools">'+
                                ' <ul class="nk-tb-actions gx-1">'+
                                '  <li>'+
                                ' <div class="drodown">'+
                                '  <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>'+
                                ' <div class="dropdown-menu dropdown-menu-right">'+
                                '<ul class="link-list-opt no-bdr">'+
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
                    url: '{{ url('admin/delete-user') }}',
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
                    url: '{{ url('admin/edit-user') }}',
                    type: 'get',
                    async: false,
                    dataType: 'json',
                    data: { id: id },
                    success: function(res) {
                        console.log(res);

                        $('input[name=id]').val(id);
                        $('input[name=e_first_name]').val(res.st.first_name);
                        $('input[name=e_last_name]').val(res.st.last_name);
                        $('input[name=e_email]').val(res.st.email);
                        $('input[name=e_phone]').val(res.st.phone);
                        $('textarea[name=e_address]').val(res.st.address);

                        var gender = (res.st.profile && res.st.profile.gender) ? res.st.profile.gender.toLowerCase() : '';
                        $('input[name=e_gender]').prop('checked', false);
                        if (gender) {
                            $('input[name=e_gender][value="' + gender + '"]').prop('checked', true);
                        }

                        $('input[name=e_employee_id]').val(res.st.profile ? res.st.profile.employee_id : '');
                        $('select[name=e_department_id]').val(res.st.department ? res.st.department.id : '');
                        $('select[name=e_designation_id]').val(res.st.designation ? res.st.designation.id : '');
                        $('select[name=e_employment_status_id]').val(res.st.employment_status ? res.st.employment_status.id : '');

                        var joiningDate = res.st.profile && res.st.profile.joining_date ? res.st.profile.joining_date : '';
                        if (joiningDate) {
                            joiningDate = joiningDate.substring(0, 10);
                        }
                        $('input[name=e_joining_date]').val(joiningDate);


                        $('select[name="e_status"]')
                            .html(
                                `<option value="1" ${res.st.status == 'active' || res.st.status == 1 ? 'selected' : ''}>Active</option>`+
                                `<option value="0" ${res.st.status== 'inactive' || res.st.status == 0 ? 'selected' : ''}>In-Active</option>`
                            );

                        $('select[name="e_portal_role"]').empty().append('<option value="">None (HRM only)</option>');
                        $.each(res.portal_roles || res.role || [], function(key, role) {
                            var selected = (role.id == res.portal_role_id) ? 'selected' : '';
                            $('select[name="e_portal_role"]').append(
                                `<option value="${role.id}" ${selected}>${role.name}</option>`
                            );
                        });

                        $('select[name="e_hrm_role"]').empty().append('<option value="">None</option>');
                        $.each(res.hrm_roles || [], function(key, role) {
                            var selected = (role.id == res.hrm_role_id) ? 'selected' : '';
                            $('select[name="e_hrm_role"]').append(
                                `<option value="${role.id}" ${selected}>${role.name}</option>`
                            );
                        });
                        var output = document.getElementById('e_file');
                        output.src = '{{ asset('storage/uploads/user-images/') }}/'+res.st.avatar;
                        output.onload = function () {
                            URL.revokeObjectURL(output.src) // free memory
                        }

                    },
                    error: function() {
                        toastr.error('any technical error');
                    }
                });
            });
            $('#updateCountryForm').on('submit', function(e) {
                e.preventDefault();
                let formData = new FormData($('#updateCountryForm')[0])
                $.ajax({
                    type: "POST",
                    url: '{{ url('admin/update-user') }}',
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

                    error: function(xhr, status, error) {
                        $('.btn-update').text('Save Changes');
                        $(".btn-update").prop("disabled", false);

                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            // Show all validation errors
                            $.each(xhr.responseJSON.errors, function(key, value) {
                                toastr.error(value);
                            });
                        } else if (xhr.responseJSON && xhr.responseJSON.error) {
                            // Custom error from controller
                            toastr.error(xhr.responseJSON.error);
                        } else {
                            // Fallback
                            toastr.error("Something went wrong: " + xhr.status + " " + error);
                        }
                    }

                });


            });

        });
    </script>
    <script>

        var loadFile = function(event) {
            var output = document.getElementById('e_file');
            output.src = URL.createObjectURL(event.target.files[0]);
            output.onload = function () {
                URL.revokeObjectURL(output.src) // free memory
            }
        };



    </script>
@endsection



