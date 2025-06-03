@extends('backend.layouts.app')
@section('title', '| Move-In Request')
@section('content')
    <div class="components-preview wide-md mx-auto">
        <div class="nk-block nk-block-lg">
            <div class="nk-block-head">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h3 class="nk-block-title page-title">Move-In Request List</h3>
                        <div class="nk-block-des text-soft">

                        </div>
                    </div>
                    <div class="nk-block-head-content">
                        <div class="toggle-wrap nk-block-tools-toggle">
                            <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-more-v"></em></a>
                            <div class="toggle-expand-content" data-content="pageMenu">
                                <ul class="nk-block-tools g-3">
                                    <li class="nk-block-tools-opt">
                                        <a href="#" class="btn btn-primary btn-sm"  data-toggle="modal" data-target="#addCountry"><em class="icon ni ni-plus"></em><span>Add Move-In Request</span></a>
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
                            <th class="nk-tb-col text-left"><span class="sub-text">Sr No.</span></th>
                            <th class="nk-tb-col"><span class="sub-text">Customer</span></th>
                            <th class="nk-tb-col"><span class="sub-text">Contract</span></th>
                            <th class="nk-tb-col"><span class="sub-text">Request Date</span></th>
                            <th class="nk-tb-col"><span class="sub-text">Generated Barcodes</span></th>
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
                    <h5 class="modal-title text-capitalize" id="ajax_model_title">Add Move-In Request</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" data-form="ajax-form-modal">
                    @isset($data)
                        <form method="post" action="{{ url('admin/save-move-in-request') }}" id="CountryForm">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Customer<span class="text-danger">*</span></label>
                                        <select name="customer_id" id="customer_id" class="form-control select2" data-live-search="true" required>
                                            <option value="">Choose One</option>
                                            @foreach( $data['customers'] as $customer)
                                                <option value="{{$customer->id}}">{{$customer->customer_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Contract<span class="text-danger">*</span></label>
                                        <select name="contract_id" id="" class="form-control select2 ContractSection" data-live-search="true" required>
                                            <option value="">Choose One</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label> Select Request Date <span class="text-danger">*</span></label>
                                        <input class="form-control" type="date" name="date" placeholder="Date" required>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label> Note <span class="text-danger"></span></label>
                                        <textarea class="form-control"  name="note" placeholder="Note" required> </textarea>
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
                    @endisset
                </div>
            </div><!-- .modal-content -->
        </div><!-- .modla-dialog -->
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" id="editCountry" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-capitalize" id="ajax_model_title">Edit Movie-In Request</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" data-form="ajax-form-modal">
                    <form method="post" action="{{ url('admin/update-move-in-request') }}" id="updateCountryForm">
                        @csrf
                        <input type="hidden" name="id">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Customer<span class="text-danger">*</span></label>
                                    <select name="edit_customer_id" id="edit_customer_id" class="form-control select2" data-live-search="true" required>
                                        <option value="">Choose One</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Contract<span class="text-danger">*</span></label>
                                    <select name="edit_contract_id" id="" class="form-control select2 ContractSection" data-live-search="true" required>
                                        <option value="">Choose One</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label> Select Request Date <span class="text-danger">*</span></label>
                                    <input class="form-control" type="date" name="edit_date" placeholder="Date" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label> Note <span class="text-danger"></span></label>
                                    <textarea class="form-control"  name="edit_note" placeholder="Note" required> </textarea>
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
 <div class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" id="generateBarcode" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-capitalize" id="ajax_model_title">Generate Barcode Label</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" data-form="ajax-form-modal">
                    <form method="post" action="{{ url('admin/barcode-label') }}" id="updateCountryForm1">
                        @csrf
                        <input type="hidden" name="request_id">
                        <input type="hidden" name="contract_id">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Quantity<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" placeholder="Enter Quantity" name="qty" id="qty" required>
                                </div>
                            </div>
                        </div>

                        <div class="float-right">
                            <button class="btn btn-primary mt-2 btn-genrate" type="submit">Generate Label</button>
                        </div>
                    </form>

                </div>
            </div><!-- .modal-content -->
        </div><!-- .modla-dialog -->
    </div>

    <script>
        $(document).ready(function() {

            $("#customer_id").on('change', function() {
                var customer_id = $(this).val();
                getContracts(customer_id);
            });
            $("#edit_customer_id").on('change', function() {
                var customer_id = $(this).val();
                getContracts(customer_id);
            });

            function getContracts(customer_id) {
                $.ajax({
                    url: '{{ url('admin/get-customer-contracts') }}',
                    type: 'get',
                    async: false,
                    dataType: 'json',
                    data: { customer_id: customer_id },
                    success: function(data) {
                        // console.log(data);
                        $('.ContractSection').empty();
                        var html3 = '';
                        var i;
                        var c = 0;
                        $('.ContractSection').html('<option value="">Select Contract</option>');
                        if (data.length > 0) {
                            for (i = 0; i < data.length; i++) {
                                html3 += '<option  value="' + data[i].id + '">' + data[i].subject + '</option>';
                            }
                        } else {
                            var html3 = '<option value="">No Contract Found</option>';
                        }
                        $('.ContractSection').append(html3);
                    },
                    error: function() {
                        toastr.error('any technical error');
                    }
                });
            }



            $('#CountryForm').on('submit', function(e) {

                e.preventDefault();
                  var formData=$('#CountryForm').serialize()
                $.ajax({
                    type: "get",
                    url: '{{ url('admin/save-move-in-request') }}',
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

                    url: '{{ url('admin/get-move-in-request') }}',
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
                            ' <td class="nk-tb-col nk-tb-col-tools">'+data[i].customer.customer_name+'</td>'+
                            ' <td class="nk-tb-col nk-tb-col-tools contract_id "  data='+ data[i].contract.id +'>'+data[i].contract.subject+'</td>'+
                            ' <td class="nk-tb-col nk-tb-col-tools">'+data[i].request_date+'</td>'+
                            ' <td class="nk-tb-col nk-tb-col-tools">'+data[i].barcode_count+'</td>'+
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
                            '<li><a href="#" class="btn-label" data='+data[i].id+' data-toggle="modal" data-target="#generateBarcode" ><em class="icon ni ni-edit"></em><span>Generate BarCode Labels</span></a></li>'+
                            '<li><a href="{{url('admin/view-barcode-labels')}}/'+data[i].id+'" class="btn-label" data='+data[i].id+' ><em class="icon ni ni-edit"></em><span>View BarCode Labels</span></a></li>'+
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
                        url: '{{ url('admin/delete-move-in-request') }}',
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


            $('#countryTable').on('click', '.btn-edit', function() {
                var id = $(this).attr('data');

                $.ajax({
                    url: '{{ url('admin/edit-move-in-request') }}',
                    type: 'get',
                    async: false,
                    dataType: 'json',
                    data: { id: id },
                    success: function(res) {
                        console.log(res.contracts);

                        $('input[name=id]').val(id);
                        $('textarea[name=edit_note]').val(res.moveInRequest.note);
                        $('input[name=edit_date]').val(res.moveInRequest.request_date);
                            $('select[name="edit_status"]')
                                .html(
                                    `<option value="1" ${res.moveInRequest.status == 'Active' ? 'selected' : ''}>Active</option>`+
                                    `<option value="0" ${res.moveInRequest.status== 'In-Active' ? 'selected' : ''}>In-Active</option>`
                                )

                        $('select[name="edit_customer_id"]')
                            .empty()

                        //edit dropdown in ajax
                        $.each(res.customers, function(key, customer) {

                            $('select[name="edit_customer_id"]')
                                .append(
                                    `<option value="${customer.id}" ${customer.id == res.moveInRequest.customer_id ? 'selected' : ''}>${customer.customer_name}</option>`
                                )
                        });
                        $('select[name="edit_contract_id"]')
                            .empty()

                        //edit dropdown in ajax
                        $.each(res.contracts, function(key, contract) {

                            $('select[name="edit_contract_id"]')
                                .append(
                                    `<option value="${contract.id}" ${contract.id == res.moveInRequest.contract_id ? 'selected' : ''}>${contract.subject}</option>`
                                )
                        });
                    },
                    error: function() {
                        toastr.error('any technical error');
                    }
                });
            });
            $('#countryTable').on('click', '.btn-label', function() {
                var id = $(this).attr('data');
                var cid = $('.contract_id').attr('data');

                $('input[name=request_id]').val(id);
                $('input[name=contract_id]').val(cid);
            });


            $('#updateCountryForm').on('submit', function(e) {
                e.preventDefault();
                var formData=$('#updateCountryForm').serialize()
                $.ajax({
                    type: "get",
                    url: '{{ url('admin/update-move-in-request') }}',
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



