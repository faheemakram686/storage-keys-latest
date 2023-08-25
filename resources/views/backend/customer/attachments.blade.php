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
                    <a href="{{url("admin/leads")}}" class="btn btn-primary btn-sm d-none d-md-inline-flex"><em class="icon ni ni-arrow-left"></em><span>Back</span></a>
                </div>
            </div>
            <div class="nk-content-body">
                <div class="nk-block">
{{--                    @isset($data)--}}
                        <div class="card">
                            <div class="card-aside-wrap">
                                <div class="card-inner card-inner-lg">
                                    <div class="nk-block-head">
                                        <div class="nk-block-between d-flex justify-content-between">
                                            <div class="nk-block-head-content">
                                                <h4 class="nk-block-title">Attachments</h4>
                                                <div class="nk-block-des">

                                                </div>
                                            </div>
                                            <div class="d-flex align-center">
                                                <div class="nk-tab-actions me-n1">
                                                    <a href="#" class="btn btn-primary btn-sm"  data-toggle="modal" data-target="#addTask"><em class="icon ni ni-plus"></em><span>Add Attachment</span></a>
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
                                                    <th class="nk-tb-col"><span class="sub-text">Name</span></th>
                                                    <th class="nk-tb-col"><span class="sub-text">Document Type</span></th>
                                                    <th class="nk-tb-col"><span class="sub-text"></span>Created At</th>
                                                    <th class="nk-tb-col"><span class="sub-text">Status</span></th>
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
{{--                    @endisset--}}
                    <!-- .card -->
                </div>
                <!-- .nk-block -->
                <div class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" id="addTask" aria-hidden="true">
                    <div class="modal-dialog modal-md" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title text-capitalize" id="ajax_model_title">Add New Attachment</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body" data-form="ajax-form-modal">
                                <form method="post" action="{{ url('admin/save-common-attachment') }}" id="AttachmentForm"  enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="estimate_id" value="{{$data['customer']->id}}">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Select Attachment Type <span class="text-danger">*</span></label>
                                                <select name="type"  class="form-control" readonly>
                                                    <option value="">Choose One</option>
                                                    <option value="lead" >Lead</option>
                                                    <option value="customer" selected>Customer</option>
                                                    <option value="estimate"  >Estimate</option>
                                                    <option value="contract" >Contract</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Select Attachment <span class="text-danger">*</span></label>
                                                <input class="form-control" type="file" name="files" required>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="float-right">
                                        <a href="#" class=" btn btn-primary mt-2 btn-back" data-toggle="modal" data-dismiss="modal" >Cancel</a>
                                        <button class="btn btn-primary mt-2 btn-submit" type="submit">Upload</button>
                                    </div>
                                </form>
                            </div>
                        </div><!-- .modal-content -->
                    </div><!-- .modla-dialog -->
                </div>

            </div>

        </div>
    </div>

    <script>
        $(document).ready(function() {



            $('#AttachmentForm').on('submit', function(e) {

                e.preventDefault();

                let formData = new FormData($('#AttachmentForm')[0])

                $.ajax({
                    type: "POST",
                    url: '{{ url('admin/save-common-attachment') }}',
                    data: formData ,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('.btn-submit').text('Uploading...');
                        $(".btn-submit").prop("disabled", true);
                    },
                    success: function(data) {
                        console.log(data);
                        if (data.success) {
                            $('#AttachmentForm')[0].reset();
                            $('.close').click();
                            getAllCities();
                            toastr.success(data.success);

                        }
                        if (data.errors) {
                            toastr.error(data.errors);
                            $('.btn-submit').text('Upload Documents');
                            $(".btn-submit").prop("disabled", false);
                        }
                    },

                    complete: function(data) {
                        $(".btn-submit").html("Upload Documents");
                        $(".btn-submit").prop("disabled", false);
                    },

                    error: function() {

                        toastr.error('any technical error');
                        $('.btn-submit').text('Upload Documents');
                        $(".btn-submit").prop("disabled", false);
                    }
                });


            });


            getAllCities();
            function getAllCities() {
                var type_id=$('#customer_id').val();
                var type='customer';
                $.ajax({

                    url: '{{ url('admin/get-estimate-attachments') }}',
                    type: 'get',
                    async: false,
                    dataType: 'json',
                    data: { type_id:type_id,type:type},

                    success: function(data) {
                        console.log(data);

                        var html = '';
                        var i;
                        var c = 0;

                        for (i = 0; i < data.length; i++) {
                            c++;
                            html += ' <tr class="nk-tb-item odd">'+
                                ' <td class="nk-tb-col nk-tb-col-tools sorting_1">'+c+'</td>'+
                                {{--' <td class="nk-tb-col nk-tb-col-tools"><a href={{url('admin/contact')}}/' + data[i].id + ' class="btn-edit" data='+data[i].id+'>' + data[i].first_name + ' ' + data[i].last_name + '</a></td>' +--}}
                                ' <td class="nk-tb-col nk-tb-col-tools"><a href="{{ asset('storage/files/') }}/' + data[i].name + '" class="btn-edit" data='+data[i].id+' >' + data[i].name + '</a></td>' +
                                ' <td class="nk-tb-col nk-tb-col-tools">' + data[i].require_document.title + '</td>'+
                                ' <td class="nk-tb-col nk-tb-col-tools">' + data[i].created_at + '</td>'+
                                '<td class="nk-tb-col nk-tb-col-tools" >'+
                                ' <span class="badge badge-success">'+data[i].status+'</span>'+
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


        });
    </script>
@endsection



