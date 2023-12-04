@extends('backend.layouts.app')
@section('title', '| Contract')
@section('content')
    <div class="components-preview wide-md mx-auto">
        <div class="nk-block nk-block-lg">
            <div class="nk-block-head">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h3 class="nk-block-title page-title">Contract List</h3>
                        <div class="nk-block-des text-soft">

                        </div>
                    </div>

                    <div class="nk-block-head-content">
                        <div class="toggle-wrap nk-block-tools-toggle">
                            <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-more-v"></em></a>
                            <div class="toggle-expand-content" data-content="pageMenu">
                                <ul class="nk-block-tools g-3">
                                    <li class="nk-block-tools-opt">
                                        <a href="{{route('create-contract')}}" class="btn btn-primary btn-sm" ><em class="icon ni ni-plus"></em><span>Add New Contract</span></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="card card-preview">
                @if (session()->has('success'))
                    <div class="alert alert-success">
                        @if(is_array(session('success')))
                            <ul>
                                @foreach (session('success') as $message)
                                    <li>{{ $message }}</li>
                                @endforeach
                            </ul>
                        @else
                            {{ session('success') }}
                        @endif
                    </div>
                @endif
                <div class="card-inner">
                    <table  class=" table table-md datatable-init-export nk-tb-list nk-tb-ulist" data-auto-responsive="true"  id="datatable" >
                        <thead>
                            <tr class="nk-tb-item nk-tb-head">
                            <th class="nk-tb-col text-left"><span class="sub-text">ID</span></th>
                            <th class="nk-tb-col"><span class="sub-text">Subject</span></th>
                            <th class="nk-tb-col"><span class="sub-text">Customer</span></th>
                            <th class="nk-tb-col"><span class="sub-text">Estiamte</span></th>
                            <th class="nk-tb-col"><span class="sub-text">Value</span></th>
                            <th class="nk-tb-col"><span class="sub-text">Start</span></th>
                            <th class="nk-tb-col"><span class="sub-text">End</span></th>
                            <th class="nk-tb-col"><span class="sub-text">Status</span></th>
                            <th class="nk-tb-col"><span class="sub-text">Sign</span></th>
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


    <script>
        $(document).ready(function() {

            $('#CountryForm').on('submit', function(e) {

                e.preventDefault();
                var formData=$('#CountryForm').serialize()
                $.ajax({
                    type: "get",
                    url: '{{ url('admin/save-storage-type') }}',
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

            getAllCities();
            function getAllCities() {

                $.ajax({

                    url: '{{ url('admin/get-contracts') }}',
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
                                ' <td class="nk-tb-col nk-tb-col-tools"><a href={{url('admin/contract/detail')}}/' + data[i].id + '>'+data[i].subject+'</a></td>'+
                                ' <td class="nk-tb-col nk-tb-col-tools"><a href={{url('admin/customer/profile')}}/' + data[i].customer.id + '>'+data[i].customer.customer_name+'</a></td>'+
                                ' <td class="nk-tb-col nk-tb-col-tools"><a href={{url('admin/estimate/detail')}}/' + data[i].estimate.id + '>'+data[i].estimate.id+'-'+data[i].estimate.storageunit.storage_unit_name+'/'+data[i].estimate.term_length.title+'</a></td>'+
                                ' <td class="nk-tb-col nk-tb-col-tools">'+data[i].contract_value+'</td>'+
                                // ' <td class="nk-tb-col nk-tb-col-tools">'+data[i].contract_type+'</td>'+
                                ' <td class="nk-tb-col nk-tb-col-tools">'+data[i].start_date+'</td>'+
                                ' <td class="nk-tb-col nk-tb-col-tools">'+data[i].end_date+'</td>'+
                                '<td class="nk-tb-col nk-tb-col-tools" >'+
                                ' <span class="badge '+((data[i].status == 'Approved' ? "badge-success":"badge-danger"))+'">'+data[i].status+'</span>'+
                                ' </td>'+
                                '<td class="nk-tb-col nk-tb-col-tools" >'+
                                ' <span class="badge '+((data[i].is_signed == 'Signed')? 'badge-success':'badge-danger') +' ">'+data[i].is_signed+'</span>'+
                                ' </td>'+
                                '  <td class="nk-tb-col nk-tb-col-tools">'+
                                ' <ul class="nk-tb-actions gx-1">'+
                                '  <li>'+
                                ' <div class="drodown">'+
                                '  <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>'+
                                ' <div class="dropdown-menu dropdown-menu-right">'+
                                '<ul class="link-list-opt no-bdr">'+
                                // '<li><a href="#" class="btn-approve" data='+data[i].id+'><em class="icon ni ni-edit"></em><span>Approve</span></a></li>'+
                                '<li><a href={{url('/contract-to-customer')}}/'+data[i].id +' ><em class="icon ni ni-edit"></em><span>View as Customer</span></a></li>'+
                                '<li><a href={{url('admin/contract-pdf')}}/'+data[i].id +' ><em class="icon ni ni-edit"></em><span>View as PDF</span></a></li>'+
                                {{--'<li><a href={{url('admin/convert-invoice')}}/'+data[i].id +' ><em class="icon ni ni-edit"></em><span>Convert to Invoice </span></a></li>'+--}}
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

            $('#countryTable').on('click', '.btn-approve', function() {
                var id = $(this).attr('data');
                $.ajax({
                    url: '{{ url('admin/approve-contract') }}',
                    type: 'get',
                    async: false,
                    dataType: 'json',
                    data: { id: id},
                    success: function(data) {
                        if (data.success) {
                            getAllCities();
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



