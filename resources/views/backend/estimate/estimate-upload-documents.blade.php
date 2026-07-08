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
                                            <input type="file" class="no-bottom-margin form-control required-document" name="files[]" data-title="{{ $doc->title }}" required style="padding: 0px 0px;height: 32px;">
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

                let missing = [];
                $('input.required-document').each(function() {
                    if (!this.files || !this.files.length) {
                        missing.push($(this).data('title'));
                    }
                });

                if (missing.length) {
                    toastr.error('Please upload the following documents: ' + missing.join(', '));
                    return;
                }

                let formData = new FormData($('#AttachmentForm')[0]);

                $.ajax({
                    type: "POST",
                    url: '{{ url('upload-estimate-documents') }}',
                    data: formData,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('.btn-submit').text('Uploading...');
                        $(".btn-submit").prop("disabled", true);
                    },
                    success: function(data) {
                        if (data.success) {
                            $('#AttachmentForm')[0].reset();
                            toastr.success(data.success);
                            setTimeout(function() {
                                window.location.href = "{{ url('/') }}";
                            }, 1500);
                        }
                        if (data.errors) {
                            toastr.error(data.errors);
                        }
                    },
                    complete: function() {
                        $(".btn-submit").html("Upload Documents");
                        $(".btn-submit").prop("disabled", false);
                    },
                    error: function(xhr) {
                        let message = 'A technical error occurred. Please try again.';
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            message = typeof xhr.responseJSON.errors === 'string'
                                ? xhr.responseJSON.errors
                                : Object.values(xhr.responseJSON.errors).flat().join(' ');
                        }
                        toastr.error(message);
                    }
                });
            });

        });
    </script>


@endsection