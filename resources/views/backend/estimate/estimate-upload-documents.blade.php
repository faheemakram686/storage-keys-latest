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
            margin-bottom: 8px;
        }
        .upload-documents-page .upload-file-wrap {
            display: block;
            width: 100%;
            position: relative;
            z-index: 1;
        }
        /* Keep file inputs tappable on iOS/Android (flex shrink + tiny hit areas break them) */
        .upload-documents-page input[type="file"].form-control {
            display: block;
            width: 100% !important;
            max-width: 100%;
            min-width: 0;
            height: auto !important;
            min-height: 44px;
            padding: 8px;
            font-size: 16px; /* prevents iOS zoom on focus */
            -webkit-appearance: none;
            appearance: none;
            touch-action: manipulation;
            cursor: pointer;
            background: #fff;
            position: relative;
            z-index: 2;
        }
        .upload-documents-page .btn-submit {
            position: relative;
            z-index: 2;
            touch-action: manipulation;
            min-height: 44px;
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
                    <form action="{{ url('upload-estimate-documents') }}" method="POST" enctype="multipart/form-data" id="AttachmentForm" novalidate>
                        @csrf
                        <input type="hidden" name="estimate_id" id="estimate" value="{{ $estimate->id }}">
                        <div class="row reservations-sections justify-content-center">
                            <div class="col-10 col-sm-8 col-md-6 term-section-header">
                                Upload Required Document
                            </div>
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
                                                        <label class="check-container" for="estimate-doc-{{ $doc->id }}">{{ $doc->title }}
                                                            @if ($isUploaded)
                                                                <span class="badge badge-success" style="background-color:#28a745;color:#fff;">Uploaded</span>
                                                            @endif
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-7 col-md-6">
                                                    <div class="upload-file-wrap">
                                                        {{-- Key files by document id so empty inputs omitted on mobile Safari cannot shift indexes --}}
                                                        <input
                                                            type="file"
                                                            id="estimate-doc-{{ $doc->id }}"
                                                            class="no-bottom-margin form-control {{ $isUploaded ? '' : 'required-document' }}"
                                                            name="files[{{ $doc->id }}]"
                                                            data-doc-id="{{ $doc->id }}"
                                                            data-title="{{ $doc->title }}"
                                                            accept="image/*,.pdf,application/pdf,.doc,.docx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document"
                                                        >
                                                    </div>
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
        </div>
        @endforeach
    </div>
    @endisset
@endsection

@section('javascriptWork')
<script>
    (function ($) {
        $(function () {
            var $form = $('#AttachmentForm');
            if (!$form.length) {
                return;
            }

            $form.on('submit', function (e) {
                e.preventDefault();
                e.stopPropagation();

                var missing = [];
                $form.find('input.required-document').each(function () {
                    if (!this.files || !this.files.length) {
                        missing.push($(this).data('title') || 'Document');
                    }
                });

                if (missing.length) {
                    toastr.error('Please upload the following documents: ' + missing.join(', '));
                    return false;
                }

                var formData = new FormData();
                formData.append('_token', $form.find('input[name="_token"]').val());
                formData.append('estimate_id', $form.find('input[name="estimate_id"]').val());

                // Append only selected files, keyed by require-document id
                $form.find('input[type="file"]').each(function () {
                    if (this.files && this.files.length) {
                        var docId = $(this).data('doc-id');
                        formData.append('files[' + docId + ']', this.files[0]);
                        formData.append('id[]', docId);
                    }
                });

                $.ajax({
                    type: 'POST',
                    url: '{{ url('upload-estimate-documents') }}',
                    data: formData,
                    contentType: false,
                    processData: false,
                    cache: false,
                    beforeSend: function () {
                        $form.find('.btn-submit').text('Uploading...').prop('disabled', true);
                    },
                    success: function (data) {
                        if (data.success) {
                            toastr.success(data.success);
                            setTimeout(function () {
                                window.location.href = "{{ url('/') }}";
                            }, 1500);
                            return;
                        }
                        if (data.errors) {
                            toastr.error(data.errors);
                        }
                    },
                    complete: function () {
                        $form.find('.btn-submit').html('Upload Documents').prop('disabled', false);
                    },
                    error: function (xhr) {
                        var message = 'A technical error occurred. Please try again.';
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            message = typeof xhr.responseJSON.errors === 'string'
                                ? xhr.responseJSON.errors
                                : Object.values(xhr.responseJSON.errors).flat().join(' ');
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }
                        toastr.error(message);
                    }
                });

                return false;
            });
        });
    })(jQuery);
</script>
@endsection
