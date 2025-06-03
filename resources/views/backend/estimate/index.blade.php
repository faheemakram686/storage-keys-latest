@extends('backend.layouts.app')
@section('title', '| Estimate')
@section('content')
    <div class="components-preview wide-md mx-auto">
        <div class="nk-block nk-block-lg">
            <div class="nk-block-head">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h3 class="nk-block-title page-title">Estimate List</h3>
                        <div class="nk-block-des text-soft">
                        </div>
                    </div>
                    <div class="nk-block-head-content">
                        <div class="toggle-wrap nk-block-tools-toggle">
                            <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-more-v"></em></a>
                            <div class="toggle-expand-content" data-content="pageMenu">
                                <ul class="nk-block-tools g-3">
                                    <li class="nk-block-tools-opt">
                                        <a href="{{route('create-estimate')}}" class="btn btn-primary btn-sm" ><em class="icon ni ni-plus"></em><span>Add New Estimate</span></a>
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
                            <th class="nk-tb-col text-left"><span class="sub-text">Sr no.</span></th>
                            <th class="nk-tb-col"><span class="sub-text">Estimate</span></th>
                            <th class="nk-tb-col"><span class="sub-text">Customer Name</span></th>
                            <th class="nk-tb-col"><span class="sub-text">Email</span></th>
                            <th class="nk-tb-col"><span class="sub-text">Unit-Price</span></th>
                            <th class="nk-tb-col"><span class="sub-text">Estimate Date</span></th>
                            <th class="nk-tb-col"><span class="sub-text">Expiry Date</span></th>
                            <th class="nk-tb-col"><span class="sub-text">Expire</span></th>
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
                    <h5 class="modal-title text-capitalize" id="ajax_model_title">Create Contract</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @isset($data)
                <div class="modal-body" data-form="ajax-form-modal">
                        <form method="post" action="{{ url('admin/save-contract') }}" id="TaskForm">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="lbl" >Select Customer</label>
                                        <select class="form-control select2 " data-live-search="true" name="customer_id" >
                                            <option value="" selected >Select One</option>
                                            @isset($data)
                                                @foreach($data['customer'] as $customer)
                                                    <option value="{{$customer->id}}" >{{$customer->company_name}}</option>
                                                @endforeach
                                            @endisset
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="lbl" >Estimate</label>
                                        <select class=" form-control select2 " data-live-search="true" name="estimate_id" >
                                            <option value="" selected >Select Estimate</option>
                                            @isset($data)
                                                @foreach ($data['estimate'] as $estiamte)
                                                    <option value="{{ $estiamte->id }}" selected > {{ $estiamte->id }} {{$estiamte->f_name }} {{$estiamte->l_name }}</option>
                                                @endforeach
                                            @endisset
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Subject <span class="text-danger">*</span></label>
                                        <input class="form-control" type="text" name="subject" placeholder="Subject" required>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Contract Value <span class="text-danger">*</span></label>
                                        <input class="form-control" type="text" name="contract_value" placeholder="Contract Value" required>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Contract Type <span class="text-danger"></span></label>
                                        <input class="form-control" type="text" name="contract_type" placeholder="Contract Type" >
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label> Start Date <span class="text-danger">*</span></label>
                                        <input class="form-control" type="date" name="s_date" placeholder="Start Date" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>End Date<span class="text-danger"></span></label>
                                        <input class="form-control" type="date" name="e_date" placeholder="End Date">
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label> Task Description <span class="text-danger"></span></label>
                                        <textarea class="form-control"  name="description" placeholder="Description"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Status <span class="text-danger mt-1">*</span></label>
                                        <select name="status" id="" class="form-control" required>
                                            <option value="">Choose One</option>
                                            <option value="1">Active</option>
                                            <option value="0">In-Active</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="float-right">
                                <a href="#" class=" btn btn-primary mt-2 btn-back" data-toggle="modal" data-dismiss="modal" >Cancel</a>
                                <button class="btn btn-primary mt-2 btn-submit" type="submit">Save</button>
                            </div>
                        </form>
                </div>
                @endisset
            </div><!-- .modal-content -->
        </div><!-- .modla-dialog -->
    </div>


    <script>
        $(document).ready(function() {

            $('#TaskForm').on('submit', function(e) {

                e.preventDefault();
                var formData=$('#TaskForm').serialize()
                $.ajax({
                    type: "get",
                    url: '{{ url('admin/save-contract') }}',
                    data: formData,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('.btn-submit').text('Saving...');
                        $(".btn-submit").prop("disabled", true);
                    },
                    success: function(data) {

                        if (data.success) {

                            $('#TaskForm')[0].reset();
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
                        window.location.href = "{{ url('admin/contract')}}";
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

                    url: '{{ url('admin/get-estimates') }}',
                    type: 'get',
                    async: false,
                    dataType: 'json',

                    success: function(data) {
                        console.log(data);

                        var html = '';
                        var i;
                        var c = 0;
                        var encrypt = '';
                         var id = 0;
                        var TodayDate = new Date();

                        for (i = 0; i < data.length; i++) {
                            c++;

                            var endDate= new Date(Date.parse(data[i].expiry_date));



                            html += ' <tr class="nk-tb-item odd">'+
                                ' <td class="nk-tb-col nk-tb-col-tools sorting_1">'+c+'</td>'+
                            ' <td class="nk-tb-col nk-tb-col-tools"><a href={{url('admin/estimate/detail')}}/' + data[i].id + '>'+data[i].storageunit.storage_unit_name+'/'+data[i].term_length.title+'</a></td>'+
                            ' <td class="nk-tb-col nk-tb-col-tools">'+ ((data[i].customer.customer_name == null) ? ' ' : data[i].customer.customer_name)+'</td>'+
                            ' <td class="nk-tb-col nk-tb-col-tools">'+data[i].email+'</td>'+
                            ' <td class="nk-tb-col nk-tb-col-tools">'+data[i].storageunit.price+'</td>'+ ' <td class="nk-tb-col nk-tb-col-tools">'+data[i].estimate_date+'</td>'+ ' <td class="nk-tb-col nk-tb-col-tools">'+data[i].expiry_date+'</td>'+
                            ' <td class="nk-tb-col nk-tb-col-tools" >'+
                            ' <span class="badge '+((endDate < TodayDate ? "badge-danger":"badge-success"))+'">'+((endDate < TodayDate ? "Expired":"Active"))+'</span>'+
                            ' </td>'+
                            ' <td class="nk-tb-col nk-tb-col-tools" >'+
                            ' <span class="badge '+((data[i].status == 'Approved' ? "badge-success":"badge-danger"))+'">'+data[i].status+'</span>'+
                            ' </td>'+
                            ' <td class="nk-tb-col nk-tb-col-tools">'+
                            ' <ul class="nk-tb-actions gx-1">'+
                            ' <li>'+
                            ' <div class="drodown">'+
                            ' <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>'+
                            ' <div class="dropdown-menu dropdown-menu-right">'+
                            ' <ul class="link-list-opt no-bdr">'+
                            // ' <li><a href="#" class="btn-approve" data='+data[i].id+'><em class="icon ni ni-edit"></em><span>Approve</span></a></li>'+
                            ' <li><a href={{url('/estimatetocustomer')}}/'+data[i].id +' ><em class="icon ni ni-edit"></em><span>View as customer</span></a></li>'+
                            ' <li><a href={{url('/admin/estimatePdf')}}/'+data[i].id +' ><em class="icon ni ni-edit"></em><span>View as PDF</span></a></li>'+
                            ' <li><a href="#" class="btn-edit" data='+data[i].id+' data-toggle="modal" data-target="#addCountry"><em class="icon ni ni-edit"></em><span>Create Contract</span></a></li>'+
                            ' <li><a href="#" class="btn-delete" data='+data[i].id+'><em class="icon ni ni-trash"></em><span>Delete</span></a></li>'+
                            ' </ul>'+
                            ' </div>'+
                            ' </div>'+
                            ' </li>'+
                            ' </ul>'+
                            ' </td>'+
                            ' </tr>';
                        }

                        $('#countryTable').html(html);

                    },
                    error: function() {
                        toastr.error('something went wrong');
                    }

                });
            }

            $('#countryTable').on('click', '.btn-approve', function() {
                var id = $(this).attr('data');
                $.ajax({
                    url: '{{ url('admin/approve-estimate') }}',
                    type: 'get',
                    async: false,
                    dataType: 'json',
                    data: { id: id},
                    success: function(data) {
                        if (data.success) {
                            getCountries();
                            $('.close').click();
                            toastr.success(data.success);
                        }else{
                            toastr.error(data.error);
                        }
                    },
                    error: function() {
                        toastr.error('something went wrong');
                    }

                });

            });

        });
    </script>
@endsection



