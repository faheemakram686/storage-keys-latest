@extends('backend.layouts.app')
@section('title', '| Lead')
@section('content')
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-block-head">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h4 class="title nk-block-title">Lead Information</h4>
                    </div>
                    <a href="{{url("admin/leads")}}" class="btn btn-primary btn-sm d-none d-md-inline-flex"><em class="icon ni ni-arrow-left"></em><span>Back</span></a>
                </div>
            </div>
            <div class="nk-content-body">
                <div class="nk-block">
                    @isset($data)
{{--                    {{dd($data)}}--}}
                    <div class="card">
                        <div class="card-aside-wrap">
                            <div class="card-inner card-inner-lg">
                                <div class="nk-block-head">
                                    <div class="nk-block-between d-flex justify-content-between">
                                        <div class="nk-block-head-content">
                                            <h4 class="nk-block-title">Lead Information</h4>
                                            <div class="nk-block-des">
                                                <p>Basic info about lead.</p>
                                            </div>
                                        </div>
                                        <div class="d-flex align-center">
                                            <div class="nk-tab-actions me-n1">
                                                <a class="btn btn-icon btn-trigger" title="Edit Lead"  href={{url('admin/edit-lead/'.$data['lead'][0]->id)}}><em class="icon ni ni-edit"></em></a>
                                            </div>
                                            <div class="nk-block-head-content align-self-start d-lg-none">
                                                <a href="#" class="toggle btn btn-icon btn-trigger" data-target="userAside"><em class="icon ni ni-menu-alt-r"></em></a>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- .nk-block-head -->
                                <div class="nk-block">
                                    <div class="nk-data data-list">
                                        <div class="data-head">
                                            <h6 class="overline-title">Lead Information</h6>
                                        </div>
                                        @if($data['lead'][0]->company_name != null)
                                        <div class="data-item">
                                            <div class="data-col">
                                                <span class="data-label">Company Name</span>
                                                <span class="data-value">{{$data['lead'][0]->company_name}}</span>
                                            </div>
                                        </div><!-- data-item -->
                                        @endif
                                        <div class="data-item">
                                            <div class="data-col">
                                                <span class="data-label">Full Name</span>
                                                <span class="data-value">{{$data['lead'][0]->f_name}} {{$data['lead'][0]->l_name}}</span>
                                            </div>
                                        </div><!-- data-item -->
                                        <div class="data-item">
                                            <div class="data-col">
                                                <span class="data-label">Email</span>
                                                <span class="data-value">{{$data['lead'][0]->email}}</span>
                                            </div>
                                        </div><!-- data-item -->
                                        <div class="data-item">
                                            <div class="data-col">
                                                <span class="data-label">Phone Number</span>
                                                <span class="data-value text-soft">{{$data['lead'][0]->phone}}</span>
                                            </div>
                                        </div><!-- data-item -->
                                        <div class="data-item">
                                            <div class="data-col">
                                                <span class="data-label">Requested Date</span>
                                                <span class="data-value">{{$data['lead'][0]->r_date}}</span>
                                            </div>
                                        </div><!-- data-item -->
                                        @php
                                         $totalprice = $data['su'][0]->price * $data['lead'][0]->termLength->term_period;
                                        @endphp
                                        <div class="data-item">
                                            <div class="data-col">
                                                <span class="data-label">Lead Cost</span>
                                                <span class="data-value">{{$data['lead'][0]->termLength->title}} - {{$totalprice -  ( $totalprice *  $data['lead'][0]->termLength->discount_percentage / 100 )}}/mo AED</span>
{{--                                                <span class="data-value">{{$data['lead'][0]->termLength->title}} - {{($data['lead'][0]->unit_price * 3) - ($data['lead'][0]->unit_price * 4/100)}}/mo AED</span>--}}
                                            </div>
                                        </div><!-- data-item -->
                                        <div class="data-item" data-tab-target="#address">
                                            <div class="data-col">
                                                <span class="data-label">Storage Unit:</span>
                                                <span class="data-value">{{$data['su'][0]->storage_unit_name}} , {{$data['su'][0]->warehouse->name}}<br>{{$data['su'][0]->warehouse->loc->loc_name}}, {{$data['su'][0]->warehouse->loc->city->city_name}}, {{$data['su'][0]->warehouse->loc->city->country->name}}</span>
                                            </div>
                                        </div><!-- data-item -->
                                    </div><!-- data-list -->
                                    <div class="nk-data data-list">
                                        <div class="data-head">
                                            <h6 class="overline-title">General Information</h6>
                                        </div>
                                        <div class="data-item">
                                            <div class="data-col">
                                                <span class="data-label">Status</span>
                                                <span class="data-value"><p class="badge rounded-pill text-white bg-success">{{$data['lead'][0]->leadStatus->title}}</p></span>
                                            </div>
                                            <div class="data-col data-col-end"><a data-toggle="modal" href="#changeinfo" class="link link-primary">Change Status</a></div>
                                        </div><!-- data-item -->
                                        <div class="data-item">
                                            <div class="data-col">
                                                <span class="data-label">Lead Source</span>
                                                <span class="data-value">{{$data['lead'][0]->leadsource->title}}</span>
                                            </div>
                                            <div class="data-col data-col-end"><a data-toggle="modal" href="#changesource" class="link link-primary">Change</a></div>
                                        </div><!-- data-item -->
                                        <div class="data-item">
                                            <div class="data-col">
                                                <span class="data-label">Created At</span>
                                                <span class="data-value">{{$data['lead'][0]->created_at}}</span>
                                            </div>
                                            <div class="data-col data-col-end"><a data-bs-toggle="modal" href="#modalDate" class="link link-primary"></a></div>
                                        </div><!-- data-item -->
                                        <div class="data-item">
                                            <div class="data-col">
                                                <span class="data-label">Responsible User</span>
                                                <span class="data-value">{{$data['lead'][0]->userresponsible->first_name}} {{$data['lead'][0]->userresponsible->last_name}}</span>
                                            </div>
                                            <div class="data-col data-col-end"><a data-toggle="modal" href="#changeassignee" class="link link-primary">Change</a></div>
                                        </div><!-- data-item -->
                                        <div class="data-item">
                                            <div class="data-col">
                                                <span class="data-label">Lead Rating</span>
                                                <span class="data-value">{{$data['lead'][0]->rating}}</span>
                                            </div>
                                            <div class="data-col data-col-end"><a data-bs-toggle="modal" href="#modalDate" class="link link-primary"></a></div>
                                        </div><!-- data-item -->
                                    </div><!-- data-list -->
                                </div><!-- .nk-block -->
                            </div>
                            @include('backend.leads.aside')
                            <!-- card-aside -->
                        </div>
                        <!-- .card-aside-wrap -->
                    </div>
                    @endisset
                    <!-- .card -->
                </div>
                <!-- .nk-block -->
            </div>
            <div class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" id="changeinfo" aria-hidden="true">
                <div class="modal-dialog modal-sm" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title text-capitalize" id="ajax_model_title">Change Lead Status</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" data-form="ajax-form-modal">
                            <form method="post" action="{{ url('admin/change-lead-status') }}" id="changeStatus">
                                @csrf
                                <div class="row">
                                    <input class="form-control" type="hidden" name="lead_id" value="{{$data['lead'][0]->id}}" required>
                                    <div class="col-md-12">
                                        <label class="lbl" >Lead Status</label>
                                        <select class="selectpicker form-control  form-select" name="lead_status" id="lead_status">
                                            <option value="">Select Lead Status</option>
                                            @isset($data)
                                                @foreach ($data['status'] as $sl)
                                                    <option value="{{ $sl->id }}" {{$sl->id == $data['lead'][0]->leadStatus->id  ? 'selected' : ' '}} >{{ $sl->title }}</option>
                                                @endforeach
                                            @endisset
                                        </select>
                                    </div>
                                </div>

                                <div class="float-right">
                                    <button class="btn btn-primary mt-2 btn-submit" type="submit">Save Changes</button>
                                </div>
                            </form>

                        </div>
                    </div><!-- .modal-content -->
                </div><!-- .modla-dialog -->
            </div>
            <div class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" id="changesource" aria-hidden="true">
                <div class="modal-dialog modal-sm" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title text-capitalize" id="ajax_model_title">Change Lead Source</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" data-form="ajax-form-modal">
                            <form method="post" action="{{ url('admin/change-lead-source') }}" id="changeSource">
                                @csrf
                                <div class="row">
                                    <input class="form-control" type="hidden" name="lead_id" value="{{$data['lead'][0]->id}}" required>
                                    <div class="col-md-12">
                                        <label class="lbl" >Lead Status</label>
                                        <select class="selectpicker form-control  form-select" name="lead_source" id="lead_source">
                                            <option value="">Select Lead Source</option>
                                            @isset($data)
                                                @foreach ($data['source'] as $sl)
                                                    <option value="{{ $sl->id }}" {{$sl->id == $data['lead'][0]->leadSource->id  ? 'selected' : ' '}} >{{ $sl->title }}</option>
                                                @endforeach
                                            @endisset
                                        </select>
                                    </div>
                                </div>

                                <div class="float-right">
                                    <button class="btn btn-primary mt-2 btn-submit" type="submit">Save Changes</button>
                                </div>
                            </form>

                        </div>
                    </div><!-- .modal-content -->
                </div><!-- .modla-dialog -->
            </div>
            <div class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" id="changeassignee" aria-hidden="true">
                <div class="modal-dialog modal-sm" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title text-capitalize" id="ajax_model_title">Change Responsible User</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" data-form="ajax-form-modal">
                            <form method="post" action="{{ url('admin/change-lead-assignee') }}" id="changeAssigneeForm">
                                @csrf
                                <div class="row">
                                    <input class="form-control" type="hidden" name="lead_id" value="{{$data['lead'][0]->id}}" required>
                                    <div class="col-md-12">
                                        <label class="lbl" >User responsible</label>
                                        <select class="selectpicker form-control  form-select" name="lead_assignee" id="lead_assignee">
                                            <option value="">Choose One</option>
                                            @isset($data)
                                                @foreach ($data['user'] as $sl)
                                                    <option value="{{ $sl->id }}" {{$sl->id == $data['lead'][0]->user_res_id  ? 'selected' : ' '}} >{{ $sl->first_name }} {{ $sl->last_name }}</option>
                                                @endforeach
                                            @endisset
                                        </select>
                                    </div>
                                </div>

                                <div class="float-right">
                                    <button class="btn btn-primary mt-2 btn-submit" type="submit">Save Changes</button>
                                </div>
                            </form>

                        </div>
                    </div><!-- .modal-content -->
                </div><!-- .modla-dialog -->
            </div>
        </div>
    </div>

    <script>
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



