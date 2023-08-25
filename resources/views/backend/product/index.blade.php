@extends('backend.layouts.app')
@section('title', '| Products')
@section('content')
    <div class="components-preview wide-md mx-auto">
        <div class="nk-block nk-block-lg">
            <div class="nk-block-head">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h3 class="nk-block-title page-title">Product List</h3>
                        <div class="nk-block-des text-soft">

                        </div>
                    </div>
                    <div class="nk-block-head-content">
                        <div class="toggle-wrap nk-block-tools-toggle">
                            <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-more-v"></em></a>
                            <div class="toggle-expand-content" data-content="pageMenu">
                                <ul class="nk-block-tools g-3">
                                    <li class="nk-block-tools-opt">
                                        <a href="#" class="btn btn-primary btn-sm"  data-toggle="modal" data-target="#addCountry"><em class="icon ni ni-plus"></em><span>Add Product</span></a>
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
                            <th class="nk-tb-col"><span class="sub-text">Purchase Price</span></th>
                            <th class="nk-tb-col"><span class="sub-text">Sale Price</span></th>
                            <th class="nk-tb-col"><span class="sub-text">Quantity</span></th>
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

    <div class="modal fade" tabindex="-1" role="dialog" id="addCountry" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-capitalize" id="ajax_model_title">Add New Product</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" data-form="ajax-form-modal">

                    <form method="post" action="{{ url('admin/save-product') }}" id="CountryForm" enctype="multipart/form-data" >
                        @csrf
                        <div class="row g-3 align-center">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="form-label" for="dashboard_title">Product Image</label>
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="form-group">
                                    <div class=" logo">

                                        <label for="logo-input">
                                            <img id="logo" src="{{ asset('sk-assets/assets/images/no_image.png') }}" alt="store logo" class="" style="max-width:100px;max-height:120px" >
                                            <input id="logo-input" preview="#logo" name="file" class="d-none" type="file" accept="image/*" onchange="loadFile(event)">
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-4">

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Name <span class="text-danger"></span></label>
                                    <div class="form-control-wrap">
                                        <input class="form-control" type="text" name="p_name" placeholder="Name" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Description <span class="text-danger"></span></label>
                                    <div class="form-control-wrap">
                                        <input class="form-control" type="text" name="des" placeholder="Description" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Purchase Price <span class="text-danger"></span></label>
                                    <div class="form-control-wrap">
                                        <input class="form-control" type="number" name="p_price" placeholder="Purchase Price" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Sale Price <span class="text-danger"></span></label>
                                    <div class="form-control-wrap">
                                        <input class="form-control" type="number" name="s_price" placeholder="Sale Price" required>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label" for="status">Discount Type</label>
                                    <select class="form-control "  name="dis_type" required>
                                        <option value="">Choose One</option>
                                        <option value="fixed">Fixed</option>
                                        <option value="percentage">Percentage</option>
                                    </select>

                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label" for="status">Discount Amount</label>
                                    <div class="form-control-wrap">
                                        <input class="form-control" type="number" name="dis_amount" placeholder="Discount Amount" required>
                                    </div>

                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label" for="status">Quantity</label>
                                    <div class="form-control-wrap">
                                        <input class="form-control" type="number" name="qty" placeholder="Quantity" required>
                                    </div>

                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label" for="status">Status</label>
                                    <select class="form-control e" id="status" name="status" required=>
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

    <div class="modal fade" tabindex="-1" role="dialog" id="editCountry" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-capitalize" id="ajax_model_title">Edit Product</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" data-form="ajax-form-modal">

                    <form method="post" action="{{ url('admin/update-product') }}" id="updateCountryForm"  enctype="multipart/form-data">
                        @csrf
                        <div class="row g-3 align-center">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="form-label" for="dashboard_title">Product Image</label>
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="form-group">
                                    <div class=" logo">
                                        <label for="logo-input">
                                            <img id="e_logo" src="{{ asset('sk-assets/assets/images/no_image.png') }}" alt="store logo" class="" style="max-width:100px;max-height:120px" >
                                            <input  name="e_file"  type="file"  onchange="loadFile(event)">
                                            <span id="store_image"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-4">

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Name <span class="text-danger"></span></label>
                                    <div class="form-control-wrap">
                                        <input type="hidden" name="id">
                                        <input class="form-control" type="text" name="e_p_name" placeholder="Name" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Description <span class="text-danger"></span></label>
                                    <div class="form-control-wrap">
                                        <input class="form-control" type="text" name="e_des" placeholder="Description" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Purchase Price <span class="text-danger"></span></label>
                                    <div class="form-control-wrap">
                                        <input class="form-control" type="number" name="e_p_price" placeholder="Purchase Price" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Sale Price <span class="text-danger"></span></label>
                                    <div class="form-control-wrap">
                                        <input class="form-control" type="number" name="e_s_price" placeholder="Sale Price" required>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label" for="status">Discount Type</label>
                                    <select class="form-control "  name="e_dis_type" required>
                                        <option value="">Choose One</option>

                                    </select>

                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label" for="status">Discount Amount</label>
                                    <div class="form-control-wrap">
                                        <input class="form-control" type="number" name="e_dis_amount" placeholder="Discount Amount" required>
                                    </div>

                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label" for="status">Quantity</label>
                                    <div class="form-control-wrap">
                                        <input class="form-control" type="number" name="e_qty" placeholder="Quantity" required>
                                    </div>

                                </div>
                            </div>

                            <div class="col-lg-6">
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

                let formData = new FormData($('#CountryForm')[0])

                $.ajax({
                    type: "POST",
                    url: '{{ url('admin/save-product') }}',
                    data: formData ,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('.btn-submit').text('Saving...');
                        $(".btn-submit").prop("disabled", true);
                    },
                    success: function(data) {
                            console.log(data);
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

                    error: function() {

                        toastr.error('any technical error');
                        $('.btn-submit').text('Save');
                        $(".btn-submit").prop("disabled", false);
                    }
                });


            });

            getAllCities();
            function getAllCities() {

                $.ajax({

                    url: '{{ url('admin/get-product') }}',
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
                            ' <td class="nk-tb-col nk-tb-col-tools">'+data[i].p_name+'</td>'+
                            ' <td class="nk-tb-col nk-tb-col-tools">'+data[i].pur_price+'</td>'+
                            ' <td class="nk-tb-col nk-tb-col-tools">'+data[i].sell_price+'</td>'+
                            ' <td class="nk-tb-col nk-tb-col-tools">'+data[i].qty+'</td>'+
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
                        url: '{{ url('admin/delete-product') }}',
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
                    url: '{{ url('admin/edit-product') }}',
                    type: 'get',
                    async: false,
                    dataType: 'json',
                    data: { id: id },
                    success: function(res) {
                        console.log(res);

                        $('input[name=id]').val(id);
                        $('input[name=e_p_name]').val(res.st.p_name);
                        $('input[name=e_des]').val(res.st.detail);
                        $('input[name=e_p_price]').val(res.st.pur_price);
                        $('input[name=e_s_price]').val(res.st.sell_price);
                        $('input[name=e_dis_amount]').val(res.st.disc_amount);
                        $('input[name=e_qty]').val(res.st.qty);

                            $('select[name="e_status"]')
                                .html(
                                    `<option value="1" ${res.st.status == 'Active' ? 'selected' : ''}>Active</option>`+
                                    `<option value="0" ${res.st.status== 'In-Active' ? 'selected' : ''}>In-Active</option>`
                                )

                        $('select[name="e_dis_type"]')
                            .html(
                                `<option value="fixed" ${res.st.disc_type == 'fixed' ? 'selected' : ''}>Fixed</option>`+
                                `<option value="percentage" ${res.st.disc_type== 'percentage' ? 'selected' : ''}>Percentage</option>`
                            )
                        var output = document.getElementById('e_logo');
                        output.src = '{{ asset('storage/uploads/product-images/') }}/'+res.st.image;
                        output.onload = function () {
                            URL.revokeObjectURL(output.src) // free memory
                        }


                    },
                    error: function() {
                        toastr.error('any technical error');
                    }
                });
            });
            $('#updateCountryForm').on('submit', function(e) {
                e.preventDefault();

                let formData = new FormData($('#updateCountryForm')[0])
                $.ajax({
                    type: "POST",
                    url: '{{ url('admin/update-product') }}',
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
    <script>

            var loadFile = function(event) {
                var output = document.getElementById('logo');
                output.src = URL.createObjectURL(event.target.files[0]);
                output.onload = function () {
                    URL.revokeObjectURL(output.src) // free memory
                }
            };



    </script>
@endsection



