@extends('ui.layouts.frontend')
@section('title', '| Bookings')
@section('content')
    @isset($data)
    <style>
        /* Page-scoped responsive tweaks for the estimate document upload page */
        .upload-documents-page.checkout-page {
            padding-top: 200px;
            padding-bottom: 60px;
        }
        .upload-documents-page .term-section-body {
            width: 100%;
        }
        .upload-documents-page .upload-doc-row {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
        }
        .upload-documents-page input[type="file"].form-control {
            width: 100%;
            max-width: 100%;
            height: auto;
            min-height: 32px;
            padding: 2px;
        }
        @media (max-width: 991.98px) {
            .upload-documents-page.checkout-page {
                padding-top: 140px;
            }
            .upload-documents-page .term-section-body {
                padding: 20px 15px;
            }
        }
        @media (max-width: 575.98px) {
            .upload-documents-page.checkout-page {
                padding-top: 110px;
            }
            .upload-documents-page .term-section-header {
                font-size: 1rem;
            }
            .upload-documents-page .check-container {
                font-size: 0.9rem;
                margin-bottom: 5px;
            }
            .upload-documents-page .btn-submit {
                width: 100%;
                float: none !important;
            }
        }
    </style>
    <div class="justify-content-center checkout-page upload-documents-page">
        @foreach ($data['estimate'] as $estimate)
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-md-10 col-lg-8 details-section">
                    <form action="{{ url('upload-estimate-documents') }}" method="POST" enctype="multipart/form-data" id="AttachmentForm">
                        @csrf
                        <input type="hidden" name="estimate_id" id="estimate" value="{{$estimate->id}}">
                    <div class="row reservations-sections justify-content-center">
                        <div class="col-10 col-sm-8 col-md-6 term-section-header">
                            Upload Required Document</div>
                        <div class="col-12 term-section-body">
                            <div class="row">
                                <div class="col-12">
                                    @if ($data['req_documents']->isEmpty())
                                    <p>No documents are required for this estimate. You are all set!</p>
                                    @else
                                    <p>Please Upload your required documents for varification!</p>
                                    @endif
                                    @foreach ($data['req_documents'] as $doc)
                                    @php $isUploaded = in_array((string) $doc->id, $data['uploaded_doc_ids'] ?? [], true); @endphp
                                    <div class="row upload-doc-row">
                                        <div class="col-12 col-sm-5 col-md-6">
                                            <div class="form-check">
                                                <label class="check-container" for="flexCheckDefault">{{$doc->title}}
                                                    @if ($isUploaded)
                                                    <span class="badge badge-success" style="background-color:#28a745;color:#fff;">Uploaded</span>
                                                    @endif
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-7 col-md-6 d-flex">
                                            <input type="hidden" name="id[]" value="{{$doc->id}}">
                                            <input type="file" class="no-bottom-margin form-control {{ $isUploaded ? '' : 'required-document' }}" name="files[]" data-title="{{ $doc->title }}" {{ $isUploaded ? '' : 'required' }}>
                                        </div>
                                    </div>
                                    <div class="separator-item"></div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row submission-sections">
                        @if (!$data['req_documents']->isEmpty())
                        <div class="col-12 d-flex justify-content-end">
                            <button class="btn btn-qoutation btn-sm active btn-submit" type="submit">Upload Documents</button>
                        </div>
                        @endif
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