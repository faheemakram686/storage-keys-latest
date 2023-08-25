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
                                                <h4 class="nk-block-title">Contracts</h4>
                                                <div class="nk-block-des">

                                                </div>
                                            </div>
                                            <div class="d-flex align-center">
                                                <div class="nk-tab-actions me-n1">
                                                    <a href="#" class="btn btn-primary btn-sm"  data-toggle="modal" data-target="#addTask"><em class="icon ni ni-plus"></em><span>Add Contract</span></a>
{{--                                                <a class="btn btn-icon btn-trigger" data-bs-toggle="modal" href="#profile-edit"><em class="icon ni ni-edit"></em></a>--}}
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
                                                    <th class="nk-tb-col text-left"><span class="sub-text">ID</span></th>
                                                    <th class="nk-tb-col"><span class="sub-text">Subject</span></th>
                                                    <th class="nk-tb-col"><span class="sub-text">Estimate</span></th>
                                                    <th class="nk-tb-col"><span class="sub-text">Start Date</span></th>
                                                    <th class="nk-tb-col"><span class="sub-text">Due Date</span></th>
                                                    <th class="nk-tb-col"><span class="sub-text">Value</span></th>
                                                    <th class="nk-tb-col"><span class="sub-text">Type</span></th>
                                                    <th class="nk-tb-col"><span class="sub-text">Status</span></th>
                                                    <th class="nk-tb-col"><span class="sub-text">Action</span></th>
                                                </tr>
                                                </thead>
                                                <tbody id="countryTable">
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                @include('backend.customer.aside')
                                <!-- card-aside -->
                            </div>
                            <!-- .card-aside-wrap -->
                        </div>
                    @endisset
                    <!-- .card -->
                </div>
                <!-- .nk-block -->
                <div class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" id="addTask" aria-hidden="true">
                    <div class="modal-dialog modal-md" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title text-capitalize" id="ajax_model_title">Add New Contract</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body" data-form="ajax-form-modal">
                                <form method="post" action="{{ url('admin/save-contract') }}" id="TaskForm">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="lbl" >Customer</label>
                                                <select class="selectpicker form-control  form-select" name="customer_id" >
                                                    <option value="" selected >Select One</option>
                                                    <option value="{{$data['customer']->id}}" selected>{{$data['customer']->company_name}}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="lbl" >Estimate</label>
                                                <select class="select2 form-control" name="estimate_id" >
                                                    <option value="" selected >Select Estimate</option>
                                                    @isset($data)
                                                        @foreach ($data['estimate'] as $estiamte)
                                                            <option value="{{ $estiamte->id }}"  > {{ $estiamte->id }} {{$estiamte->f_name }} {{$estiamte->l_name }}</option>
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
                        </div><!-- .modal-content -->
                    </div><!-- .modla-dialog -->
                </div>
            </div>


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
                            getAllCities();
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
                var customer_id=$('#customer_id').val();

                $.ajax({

                    url: '{{ url('admin/get-customer-contracts') }}',
                    type: 'get',
                    async: false,
                    dataType: 'json',
                    data: { customer_id:customer_id,},

                    success: function(data) {
                        console.log(data);

                        var html = '';
                        var i;
                        var c = 0;

                        for (i = 0; i < data.length; i++) {
                            c++;
                            html += ' <tr class="nk-tb-item odd">'+
                                ' <td class="nk-tb-col nk-tb-col-tools sorting_1">'+c+'</td>'+
                                ' <td class="nk-tb-col nk-tb-col-tools"><a href={{url('admin/contract/detail')}}/' + data[i].id + ' class="btn-edit" data='+data[i].id+'>' + data[i].subject + '</a></td>' +
                                ' <td class="nk-tb-col nk-tb-col-tools"><a href={{url('admin/estimate/detail')}}/' + data[i].estimate_id + ' class="btn-edit" data='+data[i].estimate_id+' >' + data[i].estimate.id + ' ' + data[i].estimate.storageunit.storage_unit_name + '/' + data[i].estimate.term_length.title + '</a></td>' +
                                ' <td class="nk-tb-col nk-tb-col-tools">' + data[i].start_date + '</td>'+
                                ' <td class="nk-tb-col nk-tb-col-tools">' +  ((data[i].end_date == null) ? '-' : data[i].end_date) + '</td>'+
                                ' <td class="nk-tb-col nk-tb-col-tools">' + data[i].contract_value + '</td>'+
                                ' <td class="nk-tb-col nk-tb-col-tools">' + data[i].contract_type + '</td>'+
                                '<td class="nk-tb-col nk-tb-col-tools" >'+
                                ' <span class="badge badge-success">'+data[i].status+'</span>'+
                                '</td>'+
                                '  <td class="nk-tb-col nk-tb-col-tools">'+
                                ' <ul class="nk-tb-actions" gx-1 style="right: 1.0rem; !important">'+
                                '  <li>'+
                                ' <div class="drodown">'+
                                '  <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>'+
                                ' <div class="dropdown-menu dropdown-menu-right">'+
                                '<ul class="link-list-opt no-bdr">'+
                                '<li><a href={{url('admin/edit-contract')}}/'+data[i].id+' class="btn-edit" data='+data[i].id+'><em class="icon ni ni-edit"></em><span>Edit</span></a></li>'+
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
                    url: '{{ url('admin/delete-contract') }}',
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

        });
    </script>
@endsection



