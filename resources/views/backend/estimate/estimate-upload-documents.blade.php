@extends('ui.layouts.frontend')
@section('title', '| Bookings')
@section('content')
    @isset($data)
{{--        {{dd($data)}}--}}
    <div class="justify-content-center checkout-page">
        @foreach ($data['estimate'] as $estimate)
        <div class="container">
            <div class="row">
                <div class="col-12 col-sm-12 col-md-12 col-lg-12 details-section">
                    <form action="{{ url('upload-estimate-documents') }}" method="POST" enctype="multipart/form-data" id="AttachmentForm">
                        @csrf
                        <input type="hidden" name="estimate_id" id="estimate" value="{{$estimate->id}}">
                    <div class="row reservations-sections">
                        <div class="offset-sm-2 offset-md-2 offset-lg-2 offset-1 col-8 col-sm-6 col-md-4 col-lg-4 term-section-header">
                            Upload Required Document</div>
                        <div class="offset-md-1 offset-lg-1 col-12 col-sm-12 col-md-9 col-lg-10 term-section-body">
                            <div class="row">
                                <div class="offset-lg-1 offset-md-1 col-12 col-sm-12 col-md-10 col-lg-10">
                                    <p>Please Upload your required documents for varification!</p>
                                    @foreach ($data['req_documents'] as $doc)
                                    <div class="row">
                                        <div class="col-8">
                                            <div class="form-check">
                                                <label class="check-container" for="flexCheckDefault">{{$doc->title}}</label>
                                            </div>
                                        </div>
                                        <div class="col-4 d-flex">
                                            <input type="hidden" name="id[]" value="{{$doc->id}}">
                                            <input type="file" class=" no-bottom-margin form-control" name="files[]" style="padding: 0px 0px;height: 32px;" >
                                        </div>
                                    </div>
                                    <div class="separator-item"></div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row submission-sections">
                        <button class="offset-md-1 offset-lg-1 btn btn-qoutation btn-sm active btn-submit float-end" type="submit">Upload Documents</button>
                    </div>
                    </form>
                </div>
            </div>
                @endforeach
        </div>

    </div>

    @endisset
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    <script>
        $(document).ready(function() {

            $('#AttachmentForm').on('submit', function(e) {

                e.preventDefault();

                let formData = new FormData($('#AttachmentForm')[0])

                $.ajax({
                    type: "POST",
                    url: '{{ url('upload-estimate-documents') }}',
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
                        window.location.href = "{{ url('/')}}";
                    },

                    error: function() {

                        toastr.error('any technical error');
                        $('.btn-submit').text('Upload Documents');
                        $(".btn-submit").prop("disabled", false);
                    }
                });


            });

        });
    </script>


@endsection