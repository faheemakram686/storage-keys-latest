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
                                                <h4 class="nk-block-title">Templates</h4>
                                                <div class="nk-block-des">

                                                </div>
                                            </div>
                                            <div class="d-flex align-center">
                                                <div class="nk-tab-actions me-n1">
                                                    <a href="#" class="btn btn-primary btn-sm"  data-toggle="modal" data-target="#addCountry"><em class="icon ni ni-plus"></em><span>Add Template</span></a>
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
                                                    <th class="nk-tb-col"><span class="sub-text">Template Title</span></th>
                                                    <th class="nk-tb-col"><span class="sub-text">Status</span></th>
                                                    <th class="nk-tb-col"><span class="sub-text">Created At</span></th>
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
                <div class="modal-dialog modal-xl" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title text-capitalize" id="ajax_model_title">Add New Template</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" data-form="ajax-form-modal">
                            <form method="post" action="{{ url('admin/save-contract-template') }}" id="CountryForm">
                                @csrf

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Template Title<span class="text-danger"> * </span></label>
                                            <input class="form-control " type="text" name="temp_title" placeholder="Enter Title" required>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Template Content<span class="text-danger"></span></label>
                                            <textarea class="summernote-basic"  id="editor" name="temp_body"></textarea>
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
                    url: '{{ url('admin/save-contract-template') }}',
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


                $.ajax({

                    url: '{{ url('admin/get-contract-templates') }}',
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
                                ' <td class="nk-tb-col nk-tb-col-tools">' + data[i].temp_title + '</td>'+
                                '<td class="nk-tb-col nk-tb-col-tools" >'+
                                ' <span class="badge badge-success">'+data[i].status+'</span>'+
                                '</td>'+
                                ' <td class="nk-tb-col nk-tb-col-tools">' + data[i].created_at + '</td>'+
                                '  <td class="nk-tb-col nk-tb-col-tools">'+
                                ' <ul class="nk-tb-actions" gx-1 style="right: 4.0rem; !important">'+
                                '  <li>'+
                                ' <div class="drodown">'+
                                '  <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>'+
                                ' <div class="dropdown-menu dropdown-menu-left">'+
                                '<ul class="link-list-opt no-bdr">'+
                                {{--'<li><a href={{url('admin/insert-contract-content')}}/'+data[i].id+'><em class="icon ni ni-edit"></em><span>Insert</span></a></li>'+--}}
                                '<li><a href={{url('admin/edit-contract-template')}}/'+data[i].id+'><em class="icon ni ni-edit"></em><span>Edit</span></a></li>'+
                                 // '<li><a href="#" class="btn-edit" data='+data[i].id+' data-toggle="modal" data-target="#editCountry"><em class="icon ni ni-edit"></em><span>Edit</span></a></li>'+
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
                    url: '{{ url('admin/delete-contract-template') }}',
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




        });
    </script>
@endsection



