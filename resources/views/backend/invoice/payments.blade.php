@extends('backend.layouts.app')
@section('title', '| Invoice Payments')
@section('content')
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-block-head">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h4 class="title nk-block-title">Invoice Information</h4>
                    </div>
                    <a href="{{url("admin/invoices")}}" class="btn btn-primary btn-sm d-none d-md-inline-flex"><em class="icon ni ni-arrow-left"></em><span>Back</span></a>
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
                                                <h4 class="nk-block-title">Payments</h4>
                                                <div class="nk-block-des">

                                                </div>
                                            </div>
                                            <div class="d-flex align-center">
                                                <div class="nk-tab-actions me-n1">
                                                    <a href="{{url('admin/invoice/payment/'.$data['invoice'][0]->id)}}" class="btn btn-primary btn-sm"><em class="icon ni ni-plus"></em><span>Add Payment</span></a>
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
                                                    <th class="nk-tb-col text-left"><span class="sub-text">Sr No#</span></th>
                                                    <th class="nk-tb-col"><span class="sub-text">Invoice</span></th>
                                                    <th class="nk-tb-col"><span class="sub-text">Date</span></th>
                                                    <th class="nk-tb-col"><span class="sub-text">Mode</span></th>
                                                    <th class="nk-tb-col"><span class="sub-text">Amount</span></th>
                                                    <th class="nk-tb-col"><span class="sub-text">Action</span></th>
                                                </tr>
                                                </thead>
                                                <tbody id="countryTable">
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                @include('backend.invoice.aside')
                                <!-- card-aside -->
                            </div>
                            <!-- .card-aside-wrap -->
                        </div>
                    @endisset
                    <!-- .card -->
                </div>
                <!-- .nk-block -->

            </div>
            <div class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" id="editCountry" aria-hidden="true">
                <div class="modal-dialog modal-md" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title text-capitalize" id="ajax_model_title">Edit Payment</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" data-form="ajax-form-modal">
                            <form method="post" action="{{ url('admin/update-payment') }}" id="updateCountryForm">
                                @csrf
                                    <input type="hidden" name="id">
                                    <input type="hidden" name="invoice_id">
                                    <div class="form-group">
                                        <label class="form-label" for="amount_received">Amount Received <span class="text-danger">*</span></label>
                                        <div class="form-control-wrap">
                                            <input type="number" class="form-control"  name="amount_received" id="amount_received" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="payment_date">Payment Date <span class="text-danger">*</span></label>
                                        <div class="form-control-wrap">
                                            <input type="date" class="form-control" id="payment_date" name="payment_date" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="payment_mode">Payment Mode <span class="text-danger">*</span></label>
                                        <div class="form-control-wrap">
                                            <select class="form-control select2" name="payment_mode" id="payment_mode" required>
                                                <option>Choose One</option>
                                                <option value="1" >Cash</option>
                                                <option value="2" >Bank</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="trans_id">Transaction ID</label>
                                        <div class="form-control-wrap">
                                            <input type="text" class="form-control" name="trans_id" id="trans_id">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="cf-default-textarea">Leave a note</label>
                                        <div class="form-control-wrap">
                                            <textarea class="form-control form-control-sm" name="note" id="cf-default-textarea" placeholder="Leave a note"></textarea>
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



            getAllCities();
            function getAllCities() {
                var invoice_id=$('#invoice_id').val();
                $.ajax({
                    url: '{{ url('admin/get-invoice-payments') }}',
                    type: 'get',
                    async: false,
                    dataType: 'json',
                    data: {invoice_id:invoice_id},

                    success: function(data) {
                        console.log(data);

                        var html = '';
                        var i;
                        var c = 0;

                        for (i = 0; i < data.length; i++) {
                            c++;
                            html += ' <tr class="nk-tb-item odd">'+
                                ' <td class="nk-tb-col nk-tb-col-tools sorting_1">'+c+'</td>'+
                                ' <td class="nk-tb-col nk-tb-col-tools">' + data[i].invoice.invoice_no + '</td>' +
                                ' <td class="nk-tb-col nk-tb-col-tools">' + data[i].payment_date + '</td>'+
                                '<td class="nk-tb-col nk-tb-col-tools" >'+
                                ' <span class="badge badge-success">'+((data[i].payment_method == 1 )? 'Cash':'Bank')+'</span>'+
                                '</td>'+
                                ' <td class="nk-tb-col nk-tb-col-tools">' + data[i].amount_received + '</td>'+
                                // '<td class="nk-tb-col nk-tb-col-tools" >'+
                                // ' <span class="badge badge-success">'+data[i].status+'</span>'+
                                // '</td>'+
                                '  <td class="nk-tb-col nk-tb-col-tools">'+
                                ' <ul class="nk-tb-actions" gx-1 style="right: 1.0rem; !important">'+
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
                    url: '{{ url('admin/delete-payment') }}',
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
                    url: '{{ url('admin/edit-payment') }}',
                    type: 'get',
                    async: false,
                    dataType: 'json',
                    data: { id: id },
                    success: function(res) {
                        console.log(res);

                        $('input[name=id]').val(id);
                        $('input[name=invoice_id]').val(res.invoice_id);
                        $('input[name=amount_received]').val(res.amount_received);
                        $('input[name=payment_date]').val(res.payment_date);
                        $('textarea[name=note]').val(res.note);

                        $('select[name="payment_mode"]')
                            .html(
                                `<option value="1" ${res.payment_mode == 'Cash' ? 'selected' : ''}>Cash</option>`+
                                `<option value="0" ${res.payment_mode == 'Bank' ? 'selected' : ''}>Bank</option>`
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
                $.ajax({
                    type: "get",
                    url: '{{ url('admin/update-payment') }}',
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



