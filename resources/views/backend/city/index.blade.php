@extends('backend.layouts.app')
@section('title', '| City')
@section('content')
    <div class="components-preview wide-md mx-auto">
        <div class="nk-block nk-block-lg">
            <div class="nk-block-head">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h3 class="nk-block-title page-title">City List</h3>
                        <div class="nk-block-des text-soft">
                        </div>
                    </div>
                    <div class="nk-block-head-content">
                        <div class="toggle-wrap nk-block-tools-toggle">
                            <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-more-v"></em></a>
                            <div class="toggle-expand-content" data-content="pageMenu">
                                <ul class="nk-block-tools g-3">
                                    <li class="nk-block-tools-opt">
                                        <a href="#" class="btn btn-primary btn-sm"  data-toggle="modal" data-target="#addCountry"><em class="icon ni ni-plus"></em><span>Add City</span></a>
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
                            <th class="nk-tb-col"><span class="sub-text">Country</span></th>
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
                    <h5 class="modal-title text-capitalize" id="ajax_model_title">Add New City</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" data-form="ajax-form-modal">

                        <form method="post" action="{{ url('admin/saveCity') }}" id="CountryForm">
                            @csrf
                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label> Name <span class="text-danger"></span></label>
                                        <input class="form-control" type="text" name="city_name" placeholder="Name" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Country <span class="text-danger"></span></label>
                                            <select name="country_id" class="form-control selectpicker" data-container="body"
                                                    data-live-search="true">
                                            <option value="">Choose One</option>
                                            @isset($data)
                                                @foreach ($data['country'] as $country)
                                                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                                                @endforeach
                                            @endisset
                                        </select>
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
                                            <input type="checkbox" name="is_defult" class="custom-control-input" id="customSwitch1">
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
                    <h5 class="modal-title text-capitalize" id="ajax_model_title">Edit City</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" data-form="ajax-form-modal">

                    <form method="post" action="{{ url('admin/update-city') }}" id="updateCountryForm">
                        @csrf
                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label> Name <span class="text-danger"></span></label>
                                    <input type="hidden" name="city_id" required>
                                    <input class="form-control" type="text" name="edit_city_name" placeholder="Name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Country <span class="text-danger"></span></label>
                                    <select name="edit_country_id" class="form-control">
                                        <option value="">Choose One</option>

                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Status <span class="text-danger mt-1"></span></label>
                                    <select name="edit_status" id="" class="form-control" required>
                                        <option value="">Choose One</option>

                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mt-4">
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" name="edit_is_defult" class="custom-control-input" id="customSwitch2">
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
                    url: '{{ url('admin/saveCity') }}',
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

                    url: '{{ url('admin/getAllCity') }}',
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
                            ' <td class="nk-tb-col nk-tb-col-tools">'+data[i].city_name+'</td>'+
                            ' <td class="nk-tb-col nk-tb-col-tools">'+data[i].country.name+'</td>'+
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
                        url: '{{ url('admin/delete-city') }}',
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
                    url: '{{ url('admin/edit-city') }}',
                    type: 'get',
                    async: false,
                    dataType: 'json',
                    data: { id: id },
                    success: function(res) {
                        console.log(res);

                        $('input[name=city_id]').val(id);
                        $('input[name=edit_city_name]').val(res.city.city_name);
                        if (res.city.is_default=="Default")
                        {
                            $('#customSwitch2').attr('checked', true);
                        }else{
                            $('#customSwitch2').attr('checked', false);
                        }

                            $('select[name="edit_status"]')
                                .html(
                                    `<option value="1" ${res.city.status == 'Active' ? 'selected' : ''}>Active</option>`+
                                    `<option value="0" ${res.city.status== 'In-Active' ? 'selected' : ''}>In-Active</option>`
                                )

                        $('select[name="edit_country_id"]')
                            .empty()
                        //edit dropdown in ajax
                        $.each(res.country, function(key, country) {

                            $('select[name="edit_country_id"]')
                                .append(
                                    `<option value="${country.id}" ${country.id == res.city.country_id ? 'selected' : ''}>${country.name}</option>`
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
                    url: '{{ url('admin/update-city') }}',
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



