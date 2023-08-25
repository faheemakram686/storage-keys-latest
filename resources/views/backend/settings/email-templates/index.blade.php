@extends('backend.layouts.app')
@section('title', '| Email Template')
@section('content')
    <div class="components-preview wide-md mx-auto">
        <div class="nk-block nk-block-lg">
            <div class="nk-block-head">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h3 class="nk-block-title page-title">Email Template List</h3>
                        <div class="nk-block-des text-soft">

                        </div>
                    </div>
                    <div class="nk-block-head-content">
                        <div class="toggle-wrap nk-block-tools-toggle">
                            <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-more-v"></em></a>
                            <div class="toggle-expand-content" data-content="pageMenu">
                                <ul class="nk-block-tools g-3">
                                    <li class="nk-block-tools-opt">
                                        <a href="#" class="btn btn-primary btn-sm"  data-toggle="modal" data-target="#addCountry"><em class="icon ni ni-plus"></em><span>Add Email Template</span></a>
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
                            <th class="nk-tb-col"><span class="sub-text">Template Name</span></th>
                            <th class="nk-tb-col"><span class="sub-text">Template For</span></th>
                            <th class="nk-tb-col"><span class="sub-text">Template Category</span></th>
                            <th class="nk-tb-col"><span class="sub-text">Template Subject</span></th>
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

    <div class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" id="addCountry" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-capitalize" id="ajax_model_title">Add New Email Template</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" data-form="ajax-form-modal">
                        <form method="post" action="{{ url('admin/save-email-template') }}" id="CountryForm">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label> Template Name <span class="text-danger"></span></label>
                                        <input class="form-control" type="text" name="temp_name" placeholder="Template Name" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label> Template For <span class="text-danger"></span></label>
                                        <select name="temp_for" id="" class="form-control" required>
                                            <option value="">Choose One</option>
                                            <option value="contract">Contract</option>
                                            <option value="lead">Lead</option>
                                            <option value="estimate">Estimate</option>
                                            <option value="project">Project</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label> Template Category <span class="text-danger"></span></label>
                                        <select name="temp_category" id="" class="form-control" required>
                                            <option value="">Choose One</option>
                                            <option value="email">Email</option>
                                            <option value="database">System Notification</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">

                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label> Template Subject <span class="text-danger"></span></label>
                                        <input class="form-control" type="text" name="temp_subject" placeholder="Template Subject" required>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="form-label" for="reviewer">Template Body</label>
                                        <div class="form-control-wrap">
                                            <textarea id="editor" class="form-control" name="temp_body"></textarea>
                                        </div>
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
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-capitalize" id="ajax_model_title">Edit Email Template</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" data-form="ajax-form-modal">
                    <form method="post" action="{{ url('admin/update-email-template') }}" id="updateCountryForm1">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label> Template Name <span class="text-danger"></span></label>
                                    <input type="hidden" name="id">
                                    <input class="form-control" type="text" name="et_temp_name" placeholder="Template Name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label> Template For <span class="text-danger"></span></label>
                                    <select name="et_temp_for" id="" class="form-control" required>
                                        <option value="">Choose One</option>

                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label> Template Category <span class="text-danger"></span></label>
                                    <select name="et_temp_category" id="" class="form-control" required>
                                        <option value="">Choose One</option>
                                        <option value="general">General</option>
                                        <option value="hiring">Hiring</option>
                                        <option value="sales">Sales</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">

                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label> Template Subject <span class="text-danger"></span></label>
                                    <input class="form-control" type="text" name="et_temp_subject" placeholder="Template Subject" required>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-label" for="reviewer">Template Body</label>
                                    <div class="form-control-wrap">
                                        <textarea id="editor2" name="et_temp_body"></textarea>
                                    </div>
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

    <script>
        ClassicEditor
            .create( document.querySelector( '#editor' ) )
            .catch( error => {
                console.error( error );
            } );
    </script>

    <script>
        $(document).ready(function() {

            $('#CountryForm').on('submit', function(e) {

                e.preventDefault();
                  var formData=$('#CountryForm').serialize()
                $.ajax({
                    type: "get",
                    url: '{{ url('admin/save-email-template') }}',
                    data: formData,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('.btn-submit').text('Saving...');
                        $(".btn-submit").prop("disabled", true);
                    },
                    success: function(data) {

                        if (data.success) {
                            getCountries();
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

            getCountries();
            function getCountries() {

                $.ajax({

                    url: '{{ url('admin/get-email-template') }}',
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
                            ' <td class="nk-tb-col nk-tb-col-tools">'+data[i].temp_name+'</td>'+
                            ' <td class="nk-tb-col nk-tb-col-tools">'+data[i].temp_for+'</td>'+
                            ' <td class="nk-tb-col nk-tb-col-tools">'+data[i].temp_cat+'</td>'+
                            ' <td class="nk-tb-col nk-tb-col-tools">'+data[i].temp_subject+'</td>'+
                            '<td class="nk-tb-col nk-tb-col-tools" >'+
                            ' <span class="badge badge-success">'+data[i].status+'</span>'+
                            ' </td>'+
                            '  <td class="nk-tb-col nk-tb-col-tools">'+
                            ' <ul class="nk-tb-actions gx-1">'+
                            '  <li>'+
                            ' <div class="drodown">'+
                            '  <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>'+
                            ' <div class="dropdown-menu dropdown-menu-right">'+
                            '<ul class="link-list-opt no-bdr">'+
                            // '<li><a href="#" class="btn-edit" data='+data[i].id+' data-toggle="modal" data-target="#editCountry"><em class="icon ni ni-edit"></em><span>Edit</span></a></li>'+
                                '<li><a href={{url('admin/edit-email-template')}}/'+data[i].id+'><em class="icon ni ni-edit"></em><span>Edit</span></a></li>'+
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
                        url: '{{ url('admin/delete-email-template') }}',
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


            $('#countryTable111').on('click', '.btn-edit', function() {
                var id = $(this).attr('data');
                var text = '';

                $.ajax({
                    url: '{{ url('admin/edit-email-template') }}',
                    type: 'get',
                    async: false,
                    dataType: 'json',
                    data: { id: id },
                    success: function(res) {

                        console.log(res);

                        $('input[name=id]').val(id);
                        $('input[name=et_temp_name]').val(res.temp_name);
                        $('input[name=et_temp_subject]').val(res.temp_subject);

                        ClassicEditor
                            .create( document.querySelector( '#editor2' ) )
                            .then( editor2 => {
                                editor2.setData( res.temp_body );
                                text = editor2.getData();
                                $('textarea[name=et_temp_body]').val(text);
                            } )
                            .catch( error => {
                                console.error( error );
                            } );


                        console.log(text);

                        $('select[name="et_temp_for"]')
                            .html(
                                `<option value="contract" ${res.temp_for == 'contract' ? 'selected' : ''}>Contact</option>`+
                                `<option value="lead" ${res.temp_for == 'lead' ? 'selected' : ''}>Lead</option>`+
                                `<option value="project" ${res.temp_for == 'project' ? 'selected' : ''}>Project</option>`+
                                `<option value="estimate" ${res.temp_for == 'estimate' ? 'selected' : ''}>Estimate</option>`+
                                `<option value="task" ${res.temp_for== 'task' ? 'selected' : ''}>Task</option>`
                            )

                        $('select[name="et_temp_category"]')
                            .html(
                                `<option value="general" ${res.temp_cat == 'general' ? 'selected' : ''}>General</option>`+
                                `<option value="hiring" ${res.temp_cat == 'hiring' ? 'selected' : ''}>Hiring</option>`+
                                `<option value="sales" ${res.temp_cat == 'sales' ? 'selected' : ''}>Sales</option>`
                            )

                            $('select[name="edit_status"]')
                                .html(
                                    `<option value="1" ${res.status == 'Active' ? 'selected' : ''}>Active</option>`+
                                    `<option value="0" ${res.status== 'In-Active' ? 'selected' : ''}>In-Active</option>`
                                )
                    },
                    error: function() {
                        toastr.error('any technical error');
                    }
                });
            });


            $('#updateCountryForm').on('submit', function(e) {
                e.preventDefault();
                var formData=$('#updateCountryForm').serialize()
               var text = $('textarea[name=et_temp_body]').val();
                console.log(text);
                $.ajax({
                    type: "get",
                    url: '{{ url('admin/update-email-template') }}',
                    data: formData,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('.btn-update').text('loading...');
                        $(".btn-update").prop("disabled", true);
                    },
                    success: function(data) {

                        if (data.success) {
                            getCountries();
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



