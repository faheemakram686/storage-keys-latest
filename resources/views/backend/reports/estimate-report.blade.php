@extends('backend.layouts.app')
@section('title', '| Estimate Report')
@section('content')
    <div class="components-preview wide-md mx-auto">
        <div class="nk-block nk-block-lg">
            <div class="nk-block-head">

                    <div class="nk-block-head-content">
                        <h3 class="nk-block-title page-title">Estimate Report</h3>
                        <div class="nk-block-des text-soft">
                            <form method="get" action="{{ url('admin/filter-estimate-report') }}" id="ReportFilterForm">
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
                                            <label class="form-label" for="status">Estimate Status</label>
                                            <select class="form-control select2" data-live-search="true" name="status" id="status">
                                                <option value="" selected>Select Estimate Status</option>
                                                <option value="3" >Approved</option>
                                                <option value="2" >Approved Level 2</option>
                                                <option value="1">Approved Level 1</option>
                                                <option value="0">Not Approved</option>
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
                            <th class="nk-tb-col"><span class="sub-text">Full Name</span></th>
                            <th class="nk-tb-col"><span class="sub-text">Estimate</span></th>
                            <th class="nk-tb-col"><span class="sub-text">Unit-Price</span></th>
                            <th class="nk-tb-col"><span class="sub-text">Estimate Date</span></th>
                            <th class="nk-tb-col"><span class="sub-text">Expiry Date</span></th>
                            <th class="nk-tb-col"><span class="sub-text">Responsible</span></th>
                            <th class="nk-tb-col"><span class="sub-text">Expire</span></th>
                            <th class="nk-tb-col"><span class="sub-text">Status</span></th>
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

            // Replace table rows and keep DataTables (search/pagination/export) in sync
            function renderRows(html) {
                var wasInitialized = $.fn.DataTable && $.fn.DataTable.isDataTable('#datatable');
                if (wasInitialized) {
                    $('#datatable').DataTable().destroy();
                }
                $('#countryTable').html(html);
                if (wasInitialized && window.NioApp && NioApp.DataTable) {
                    NioApp.DataTable('#datatable', {
                        responsive: { details: true },
                        buttons: ['copy', 'excel', 'csv', 'pdf']
                    });
                }
            }

            getReport(formData);

            $('#ReportFilterForm').on('submit', function(e) {
                e.preventDefault();
                formData=$('#ReportFilterForm').serialize()
                getReport(formData);
            });

            function getReport(formData) {

                $.ajax({
                    url: '{{ url('admin/filter-estimate-report') }}',
                    data: formData,
                    type: 'get',
                    async: true,
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
                                c++;
                                var endDate= new Date(Date.parse(data[i].expiry_date));
                                var termLengthTitle = (data[i].term_length && data[i].term_length.title) ? data[i].term_length.title : 'N/A';
                                var storageUnitName = 'N/A';
                                if (data[i].estimate_storage_units && data[i].estimate_storage_units.length > 0) {
                                    storageUnitName = data[i].estimate_storage_units.map(function(u) {
                                        return (u.storageunit && u.storageunit.storage_unit_name) ? u.storageunit.storage_unit_name : ('#'+u.storage_unit_id);
                                    }).join(', ');
                                } else if (data[i].storageunit && data[i].storageunit.storage_unit_name) {
                                    storageUnitName = data[i].storageunit.storage_unit_name;
                                }
                                var customerName = (data[i].customer && data[i].customer.customer_name) ? data[i].customer.customer_name : ' ';
                                var responsibleName = (data[i].user_responsible && data[i].user_responsible.full_name) ? data[i].user_responsible.full_name : 'N/A';

                                html += ' <tr class="nk-tb-item odd">'+
                                    ' <td class="nk-tb-col nk-tb-col-tools sorting_1">'+c+'</td>'+
                                    ' <td class="nk-tb-col nk-tb-col-tools">'+customerName+'</td>'+
                                    ' <td class="nk-tb-col nk-tb-col-tools"><a href={{url('admin/estimate/detail')}}/' + data[i].id + '>'+storageUnitName+'/'+termLengthTitle+'</a></td>'+
                                    ' <td class="nk-tb-col nk-tb-col-tools">'+data[i].unit_price+'</td>'+
                                    ' <td class="nk-tb-col nk-tb-col-tools">'+data[i].estimate_date+'</td>'+
                                    ' <td class="nk-tb-col nk-tb-col-tools">'+data[i].expiry_date+'</td>'+
                                    ' <td class="nk-tb-col nk-tb-col-tools">'+responsibleName+'</td>'+
                                    ' <td class="nk-tb-col nk-tb-col-tools" >'+
                                    ' <span class="badge '+((endDate < TodayDate ? "badge-danger":"badge-success"))+'">'+((endDate < TodayDate ? "Expired":"Active"))+'</span>'+
                                    ' </td>'+
                                    ' <td class="nk-tb-col nk-tb-col-tools" >'+
                                    ' <span class="badge '+((data[i].status == 'Approved' ? "badge-success":"badge-danger"))+'">'+data[i].status+'</span>'+
                                    ' </td>'+
                                    ' </tr>';
                            }

                        }else {
                            toastr.error('Result Not Found');
                        }
                        renderRows(html);
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



