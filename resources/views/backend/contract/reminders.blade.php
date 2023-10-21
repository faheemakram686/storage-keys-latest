@extends('backend.layouts.app')
@section('title', '| Contract')
@section('content')
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-block-head">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h4 class="title nk-block-title">Contract</h4>
                    </div>
                    <a href="{{url("admin/contract")}}" class="btn btn-primary btn-sm d-none d-md-inline-flex"><em class="icon ni ni-arrow-left"></em><span>Back</span></a>
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
                                                <h4 class="nk-block-title">Reminders</h4>
                                                <div class="nk-block-des">

                                                </div>
                                            </div>
                                            <div class="d-flex align-center">
                                                <div class="nk-tab-actions me-n1">
                                                    <a href="#" class="btn btn-primary btn-sm"  data-toggle="modal" data-target="#addCountry"><em class="icon ni ni-plus"></em><span>Set Reminder</span></a>
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
                                                    <th class="nk-tb-col"><span class="sub-text">Description</span></th>
                                                    <th class="nk-tb-col"><span class="sub-text">Date</span></th>
                                                    <th class="nk-tb-col"><span class="sub-text">Remind</span></th>
                                                    <th class="nk-tb-col"><span class="sub-text">Status</span></th>
                                                    <th class="nk-tb-col"><span class="sub-text">Action</span></th>
                                                </tr>
                                                </thead>
                                                <tbody id="countryTable">
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                @include('backend.contract.aside')
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
                            <h5 class="modal-title text-capitalize" id="ajax_model_title">Set Reminder</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" data-form="ajax-form-modal">
                            <form method="post" action="{{ url('admin/save-reminder') }}" id="CountryForm">
                                @csrf
                                <input type="hidden" name="type_id" value="{{$data['contract'][0]->id}}">
                                <input type="hidden" name="type" value="contract">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Date to be notified<span class="text-danger">*</span></label>
                                            <input class="form-control datepicker" type="datetime-local" name="reminder_date" placeholder="Date to be notified" required>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Set reminder to<span class="text-danger">*</span></label>
                                            <select class="form-control"  name="reminder_to" required>
                                                <option value="">Choose One</option>
                                                @isset($data)
                                                    @foreach ($data['users'] as $user)
                                                        <option value="{{ $user->id }} " {{$user->id == auth()->user()->id ? 'selected' : ''}}>{{ $user->first_name }} {{ $user->last_name }}</option>
                                                    @endforeach
                                                @endisset
                                            </select>

                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Description<span class="text-danger"></span></label>
                                            <textarea class="form-control"  name="description" placeholder="Description"></textarea>
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
                            <h5 class="modal-title text-capitalize" id="ajax_model_title">Edit Reminder</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" data-form="ajax-form-modal">
                            <form method="post" action="{{ url('admin/update-reminder') }}" id="updateCountryForm">
                                @csrf
                                <input type="hidden" name="id">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Date to be notified<span class="text-danger">*</span></label>
                                            <input class="form-control datepicker" type="datetime-local" name="reminder_date" placeholder="Date to be notified" required>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Set reminder to<span class="text-danger">*</span></label>
                                            <select class="form-control"  name="reminder_to" required readonly="">
                                                <option value="">Choose One</option>

                                            </select>

                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Description<span class="text-danger"></span></label>
                                            <textarea class="form-control"  name="description" placeholder="Description"></textarea>
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
        </div>
    </div>

    <script>
        $(document).ready(function() {

            $('#CountryForm').on('submit', function(e) {

                e.preventDefault();
                var formData=$('#CountryForm').serialize()
                $.ajax({
                    type: "get",
                    url: '{{ url('admin/save-reminder') }}',
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

                    error: function() {;
                        toastr.error('any technical error');
                        $('.btn-submit').text('Save');
                        $(".btn-submit").prop("disabled", false);
                    }
                });


            });


            getAllCities();
            function getAllCities() {
                var type_id=$('#contract_id').val();
                var type='contract';

                $.ajax({

                    url: '{{ url('admin/get-realted-reminders') }}',
                    type: 'get',
                    async: false,
                    dataType: 'json',
                    data: { type_id:type_id,type:type},

                    success: function(data) {
                        console.log(data);

                        var html = '';
                        var i;
                        var c = 0;

                        for (i = 0; i < data.length; i++) {
                            c++;
                            html += ' <tr class="nk-tb-item odd">'+
                                // ' <td class="nk-tb-col nk-tb-col-tools sorting_1">'+c+'</td>'+
                                {{--' <td class="nk-tb-col nk-tb-col-tools"><a href={{url('admin/contact')}}/' + data[i].id + ' class="btn-edit" data='+data[i].id+'>' + data[i].first_name + ' ' + data[i].last_name + '</a></td>' +--}}
                                // ' <td class="nk-tb-col nk-tb-col-tools"><a href="#">' + data[i].first_name + ' ' + data[i].last_name + '</a></td>' +
                                ' <td class="nk-tb-col nk-tb-col-tools">' + data[i].description + '</td>'+
                                ' <td class="nk-tb-col nk-tb-col-tools">' + data[i].reminder_date + '</td>'+
                                ' <td class="nk-tb-col nk-tb-col-tools"><a href="#" class="btn-edit" data='+data[i].id+' data-toggle="modal" data-target="#editCountry">' + data[i].remind.first_name + ' ' + data[i].remind.last_name + '</a></td>' +
                                '<td class="nk-tb-col nk-tb-col-tools" >'+
                                ' <span class="badge badge-success">'+data[i].status+'</span>'+
                                '</td>'+
                                '  <td class="nk-tb-col nk-tb-col-tools">'+
                                ' <ul class="nk-tb-actions" gx-1 style="right: 2.5rem; !important">'+
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
                    url: '{{ url('admin/delete-reminder') }}',
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
                    url: '{{ url('admin/edit-reminder') }}',
                    type: 'get',
                    async: false,
                    dataType: 'json',
                    data: { id: id },
                    success: function(res) {
                        console.log(res);

                        $('input[name=id]').val(id);
                        $('textarea[name=description]').val(res.reminder.description);
                        $('input[name=reminder_date]').val(res.reminder.reminder_date);


                        $('select[name="edit_status"]')
                            .html(
                                `<option value="1" ${res.reminder.status == 'Active' ? 'selected' : ''}>Active</option>`+
                                `<option value="0" ${res.reminder.status== 'In-Active' ? 'selected' : ''}>In-Active</option>`
                            )
                        $('select[name="reminder_to"]')
                            .empty()

                        //edit dropdown in ajax
                        $.each(res.users, function(key, user) {

                            $('select[name="reminder_to"]')
                                .append(
                                    `<option value="${user.id}" ${user.id == res.reminder.reminder_to ? 'selected' : ''}>${user.first_name} ${user.last_name}</option>`
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
                    url: '{{ url('admin/update-reminder') }}',
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



