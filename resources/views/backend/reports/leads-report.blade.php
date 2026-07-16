@extends('backend.layouts.app')
@section('title', '| Leads Report')
@section('content')
    <div class="components-preview wide-md mx-auto">
        <div class="nk-block nk-block-lg">
            <div class="nk-block-head">

                    <div class="nk-block-head-content">
                        <h3 class="nk-block-title page-title">Leads Report</h3>
                        <div class="nk-block-des text-soft">
                            <form method="get" action="{{ url('admin/filter-lead-report') }}" id="LeadReportForm">
                                @csrf
                                <div class="row">
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
                                            <label class="form-label" for="status">Lead Status</label>
                                            <select class="form-control select2" data-live-search="true" name="lead_status" id="lead_status">
                                                <option value="" selected>Select Lead Status</option>
                                                @isset($data)
                                                    @foreach ($data['status'] as $sl)
                                                        <option value="{{ $sl->id }}">{{ $sl->title }}</option>
                                                    @endforeach
                                                @endisset

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

                    <table class="datatable-init-export nk-tb-list nk-tb-ulist" data-auto-responsive="true" id="datatable">
                        <thead>
                        <tr class="nk-tb-item nk-tb-head">
                            <th class="nk-tb-col text-left"><span class="sub-text">ID</span></th>
                            <th class="nk-tb-col"><span class="sub-text">Full Name</span></th>
                            <th class="nk-tb-col"><span class="sub-text">Lead</span></th>
                            <th class="nk-tb-col"><span class="sub-text">Type</span></th>
                            <th class="nk-tb-col"><span class="sub-text">Source</span></th>
                            <th class="nk-tb-col"><span class="sub-text">Lead Status</span></th>
                            <th class="nk-tb-col"><span class="sub-text">Responsible</span></th>
                            <th class="nk-tb-col"><span class="sub-text">Created Date</span></th>
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

            $('#LeadReportForm').on('submit', function(e) {
                e.preventDefault();
                formData=$('#LeadReportForm').serialize()
                console.log(formData);
                getReport(formData);

            });

            function getReport(formData) {

                $.ajax({
                    url: '{{ url('admin/filter-lead-report') }}',
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
                            for (i = 0; i < data.length; i++) {
                                c++;
                                var termLengthTitle = (data[i].term_length && data[i].term_length.title) ? data[i].term_length.title : 'N/A';
                                var storageUnitName = 'N/A';
                                if (data[i].storage_units && data[i].storage_units.length > 0) {
                                    storageUnitName = data[i].storage_units.map(function(u) { return u.storage_unit_name; }).join(', ');
                                } else if (data[i].storageunit && data[i].storageunit.storage_unit_name) {
                                    storageUnitName = data[i].storageunit.storage_unit_name;
                                }
                                var leadSourceTitle = (data[i].lead_source && data[i].lead_source.title) ? data[i].lead_source.title : 'N/A';
                                var leadStatusTitle = (data[i].lead_status && data[i].lead_status.title) ? data[i].lead_status.title : 'N/A';
                                var responsibleName = data[i].userresponsible ? (data[i].userresponsible.first_name + ' ' + data[i].userresponsible.last_name) : 'N/A';

                                html += ' <tr class="nk-tb-item odd">'+
                                    ' <td class="nk-tb-col nk-tb-col-tools sorting_1">'+c+'</td>'+
                                    ' <td class="nk-tb-col nk-tb-col-tools">'+ ((data[i].company_name == null) ? data[i].f_name+' '+data[i].l_name : data[i].company_name)+'</td>'+
                                    ' <td class="nk-tb-col nk-tb-col-tools"><a href={{url('admin/lead/profile')}}/' + data[i].id + '>'+storageUnitName+'/'+termLengthTitle+'</a></td>'+
                                    ' <td class="nk-tb-col nk-tb-col-tools">'+data[i].lead_type+'</td>'+
                                    ' <td class="nk-tb-col nk-tb-col-tools">'+leadSourceTitle+'</td>'+
                                    ' <td class="nk-tb-col nk-tb-col-tools" >'+
                                    ' <span class="badge badge-success">'+leadStatusTitle+'</span>'+
                                    ' </td>'+
                                    ' <td class="nk-tb-col nk-tb-col-tools">'+responsibleName+'</td>'+
                                    ' <td class="nk-tb-col nk-tb-col-tools">'+data[i].created_at+'</td>'+
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



