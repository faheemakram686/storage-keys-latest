@extends('backend.layouts.app')
@section('title', '| Leads')
@section('content')
    <div class="components-preview wide-md mx-auto">
        <div class="nk-block nk-block-lg">
            <div class="nk-block-head">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h3 class="nk-block-title page-title">Leads List</h3>
                        <div class="nk-block-des text-soft">

                        </div>
                    </div>
                    <div class="nk-block-head-content">
                        <div class="toggle-wrap nk-block-tools-toggle">
                            <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-more-v"></em></a>
                            <div class="toggle-expand-content" data-content="pageMenu">
                                <ul class="nk-block-tools g-3">
                                    <li class="nk-block-tools-opt">
{{--                                        @can('create_lead')--}}
                                            <a href="{{route('lead.create')}}" class="btn btn-primary btn-sm"  ><em class="icon ni ni-plus"></em><span>Add New Lead</span></a>
{{--                                        @endcan--}}

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
                            <th class="nk-tb-col text-left"><span class="sub-text">Sr no.</span></th>
                            <th class="nk-tb-col"><span class="sub-text">Lead</span></th>
                            <th class="nk-tb-col"><span class="sub-text">Customer Name</span></th>
                            <th class="nk-tb-col"><span class="sub-text">Phone</span></th>
                            <th class="nk-tb-col"><span class="sub-text">Email</span></th>
                            <th class="nk-tb-col"><span class="sub-text">Lead Status</span></th>
                            <th class="nk-tb-col"><span class="sub-text">Lead Created</span></th>
                            <th class="nk-tb-col"><span class="sub-text">Lead Owner</span></th>
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

    <div class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" id="addCountry" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-capitalize" id="ajax_model_title">Add New Lead</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" data-form="ajax-form-modal">

                        <form method="post" action="#" id="CountryForm1">
                            @csrf
                                <div class="row">

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label> Individual <span class="text-danger"></span></label>
                                            <input class="form-control" type="radio" name="lead_type" value="individual" >

                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label> Company <span class="text-danger"></span></label>
                                            <input class="form-control" type="radio"  name="lead_type" value="company" >
                                        </div>
                                    </div>
                                </div>
                            <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label>First Name  <span class="text-danger"></span></label>
                                            <input class="form-control" type="text" name="f_name" placeholder="First name" required>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label> Last Name <span class="text-danger">*</span></label>
                                            <input class="form-control" type="text" name="l_name" placeholder="Last name" required>
                                        </div>
                                    </div>

                                </div>
                                <div class="row">

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label> Title <span class="text-danger"></span></label>
                                            <input class="form-control" type="text" name="title" placeholder="Title" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Lead Status <span class="text-danger">*</span></label>
                                            <input class="form-control" type="text" name="lead_status" placeholder="Lead Status" required>
                                        </div>
                                    </div>


                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label> Lead Rating <span class="text-danger"></span></label>
                                            <input class="form-control" type="number" name="lead_rating" placeholder="Lead Rating" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>User Responsible <span class="text-danger"></span></label>
                                            <input class="form-control" type="text" name="user_res" placeholder="User Responsible" required>
                                        </div>
                                    </div>

                                </div>
                                <div class="row">

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label> Email Address <span class="text-danger"></span></label>
                                                <input class="form-control" type="text" name="email" placeholder="Email Address" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Lead Source <span class="text-danger">*</span></label>
                                                <input class="form-control" type="text" name="lead_source" placeholder="Lead Source" required>
                                            </div>
                                        </div>
                                    </div>
                                <div class="row">

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label> Phone <span class="text-danger"></span></label>
                                                <input class="form-control" type="text" name="phone" placeholder="Phone" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label> Mobile 1 <span class="text-danger"></span></label>
                                                <input class="form-control" type="text" name="mobile_phone" placeholder="Mobile Phone " required>
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
    <div class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" id="makeCustomer" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-capitalize" id="ajax_model_title">Lead Convert to Customer</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" data-form="ajax-form-modal">
                    <div class="nk-block nk-block-sm">
                        <form method="post" action="{{ url('admin/convert-customer') }}" id="CountryForm">
                            @csrf
                            <input type="hidden" name="lead_id">
                            <input type="hidden" name="lead_type">
                            <div class="row">
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
                                    <div class="row" id="company">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Company Name <span class="text-danger"></span></label>
                                                <input class="form-control" type="text" name="company_name" placeholder="Company Name" >
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Trade License No <span class="text-danger"></span></label>
                                                <input class="form-control" type="text" name="license_no" placeholder="Trade License No" >
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>VAT <span class="text-danger"></span></label>
                                                <input class="form-control" type="text" name="vat" placeholder="VAT" >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" id="individual">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Customer ID <span class="text-danger"></span></label>
                                                <input class="form-control" type="text" name="customer_id_card" placeholder="Customer ID">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Passport No <span class="text-danger"></span></label>
                                                <input class="form-control" type="text" name="passport_no" placeholder="Passport No" >
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Date of Birth <span class="text-danger"></span></label>
                                                <input class="form-control" type="text" name="dob" placeholder="Date of Birth" >
                                            </div>
                                        </div>
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
                                        <input class="form-control" type="password" name="password" placeholder="Password" autocomplete="new-password" >
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Contact Type <span class="text-danger">*</span></label>
                                        <select name="contact_type" id="" class="form-control" required >
                                            <option value="">Choose One</option>
                                            <option value="primary" selected>Primary</option>
                                            <option value="general">General</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Status <span class="text-danger">*</span></label>
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
                </div>
            </div><!-- .modal-content -->
        </div><!-- .modla-dialog -->
    </div>
    <div class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" id="editCountry" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
                <div class="modal-body modal-body-sm">
                    <h4 class="title " id="c_title"></h4>
                    <input type="hidden" name="lead_id_backup" id="lead_id_backup">
                    <ul class="nk-nav nav nav-tabs">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#tabItem1">Profile</a>
                        </li>
{{--                        <li class="nav-item">--}}
{{--                            <a class="nav-link" data-toggle="tab" href="#tabItem2">Estimate</a>--}}
{{--                        </li>--}}
{{--                        <li class="nav-item">--}}
{{--                            <a class="nav-link" data-toggle="tab" href="#tabItem3">Tasks</a>--}}
{{--                        </li>--}}
{{--                        <li class="nav-item">--}}
{{--                            <a class="nav-link" data-toggle="tab" href="#tabItem4">Attachments</a>--}}
{{--                        </li>--}}
{{--                        <li class="nav-item">--}}
{{--                            <a class="nav-link" data-toggle="tab" href="#tabItem56">Notes</a>--}}
{{--                        </li>--}}
{{--                        <li class="nav-item">--}}
{{--                            <a class="nav-link" data-toggle="tab" href="#tabItem6">Activity Log</a>--}}
{{--                        </li>--}}
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tabItem1">
                            <div class="nk-block">
                                <div class="profile-ud-list">
                                    <div class="profile-ud-item">
                                        <div class="profile-ud wider float-right" id="convert">

                                        </div>
                                    </div>
                                    <hr class="hr" />
                                    <div class="profile-ud-item">
                                        <div class="profile-ud wider">
                                            <div class="nk-block-head nk-block-head-line">
                                                <h6 class="title overline-title text-base">Lead Information</h6>
                                            </div><!-- .nk-block-head -->
                                        </div>
                                    </div>
                                    <div class="profile-ud-item">
                                        <div class="profile-ud wider">
                                            <span class="profile-ud-label">Company Name</span>
                                            <span class="profile-ud-value" id="company_name"></span>
                                        </div>
                                    </div>
                                    <div class="profile-ud-item">
                                        <div class="profile-ud wider">
                                            <span class="profile-ud-label">Full Name</span>
                                            <span class="profile-ud-value" id="full_name"></span>
                                        </div>
                                    </div>
                                    <div class="profile-ud-item">
                                        <div class="profile-ud wider">
                                            <span class="profile-ud-label">Email Address</span>
                                            <span class="profile-ud-value" id="email"></span>
                                        </div>
                                    </div>
                                    <div class="profile-ud-item">
                                        <div class="profile-ud wider">
                                            <span class="profile-ud-label">Mobile Number</span>
                                            <span class="profile-ud-value" id="mobile"></span>
                                        </div>
                                    </div>
                                    <div class="profile-ud-item">
                                        <div class="profile-ud wider">
                                            <span class="profile-ud-label">Lead Cost</span>
                                            <span class="profile-ud-value" id="cost"></span>
                                        </div>
                                    </div>
                                    <div class="profile-ud-item">
                                        <div class="profile-ud wider">
                                            <span class="profile-ud-label">Rust Date</span>
                                            <span class="profile-ud-value" id="r_date"></span>
                                        </div>
                                    </div>
                                    <div class="profile-ud-item" style="width:100%">
                                        <div class="profile-ud wider">
                                            <span class="profile-ud-label">Storage Unit:</span>
                                            <span class="profile-ud-value" id="s_unit"></span>
                                        </div>
                                    </div>
                                </div><!-- .profile-ud-list -->
                            </div><!-- .nk-block -->
                            <div class="nk-block">

                                <div class="nk-block-head nk-block-head-line">
                                    <h6 class="title overline-title text-base">General Information</h6>
                                </div><!-- .nk-block-head -->
                                <div class="profile-ud-list">
                                    <div class="profile-ud-item">
                                        <div class="profile-ud wider">
                                            <span class="profile-ud-label">Status</span>
                                            <span class="profile-ud-value text-success" id="lead_status"></span>
                                        </div>
                                    </div>
                                    <div class="profile-ud-item">
                                        <div class="profile-ud wider">
                                            <span class="profile-ud-label">Lead Source</span>
                                            <span class="profile-ud-value" id="lead_source"></span>
                                        </div>
                                    </div>
                                    <div class="profile-ud-item">
                                        <div class="profile-ud wider">
                                            <span class="profile-ud-label">Responsible User</span>
                                            <span class="profile-ud-value" id="res_user"></span>
                                        </div>
                                    </div>
                                    <div class="profile-ud-item" >
                                        <div class="profile-ud wider">
                                            <span class="profile-ud-label">Created At</span>
                                            <span class="profile-ud-value" id="created_at"></span>
                                        </div>
                                    </div>
                                    <div class="profile-ud-item" >
                                        <div class="profile-ud wider">
                                            <span class="profile-ud-label">Lead Rating</span>
                                            <span class="profile-ud-value" id="lead_rating"></span>
                                        </div>
                                    </div>
                                </div><!-- .profile-ud-list -->
                            </div><!-- .nk-block -->
                        </div>
                        <div class="tab-pane" id="tabItem2">
                            <h6 class="title">Estimate</h6>
                            <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Porro numquam distinctio ab cupiditate veniam a aperiam architecto perspiciatis quidem provident!</p>
                            <p><strong>Debitis ullam impedit</strong>, dolore architecto porro doloremque eum magni dolorum.</p>
                        </div>
                        <div class="tab-pane "  id="tabItem3">
                            <a href="#" class=" btn btn-primary btn-sm btn-task mb-3" data-toggle="modal" data-dismiss="modal" data-target="#addTask">Add Task</a>
                            <div class="card card-preview">
                                <div class="card-inner card border border-light">
                                    <table class=" table table-md datatable-init-export nk-tb-list nk-tb-ulist" data-auto-responsive="true"  id="datatable" >
                                        <thead>
                                        <tr class="nk-tb-item nk-tb-head">
                                            <th class="nk-tb-col text-left"><span class="sub-text">Sr no#</span></th>
                                            <th class="nk-tb-col"><span class="sub-text"> Name</span></th>
                                            <th class="nk-tb-col"><span class="sub-text">Status</span></th>
                                            <th class="nk-tb-col"><span class="sub-text">Start Date</span></th>
                                            <th class="nk-tb-col"><span class="sub-text">Due Date</span></th>
                                            <th class="nk-tb-col"><span class="sub-text">Assign To</span></th>
                                            <th class="nk-tb-col"><span class="sub-text">Priority</span></th>
                                        </tr>
                                        </thead>
                                        <tbody id="taskTable">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane " id="tabItem4">
                            <h6 class="title">Attachments</h6>
                            <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Porro numquam distinctio ab cupiditate veniam a aperiam architecto perspiciatis quidem provident!</p>
                            <p><strong>Debitis ullam impedit</strong>, dolore architecto porro doloremque eum magni dolorum.</p>
                        </div>
                        <div class="tab-pane " id="tabItem56">
                            <h6 class="title">Notes</h6>
                            <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Porro numquam distinctio ab cupiditate veniam a aperiam architecto perspiciatis quidem provident!</p>
                            <p><strong>Debitis ullam impedit</strong>, dolore architecto porro doloremque eum magni dolorum.</p>
                        </div>
                        <div class="tab-pane " id="tabItem6">
                            <h6 class="title">Activity Log</h6>
                            <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Porro numquam distinctio ab cupiditate veniam a aperiam architecto perspiciatis quidem provident!</p>
                            <p><strong>Debitis ullam impedit</strong>, dolore architecto porro doloremque eum magni dolorum.</p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" id="addTask" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-capitalize" id="ajax_model_title">Add New Task</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" data-form="ajax-form-modal">
                        <form method="post" action="{{ url('admin/save-task') }}" id="TaskForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Subject <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="subject" placeholder="Subject" required>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label> Start Date <span class="text-danger">*</span></label>
                                    <input class="form-control" type="date" name="start_date" placeholder="Start DAte" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Due Date<span class="text-danger"></span></label>
                                    <input class="form-control" type="date" name="due_date" placeholder="Due Date">
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Related To <span class="text-danger mt-1"></span></label>
                                    <select name="related_to" id="" class="form-control" readonly="">
                                        <option value="">Choose One</option>
                                        <option value="lead" selected>Lead</option>
                                        <option value="customer">Customer</option>
                                        <option value="estimate">Estimate</option>
                                        <option value="project">Project</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="lbl" >Lead</label>
                                    <select class="selectpicker form-control  form-select" name="related_id" >
                                        <option value="" selected >Select User Responsible</option>
                                        @isset($data)
                                            @foreach($data['lead'] as $lead)
                                                <option value="{{$lead->id}}">{{$lead->f_name}}</option>
                                            @endforeach
                                        @endisset
                                    </select>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="lbl" >Lead</label>
                                    <select class="selectpicker form-control  form-select" name="assignee" >
                                        <option value="" selected >Select User Responsible</option>
                                        @isset($data)
                                            @foreach ($data['user'] as $user)
                                                <option value="{{ $user->id }}" {{ (auth()->user()->id == $user->id)? "selected" : "" }} >{{$user->first_name }} {{$user->last_name }}</option>
                                            @endforeach
                                        @endisset
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Followers <span class="text-danger"></span></label>
                                    <input class="form-control" type="text" name="follower" placeholder="Followers" >
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label> Task Description <span class="text-danger"></span></label>
                                    <textarea class="form-control"  name="description" placeholder="Description"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Priority <span class="text-danger mt-1">*</span></label>
                                    <select name="priority" id="" class="form-control" required>
                                        <option value="">Choose One</option>
                                        <option value="low">Low</option>
                                        <option value="medium">Medium</option>
                                        <option value="high">High</option>
                                        <option value="urgent">Urgent</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Status <span class="text-danger mt-1">*</span></label>
                                    <select name="status" id="" class="form-control" required>
                                        <option value="">Choose One</option>
                                        <option value="1">Active</option>
                                        <option value="0">In-Active</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="float-right">
                            <a href="#" class=" btn btn-primary mt-2 btn-back" data-toggle="modal" data-dismiss="modal" data-target="#editCountry">Back to Lead</a>
                            <button class="btn btn-primary mt-2 btn-submit" type="submit">Save</button>
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
                  var formData=$('#CountryForm').serialize()
                $.ajax({
                    type: "get",
                    url: '{{ url('admin/convert-customer') }}',
                    data: formData,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('.btn-submit').text('Saving...');
                        $(".btn-submit").prop("disabled", true);
                    },
                    success: function(data) {
                            console.log(data.customer_id);
                        if (data.success) {
                            getCountries();
                            $('#CountryForm')[0].reset();
                            $('.close').click();
                            toastr.success(data.success);
                            window.location.href = "{{ url('admin/customer/leads')}}/"+data.customer_id;
                        }
                        if (data.errors) {
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


            getCountries();
            function getCountries() {

                $.ajax({

                    url: '{{ url('admin/get-leads') }}',
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
                            ' <td class="nk-tb-col nk-tb-col-tools"><a href={{url('admin/lead/profile')}}/' + data[i].id + '>'+data[i].storageunit.storage_unit_name+'/'+data[i].term_length.title+'</a></td>'+
                            {{--' <td class="nk-tb-col nk-tb-col-tools"><a href={{url('admin/lead/profile')}}/' + data[i].id + '>'+data[i].f_name+' '+data[i].l_name+'</a></td>'+--}}
                            // ' <td class="nk-tb-col nk-tb-col-tools"><a href="#" class="btn-edit" data='+data[i].id+' data-toggle="modal" data-target="#editCountry">'+data[i].f_name+' '+data[i].l_name+'</a></td>'+
                            ' <td class="nk-tb-col nk-tb-col-tools">'+ ((data[i].company_name == null) ? data[i].f_name+' '+data[i].l_name : data[i].company_name)+'</td>'+
                            ' <td class="nk-tb-col nk-tb-col-tools">'+data[i].phone+'</td>'+
                            ' <td class="nk-tb-col nk-tb-col-tools">'+data[i].email+'</td>'+
                            '<td class="nk-tb-col nk-tb-col-tools" >'+
                            ' <span class="badge badge-success">'+data[i].lead_status.title+'</span>'+
                            ' </td>'+
                                ' <td class="nk-tb-col nk-tb-col-tools">'+data[i].created_at+'</td>'+
                                ' <td class="nk-tb-col nk-tb-col-tools">'+data[i].userresponsible.first_name+' '+data[i].userresponsible.last_name+'</td>'+
                            '  <td class="nk-tb-col nk-tb-col-tools">'+
                            ' <ul class="nk-tb-actions gx-1">'+
                            '  <li>'+
                            ' <div class="drodown">'+
                            '  <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>'+
                            ' <div class="dropdown-menu dropdown-menu-right">'+
                            '<ul class="link-list-opt no-bdr">'+
                            '<li><a href="#" class="btn-edit" data='+data[i].id+' data-toggle="modal" data-target="#editCountry"><em class="icon ni ni-edit"></em><span>Convert to customer</span></a></li>'+
                            '<li><a href="#" class="btn-estimate" data='+data[i].id+'><em class="icon ni ni-edit"></em><span>Lead Estimate</span></a></li>'+
                            {{--'<li><a href={{url('admin/estimate')}}/'+data[i].id +' ><em class="icon ni ni-edit"></em><span>Lead Estimate</span></a></li>'+--}}
                                '<li><a href={{url('admin/edit-lead')}}/'+data[i].id+' ><em class="icon ni ni-edit"></em><span>Edit</span></a></li>'+
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

            $('.btn-estimate').on('click', function() {
                var id = $(this).attr('data');
                // alert(id);
                $.ajax({
                    url: '{{ url('admin/is-customer') }}',
                    type: 'get',
                    async: false,
                    dataType: 'json',
                    data: { id: id},
                    success: function(data) {
                        console.log(data);

                        if (data.customer[0].customer_id != null) {
                            window.location.href = "{{ url('admin/estimate')}}/"+id;
                        }else{
                            toastr.error('Convert to Customer Before Estimate');
                        }

                    },
                    error: function() {
                        toastr.error('something went wrong');
                    }

                });
            });


            $('#countryTable').on('click', '.btn-delete', function() {
                var id = $(this).attr('data');
                $.ajax({
                    url: '{{ url('admin/delete-lead') }}',
                    type: 'get',
                    async: false,
                    dataType: 'json',
                    data: { id: id},
                    success: function(data) {
                        if (data.success) {
                            getCountries();
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
                    url: '{{ url('admin/show-lead') }}',
                    type: 'get',
                    async: false,
                    dataType: 'json',
                    data: { id: id },
                    success: function(data) {
                        var html = '<a href="#" id="btn-make" data="'+data.lead[0].id+'"  data-toggle="modal" data-target="#makeCustomer" class="btn btn-sm btn-success btn-make" data-dismiss="modal">Convert to Customer</a>';
                        $('#convert').html(html);

                        $('#c_title').text('#'+data.lead[0].id+'-'+data.lead[0].f_name);
                        $('#lead_id_backup').val(data.lead[0].id);
                        $('#company_name').text(((data.lead[0].company_name == null) ? '-' : data.lead[0].company_name));
                        $('#full_name').text(data.lead[0].f_name+' '+data.lead[0].l_name);
                        $('#email').text(data.lead[0].email);
                        $('#mobile').text(data.lead[0].mobile1);
                        $('#cost').text(data.lead[0].price);
                        $('#r_date').text(data.lead[0].r_date);
                        $('#s_unit').text(data.su[0].storage_unit_name+' -> '+data.su[0].warehouse.name+' -> '+data.su[0].warehouse.loc.loc_name+' -> '+data.su[0].warehouse.loc.city.city_name+' -> '+data.su[0].warehouse.loc.city.country.name);
                        $('#lead_status').text(data.lead[0].lead_status.title);
                        $('#lead_source').text(data.lead[0].lead_source.title);
                        $('#lead_rating').text(data.lead[0].lead_rating);
                        $('#created_at').text(data.lead[0].created_at);
                        $('#res_user').text(data.lead[0].userresponsible.first_name+' '+data.lead[0].userresponsible.last_name);

                    },
                    error: function() {
                        toastr.error('any technical error');
                    }
                });
            });

            $('#convert').on('click', '.btn-make', function() {
                var id = $(this).attr('data');
                $('#company').hide();
                $('#individual').hide();
                $.ajax({
                    url: '{{ url('admin/show-lead') }}',
                    type: 'get',
                    async: false,
                    dataType: 'json',
                    data: { id: id },
                    success: function(data) {
                        console.log(data);
                        if(data.lead[0].lead_type == 'company')
                        {
                            $('#company').show();
                            $('#individual').hide();
                        }else {
                            $('#individual').show();
                            $('#company').hide()
                        }
                        $('input[name=id]').val(id);
                        $('input[name=lead_id]').val(data.lead[0].id);
                        $('input[name=lead_type]').val(data.lead[0].lead_type);
                        $('input[name=first_name]').val(data.lead[0].f_name);
                        $('input[name=last_name]').val(data.lead[0].l_name);
                        $('input[name=company_name]').val(((data.lead[0].company_name == null) ? ' ' : data.lead[0].company_name));
                        $('input[name=email]').val(data.lead[0].email);
                        $('input[name=phone]').val(data.lead[0].phone);
                        $('#c_title').text('#'+data.lead[0].id+'-'+data.lead[0].f_name);
                    },
                    error: function() {
                        toastr.error('any technical error');
                    }
                });
            });





        });
    </script>
@endsection



