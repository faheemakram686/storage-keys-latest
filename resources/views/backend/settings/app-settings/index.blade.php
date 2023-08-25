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
                            <div class="row">
                                <div class="col-md-6">
                                    <label> <h6>By Default Estimate Approval</h6>  <span class="text-danger"></span></label>
                                    <div class="form-group">
                                        <label> Level 1 <span class="text-danger"></span></label>
                                            <select name="estimate_level_1" id="" class="form-control select2" data-live-search="true" >
                                                <option value="">Choose One</option>
                                                @isset($data)
                                                    @foreach ($data['users'] as $user)
                                                        <option value="{{ $user->id }}" {{$data['appSettings'][0]->value ==  $user->id ? 'selected':'' }}  >{{$user->first_name }} {{$user->last_name }}</option>
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
                                                    <option value="{{ $user->id }}" {{$data['appSettings'][1]->value ==  $user->id ? 'selected':'' }} >{{$user->first_name }} {{$user->last_name }}</option>
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
                                                    <option value="{{ $user->id }}" {{$data['appSettings'][2]->value ==  $user->id ? 'selected':'' }} >{{$user->first_name }} {{$user->last_name }}</option>
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
                                                    <option value="{{ $user->id }}" {{$data['appSettings'][3]->value ==  $user->id ? 'selected':'' }}  >{{$user->first_name }} {{$user->last_name }}</option>
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
                                                    <option value="{{ $user->id }}" {{$data['appSettings'][4]->value ==  $user->id ? 'selected':'' }} >{{$user->first_name }} {{$user->last_name }}</option>
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
                                                    <option value="{{ $user->id }}" {{$data['appSettings'][5]->value ==  $user->id ? 'selected':'' }} >{{$user->first_name }} {{$user->last_name }}</option>
                                                @endforeach
                                            @endisset
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 mt-3">
                                    <label><h6>By Default Lead Assign</h6>  <span class="text-danger"></span></label>
                                    <div class="form-group">
                                        <label> Level 1 <span class="text-danger"></span></label>
                                        <select name="lead_level_1" id="" class="form-control select2" data-live-search="true" >
                                            <option value="">Choose One</option>
                                            @isset($data)
                                                @foreach ($data['users'] as $user)
                                                    <option value="{{ $user->id }}" {{$data['appSettings'][6]->value ==  $user->id ? 'selected':'' }}  >{{$user->first_name }} {{$user->last_name }}</option>
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
                                                    <option value="{{ $user->id }}" {{$data['appSettings'][7]->value ==  $user->id ? 'selected':'' }} >{{$user->first_name }} {{$user->last_name }}</option>
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
                                                    <option value="{{ $user->id }}" {{$data['appSettings'][8]->value ==  $user->id ? 'selected':'' }} >{{$user->first_name }} {{$user->last_name }}</option>
                                                @endforeach
                                            @endisset
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="float-right">
                                <button class="btn btn-primary mt-2 btn-update" type="submit">Save Changes</button>
                            </div>
                    </form>
                </div>
            </div>
            <!-- .card-preview -->
        </div>
        <!-- nk-block -->
    </div>

    <script>
        $(document).ready(function() {

            $('#updateCountryForm').on('submit', function(e) {
                e.preventDefault();
                var formData=$('#updateCountryForm').serialize()
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



