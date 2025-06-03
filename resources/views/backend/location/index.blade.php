@extends('backend.layouts.app')
@section('title', '| Location')
@section('content')
    <div class="components-preview wide-md mx-auto">
        <div class="nk-block nk-block-lg">
            <div class="nk-block-head">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h3 class="nk-block-title page-title">Location List</h3>
                        <div class="nk-block-des text-soft">
                        </div>
                    </div>
                    <div class="nk-block-head-content">
                        <div class="toggle-wrap nk-block-tools-toggle">
                            <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-more-v"></em></a>
                            <div class="toggle-expand-content" data-content="pageMenu">
                                <ul class="nk-block-tools g-3">
                                    <li class="nk-block-tools-opt">
                                        <a href="#" class="btn btn-primary btn-sm"  data-toggle="modal" data-target="#addCountry"><em class="icon ni ni-plus"></em><span>Add Location</span></a>
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
                            <th class="nk-tb-col"><span class="sub-text">Location Name</span></th>
                            <th class="nk-tb-col"><span class="sub-text">Country</span></th>
                            <th class="nk-tb-col"><span class="sub-text">City</span></th>
                            <th class="nk-tb-col"><span class="sub-text">Longitude</span></th>
                            <th class="nk-tb-col"><span class="sub-text">Latitude</span></th>
                            <th class="nk-tb-col"><span class="sub-text">Status</span></th>
                            <th class="nk-tb-col"><span class="sub-text">Default</span></th>
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
                    <h5 class="modal-title text-capitalize" id="ajax_model_title">Add New Location</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" data-form="ajax-form-modal">

                        <form method="post" action="{{ url('admin/save-location') }}" id="CountryForm">
                            @csrf
                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Location Name <span class="text-danger"></span></label>
                                        <input class="form-control" type="text" name="loc_name" placeholder="Location Name" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Country <span class="text-danger"></span></label>
                                            <select name="country_id" class="form-control" id="countryId">
                                            <option value="">Choose One</option>
                                            @isset($data)
                                                @foreach ($data['country'] as $country)
                                                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                                                @endforeach
                                            @endisset
                                        </select>
                                    </div>
                                </div>


                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>City <span class="text-danger"></span></label>
                                        <select name="city_id" class="form-control selectpicker citySection" >
                                            <option value="">Choose One</option>

                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Latitude <span class="text-danger"></span></label>

                                        <input type="number" class="form-control" name="lat" placeholder="Latitude">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Longitude <span class="text-danger"></span></label>
                                        <input type="number" class="form-control" name="lang" placeholder="Longitude">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Status <span class="text-danger mt-1"></span></label>
                                        <select name="status" id="" class="form-control" required>
                                            <option value="">Choose One</option>
                                            <option value="1">Active</option>
                                            <option value="0">In-Active</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 mt-4">
                                    <div class="form-group">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" name="is_default" class="custom-control-input" id="customSwitch1">
                                            <label class="custom-control-label" for="customSwitch1">Is Default</label>
                                        </div>
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
                    <h5 class="modal-title text-capitalize" id="ajax_model_title">Edit Location</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" data-form="ajax-form-modal">

                    <form method="post" action="{{ url('admin/update-location') }}" id="updateCountryForm">
                        @csrf
                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Location Name <span class="text-danger"></span></label>
                                    <input  type="hidden" name="location_id"  required>
                                    <input class="form-control" type="text" name="e_loc_name" placeholder="Location Name" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Country <span class="text-danger"></span></label>
                                    <select name="e_country_id" class="form-control selectpicker" data-container="body"
                                            data-live-search="true">
                                        <option value="">Choose One</option>

                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>City <span class="text-danger"></span></label>
                                    <select name="e_city_id" class="form-control selectpicker" data-container="body"
                                            data-live-search="true">
                                        <option value="">Choose One</option>

                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Latitude <span class="text-danger"></span></label>

                                    <input type="number" class="form-control" name="e_lat" placeholder="Latitude">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Longitude <span class="text-danger"></span></label>
                                    <input type="number" class="form-control" name="e_lang" placeholder="Longitude">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Status <span class="text-danger mt-1"></span></label>
                                    <select name="e_status" id="" class="form-control" required>
                                        <option value="">Choose One</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mt-4">
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" name="edit_is_default" class="custom-control-input" id="customSwitch2">
                                        <label class="custom-control-label" for="customSwitch2">Is Default</label>
                                    </div>
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
                    url: '{{ url('admin/save-location') }}',
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

                    url: '{{ url('admin/get-locations') }}',
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
                            ' <td class="nk-tb-col nk-tb-col-tools">'+data[i].loc_name+'</td>'+
                            ' <td class="nk-tb-col nk-tb-col-tools">'+data[i].country.name+'</td>'+
                            ' <td class="nk-tb-col nk-tb-col-tools">'+data[i].city.city_name+'</td>'+
                            ' <td class="nk-tb-col nk-tb-col-tools">'+data[i].lat+'</td>'+
                            ' <td class="nk-tb-col nk-tb-col-tools">'+data[i].lang+'</td>'+
                            '<td class="nk-tb-col nk-tb-col-tools" >'+
                            ' <span class="badge badge-success">'+data[i].status+'</span>'+
                            ' </td>'+
                                '<td class="nk-tb-col nk-tb-col-tools" >'+
                                ' <span class="badge badge-success">'+data[i].is_default+'</span>'+
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
                        url: '{{ url('admin/delete-location') }}',
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
                    url: '{{ url('admin/edit-location') }}',
                    type: 'get',
                    async: false,
                    dataType: 'json',
                    data: { id: id },
                    success: function(res) {
                        console.log(res);

                        $('input[name=location_id]').val(res.loc.id);
                        $('input[name=e_loc_name]').val(res.loc.loc_name);
                        $('input[name=e_lat]').val(res.loc.lat);
                        $('input[name=e_lang]').val(res.loc.lang);
                        if (res.loc.is_default=='Default')
                        {
                            $('#customSwitch2').attr('checked', true);
                        }else{
                            $('#customSwitch2').attr('checked', false);
                        }

                            $('select[name="e_status"]')
                                .html(
                                    `<option value="1" ${res.loc.status == 'Active' ? 'selected' : ''}>Active</option>`+
                                    `<option value="0" ${res.loc.status== 'In-Active' ? 'selected' : ''}>In-Active</option>`
                                )

                        $('select[name="e_country_id"]')
                            .empty()
                        //edit dropdown in ajax
                        $.each(res.country, function(key, country) {

                            $('select[name="e_country_id"]')

                                .append(
                                    `<option value="${country.id}" ${country.id == res.loc.country_id ? 'selected' : ''}>${country.name}</option>`
                                )
                        });

                        $('select[name="e_city_id"]')
                            .empty()

                        $.each(res.city, function(key, city) {

                            $('select[name="e_city_id"]')
                                .append(
                                    `<option value="${city.id}" ${city.id == res.loc.city_id ? 'selected' : ''}>${city.city_name}</option>`
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
                    url: '{{ url('admin/update-location') }}',
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


            $('#countryId').change(function() {
                var country_id = $('select[name=country_id]').val();

                $.ajax({
                    type: 'ajax',
                    method: 'get',
                    url: '{{ url('admin/get-country-base-city') }}',
                    data: {country_id: country_id },
                    async: false,
                    dataType: 'json',
                    success: function(data) {
                        var html = '';
                        var i;
                        if (data.length > 0) {
                            for (i = 0; i < data.length; i++) {
                                html += '<option value="' + data[i].id + '">' + data[i].city_name + '</option>';
                            }
                        } else {
                            var html = '<option value="">Choose One</option>';
                            toastr.error('data not found');
                        }

                        $('.citySection').html(html);

                    },

                    error: function() {

                        alert('Could not get Data from Database');

                    }

                });
            });

        });
    </script>
@endsection



