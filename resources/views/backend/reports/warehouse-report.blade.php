@extends('backend.layouts.app')
@section('title', '| Warehouse Report')
@section('content')
    <div class="components-preview wide-md mx-auto">
        <div class="nk-block nk-block-lg">
            <div class="nk-block-head">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h3 class="nk-block-title page-title">Warehouse Report</h3>
                        <div class="nk-block-des text-soft">
                            <form method="get" action="{{ url('admin/filter-warehouse-report') }}" id="WarehouseReportForm">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label class="form-label" for="status">Warehouse</label>
                                            <select class="form-control select2" data-live-search="true"  name="wh_id" >
                                                <option value="">Choose One</option>
                                                @isset($data)
                                                    @foreach ($data['wh'] as $wh)
                                                        <option value="{{ $wh->id }}">{{$wh->loc->country->name."-".$wh->loc->city->city_name."-".$wh->loc->loc_name."-".$wh->name }}</option>
                                                    @endforeach
                                                @endisset
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label class="form-label" for="status">Storage Unit Level</label>
                                            <select class="form-control select2" data-live-search="true" name="sl_id" >
                                                <option value="">Choose One</option>
                                                @isset($data)
                                                    @foreach ($data['sl'] as $sl)
                                                        <option value="{{ $sl->id }}">{{ $sl->name }}</option>
                                                    @endforeach
                                                @endisset
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label class="form-label" for="status">Storage Type</label>
                                            <select class="form-control select2" data-live-search="true"  name="st_id" >
                                                <option value="">Choose One</option>
                                                @isset($data)
                                                    @foreach ($data['st'] as $st)
                                                        <option value="{{ $st->id }}">{{ $st->name }}</option>
                                                    @endforeach
                                                @endisset
                                            </select>

                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label class="form-label" for="status">Storage Unit Size</label>
                                            <select class="form-control select2" data-live-search="true"  name="ss_id" >
                                                <option value="">Choose One</option>
                                                @isset($data)
                                                    @foreach ($data['ss'] as $ss)
                                                        <option value="{{ $ss->id }}">{{ $ss->unit_type_name }}</option>
                                                    @endforeach
                                                @endisset
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label class="form-label" for="status">Status</label>
                                            <select class="form-control select2" data-live-search="true" id="status" name="status">
                                                <option value="" >Select Status</option>
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
                                    <button class="btn btn-md btn-primary btn-submit" type="submit"><em class="icon ni ni-filter"></em><span>Filter</span></button>
{{--                                    <a  class="btn btn-primary btn-sm"  type="submit"><em class="icon ni ni-filter"></em><span>Filter</span></a>--}}
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
{{--            <div class="card card-preview">--}}
{{--                <div class="card-inner">--}}

{{--                    <table class="datatable-init-export nk-tb-list nk-tb-ulist" data-auto-responsive="true"  id="datatable" >--}}
{{--                        <thead>--}}
{{--                        <tr class="nk-tb-item nk-tb-head">--}}
{{--                            <th class="nk-tb-col text-left"><span class="sub-text">ID</span></th>--}}
{{--                            <th class="nk-tb-col"><span class="sub-text">Warehouse</span></th>--}}
{{--                            <th class="nk-tb-col"><span class="sub-text"> Unit Name</span></th>--}}
{{--                            <th class="nk-tb-col"><span class="sub-text"> Unit Type</span></th>--}}
{{--                            <th class="nk-tb-col"><span class="sub-text"> Unit Level</span></th>--}}
{{--                            <th class="nk-tb-col"><span class="sub-text"> Unit Size</span></th>--}}
{{--                            <th class="nk-tb-col"><span class="sub-text">Location</span></th>--}}
{{--                            <th class="nk-tb-col"><span class="sub-text">Price</span></th>--}}
{{--                            <th class="nk-tb-col"><span class="sub-text">Status</span></th>--}}
{{--                        </tr>--}}
{{--                        </thead>--}}
{{--                        <tbody id="countryTable">--}}
{{--                        </tbody>--}}
{{--                    </table>--}}
{{--                </div>--}}
{{--            </div>--}}
            <!-- .card-preview -->
            <div class="card card-bordered card-preview">
                <div class="card-inner">
                    <table class="datatable-init-export nowrap table" data-export-title="Export">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Warehouse</th>
                            <th> Unit Name</th>
                            <th>Unit Type</th>
                            <th>Unit Level</th>
                            <th>Unit Size</th>
                            <th>Location</th>
                            <th>Price</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        <tbody id="tbodyrow">

                        </tbody >
                    </table>
                </div>
            </div><!-- .card-preview -->

        </div>
        <!-- nk-block -->
    </div>



    <script>
        $(document).ready(function() {

            var formData = '';

            getReport(formData);

            $('#WarehouseReportForm').on('submit', function(e) {
                e.preventDefault();
                formData=$('#WarehouseReportForm').serialize()
                var table = $('#datatable').DataTable();
                getReport(formData);
                table.draw();


            });

            function getReport(formData) {

                $.ajax({
                    url: '{{ url('admin/filter-warehouse-report') }}',
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
                        var html = '';
                        var i;
                        var c = 0;
                        if (data.length > 0) {
                            for (i = 0; i < data.length; i++) {
                                c++;
                                // html += ' <tr class="nk-tb-item odd">'+
                                //     ' <td class="nk-tb-col nk-tb-col-tools sorting_1">'+c+'</td>'+
                                //     ' <td class="nk-tb-col nk-tb-col-tools">'+data[i].warehouse.name+'</td>'+
                                //     ' <td class="nk-tb-col nk-tb-col-tools">'+data[i].storage_unit_name+'</td>'+
                                //     ' <td class="nk-tb-col nk-tb-col-tools">'+data[i].storagetype.name+'</td>'+
                                //     ' <td class="nk-tb-col nk-tb-col-tools">'+data[i].storagelevel.name+'</td>'+
                                //     ' <td class="nk-tb-col nk-tb-col-tools">'+data[i].storagesize.unit_type_name+'</td>'+
                                //     ' <td class="nk-tb-col nk-tb-col-tools">'+data[i].location+'</td>'+
                                //     ' <td class="nk-tb-col nk-tb-col-tools">'+data[i].price+'</td>'+
                                //     ' <td class="nk-tb-col nk-tb-col-tools" >'+
                                //     ' <span class="badge '+(data[i].status =='booked'? "badge-success":data[i].status == 'vacant'? "badge-danger":"badge-primary")+' ">'+data[i].status+'</span>'+
                                //     ' </td>'+
                                //     ' </tr>';
                                html += ' <tr>'+
                                    ' <td >'+c+'</td>'+
                                    ' <td>'+data[i].warehouse.name+'</td>'+
                                    ' <td >'+data[i].storage_unit_name+'</td>'+
                                    ' <td>'+data[i].storagetype.name+'</td>'+
                                    ' <td >'+data[i].storagelevel.name+'</td>'+
                                    ' <td >'+data[i].storagesize.unit_type_name+'</td>'+
                                    ' <td >'+data[i].location+'</td>'+
                                    ' <td >'+data[i].price+'</td>'+
                                    ' <td >'+
                                    ' <span class="badge '+(data[i].status =='booked'? "badge-success":data[i].status == 'vacant'? "badge-danger":"badge-primary")+' ">'+data[i].status+'</span>'+
                                    ' </td>'+
                                    ' </tr>';
                            }
                        }else {
                            toastr.error('Result Not Found');
                        }
                        $('#tbodyrow').html(html);
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



