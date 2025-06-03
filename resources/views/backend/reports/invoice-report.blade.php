@extends('backend.layouts.app')
@section('title', '| Invoice Report')
@section('content')
    <div class="components-preview wide-md mx-auto">
        <div class="nk-block nk-block-lg">
            <div class="nk-block-head">

                    <div class="nk-block-head-content">
                        <h3 class="nk-block-title page-title">Invoice Report</h3>
                        <div class="nk-block-des text-soft">
                            <form method="get" action="{{ url('admin/filter-invoice-report') }}" id="ReportFilterForm">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-label" for="customer_id">Customer</label>
                                            <select class="form-control select2" data-live-search="true" name="customer_id" id="customer_id">
                                                <option value="" selected >Select Customer</option>
                                                @isset($data)
                                                    @foreach ($data['customer'] as $customer)
                                                        <option value="{{ $customer->id }}" @isset($id) {{(($customer->id == $id)? 'selected':'')}} @endisset >{{$customer->customer_name }}</option>
                                                    @endforeach
                                                @endisset
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-label" for="contract_id">Contract</label>
                                            <select class="form-control select2" data-live-search="true" name="contract_id" id="contract_id">
                                                <option value="" selected >Select Contract</option>
                                                @isset($data)
                                                    @foreach ($data['contracts'] as $contract)
                                                        <option value="{{ $contract->id }}" >{{$contract->subject}} </option>
                                                    @endforeach
                                                @endisset
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-label" for="user_res">User Responsible</label>
                                            <select class="form-control select2" data-live-search="true" name="user_res" id="user_res">
                                                <option value="" selected >Select User Responsible</option>
                                                @isset($data)
                                                    @foreach ($data['user'] as $user)
                                                        <option value="{{ $user->id }}" >{{$user->first_name }} {{$user->last_name }}</option>
                                                    @endforeach
                                                @endisset
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-label" for="status">Status</label>
                                            <select class="form-control select2" data-live-search="true" name="payment_status" id="payment_status">
                                                <option value="" selected>Select Status</option>
                                                <option value="1" >PAID</option>
                                                <option value="2" >PARTIALLY PAID</option>
                                                <option value="0">UNPAID</option>

                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-label" for="end_date">Start Date</label>
                                            <input type="date" class="form-control" id="end_date" name="start_date">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-label" for="end_date">End Date</label>
                                            <input type="date" class="form-control" id="end_date" name="end_date">
                                        </div>
                                    </div>
                                </div>
                                <div class="float-right">
                                    <button class="btn btn-md mt-3 btn-primary btn-submit" type="submit"><em class="icon ni ni-filter"></em><span>Filter</span></button>
                                </div>
                            </form>
                        </div>
                    </div>


            </div>
            <div class="card card-preview mt-5">
                <div class="card-inner">

                    <table class="datatable-init-export nk-tb-list nk-tb-ulist" data-auto-responsive="true"  id="datatable" >
                        <thead>
                        <tr class="nk-tb-item nk-tb-head">
                            <th class="nk-tb-col text-left"><span class="sub-text">SrNo#</span></th>
                            <th class="nk-tb-col"><span class="sub-text">INV-No</span></th>
                            <th class="nk-tb-col"><span class="sub-text">Customer</span></th>
                            <th class="nk-tb-col"><span class="sub-text">Contract/Order</span></th>
                            <th class="nk-tb-col"><span class="sub-text">INV-Date</span></th>
                            <th class="nk-tb-col"><span class="sub-text">Due</span></th>
                            <th class="nk-tb-col"><span class="sub-text">Amount</span></th>
                            <th class="nk-tb-col"><span class="sub-text">Responsible</span></th>
                            <th class="nk-tb-col"><span class="sub-text">Method</span></th>
                            <th class="nk-tb-col"><span class="sub-text">Payment</span></th>
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



    <script>
        $(document).ready(function() {

            var formData = '';

            getReport(formData);

            $('#ReportFilterForm').on('submit', function(e) {
                e.preventDefault();
                formData=$('#ReportFilterForm').serialize()
                console.log(formData);
                getReport(formData);
             $('#datatable').DataTable.reload();
            });

            function getReport(formData) {

                $.ajax({
                    url: '{{ url('admin/filter-invoice-report') }}',
                    data: formData,
                    type: 'get',
                    async: false,
                    dataType: 'json',
                    contentType: false,
                    processData: true,
                    beforeSend: function() {
                        $('.btn-submit').text('Filtering...');
                        $(".btn-submit").prop("disabled", true);
                    },
                    success: function(data) {
                        console.log(data);
                        var html = '';
                        var i;
                        var c = 0;
                        if (data.length > 0) {
                            var TodayDate = new Date();
                            for (i = 0; i < data.length; i++) {
                                c++
                                html += '<tr class="nk-tb-item odd">'+
                                    ' <td class="nk-tb-col nk-tb-col-tools sorting_1">'+c+'</td>'+
                                    ' <td class="nk-tb-col nk-tb-col-tools"><a href={{url('admin/invoice/detail')}}/' + data[i].id + '>'+data[i].invoice_no + ' '+((data[i].recurring != '0')? '<span class="badge badge-outline-primary">Recurring</span></a>':' ') +'</td>'+
                                    ' <td class="nk-tb-col nk-tb-col-tools"><a href={{url('admin/customer/profile')}}/' + data[i].customer.id + '>'+data[i].customer.customer_name+'</a></td>'+
                                    ' <td class="nk-tb-col nk-tb-col-tools"> '+((data[i].contract != null)? '<a href={{url('admin/contract/detail')}}/' + data[i].contract.id + '>'+data[i].contract.subject+'</a>':'<a href={{url('admin/order/detail')}}/' + data[i].order.id + '>Order No: '+data[i].order.id+'</a>') +' </td>'+
                                    ' <td class="nk-tb-col nk-tb-col-tools">'+data[i].invoice_date+'</td>'+
                                    ' <td class="nk-tb-col nk-tb-col-tools">'+data[i].due_date+'</td>'+
                                    ' <td class="nk-tb-col nk-tb-col-tools">'+data[i].grand_total+'</td>'+
                                    ' <td class="nk-tb-col nk-tb-col-tools">'+data[i].user_responsible.full_name+'</td>'+
                                    '<td class="nk-tb-col nk-tb-col-tools" >'+
                                    ' <span class="badge badge-success">'+data[i].payment_method+'</span>'+
                                    ' </td>'+
                                    '<td class="nk-tb-col nk-tb-col-tools" >'+
                                    ' <span class="badge '+((data[i].payment_status == 'PAID')? 'badge-success':'badge-danger') +' ">'+data[i].payment_status+'</span>'+
                                    ' </td>'+
                                    '</tr>';

                            }

                        }else {
                            toastr.error('Result Not Found');
                        }
                        $('#countryTable').html(html);
                    },

                    complete: function(data) {
                        $(".btn-submit").html('<em class="icon ni ni-filter"></em><span>Filter</span>');
                        $(".btn-submit").prop("disabled", false);
                    },

                    error: function() {;
                        toastr.error('any technical error');
                        $('.btn-submit').text('Filter');
                        $(".btn-submit").prop("disabled", false);
                    }
                });
            }

        });
    </script>

@endsection



