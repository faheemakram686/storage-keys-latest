@extends('backend.layouts.app')
@section('title', '| Estimate')
@section('content')
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-block-head">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h4 class="title nk-block-title">Estimate Information</h4>
                    </div>
                    <a href="{{url("admin/estimate")}}" class="btn btn-primary btn-sm d-none d-md-inline-flex"><em class="icon ni ni-arrow-left"></em><span>Back</span></a>
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
                                                <h4 class="nk-block-title">Tasks</h4>
                                                <div class="nk-block-des">

                                                </div>
                                            </div>
                                            <div class="d-flex align-center">
                                                <div class="nk-tab-actions me-n1">
                                                    <a href="#" class="btn btn-primary btn-sm"  data-toggle="modal" data-target="#addTask"><em class="icon ni ni-plus"></em><span>Add Task</span></a>
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
                                                    <th class="nk-tb-col"><span class="sub-text"> Name</span></th>
                                                    <th class="nk-tb-col"><span class="sub-text">Status</span></th>
                                                    <th class="nk-tb-col"><span class="sub-text">Start Date</span></th>
                                                    <th class="nk-tb-col"><span class="sub-text">Due Date</span></th>
                                                    <th class="nk-tb-col"><span class="sub-text">Assign To</span></th>
                                                    <th class="nk-tb-col"><span class="sub-text">Priority</span></th>
                                                    <th class="nk-tb-col"><span class="sub-text">Action</span></th>
                                                </tr>
                                                </thead>
                                                <tbody id="countryTable">
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                @include('backend.estimate.aside')
                                <!-- card-aside -->
                            </div>
                            <!-- .card-aside-wrap -->
                        </div>
                    @endisset
                    <!-- .card -->
                </div>
                <!-- .nk-block -->

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
                                                <option value="lead" >Lead</option>
                                                <option value="customer">Customer</option>
                                                <option value="estimate" selected>Estimate</option>
                                                <option value="project">Project</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="lbl" >Estimate</label>
                                            <select class="selectpicker form-control  form-select" name="related_id" >
                                                <option value="" selected >Select Estimate</option>
                                                @isset($data)
                                                    @foreach($data['estimate'] as $estimate)
                                                        <option value="{{$estimate->id}}" selected>{{$estimate->id}} - {{$estimate->f_name}} {{$estimate->l_name}} </option>
                                                    @endforeach
                                                @endisset
                                            </select>
                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="lbl" >Assignee</label>
                                            <select class="selectpicker form-control  form-select" name="assignee" >
                                                <option value="" selected >Select Assignee</option>
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
                                    <a href="#" class=" btn btn-primary mt-2 btn-back" data-toggle="modal" data-dismiss="modal" >Cancel</a>
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
                            <h5 class="modal-title text-capitalize" id="ajax_model_title">Edit Task</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" data-form="ajax-form-modal">
                            <form method="post" action="{{ url('admin/update-task') }}" id="updateCountryForm">
                                @csrf
                                <input type="hidden" name="id">
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
                                            <label class="lbl" >Assignee</label>
                                            <select class="selectpicker form-control  form-select" name="assignee" >
                                                <option value="" selected >Select Assignee</option>

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

                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Status <span class="text-danger mt-1">*</span></label>
                                            <select name="status" id="" class="form-control" required>
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
        </div>
    </div>

    <script>
        $(document).ready(function() {

            $('#TaskForm').on('submit', function(e) {

                e.preventDefault();
                var formData=$('#TaskForm').serialize()
                $.ajax({
                    type: "get",
                    url: '{{ url('admin/save-task') }}',
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
                            $('#TaskForm')[0].reset();
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

                    error: function() {;
                        toastr.error('any technical error');
                        $('.btn-submit').text('Save');
                        $(".btn-submit").prop("disabled", false);
                    }
                });


            });

            getAllCities();
            function getAllCities() {
                var related_id=$('#estimate_id').val();
                var related_to='estimate';
                $.ajax({

                    url: '{{ url('admin/get-realted-tasks') }}',
                    type: 'get',
                    async: false,
                    dataType: 'json',
                    data: { related_id:related_id,related_to:related_to},

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
                                ' <td class="nk-tb-col nk-tb-col-tools"><a href="#" class="btn-edit" data='+data[i].id+' data-toggle="modal" data-target="#editCountry">' + data[i].subject + '</a></td>' +
                                '<td class="nk-tb-col nk-tb-col-tools" >'+
                                ' <span class="badge badge-success">'+data[i].status+'</span>'+
                                '</td>'+
                                // ' <td class="nk-tb-col nk-tb-col-tools"><a href="#">' + data[i].first_name + ' ' + data[i].last_name + '</a></td>' +
                                ' <td class="nk-tb-col nk-tb-col-tools">' + data[i].start_date + '</td>'+
                                ' <td class="nk-tb-col nk-tb-col-tools">' + data[i].due_date + '</td>'+
                                ' <td class="nk-tb-col nk-tb-col-tools">' + data[i].assignee.first_name + ' ' + data[i].assignee.last_name + '</td>'+
                                ' <td class="nk-tb-col nk-tb-col-tools">' + data[i].priority + '</td>'+
                                '  <td class="nk-tb-col nk-tb-col-tools">'+
                                ' <ul class="nk-tb-actions" gx-1 style="right: 1.0rem; !important">'+
                                '  <li>'+
                                ' <div class="drodown">'+
                                '  <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>'+
                                ' <div class="dropdown-menu dropdown-menu-right">'+
                                '<ul class="link-list-opt no-bdr">'+
                                    {{--'<li><a href={{url('admin/edit-customer')}}/'+data[i].id+'><em class="icon ni ni-edit"></em><span>Edit</span></a></li>'+--}}
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
                    url: '{{ url('admin/delete-task') }}',
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
                    url: '{{ url('admin/edit-task') }}',
                    type: 'get',
                    async: false,
                    dataType: 'json',
                    data: { id: id },
                    success: function(res) {
                        console.log(res);

                        $('input[name=id]').val(id);
                        $('textarea[name=description]').val(res.task.description);
                        $('input[name=start_date]').val(res.task.start_date);
                        $('input[name=due_date]').val(res.task.due_date);
                        $('input[name=subject]').val(res.task.subject);
                        $('input[name=follower]').val(res.task.follower);

                        $('select[name="priority"]')
                            .html(
                                `<option value="low" ${res.task.priority == 'low' ? 'selected' : ''}>Low</option>`+
                                `<option value="medium" ${res.task.priority == 'medium' ? 'selected' : ''}>Medium</option>`+
                                `<option value="high" ${res.task.priority == 'high' ? 'selected' : ''}>High</option>`+
                                `<option value="urgent" ${res.task.priority == 'urgent' ? 'selected' : ''}>Urgent</option>`

                            )
                        $('select[name="status"]')
                            .html(
                                `<option value="1" ${res.task.status == 'Active' ? 'selected' : ''}>Active</option>`+
                                `<option value="0" ${res.task.status== 'In-Active' ? 'selected' : ''}>In-Active</option>`
                            )
                        $('select[name="assignee"]')
                            .empty()

                        //edit dropdown in ajax
                        $.each(res.users, function(key, user) {

                            $('select[name="assignee"]')
                                .append(
                                    `<option value="${user.id}" ${user.id == res.task.assignee ? 'selected' : ''}>${user.first_name} ${user.last_name}</option>`
                                )
                        });
                    },
                    error: function() {
                        toastr.error('any technical error');
                    }
                });
            });

            $('#updateCountryForm').on('submit', function(e) {
                e.preventDefault();
                var formData=$('#updateCountryForm').serialize()
                $.ajax({
                    type: "get",
                    url: '{{ url('admin/update-task') }}',
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



