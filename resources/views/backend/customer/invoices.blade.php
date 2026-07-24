@extends('backend.layouts.app')
@section('title', '| Customer')
@section('content')
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-block-head">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h4 class="title nk-block-title">Customer Information</h4>
                    </div>
                    <a href="{{url("admin/customer")}}" class="btn btn-primary btn-sm d-none d-md-inline-flex"><em class="icon ni ni-arrow-left"></em><span>Back</span></a>
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
                                                <h4 class="nk-block-title">Invoices</h4>
                                                <div class="nk-block-des">
                                                    <p>All invoices for this customer.</p>
                                                </div>
                                            </div>
                                            <div class="d-flex align-center">
                                                <div class="nk-tab-actions me-n1">
                                                    <a href="{{ route('create-invoice') }}" class="btn btn-primary btn-sm"><em class="icon ni ni-plus"></em><span>Add Invoice</span></a>
                                                </div>
                                                <div class="nk-block-head-content align-self-start d-lg-none">
                                                    <a href="#" class="toggle btn btn-icon btn-trigger" data-target="userAside"><em class="icon ni ni-menu-alt-r"></em></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="nk-block">
                                        <div class="card border border-light">
                                            <table class="table table-md datatable-init-export nk-tb-list nk-tb-ulist" data-auto-responsive="true">
                                                <thead>
                                                <tr class="nk-tb-item nk-tb-head">
                                                    <th class="nk-tb-col text-left"><span class="sub-text">ID</span></th>
                                                    <th class="nk-tb-col"><span class="sub-text">INV-No</span></th>
                                                    <th class="nk-tb-col"><span class="sub-text">Contract/Order</span></th>
                                                    <th class="nk-tb-col"><span class="sub-text">INV-Date</span></th>
                                                    <th class="nk-tb-col"><span class="sub-text">Due</span></th>
                                                    <th class="nk-tb-col"><span class="sub-text">Status</span></th>
                                                    <th class="nk-tb-col"><span class="sub-text">Payment</span></th>
                                                    <th class="nk-tb-col"><span class="sub-text">Action</span></th>
                                                </tr>
                                                </thead>
                                                <tbody id="invoiceTable">
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                @include('backend.customer.aside')
                            </div>
                        </div>
                    @endisset
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            function paymentStatusBadgeClass(status) {
                if (status === 'Paid') {
                    return 'badge-success';
                }
                if (status === 'Partial') {
                    return 'badge-warning';
                }
                return 'badge-danger';
            }

            getCustomerInvoices();

            function getCustomerInvoices() {
                var customer_id = $('#customer_id').val();

                $.ajax({
                    url: '{{ url('admin/get-customer-invoices') }}',
                    type: 'get',
                    async: false,
                    dataType: 'json',
                    data: { customer_id: customer_id },
                    success: function(data) {
                        var html = '';
                        var c = 0;

                        for (var i = 0; i < data.length; i++) {
                            c++;
                            var contract = data[i].contract || null;
                            var order = data[i].order || null;
                            var contractOrOrderHtml = 'N/A';

                            if (contract) {
                                contractOrOrderHtml = '<a href={{ url('admin/contract/detail') }}/' + contract.id + '>' + (contract.subject || ('Contract #' + contract.id)) + '</a>';
                            } else if (order) {
                                contractOrOrderHtml = '<a href={{ url('admin/order/detail') }}/' + order.id + '>Order No: ' + order.id + '</a>';
                            }

                            html += '<tr class="nk-tb-item odd">' +
                                '<td class="nk-tb-col nk-tb-col-tools sorting_1">' + c + '</td>' +
                                '<td class="nk-tb-col nk-tb-col-tools"><a href={{ url('admin/invoice/detail') }}/' + data[i].id + '>' + data[i].invoice_no + '</a> ' + ((data[i].recurring != '0') ? '<span class="badge badge-outline-primary">Recurring</span>' + (data[i].recurring_interval_label ? ' <span class="badge badge-outline-info">' + data[i].recurring_interval_label + '</span>' : '') + (data[i].no_cycle != null && data[i].no_cycle !== '' ? ' <span class="badge badge-outline-primary">Cycles Remaining: ' + data[i].no_cycle + '</span>' : '') : '') + (data[i].months_overdue ? ' <span class="badge badge-outline-danger">' + data[i].months_overdue + (data[i].months_overdue == 1 ? ' month' : ' months') + ' overdue</span>' : '') + '</td>' +
                                '<td class="nk-tb-col nk-tb-col-tools">' + contractOrOrderHtml + '</td>' +
                                '<td class="nk-tb-col nk-tb-col-tools">' + data[i].invoice_date + '</td>' +
                                '<td class="nk-tb-col nk-tb-col-tools">' + data[i].due_date + '</td>' +
                                '<td class="nk-tb-col nk-tb-col-tools"><span class="badge ' + (data[i].status === 'Active' ? 'badge-success' : 'badge-danger') + '">' + data[i].status + '</span></td>' +
                                '<td class="nk-tb-col nk-tb-col-tools"><span class="badge ' + paymentStatusBadgeClass(data[i].payment_status) + '">' + data[i].payment_status + '</span></td>' +
                                '<td class="nk-tb-col nk-tb-col-tools">' +
                                '<ul class="nk-tb-actions gx-1">' +
                                '<li><div class="drodown">' +
                                '<a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>' +
                                '<div class="dropdown-menu dropdown-menu-right">' +
                                '<ul class="link-list-opt no-bdr">' +
                                '<li><a href={{ url('admin/invoice/detail') }}/' + data[i].id + '><em class="icon ni ni-eye"></em><span>View</span></a></li>' +
                                '<li><a href={{ url('/invoice-to-customer') }}/' + data[i].hashid + '><em class="icon ni ni-user"></em><span>View as Customer</span></a></li>' +
                                '<li><a href={{ url('admin/pdf-invoice') }}/' + data[i].hashid + '><em class="icon ni ni-file-pdf"></em><span>View as PDF</span></a></li>' +
                                '<li><a href={{ url('admin/edit-invoice') }}/' + data[i].id + '><em class="icon ni ni-edit"></em><span>Edit</span></a></li>' +
                                '<li><a href="#" class="btn-delete" data=' + data[i].id + '><em class="icon ni ni-trash"></em><span>Delete</span></a></li>' +
                                '</ul></div></div></li></ul></td></tr>';
                        }

                        $('#invoiceTable').html(html);
                    },
                    error: function() {
                        toastr.error('Something went wrong');
                    }
                });
            }

            $('#invoiceTable').on('click', '.btn-delete', function() {
                var id = $(this).attr('data');
                $.ajax({
                    url: '{{ url('admin/delete-invoice') }}',
                    type: 'get',
                    async: false,
                    dataType: 'json',
                    data: { id: id },
                    success: function(data) {
                        if (data.success) {
                            getCustomerInvoices();
                            toastr.success('Record deleted successfully');
                        } else {
                            toastr.error('Record not deleted');
                        }
                    },
                    error: function() {
                        toastr.error('Something went wrong');
                    }
                });
            });
        });
    </script>
@endsection
