@extends('backend.layouts.app')
@section('title', '| Create Customer')
@section('content')
    <div class="components-preview wide-md mx-auto">
        <div class="nk-block nk-block-lg">
            <div class="nk-block-head">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
{{--                        <h4 class="title nk-block-title">Email Detail</h4>--}}
                        <h6 class="title d-none d-lg-block">From: {{$data['from']['name']}}</h6>
                        <h6 class="title d-none d-lg-block">Email: {{$data['from']['email']}}</h6>
                    </div>
                    <a href="{{url("admin/emails")}}" class="btn btn-primary btn-sm d-none d-md-inline-flex"><em class="icon ni ni-arrow-left"></em><span>Back</span></a>
                </div>
            </div>
            <div class="nk-content p-0">
                <div class="nk-content-inner">
                    @isset($data)
                    <div class="nk-content-body">
                        <div class="nk-msg">
                            <div class="nk-msg-body bg-white profile-shown">
                                <div class="nk-msg-head">
                                    <h4 class="title d-none d-lg-block">Subject: {{$data['subject']}}</h4>
                                </div><!-- .nk-msg-head -->
                                <div class="nk-msg-reply nk-reply" data-simplebar>
                                    <div class="nk-msg-head py-4 d-lg-none">
                                        <h4 class="title"> {{$data['subject']}}</h4>
                                        <ul class="nk-msg-tags">
                                            <li><span class="label-tag"><em class="icon ni ni-flag-fill"></em> <span>Technical Problem</span></span></li>
                                        </ul>
                                    </div>
                                    <div class="nk-reply-item">
                                        <div class="nk-reply-header">
                                            <div class="user-card">
                                                <div class="user-avatar sm bg-blue">
                                                    <span>AB</span>
                                                </div>
                                                <div class="user-name">{{$data['from']['name']}}</div>
                                            </div>
                                            <div class="date-time">{{$data['date']}}</div>
                                        </div>
                                        <div class="nk-reply-body">
                                            <div class="nk-reply-entry entry">
                                               {!! $data['body'] !!}
                                            </div>
                                        </div>
                                    </div><!-- .nk-reply-item -->

                                </div><!-- .nk-reply -->

                            </div><!-- .nk-msg-body -->
                        </div><!-- .nk-msg -->
                    </div>
                    @endisset
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {

            $('#CountryForm').on('submit', function(e) {

                e.preventDefault();
                var formData=$('#CountryForm').serialize()
                $.ajax({
                    type: "get",
                    url: '{{ url('admin/save-customer') }}',
                    data: formData,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('.btn-submit').text('Saving...');
                        $(".btn-submit").prop("disabled", true);
                    },
                    success: function(data) {

                        if (data.success) {
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
                        window.location.href = "{{ route('customer.index')}}";
                    },

                    error: function() {;
                        toastr.error('any technical error');
                        $('.btn-submit').text('Save');
                        $(".btn-submit").prop("disabled", false);
                    }
                });


            });


        });
    </script>
@endsection



