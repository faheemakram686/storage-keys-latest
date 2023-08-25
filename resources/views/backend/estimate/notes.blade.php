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
                                        <div class="nk-block-between d-flex justify-content-between">
                                            <div class="nk-block-head-content">
                                                <h4 class="nk-block-title">Notes</h4>
                                                <div class="nk-block-des">

                                                </div>
                                            </div>
                                            <div class="d-flex align-center">
                                                <div class="nk-tab-actions me-n1">
                                                    <a href="#" class="btn btn-primary btn-sm"  data-toggle="modal" data-target="#declineModal"><em class="icon ni ni-plus"></em><span>Add Note</span></a>
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
                                            <div class="nk-msg-body bg-white profile-shown">
                                                <div class="nk-msg-reply nk-reply" id="NotesContent" data-simplebar>
{{--                                                    <div class="nk-reply-item">--}}
{{--                                                        <div class="nk-reply-header">--}}
{{--                                                            <div class="user-card">--}}
{{--                                                                <div class="user-avatar sm bg-purple">--}}
{{--                                                                    <span>IH</span>--}}
{{--                                                                </div>--}}
{{--                                                                <div class="user-name">Iliash Hossain <span>added a note</span></div>--}}
{{--                                                            </div>--}}
{{--                                                            <div class="date-time">14 Jan, 2020</div>--}}
{{--                                                        </div>--}}
{{--                                                        <div class="nk-reply-body">--}}
{{--                                                            <div class="nk-reply-entry entry note">--}}
{{--                                                                <p>Devement Team need to check out the issues.</p>--}}
{{--                                                            </div>--}}
{{--                                                        </div>--}}
{{--                                                    </div><!-- .nk-reply-item -->--}}

                                                </div><!-- .nk-reply -->
                                            </div><!-- .nk-msg-body -->
                                        </div>
                                    </div>
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

            <div class="modal fade" tabindex="-1" role="dialog" id="declineModal" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title text-capitalize" id="ajax_model_title"> Add Note</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" data-form="ajax-form-modal">
                            <form method="post" action="{{ url('admin/save-note') }}" id="DeclineForm">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <input class="form-control" type="hidden" name="id" value="{{$data['estimate'][0]->id}}" required>
                                            <input class="form-control" type="hidden" name="type" value="estimate" required>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="decline_reason"> Note Description <span class="text-danger"></span></label>
                                            <textarea name="description" id="description" class="form-control" placeholder="Enter note...."></textarea>
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

            <div class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" id="editnote" aria-hidden="true">
                <div class="modal-dialog modal-md" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title text-capitalize" id="ajax_model_title">Edit Note</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" data-form="ajax-form-modal">

                            <form method="post" action="{{ url('admin/update-note') }}" id="updateNoteForm">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <input type="hidden" name="id">
                                            <input class="form-control" type="hidden" name="type_id" value="{{$data['estimate'][0]->id}}" required>
                                            <input class="form-control" type="hidden" name="type" value="estimate" required>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="decline_reason"> Note Description <span class="text-danger"></span></label>
                                            <textarea name="description" id="description" class="form-control" placeholder="Enter note...."></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="float-right">
                                    <button class="btn btn-primary mt-2 btn-update" type="submit">Save Changes</button>
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

            $('#DeclineForm').on('submit', function(e) {

                e.preventDefault();
                var formData=$('#DeclineForm').serialize()
                $.ajax({
                    type: "get",
                    url: '{{ url('admin/save-note') }}',
                    data: formData,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('.btn-submit').text('Saving...');
                        $(".btn-submit").prop("disabled", true);
                    },
                    success: function(data) {

                        if (data.success) {
                            $('#DeclineForm')[0].reset();
                            $('.close').click();
                            getAllCities();
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
                var type_id=$('#estimate_id').val();
                var type='estimate';

                $.ajax({

                    url: '{{ url('admin/get-realted-notes') }}',
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

                            html += ' <div class="nk-reply-item">'+
                                '<div class="nk-reply-header">'+
                                '<div class="user-card">'+
                                '<div class="user-avatar sm bg-purple">'+
                                '<span>IH</span>'+
                                '</div>'+
                                 '<div class="user-name">'+ data[i].user.first_name +' '+ data[i].user.last_name +' <span>added a note</span></div>'+
                                    '</div>'+
                                 '<div class="date-time">'+ data[i].created_at +'</div>'+
                                 '</div>'+
                                '<div class="nk-reply-body">'+
                                    '<div class="chat-bubble">'+
                                        '<div class="nk-reply-entry entry note"> <p>'+ data[i].description +'</p> </div>'+
                                        '<ul class="chat-msg-more">'+
                                            '<li>'+
                                                '<div class="dropdown">'+
                                                    '<a href="#" class="btn btn-icon btn-sm btn-trigger dropdown-toggle" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>'+
                                                    '<div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">'+
                                                        '<ul class="link-list-opt no-bdr">'+
                                                            '<li><a href="#" class="btn-edit" data='+data[i].id+' data-toggle="modal" data-target="#editnote"><em class="icon ni ni-pen-alt-fill"></em> Edit</a></li>'+
                                                            '<li><a  href="#" class="btn-delete" data='+data[i].id+'><em class="icon ni ni-trash-fill"></em> Remove</a></li>'+
                                                        '</ul>'+
                                                    '</div>'+
                                                '</div>'+
                                            '</li>'+
                                        '</ul>'+
                                    '</div>'+
                                '</div>'+
                            '</div>';

                        }

                        $('#NotesContent').html(html);

                    },
                    error: function() {
                        toastr.error('something went wrong');
                    }

                });
            }

            $('#NotesContent').on('click', '.btn-delete', function() {
                var id = $(this).attr('data');
                $.ajax({
                    url: '{{ url('admin/delete-note') }}',
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


            $('#NotesContent').on('click', '.btn-edit', function() {
                var id = $(this).attr('data');
                $.ajax({
                    url: '{{ url('admin/edit-note') }}',
                    type: 'get',
                    async: false,
                    dataType: 'json',
                    data: { id: id },
                    success: function(res) {
                        console.log(res);

                        $('input[name=id]').val(id);
                        $('textarea[name=description]').val(res.description);

                    },
                    error: function() {
                        toastr.error('any technical error');
                    }
                });
            });

            $('#updateNoteForm').on('submit', function(e) {
                e.preventDefault();
                var formData=$('#updateNoteForm').serialize()
                $.ajax({
                    type: "get",
                    url: '{{ url('admin/update-note') }}',
                    data: formData,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('.btn-update').text('loading...');
                        $(".btn-update").prop("disabled", true);
                    },
                    success: function(data) {

                        if (data.success) {
                            getAllCities();
                            $('#updateNoteForm')[0].reset();
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



