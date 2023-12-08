@extends('backend.layouts.app')
@section('title', '| Warehouse')
@section('content')
    <div class="components-preview wide-md mx-auto">
        <div class="nk-block nk-block-lg">
            <div class="nk-block-head">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h3 class="nk-block-title page-title">Warehouse List</h3>
                        <div class="nk-block-des text-soft">

                        </div>
                    </div>
                    <div class="nk-block-head-content">
                        <div class="toggle-wrap nk-block-tools-toggle">
                            <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-more-v"></em></a>
                            <div class="toggle-expand-content" data-content="pageMenu">
                                <ul class="nk-block-tools g-3">
                                    <li class="nk-block-tools-opt">
                                        <a href="#" class="btn btn-primary btn-sm"  data-toggle="modal" data-target="#addWarehouse"><em class="icon ni ni-plus"></em><span>Add Warehouse</span></a>
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
                            <th class="nk-tb-col"><span class="sub-text">Name</span></th>
                            <th class="nk-tb-col"><span class="sub-text">Location</span></th>
                            <th class="nk-tb-col"><span class="sub-text">City</span></th>
                            <th class="nk-tb-col"><span class="sub-text">Country</span></th>
                            <th class="nk-tb-col"><span class="sub-text">Status</span></th>
                            <th class="nk-tb-col tb-col-mb text-right"><span class="sub-text">Actions</span></th>
                        </tr>
                        </thead>
                        <tbody id="addonTable">
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- .card-preview -->
        </div>
        <!-- nk-block -->
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="addWarehouse" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-capitalize" id="ajax_model_title">Add New Warehouse</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" data-form="ajax-form-modal">

                    <form method="post" action="{{ url('admin/save-warehouse') }}" id="WarehouseForm">
                        @csrf
                        <div class="row g-4">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label" for="name">Name</label>
                                    <div class="form-control-wrap">
                                        <input type="text" class="form-control" id="name" name="name" required="" value="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label required" for="location">Location</label>

                                    <select name="loc_id" class="form-control selectpicker" data-container="body"
                                            data-live-search="true">
                                        <option value="">Choose One</option>
                                        @isset($data)

                                            @foreach ($data['loc'] as $country)
                                                <option value="{{ $country->id }}">{{$country->city->country->name ." - ". $country->city->city_name  ." - ". $country->loc_name }}</option>
                                            @endforeach
                                        @endisset
                                    </select>

                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-label" for="description">Description</label>
                                    <div class="form-control-wrap">
                                        <textarea  id="" cols="3" rows="1" class="form-control" name="description"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label" for="status">Status</label>
                                    <select class="form-control form-select " id="status" name="status" required="" data-select2-id="status" tabindex="-1" aria-hidden="true">
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

    <div class="modal fade" tabindex="-1" role="dialog" id="editAddon" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-capitalize" id="ajax_model_title">Edit WareHouse</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" data-form="ajax-form-modal">

                    <form method="post" action="{{ url('admin/update-wh') }}" id="updateWarehouseForm">
                        @csrf
                        <div class="row g-4">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label" for="name">Name</label>
                                    <div class="form-control-wrap">
                                        <input type="hidden" name="wh_id">
                                        <input type="text" class="form-control" id="e_name" name="e_name" required="" value="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label required" for="location">Location</label>
                                    <select class="form-control "  name="e_loc_id" required>
                                        <option value="">Choose One</option>

                                    </select>

                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-label" for="description">Description</label>
                                    <div class="form-control-wrap">
                                        <textarea type="text" class="form-control" rows="2" id="e_description" required="" name="e_description"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label" for="e_status">Status</label>
                                    <select class="form-control form-select" name="e_status" required="">

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

            $('#WarehouseForm').on('submit', function(e) {

                e.preventDefault();
                var formData=$('#WarehouseForm').serialize()
                console.log(formData);
                $.ajax({
                    type: "get",
                    url: '{{ url('admin/save-warehouse') }}',
                    data: formData,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('.btn-submit').text('Saving...');
                        $(".btn-submit").prop("disabled", true);
                    },
                    success: function(data) {

                        if (data.success) {
                            getAddon();
                            $('#WarehouseForm')[0].reset();
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

            getAddon();
            function getAddon() {

                $.ajax({

                    url: '{{ url('admin/get-wh') }}',
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
                                ' <td class="nk-tb-col nk-tb-col-tools">'+data[i].name+'</td>'+
                                ' <td class="nk-tb-col nk-tb-col-tools">'+data[i].loc.loc_name+'</td>'+
                                ' <td class="nk-tb-col nk-tb-col-tools">'+data[i].loc.city.city_name+'</td>'+
                                ' <td class="nk-tb-col nk-tb-col-tools">'+data[i].loc.country.name+'</td>'+
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
                                '<li><a href="#" class="btn-edit" data='+data[i].id+' data-toggle="modal" data-target="#editAddon"><em class="icon ni ni-edit"></em><span>Edit</span></a></li>'+
                                '<li><a href="#" class="btn-delete" data='+data[i].id+'><em class="icon ni ni-trash"></em><span>Delete</span></a></li>'+
                                '</ul>'+
                                ' </div>'+
                                '</div>'+
                                ' </li>'+
                                ' </ul>'+
                                '</td>'+
                                '</tr>';
                        }

                        $('#addonTable').html(html);

                    },
                    error: function() {
                        toastr.error('something went wrong');
                    }

                });
            }

            $('#addonTable').on('click', '.btn-delete', function() {
                var id = $(this).attr('data');
                $.ajax({
                    url: '{{ url('admin/delete-wh') }}',
                    type: 'get',
                    async: false,
                    dataType: 'json',
                    data: { id: id},
                    success: function(data) {
                        if (data.success) {
                            getAddon();
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


            $('#addonTable').on('click', '.btn-edit', function() {
                var id = $(this).attr('data');
                console.log(id);
                $.ajax({
                    url: '{{ url('admin/edit-wh') }}',
                    type: 'get',
                    async: false,
                    dataType: 'json',
                    data: { id: id },
                    success: function(res) {
                        console.log(res);

                        $('input[name=wh_id]').val(id);
                        $('input[name=e_name]').val(res.wh.name);
                        $('textarea[name=e_description]').val(res.wh.description);

                        $('select[name="e_loc_id"]')
                            .empty()

                        //edit dropdown in ajax
                        $.each(res.loc, function(key, loc) {

                            $('select[name="e_loc_id"]')
                                .append(
                                    `<option value="${loc.id}" ${loc.id == res.wh.loc_id ? 'selected' : ''}>${loc.country.name} - ${loc.city.city_name} - ${loc.loc_name}</option>`
                                )
                        });

                        $('select[name="e_status"]')
                            .html(
                                `<option value="1" ${res.wh.status == "Active" ? 'selected' : ''}>Active</option>`+
                                `<option value="0" ${res.wh.status== "In-Active" ? 'selected' : ''}>In-Active</option>`
                            )
                    },
                    error: function() {
                        toastr.error('any technical error');
                    }
                });
            });

            $('#updateWarehouseForm').on('submit', function(e) {
                e.preventDefault();
                var formData=$('#updateWarehouseForm').serialize()
                $.ajax({
                    type: "get",
                    url: '{{ url('admin/update-wh') }}',
                    data: formData,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('.btn-update').text('loading...');
                        $(".btn-update").prop("disabled", true);
                    },
                    success: function(data) {

                        if (data.success) {

                            getAddon();
                            $('#updateWarehouseForm')[0].reset();
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

                    error: function() {
                        toastr.error('any technical error');
                        $('.btn-update').text('Save Changes');
                        $(".btn-update").prop("disabled", false);
                    }
                });


            });

        });
    </script>

@endsection



