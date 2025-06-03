@extends('backend.layouts.app')
@section('title', '| Roles')
@section('content')
    <div class="components-preview wide-md mx-auto">
        <div class="nk-block nk-block-lg">
            <div class="nk-block-head">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h3 class="nk-block-title page-title">Roles List</h3>
                        <div class="nk-block-des text-soft">

                        </div>
                    </div>

                    <div class="nk-block-head-content">
                        <div class="toggle-wrap nk-block-tools-toggle">
                            <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-more-v"></em></a>
                            <div class="toggle-expand-content" data-content="pageMenu">
                                <ul class="nk-block-tools g-3">
                                    <li class="nk-block-tools-opt">

                                        <a href="{{route("roles.create")}}" class="btn btn-primary btn-sm" ><em class="icon ni ni-plus"></em><span>Add New Role</span></a>

                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="card card-preview">
                @if (session()->has('success'))
                    <div class="alert alert-success">
                        @if(is_array(session('success')))
                            <ul>
                                @foreach (session('success') as $message)
                                    <li>{{ $message }}</li>
                                @endforeach
                            </ul>
                        @else
                            {{ session('success') }}
                        @endif
                    </div>
                @endif
                <div class="card-inner">
                    <table class="datatable-init-export nk-tb-list nk-tb-ulist" data-auto-responsive="true"  id="datatable" >
                        <thead>
                        <tr class="nk-tb-item nk-tb-head">
                            <th class="nk-tb-col text-left"><span class="sub-text">ID</span></th>
                            <th class="nk-tb-col"><span class="sub-text">Name</span></th>
                            <th class="nk-tb-col"><span class="sub-text">Assigned Users</span></th>
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
    <div class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" id="changeassignee" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-capitalize" id="ajax_model_title">Select User To Assign Role</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" data-form="ajax-form-modal">
                    <form method="post" action="{{ url('admin/assign-role') }}" id="changeAssigneeForm">
                        @csrf
                        <div class="row">
                            <input class="form-control" type="hidden" name="id" value="" required>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="lbl" >User</label>
                                    <select class="form-control select2" name="user_id" id="user_id" data-live-search="true" required>
                                        <option selected value="">Choose One</option>
                                        @isset($data)
                                            @foreach ($data['user'] as $sl)
                                                <option value="{{ $sl->id }}" >{{ $sl->first_name }} {{ $sl->last_name }}</option>
                                            @endforeach
                                        @endisset
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

    <div class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" id="deattachuser" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-capitalize" id="ajax_model_title">Select User To Deattach Role</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" data-form="ajax-form-modal">
                    <form method="post" action="{{ url('admin/deattach-role') }}" id="deattachUserForm">
                        @csrf
                        <div class="row">
                            <input class="form-control" type="hidden" name="role_id" value="" required>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="lbl" >User</label>
                                    <select class="form-control select2 UserSection" name="user_id_assigned" id="user_id" data-live-search="true" required>
                                        <option value="">Choose One</option>

                                    </select>
                                </div>

                            </div>
                        </div>

                        <div class="float-right">
                            <button class="btn btn-primary mt-2 btn-update" type="submit">Save</button>
                        </div>
                    </form>

                </div>
            </div><!-- .modal-content -->
        </div><!-- .modla-dialog -->
    </div>
    <script>
        $(document).ready(function() {

            $('#changeAssigneeForm').on('submit', function(e) {

                e.preventDefault();
                var formData=$('#changeAssigneeForm').serialize()
                $.ajax({
                    type: "get",
                    url: '{{ url('admin/assign-role') }}',
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
                            $('#changeAssigneeForm')[0].reset();
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

                $.ajax({

                    url: '{{ url('admin/get-role') }}',
                    type: 'get',
                    async: false,
                    dataType: 'json',

                    success: function(data) {
                        console.log(data);

                        var html = '';
                        var i;
                        var c = 0;
                        var name = '';
                        var shortname = '';
                        for (i = 0; i < data.length; i++) {
                            // console.log(data[i].users);
                            c++;
                            html += ' <tr class="nk-tb-item odd">'+
                                ' <td class="nk-tb-col nk-tb-col-tools sorting_1">'+c+'</td>'+
                                ' <td class="nk-tb-col nk-tb-col-tools">'+data[i].name+'</td>'+
                                '<td class="nk-tb-col tb-col-lg">'+
                                    '<ul class="project-users g-1">';
                            $.each(data[i].users, function(index, value) {
                                 name = value.first_name +' '+ value.last_name;
                                 shortname = name.charAt(0) + name.charAt(1);
                                html +=   '<li>'+
                                    '<div class="user-avatar sm bg-primary"><span title="'+ name +'">'+ shortname.toUpperCase() +'</span></div>'+
                                    '</li>';
                            });


                            html +=
                                '</ul>'+
                                '</td>'+
                                '  <td class="nk-tb-col nk-tb-col-tools">'+
                                ' <ul class="nk-tb-actions gx-1">'+
                                '  <li>'+
                                ' <div class="drodown">'+
                                '  <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>'+
                                ' <div class="dropdown-menu dropdown-menu-right">'+
                                '<ul class="link-list-opt no-bdr">'+
                                '<li><a data-toggle="modal" href="#changeassignee" class="btn-assign" data='+data[i].id+'><em class="icon ni ni-edit"></em><span>Assign User</span></a></li>';
                                if(data[i].users.length != 0){
                                    html += '<li><a data-toggle="modal" href="#deattachuser"  class="btn-edit" data='+data[i].id+'><em class="icon ni ni-edit"></em><span>Deattach User</span></a></li>';
                                }
                            html +='<li><a href={{url('admin/edit-role')}}/'+data[i].id+' class="btn-edit" data='+data[i].id+'><em class="icon ni ni-edit"></em><span>Manage Role</span></a></li>'+
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
                    url: '{{ url('admin/delete-role') }}',
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

            $('#countryTable').on('click', '.btn-assign', function() {
                var id = $(this).attr('data');
                $('input[name=id]').val(id);
            });

            $('#countryTable').on('click', '.btn-edit', function() {
                var id = $(this).attr('data');

                $.ajax({
                    url: '{{ url('admin/get-assign-user') }}',
                    type: 'get',
                    async: false,
                    dataType: 'json',
                    data: { id: id },
                    success: function(res) {
                        // console.log(res.users);

                        $('input[name=role_id]').val(id);

                        $('select[name="user_id_assigned"]')
                            .empty()
                        $.each(res.users, function(key, user) {

                            $('select[name="user_id_assigned"]')
                                .append(
                                    `<option value="${user.id}" >${user.first_name} ${user.last_name}</option>`
                                )
                        });



                    },
                    error: function() {
                        toastr.error('any technical error');
                    }
                });
            });

            $('#deattachUserForm').on('submit', function(e) {
                e.preventDefault();
                var formData=$('#deattachUserForm').serialize()
                $.ajax({
                    type: "get",
                    url: '{{ url('admin/deattach-role') }}',
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
                            $('#deattachUserForm')[0].reset();
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



