@extends('backend.layouts.app')
@section('title', '| Estimate')
@section('content')
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-block-head">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h4 class="title nk-block-title">Estimate Information</h4>
                    </div>
                    <a href="{{url("admin/estimate")}}" class="btn btn-primary btn-sm d-none d-md-inline-flex"><em class="icon ni ni-arrow-left"></em><span>Back</span></a>
                </div>
            </div>
            <div class="nk-content-body">
                <div class="nk-block">
                    @isset($data)
                        <div class="card">
                            <div class="card-aside-wrap">
                                <div class="card-inner card-inner-lg">
                                    <div class="nk-block-head">
                                        <div class="nk-block-between g-3">
                                            <div class="nk-block-head-content">
                                                <h4 class="nk-block-title page-title">Estiamte <strong class="text-primary small">#{{$data['estimate'][0]->id}}</strong></h4>
                                                <div class="nk-block-des text-soft">
                                                    <ul class="list-inline">
                                                        <li>Created At: <span class="text-base">{{$data['estimate'][0]->created_at}}</span></li>
                                                        <span class="badge {{(($data['estimate'][0]->status == 'Approved') ? " badge-success":"badge-danger")}}">{{$data['estimate'][0]->status}}</span>
                                                    </ul>
                                                </div>
                                            </div>
                                            @isset($data['appSettings'])
                                                @if($data['appSettings'][0]->value == auth()->id() && $data['estimate'][0]->status == 'Not Approved' )
                                                       <div class="d-flex align-center">
                                                <div class="nk-tab-actions me-n1">
                                                    <a class="btn  btn-primary btn-approve " title="Approve" id="btn-approve" href="#">Approve</a>
                                                    <a class="btn btn-danger "  data-toggle="modal" data-target="#declineModal" title="Decline" href="#">Decline</a>
                                                </div>
                                                <div class="nk-block-head-content align-self-start d-lg-none">
                                                    <a href="#" class="toggle btn btn-icon btn-trigger" data-target="userAside"><em class="icon ni ni-menu-alt-r"></em></a>
                                                </div>
                                            </div>
                                                @elseif($data['appSettings'][1]->value == auth()->id() && $data['estimate'][0]->status == 'Approved Level 1' )
                                                    <div class="d-flex align-center">
                                                        <div class="nk-tab-actions me-n1">
                                                            <a class="btn  btn-primary btn-approve" title="Approve" id="btn-approve" href="#">Approve</a>
                                                            <a class="btn btn-danger "  data-toggle="modal" data-target="#declineModal" title="Decline" href="#">Decline</a>
                                                        </div>
                                                        <div class="nk-block-head-content align-self-start d-lg-none">
                                                            <a href="#" class="toggle btn btn-icon btn-trigger" data-target="userAside"><em class="icon ni ni-menu-alt-r"></em></a>
                                                        </div>
                                                    </div>
                                                @elseif($data['appSettings'][2]->value == auth()->id() && $data['estimate'][0]->status == 'Approved Level 2' )
                                                    <div class="d-flex align-center">
                                                        <div class="nk-tab-actions me-n1">
                                                            <a class="btn  btn-primary btn-approve" title="Approve" id="btn-approve" href="#">Approve</a>
                                                            <a class="btn btn-danger "  data-toggle="modal" data-target="#declineModal" title="Decline" href="#">Decline</a>
                                                        </div>
                                                        <div class="nk-block-head-content align-self-start d-lg-none">
                                                            <a href="#" class="toggle btn btn-icon btn-trigger" data-target="userAside"><em class="icon ni ni-menu-alt-r"></em></a>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endisset
                                        </div>
                                    </div><!-- .nk-block-head -->
                                    <div class="nk-block">
                                        <div class="invoice">
                                            <div class="invoice-action">
                                                <a class="btn btn-icon btn-lg btn-white btn-dim btn-outline-primary" onclick="window.print()" target=""><em class="icon ni ni-printer-fill"></em></a>
                                            </div><!-- .invoice-actions -->
                                            <div class="invoice-wrap">
                                                <div class="invoice-brand text-center">
                                                    <img src="./images/logo-dark.png" srcset="./images/logo-dark2x.png 2x" alt="">
                                                </div>
                                                <div class="invoice-head">
                                                    <div class="invoice-contact">
                                                        <span class="overline-title">Estimate To</span>
                                                        <div class="invoice-contact-info">
                                                            <h4 class="title">{{$data['estimate'][0]->f_name}} {{$data['estimate'][0]->l_name}}</h4>
                                                            <ul class="list-plain">
                                                                <li><em class="icon ni ni-emails-fill"></em><span>{{$data['estimate'][0]->email}}</span></li>
                                                                <li><em class="icon ni ni-call-fill"></em><span>{{$data['estimate'][0]->phone}}</span></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="invoice-desc">
                                                        <h4 class="title">Estimate</h4>
                                                        <ul class="list-plain">
                                                            <li class="invoice-id"><span>Estiamte ID</span>:<span>{{$data['estimate'][0]->id}}</span></li>
                                                            <li class="invoice-date"><span>Date</span>:<span>{{$data['estimate'][0]->created_at}}</span></li>
                                                        </ul>
                                                    </div>
                                                </div><!-- .invoice-head -->
                                                <div class="invoice-bills">
                                                    <div class="table-responsive">
                                                        <table class="table table-striped">
                                                            <thead>
                                                            <tr>
                                                                <th class="w-150px">Item ID</th>
                                                                <th class="w-60">Description</th>
                                                                <th></th>
                                                                <th></th>
                                                                <th>Amount</th>
                                                            </tr>
                                                            </thead>
                                                            @php
                                                            $storageunitotal = $data['estimate'][0]->unit_price * $data['estimate'][0]->termLength->term_period;
                                                            @endphp
                                                            <tbody>
                                                            <tr>
                                                                <td>{{$data['estimate'][0]->id}}</td>
                                                                <td>{{$data['estimate'][0]->storageunit->storage_unit_name}}/{{$data['estimate'][0]->termLength->title}}</td>
                                                                <td></td>
                                                                <td></td>
                                                                <td id="storageTotal">{{ $storageunitotal - ($storageunitotal * $data['estimate'][0]->termLength->discount_percentage/100)}} </td>
                                                            @if($data['estimate'][0]->estimateAddon)
                                                                @php $addonAmount = 0; @endphp
                                                                @foreach($data['estimate'][0]->estimateAddon as $addon)
                                                                    @php
                                                                        $addonAmount += $addon->price;
                                                                    @endphp
                                                                    <tr>
                                                                        <td>{{$addon->id}}</td>
                                                                        <td>{{$addon->addon->name}}</td>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td>{{$addon->price}}</td>
                                                                    </tr>
                                                                @endforeach
                                                            @endif
                                                            @if($data['estimate'][0]->insurace !='nothanks')
                                                                <tr>
                                                                    <td>{{$data['estimate'][0]->id}}</td>
                                                                    <td>Insurance</td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td>25</td>
                                                                </tr>
                                                            @endif
                                                            </tbody>
                                                            <tfoot>
                                                            <tr>
                                                                <td colspan="2"></td>
                                                                <td colspan="2">Subtotal</td>
                                                                <td>{{ $storageunitotal - ($storageunitotal * $data['estimate'][0]->termLength->discount_percentage/100)  + $addonAmount + (($data['estimate'][0]->insurance !='nothanks')? 25:0) }} AED</td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2"></td>
                                                                <td colspan="2">TAX</td>
                                                                <td>0.00 AED</td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2"></td>
                                                                <td colspan="2">Grand Total</td>
                                                                <td>{{ $storageunitotal - ($storageunitotal * $data['estimate'][0]->termLength->discount_percentage/100)  + $addonAmount + (($data['estimate'][0]->insurance !='nothanks')? 25:0)}} AED</td>
                                                            </tr>
                                                            </tfoot>
                                                        </table>
                                                        <div class="nk-notes ff-italic fs-12px text-soft"> Estimate was created on a computer and is valid without the signature and seal. </div>
                                                    </div>
                                                </div><!-- .invoice-bills -->
                                            </div><!-- .invoice-wrap -->
                                        </div><!-- .invoice -->
                                    </div><!-- .nk-block -->
                                </div>
                                <div class="modal fade" tabindex="-1" role="dialog" id="declineModal" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title text-capitalize" id="ajax_model_title">Decline Estimate</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body" data-form="ajax-form-modal">
                                                <form method="post" action="{{ url('admin/decline-estimate') }}" id="DeclineForm">
                                                    @csrf
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <input class="form-control" type="hidden" name="id" value="{{$data['estimate'][0]->id}}" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label for="decline_reason"> Decline Reason Note <span class="text-danger"></span></label>
                                                                <textarea name="decline_reason" id="decline_reason" class="form-control" placeholder="Enter estimate decline reason note...."></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="float-right">
                                                        <button class="btn btn-primary mt-2 btn-submit" type="submit">Save</button>
                                                    </div>
                                                </form>

                                            </div>
                                        </div><!-- .modal-content -->
                                    </div><!-- .modla-dialog -->
                                </div>
                                @include('backend.estimate.aside')
                                <!-- card-aside -->
                            </div>
                            <!-- .card-aside-wrap -->
                        </div>
                    @endisset
                    <!-- .card -->
                </div>
                <!-- .nk-block -->
            </div>

        </div>
    </div>


    <script>
        $(document).ready(function() {

            $('#DeclineForm').on('submit', function(e) {

                e.preventDefault();
                var formData=$('#DeclineForm').serialize()
                $.ajax({
                    type: "get",
                    url: '{{ url('admin/decline-estimate') }}',
                    data: formData,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('.btn-submit').text('Approving...');
                        $(".btn-submit").prop("disabled", true);
                    },
                    success: function(data) {

                        if (data.success) {
                            $('#DeclineForm')[0].reset();
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



            $('#btn-approve').on('click', function (e) {
                e.preventDefault();
                var id = $('#estimate_id').val();
                $.ajax({
                    url: '{{ url('admin/approve-estimate') }}',
                    type: 'get',
                    async: false,
                    dataType: 'json',
                    data: {id: id},
                    beforeSend: function() {
                        $('.btn-approve').text('Saving...');
                        $(".btn-approve").prop("disabled", true);
                    },
                    success: function (data) {
                        if (data.success) {
                            // getAllCities();
                            // $('.close').click();
                            toastr.success(data.success);
                            window.location.reload();
                        } else {
                            toastr.error(data.error);
                        }
                    },
                    complete: function(data) {
                        $('.btn-approve').text('Approve');
                        $(".btn-approve").prop("disabled", true);
                    },
                    error: function () {
                        toastr.error('something went wrong');
                        $('.btn-approve').text('Approve');
                        $(".btn-approve").prop("disabled", true);
                    }

                });


            });

        });
        $('#changeStatus').on('submit', function(e) {

            e.preventDefault();
            var formData=$('#changeStatus').serialize()
            $.ajax({
                type: "get",
                url: '{{ url('admin/change-lead-status') }}',
                data: formData,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('.btn-submit').text('Saving...');
                    $(".btn-submit").prop("disabled", true);
                },
                success: function(data) {

                    if (data.success) {

                        $('#changeStatus')[0].reset();
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
                    location.reload();
                },

                error: function() {;
                    toastr.error('any technical error');
                    $('.btn-submit').text('Save');
                    $(".btn-submit").prop("disabled", false);
                }
            });


        });

        $('#changeSource').on('submit', function(e) {

            e.preventDefault();
            var formData=$('#changeSource').serialize()
            $.ajax({
                type: "get",
                url: '{{ url('admin/change-lead-source') }}',
                data: formData,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('.btn-submit').text('Saving...');
                    $(".btn-submit").prop("disabled", true);
                },
                success: function(data) {

                    if (data.success) {

                        $('#changeSource')[0].reset();
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
                    location.reload();
                },

                error: function() {;
                    toastr.error('any technical error');
                    $('.btn-submit').text('Save');
                    $(".btn-submit").prop("disabled", false);
                }
            });


        });

        $('#changeAssigneeForm').on('submit', function(e) {

            e.preventDefault();
            var formData=$('#changeAssigneeForm').serialize()
            $.ajax({
                type: "get",
                url: '{{ url('admin/change-lead-assignee') }}',
                data: formData,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('.btn-submit').text('Saving...');
                    $(".btn-submit").prop("disabled", true);
                },
                success: function(data) {

                    if (data.success) {

                        $('#changeAssigneeForm')[0].reset();
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
                    location.reload();
                },

                error: function() {;
                    toastr.error('any technical error');
                    $('.btn-submit').text('Save');
                    $(".btn-submit").prop("disabled", false);
                }
            });


        });
    </script>
@endsection



