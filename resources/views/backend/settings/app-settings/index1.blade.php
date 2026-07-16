@extends('backend.layouts.app')
@section('title', '| App Settings')
@section('content')
    <div class="components-preview wide-md mx-auto">

        <div class="nk-block nk-block-lg">
            <div class="nk-block-head">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h3 class="nk-block-title page-title">App Settings</h3>
                        <div class="nk-block-des text-soft">
                        </div>
                    </div>
                    <div class="nk-block-head-content">
                        <div class="toggle-wrap nk-block-tools-toggle">
                            <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-more-v"></em></a>
                            <div class="toggle-expand-content" data-content="pageMenu">
                                <ul class="nk-block-tools g-3">
                                    <li class="nk-block-tools-opt">
{{--                                        <a href="#" class="btn btn-primary btn-sm"  data-toggle="modal" data-target="#addCountry"><em class="icon ni ni-plus"></em><span>Add require document</span></a>--}}
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card card-preview">
                <div class="card-inner">
                 <form  action="{{url("admin/update-app-settings")}}"  method="post"  id="updateCountryForm">
                        @csrf
                    <ul class="nav nav-tabs mt-n3">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#tabItem4"><em class="icon ni ni-user-fill"></em><span>APP Default Settings</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#tabItem9"><em class="icon ni ni-lock-alt-fill"></em><span>Terms & Conditions</span></a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tabItem4">
                            <div class="nk-block">
                                <div class="nk-block-head">
                                    <h4 class="title">Default Settings</h4>
                                </div>
                                    @php
                                        $approvalModeSetting = isset($data['appSettings'])
                                            ? $data['appSettings']->firstWhere('key', 'approval_mode')
                                            : null;
                                        $approvalMode = $approvalModeSetting->value ?? 'multi_level';
                                    @endphp
                                    <div class="row mb-4">
                                        <div class="col-md-12">
                                            <label><h6>Approval Mode</h6></label>
                                            <div class="form-group">
                                                <div class="custom-control custom-radio mb-1">
                                                    <input type="radio" id="approval_mode_multi" name="approval_mode" value="multi_level" class="custom-control-input"
                                                        {{ $approvalMode === 'multi_level' ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="approval_mode_multi">3-Level Approval</label>
                                                </div>
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" id="approval_mode_direct" name="approval_mode" value="direct" class="custom-control-input"
                                                        {{ $approvalMode === 'direct' ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="approval_mode_direct">Direct Admin Approval</label>
                                                </div>
                                                <small class="text-soft">Direct mode lets App Admin fully approve Estimates, Contracts, and Leads in one step. 3-Level uses the users configured below.</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" id="approval-levels-section">
                                        <div class="col-md-6">
                                            <label> <h6>By Default Estimate Approval</h6>  <span class="text-danger"></span></label>
                                            <div class="form-group">
                                                <label> Level 1 <span class="text-danger"></span></label>
                                                <select name="estimate_level_1" id="" class="form-control select2" data-live-search="true" >
                                                    <option value="">Choose One</option>
                                                    @isset($data)
                                                        @foreach ($data['users'] as $user)
                                                            @php
                                                                $selectedUserId = isset($data['appSettings'][0]) ? $data['appSettings'][0]->value : null;
                                                            @endphp
                                                            <option value="{{ $user->id }}" {{ $selectedUserId == $user->id ? 'selected' : '' }}>
                                                                {{ $user->first_name }} {{ $user->last_name }}
                                                            </option>
                                                        @endforeach
                                                    @endisset

                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label> Level 2 <span class="text-danger"></span></label>
                                                <select name="estimate_level_2" id="" class="form-control select2" data-live-search="true" >
                                                    <option value="">Choose One</option>
                                                    @isset($data)
                                                        @foreach ($data['users'] as $user)
                                                            @php
                                                                $selectedUserId = isset($data['appSettings'][1]) ? $data['appSettings'][1]->value : null;
                                                            @endphp
                                                            <option value="{{ $user->id }}" {{ $selectedUserId == $user->id ? 'selected' : '' }}>
                                                                {{ $user->first_name }} {{ $user->last_name }}
                                                            </option>
                                                        @endforeach
                                                    @endisset
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label> Level 3 <span class="text-danger"></span></label>
                                                <select name="estimate_level_3" id="" class="form-control select2" data-live-search="true" >
                                                    <option value="">Choose One</option>
                                                    @isset($data)
                                                        @foreach ($data['users'] as $user)
                                                            @php
                                                                $selectedUserId = isset($data['appSettings'][2]) ? $data['appSettings'][2]->value : null;
                                                            @endphp
                                                            <option value="{{ $user->id }}" {{ $selectedUserId == $user->id ? 'selected' : '' }}>
                                                                {{ $user->first_name }} {{ $user->last_name }}
                                                            </option>
                                                        @endforeach
                                                    @endisset
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label> <h6>By Default Contract Approval</h6>  <span class="text-danger"></span></label>
                                            <div class="form-group">
                                                <label> Level 1 <span class="text-danger"></span></label>
                                                <select name="contract_level_1" id="" class="form-control select2" data-live-search="true" >
                                                    <option value="">Choose One</option>
                                                    @isset($data)
                                                        @foreach ($data['users'] as $user)
                                                            @php
                                                                $selectedUserId = isset($data['appSettings'][3]) ? $data['appSettings'][3]->value : null;
                                                            @endphp
                                                            <option value="{{ $user->id }}" {{ $selectedUserId == $user->id ? 'selected' : '' }}>
                                                                {{ $user->first_name }} {{ $user->last_name }}
                                                            </option>
                                                        @endforeach
                                                    @endisset
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label> Level 2 <span class="text-danger"></span></label>
                                                <select name="contract_level_2" id="" class="form-control select2" data-live-search="true" >
                                                    <option value="">Choose One</option>
                                                    @isset($data)
                                                        @foreach ($data['users'] as $user)
                                                            @php
                                                                $selectedUserId = isset($data['appSettings'][4]) ? $data['appSettings'][4]->value : null;
                                                            @endphp
                                                            <option value="{{ $user->id }}" {{ $selectedUserId == $user->id ? 'selected' : '' }}>
                                                                {{ $user->first_name }} {{ $user->last_name }}
                                                            </option>
                                                        @endforeach
                                                    @endisset
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label> Level 3 <span class="text-danger"></span></label>
                                                <select name="contract_level_3" id="" class="form-control select2" data-live-search="true" >
                                                    <option value="">Choose One</option>
                                                    @isset($data)
                                                        @foreach ($data['users'] as $user)
                                                            @php
                                                                $selectedUserId = isset($data['appSettings'][5]) ? $data['appSettings'][5]->value : null;
                                                            @endphp
                                                            <option value="{{ $user->id }}" {{ $selectedUserId == $user->id ? 'selected' : '' }}>
                                                                {{ $user->first_name }} {{ $user->last_name }}
                                                            </option>
                                                        @endforeach
                                                    @endisset
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mt-3">
                                            <label><h6>By Default Lead Approval</h6>  <span class="text-danger"></span></label>
                                            <div class="form-group">
                                                <label> Level 1 <span class="text-danger"></span></label>
                                                <select name="lead_level_1" id="" class="form-control select2" data-live-search="true" >
                                                    <option value="">Choose One</option>
                                                    @isset($data)
                                                        @foreach ($data['users'] as $user)
                                                            @php
                                                                $selectedUserId = isset($data['appSettings'][6]) ? $data['appSettings'][6]->value : null;
                                                            @endphp
                                                            <option value="{{ $user->id }}" {{ $selectedUserId == $user->id ? 'selected' : '' }}>
                                                                {{ $user->first_name }} {{ $user->last_name }}
                                                            </option>
                                                        @endforeach
                                                    @endisset
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label> Level 2 <span class="text-danger"></span></label>
                                                <select name="lead_level_2" id="" class="form-control select2" data-live-search="true" >
                                                    <option value="">Choose One</option>
                                                    @isset($data)
                                                        @foreach ($data['users'] as $user)
                                                            @php
                                                                $selectedUserId = isset($data['appSettings'][7]) ? $data['appSettings'][7]->value : null;
                                                            @endphp
                                                            <option value="{{ $user->id }}" {{ $selectedUserId == $user->id ? 'selected' : '' }}>
                                                                {{ $user->first_name }} {{ $user->last_name }}
                                                            </option>
                                                        @endforeach
                                                    @endisset
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label> Level 3 <span class="text-danger"></span></label>
                                                <select name="lead_level_3" id="" class="form-control select2" data-live-search="true" >
                                                    <option value="">Choose One</option>
                                                    @isset($data)
                                                        @foreach ($data['users'] as $user)
                                                            @php
                                                                $selectedUserId = isset($data['appSettings'][8]) ? $data['appSettings'][8]->value : null;
                                                            @endphp
                                                            <option value="{{ $user->id }}" {{ $selectedUserId == $user->id ? 'selected' : '' }}>
                                                                {{ $user->first_name }} {{ $user->last_name }}
                                                            </option>
                                                        @endforeach
                                                    @endisset
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tabItem9">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        @isset($data)
                                        @php
                                            $termCondition = $data['appSettings']->firstWhere('key', 'term_condition')
                                                ?? ($data['appSettings'][9] ?? null);
                                        @endphp
                                        <label>Terms & Conditions<span class="text-danger"></span></label>
                                        <div id="terms-toolbar-container"></div>
                                        <div id="terms-editor">
                                            @if($termCondition) {!! $termCondition->value !!} @endif
                                        </div>
                                        @endisset
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
                    <div class="float-right">
                        <button class="btn btn-primary mt-2 btn-update" type="submit">Save Changes</button>
                    </div>
                </form>
            </div>
            <!-- .card-preview -->
        </div>
        <!-- nk-block -->
    </div>

    <script>
        $(document).ready(function() {
            var termsEditor = '';

            if (document.querySelector('#terms-editor')) {
                DecoupledEditor
                    .create(document.querySelector('#terms-editor'))
                    .then(function(editor) {
                        document.querySelector('#terms-toolbar-container').appendChild(editor.ui.view.toolbar.element);
                        termsEditor = editor;
                    })
                    .catch(function(error) {
                        console.error(error);
                    });
            }

            function toggleApprovalLevels() {
                var isDirect = $('input[name="approval_mode"]:checked').val() === 'direct';
                if (isDirect) {
                    $('#approval-levels-section').slideUp(150);
                } else {
                    $('#approval-levels-section').slideDown(150);
                }
            }
            $('input[name="approval_mode"]').on('change', toggleApprovalLevels);
            toggleApprovalLevels();

            $('#updateCountryForm').on('submit', function(e) {
                e.preventDefault();
                var formData = $('#updateCountryForm').serialize();
                if (termsEditor) {
                    formData += '&term_condition=' + encodeURIComponent(termsEditor.getData());
                }
                $.ajax({
                    type: "get",
                    url: '{{ url('admin/update-app-settings') }}',
                    data: formData,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('.btn-update').text('loading...');
                        $(".btn-update").prop("disabled", true);
                    },
                    success: function(data) {

                        if (data.success) {
                            $('#updateCountryForm')[0].reset();
                            $('.close').click();
                            toastr.success(data.success);

                        }
                        if (data.errors) {
                            toastr.error(data.errors);
                            $('.btn-update').text('Save Changes');
                            $(".btn-update").prop("disabled", false);
                        }
                    },

                    complete: function(data) {
                        $(".btn-update").html("Save Changes");
                        $(".btn-update").prop("disabled", false);
                        window.location.reload();
                    },

                    error: function() {;
                        toastr.error('any technical error');
                        $('.btn-update').text('Save Changes');
                        $(".btn-update").prop("disabled", false);
                    }
                });


            });
        });
    </script>
@endsection



