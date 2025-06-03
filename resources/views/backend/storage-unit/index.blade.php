@extends('backend.layouts.app')
@section('title', '| Storage Unit')
@section('content')
    <div class="components-preview wide-md mx-auto">
        <div class="nk-block nk-block-lg">
            <div class="nk-block-head">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h3 class="nk-block-title page-title">Storage Unit List</h3>
                        <div class="nk-block-des text-soft">

                        </div>
                    </div>
                    <div class="nk-block-head-content">
                        <div class="toggle-wrap nk-block-tools-toggle">
                            <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-more-v"></em></a>
                            <div class="toggle-expand-content" data-content="pageMenu">
                                <ul class="nk-block-tools g-3">
                                    <li class="nk-block-tools-opt">
                                        <a href="#" class="btn btn-primary btn-sm"  data-toggle="modal" data-target="#addCountry"><em class="icon ni ni-plus"></em><span>Add Storage Unit</span></a>
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
                            <th class="nk-tb-col"><span class="sub-text"> Unit Name</span></th>
                            <th class="nk-tb-col"><span class="sub-text">Warehouse</span></th>
                            <th class="nk-tb-col"><span class="sub-text"> Unit Type</span></th>
                            <th class="nk-tb-col"><span class="sub-text"> Unit Level</span></th>
                            <th class="nk-tb-col"><span class="sub-text"> Unit Size</span></th>
                            <th class="nk-tb-col"><span class="sub-text">Location</span></th>
                            <th class="nk-tb-col"><span class="sub-text">Price</span></th>
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

    <div class="modal fade" tabindex="-1" role="dialog"  data-backdrop="static" data-keyboard="false" id="addCountry" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-capitalize" id="ajax_model_title">Add New Storage Unit</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" data-form="ajax-form-modal">

                    <form method="post" action="{{ url('admin/save-storage-unit') }}" id="CountryForm">
                        @csrf
                        <div class="row g-4">

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label> Storage Unit Name <span class="text-danger"></span></label>
                                    <div class="form-control-wrap">
                                        <input class="form-control" type="text" name="su_name" placeholder="Name" required>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label" for="status">Warehouse</label>
                                    <select class="form-control"  name="wh_id" required>
                                        <option value="">Choose One</option>
                                        @isset($data)
                                            @foreach ($data['wh'] as $wh)
                                                <option value="{{ $wh->id }}">{{$wh->loc->country->name."-".$wh->loc->city->city_name."-".$wh->loc->loc_name."-".$wh->name }}</option>
                                            @endforeach
                                        @endisset
                                    </select>

                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label" for="status">Storage Unit Level</label>
                                    <select class="form-control"  name="sl_id" required>
                                        <option value="">Choose One</option>
                                        @isset($data)
                                            @foreach ($data['sl'] as $sl)
                                                <option value="{{ $sl->id }}">{{ $sl->name }}</option>
                                            @endforeach
                                        @endisset
                                    </select>

                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label" for="status">Storage Type</label>
                                    <select class="form-control"  name="st_id" required>
                                        <option value="">Choose One</option>
                                        @isset($data)
                                            @foreach ($data['st'] as $st)
                                                <option value="{{ $st->id }}">{{ $st->name }}</option>
                                            @endforeach
                                        @endisset
                                    </select>

                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label" for="status">Storage Unit Size</label>
                                    <select class="form-control"  name="ss_id" required>
                                        <option value="">Choose One</option>
                                        @isset($data)
                                            @foreach ($data['ss'] as $ss)
                                                <option value="{{ $ss->id }}">{{ $ss->unit_type_name }}</option>
                                            @endforeach
                                        @endisset
                                    </select>

                                </div>
                            </div>



                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label" for="status">Width</label>
                                    <div class="form-control-wrap">
                                        <input class="form-control" type="number" name="width" placeholder="Width" step="any" required>
                                    </div>

                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label" for="status">Length</label>
                                    <div class="form-control-wrap">
                                        <input class="form-control" type="number" name="length" placeholder="Length" step="any" required>
                                    </div>

                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label" for="status">Height</label>
                                    <div class="form-control-wrap">
                                        <input class="form-control" type="number" name="height" placeholder="Height" step="any" required>
                                    </div>

                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label" for="status">Price</label>
                                    <div class="form-control-wrap">
                                        <input class="form-control" type="number" name="price" placeholder="Price" step="any" required>
                                    </div>

                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label">Location</label>
                                    <select class="form-control" name="location" required>
                                        <option value="" >Choose One</option>
                                        <option value="middle" >Middle</option>
                                        <option value="side">Side</option>
                                    </select>

                                </div>
                            </div>


                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-label" for="status">Status</label>
                                    <select class="form-control form-select" id="status" name="status" required=>
                                        <option value="vacant" >Vacant</option>
                                        <option value="occupied">Occupied</option>
                                        <option value="booked">Booked</option>
                                        <option value="booked but not paid">Booked but Not Paid </option>
                                        <option value="under maintenance">Under Maintenance </option>
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

    <div class="modal fade" tabindex="-1"  data-backdrop="static" data-keyboard="false" role="dialog" id="editCountry" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-capitalize" id="ajax_model_title">Edit Storage Unit</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" data-form="ajax-form-modal">

                    <form method="post" action="{{ url('admin/update-storage-unit') }}" id="updateCountryForm">
                        @csrf
                        <div class="row g-4">

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label> Storage Unit Name <span class="text-danger"></span></label>
                                    <div class="form-control-wrap">
                                        <input type="hidden" name="id">
                                        <input class="form-control" type="text" name="e_su_name" placeholder="Name" required>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label" for="status">Warehouse</label>
                                    <select class="form-control"  name="e_wh_id" required>
                                        <option value="">Choose One</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label" for="status">Storage Unit Level</label>
                                    <select class="form-control"  name="e_sl_id" required>
                                        <option value="">Choose One</option>

                                    </select>

                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label" for="status">Storage Type</label>
                                    <select class="form-control"  name="e_st_id" required>
                                        <option value="">Choose One</option>

                                    </select>

                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label" for="status">Storage Unit Size</label>
                                    <select class="form-control"  name="e_ss_id" required>
                                        <option value="">Choose One</option>

                                    </select>

                                </div>
                            </div>



                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label" for="status">Width</label>
                                    <div class="form-control-wrap">
                                        <input class="form-control" type="number" name="e_width" placeholder="Width" required>
                                    </div>

                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label" for="status">Length</label>
                                    <div class="form-control-wrap">
                                        <input class="form-control" type="number" name="e_length" placeholder="Length" required>
                                    </div>

                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label" for="status">Height</label>
                                    <div class="form-control-wrap">
                                        <input class="form-control" type="number" name="e_height" placeholder="Height" required>
                                    </div>

                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label" for="status">Price</label>
                                    <div class="form-control-wrap">
                                        <input class="form-control" type="number" name="e_price" placeholder="Price" required>
                                    </div>

                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label">Location</label>
                                    <select class="form-control" name="e_location" required>
                                        <option value="" >Choose One</option>

                                    </select>

                                </div>
                            </div>


                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-label" for="status">Status</label>
                                    <select class="form-control e" id="status" name="e_status" required=>
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
        $(document).ready(function() {

            $('#CountryForm').on('submit', function(e) {

                e.preventDefault();
                  var formData=$('#CountryForm').serialize()
                $.ajax({
                    type: "get",
                    url: '{{ url('admin/save-storage-unit') }}',
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

                    url: '{{ url('admin/get-storage-unit') }}',
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
                            ' <td class="nk-tb-col nk-tb-col-tools">'+data[i].storage_unit_name+'</td>'+
                                ' <td class="nk-tb-col nk-tb-col-tools">'+ data[i].warehouse.loc.city.country.name+'-'+data[i].warehouse.loc.city.city_name+'-'+data[i].warehouse.loc.loc_name+'-'+data[i].warehouse.name+'</td>'+
                                ' <td class="nk-tb-col nk-tb-col-tools">'+data[i].storagetype.name+'</td>'+
                                ' <td class="nk-tb-col nk-tb-col-tools">'+data[i].storagelevel.name+'</td>'+
                                ' <td class="nk-tb-col nk-tb-col-tools">'+data[i].storagesize.unit_type_name+'</td>'+
                            ' <td class="nk-tb-col nk-tb-col-tools">'+data[i].location+'</td>'+
                            ' <td class="nk-tb-col nk-tb-col-tools">'+data[i].price+'</td>'+
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
                        url: '{{ url('admin/delete-storage-unit') }}',
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
                    url: '{{ url('admin/edit-storage-unit') }}',
                    type: 'get',
                    async: false,
                    dataType: 'json',
                    data: { id: id },
                    success: function(res) {
                        console.log(res);

                        $('input[name=id]').val(id);
                        $('input[name=e_su_name]').val(res.su.storage_unit_name);
                        $('input[name=e_width]').val(res.su.width);
                        $('input[name=e_length]').val(res.su.length);
                        $('input[name=e_height]').val(res.su.height);
                        $('input[name=e_price]').val(res.su.price);


                            $('select[name="e_status"]')
                                .html(
                                    `<option value="vacant" ${res.su.status == 'vacant' ? 'selected' : ''}>Vacant</option>`+
                                    `<option value="occupied" ${res.su.status== 'occupied' ? 'selected' : ''}>Occupied</option>`+
                                    `<option value="booked" ${res.su.status== 'booked' ? 'selected' : ''}>Booked</option>`+
                                    `<option value="booked but not paid" ${res.su.status== 'booked but not paid' ? 'selected' : ''}>Booked but Not Paid</option>`+
                                    `<option value="under maintenance" ${res.su.status== 'under maintenance' ? 'selected' : ''}>under Maintenance</option>`

                                )

                        $('select[name="e_location"]')
                            .html(
                                `<option value="middle" ${res.su.location == 'middle' ? 'selected' : ''}>middle</option>`+
                                `<option value="side" ${res.su.location== 'side' ? 'selected' : ''}>side</option>`
                            )


                        $('select[name="e_wh_id"]')
                            .empty()

                        //edit dropdown in ajax
                        $.each(res.wh, function(key, wh) {

                            $('select[name="e_wh_id"]')
                                .append(
                                    `<option value="${wh.id}" ${wh.id == res.su.wh_id ? 'selected' : ''}>${wh.loc.country.name} - ${wh.loc.city.city_name} - ${wh.loc.loc_name} - ${wh.name}</option>`
                                )
                        });
                        $('select[name="e_st_id"]')
                            .empty()
                        $.each(res.st, function(key, st) {

                            $('select[name="e_st_id"]')
                                .append(
                                    `<option value="${st.id}" ${st.id == res.su.stype_id ? 'selected' : ''}>${st.name}</option>`
                                )
                        });
                        $('select[name="e_sl_id"]')
                            .empty()
                        $.each(res.sl, function(key, sl) {

                            $('select[name="e_sl_id"]')
                                .append(
                                    `<option value="${sl.id}" ${sl.id == res.su.slevel_id ? 'selected' : ''}>${sl.name}</option>`
                                )
                        });

                        $('select[name="e_ss_id"]')
                            .empty()
                        $.each(res.ss, function(key, ss) {

                            $('select[name="e_ss_id"]')
                                .append(
                                    `<option value="${ss.id}" ${ss.id == res.su.ssize_id ? 'selected' : ''}>${ss.unit_type_name}</option>`
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
                    url: '{{ url('admin/update-storage-unit') }}',
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



