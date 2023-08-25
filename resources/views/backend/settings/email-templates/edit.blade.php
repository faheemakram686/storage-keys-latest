@extends('backend.layouts.app')
@section('title', '| Edit Email Template')
@section('content')
    <div class="components-preview wide-md mx-auto">
        <div class="nk-block nk-block-lg">
            <div class="nk-block-head">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h4 class="title nk-block-title">Edit Email Template</h4>
                    </div>
                    <a href="{{url("admin/email-template")}}" class="btn btn-primary btn-sm d-none d-md-inline-flex"><em class="icon ni ni-arrow-left"></em><span>Back</span></a>
                </div>
            </div>
            <div class="card">
                <div class="card-inner">
                    <form  action="{{url("admin/update-email-template")}}"  method="post"  id="updateCountryForm">
                        @csrf
                        @isset($data)

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label> Template Name <span class="text-danger"></span></label>
                                        <input type="hidden" name="id" value="{{$data['st']->id}}">
                                        <input class="form-control" type="text" name="et_temp_name" placeholder="Template Name" value="{{$data['st']->temp_name}}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label> Template For <span class="text-danger"></span></label>
                                        <select name="et_temp_for" id="" class="form-control" required>
                                            <option value="">Choose One</option>
                                            <option value="contract" {{$data['st']->temp_for == 'contract' ? 'selected' : ''}}>Contract</option>
                                            <option value="lead" {{$data['st']->temp_for == 'lead' ? 'selected' : ''}}>Lead</option>
                                            <option value="estimate" {{$data['st']->temp_for == 'estimate' ? 'selected' : ''}}>Estimate</option>
                                            <option value="project" {{$data['st']->temp_for == 'project' ? 'selected' : ''}}>Project</option>

                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label> Template Category <span class="text-danger"></span></label>
                                        <select name="et_temp_category" id="" class="form-control" required>
                                            <option value="">Choose One</option>
                                            <option value="email" {{$data['st']->temp_cat == 'email' ? 'selected' : ''}}>Email</option>
                                            <option value="database" {{$data['st']->temp_cat == 'database' ? 'selected' : ''}}>System Notification</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">

                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label> Template Subject <span class="text-danger"></span></label>
                                        <input class="form-control" type="text" name="et_temp_subject" placeholder="Template Subject" value="{{$data['st']->temp_subject}}" required>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="form-label" for="reviewer">Template Body</label>
                                        <div class="form-control-wrap">
                                            <textarea id="" class="form-control" name="et_temp_body" value="{{$data['st']->temp_body}}"> {{$data['st']->temp_body}} </textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Status <span class="text-danger"></span></label>
                                        <select name="edit_status" id="" class="form-control" required>
                                            <option value="">Choose One</option>
                                            <option value="1" {{$data['st']->status == 'Active' ? 'selected' : ''}}>Active</option>
                                            <option value="0" {{$data['st']->status == 'In-Active' ? 'selected' : ''}}>In-Active</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="float-right">
                                <button class="btn btn-primary mt-2 btn-update" type="submit">Save Changes</button>
                            </div>

                        @endisset
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        ClassicEditor
            .create( document.querySelector( '#editor' ) )
            .catch( error => {
                console.error( error );
            } );
    </script>

    <script>
        $(document).ready(function() {

            $('#updateCountryForm').on('submit', function(e) {
                e.preventDefault();
                var formData=$('#updateCountryForm').serialize()
                $.ajax({
                    type: "get",
                    url: '{{ url('admin/update-email-template') }}',
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
                        window.location.href = "{{ route('email-template.index')}}";
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



