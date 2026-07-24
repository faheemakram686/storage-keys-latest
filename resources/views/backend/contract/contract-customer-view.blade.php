@extends('ui.layouts.frontend')
@section('title', '| Contract')

@section('content')
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.css">

    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <link type="text/css" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/south-street/jquery-ui.css" rel="stylesheet">
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="{{ asset('sk-assets/js/frontend/jquery.signature.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('sk-assets/css/frontend/jquery.signature.css') }}">
    <style>
        #sig {
            border: 1px solid #d5d5d5;
            border-radius: 4px;
            min-height: 200px;
            background: #fff;
        }
        #sig canvas {
            width: 100% !important;
            height: 200px !important;
        }

        /* Contract customer summary — prevent collapse / overflow */
        .contract-view-page.checkout-page {
            padding-top: 200px;
            padding-bottom: 60px;
            overflow-x: hidden;
        }
        .contract-view-page .contract-content-col {
            padding-right: 20px;
        }
        .contract-view-page .contract-content-col .term-section-body {
            width: 100%;
            max-width: 100%;
            padding: 36px 28px 28px;
            overflow-x: auto;
        }
        .contract-view-page .contract-content-col .term-section-body img,
        .contract-view-page .contract-content-col .term-section-body table {
            max-width: 100%;
            height: auto;
        }
        .contract-view-page .order-summary {
            margin-left: 0;
            max-width: 100%;
        }
        .contract-view-page .order-summary .locations-section {
            margin-left: 0;
            margin-right: 0;
            width: 100%;
        }
        .contract-view-page .order-section-header {
            margin-left: auto;
            margin-right: auto;
            float: none;
            max-width: 70%;
        }
        .contract-view-page .order-section-body {
            padding: 28px 16px 18px;
            overflow: hidden;
            width: 100%;
            box-sizing: border-box;
        }
        .contract-view-page .order-section-body .form-check {
            padding-left: 0;
            margin-bottom: 0;
        }
        .contract-view-page .order-section-body .row {
            margin-left: 0;
            margin-right: 0;
            align-items: center;
        }
        .contract-view-page .order-section-body .row > [class*="col-"] {
            padding-left: 8px;
            padding-right: 8px;
        }
        .contract-view-page .order-summary .summary-row-value {
            justify-content: flex-end;
            text-align: right;
            flex-wrap: wrap;
            word-break: break-word;
        }
        .contract-view-page .order-summary .summary-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 4px;
        }
        .contract-view-page .order-summary .summary-actions .btn-qoutation {
            flex: 1 1 calc(50% - 4px);
            min-width: 0;
            max-width: 100%;
            margin-top: 0;
            padding: 8px 10px;
            text-align: center;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            background-color: #F58320;
            color: #fff3e9;
            border-color: #F58320;
            box-sizing: border-box;
        }
        .contract-view-page .order-summary .summary-actions .btn-qoutation:only-child {
            flex: 1 1 100%;
        }
        .contract-view-page .order-summary .img-thumbnail {
            max-width: 100%;
            height: auto;
        }
        @media (max-width: 991.98px) {
            .contract-view-page.checkout-page {
                padding-top: 140px;
            }
            .contract-view-page .contract-content-col {
                padding-right: 15px;
            }
            .contract-view-page .order-summary {
                margin-top: 24px;
            }
            .contract-view-page .contract-content-col .term-section-body,
            .contract-view-page .order-section-body {
                padding: 24px 16px 16px;
            }
        }
        @media (max-width: 575.98px) {
            .contract-view-page.checkout-page {
                padding-top: 110px;
            }
            .contract-view-page .greeting-user h3 {
                font-size: 1.3rem;
            }
            .contract-view-page .order-section-header {
                font-size: 0.95rem;
                max-width: 80%;
            }
            .contract-view-page .order-summary .summary-actions .btn-qoutation {
                flex: 1 1 100%;
            }
        }
    </style>
    @isset($data)
{{--    {{dd($data)}}--}}
    <div class="justify-content-center checkout-page contract-view-page">
        @foreach ($data['contract'] as $contract)
        <div class="container">

                <div class="row">
                    <div class="offset-lg-1 offset-md-1 col-12 col-sm-12 col-md-11 col-lg-11 greeting-user">
                        <h3 class="animated fadeIn" style="color:#FF8820;"> {{$contract->subject}}</h3>
                        <h3 class="animated fadeIn" style="color:#FF8820;" > <span class="area-name"> {!! $contract->description  !!}</span> </h3>
                        <h4><span class="badge {{(($contract->is_signed == 'Signed')? 'badge-success':'badge-info')}}"> {{$contract->is_signed}} </span></h4>
                    </div>
                    {{--                <div class="offset-lg-1 offset-md-1 col-12 col-sm-12 col-md-11 col-lg-11 selected-plot-message"> <p> {{$su->warehouse->loc->loc_name}}- {{$su->warehouse->name}} - {{$su->storage_unit_name}}</p></div>--}}
                </div>


            <div class="row">
                <div class="col-12 col-sm-12 col-md-12 col-lg-12 details-section">
                    <form method="post" action="#" id="EstimateForm111">
                        @csrf
                        <input type="hidden" name="contract_id" id="contract_id" value="{{$contract->id}}">
                    <div class="row reservations-sections align-items-start">
                        <div class="col-12 col-lg-9 contract-content-col">
                            <div class="row">
                                <div class="offset-1 offset-sm-2 col-8 col-sm-5 col-md-4 col-lg-3 term-section-header">
                                    Contract Content</div>
                                <div class="col-12 term-section-body">
                                    <div class="row">
                                        <div class="col-12">
                                            @isset($contract->contractTemplate)
                                            {!! $contract->contractTemplate->temp_body !!}
                                            @endisset
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-3 order-summary">
                            <div class="row locations-section justify-content-center">
                                <div class="col-10 col-sm-8 col-md-6 col-lg-10 order-section-header">Summary</div>
                                <div class="col-12 order-section-body">
                                    <div class="row mb-2">
                                        <div class="col-12">
                                            <div class="form-check">
                                                <label class="check-container"><b>{{$contract->customer->company_name}}</b></label>
                                                <label class="check-container">{{$contract->customer->address}}</label>
                                                <label class="check-container">{{$contract->customer->city}}</label>
                                                <label class="check-container">{{$contract->customer->country}}</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="separator-item"></div>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-check">
                                                <label class="check-container">Contract Value</label>
                                            </div>
                                        </div>
                                        <div class="col-6 d-flex summary-row-value">
                                            <span class="no-bottom-margin mt-1 text-right">AED</span>
                                            <span class="no-bottom-margin mt-1 text-right ml-1">{{$contract->contract_value}}</span>
                                        </div>
                                    </div>

                                    <div class="separator-item"></div>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-check">
                                                <label class="check-container">Contract Number</label>
                                            </div>
                                        </div>
                                        <div class="col-6 d-flex summary-row-value">
                                            <span class="no-bottom-margin mt-1 text-right">{{$contract->id}}</span>
                                        </div>
                                    </div>
                                    <div class="separator-item"></div>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-check">
                                                <label class="check-container">Start Date</label>
                                            </div>
                                        </div>
                                        <div class="col-6 d-flex summary-row-value">
                                            <span class="no-bottom-margin mt-1 text-right">{{$contract->start_date}}</span>
                                        </div>
                                    </div>
                                    <div class="separator-item"></div>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-check">
                                                <label class="check-container">End Date</label>
                                            </div>
                                        </div>
                                        <div class="col-6 d-flex summary-row-value">
                                            <span class="no-bottom-margin mt-1 text-right">{{$contract->end_date}}</span>
                                        </div>
                                    </div>
                                    @if($contract->is_signed == 'Signed')
                                    <div class="separator-item"></div>
                                    <div class="row">
                                        @if($contract->sign_image != null)
                                            <div class="col-12">
                                                <div class="text-center">
                                                <img src="{{ asset('storage/uploads/contract_sign_images').'/'.$contract->sign_image}}" alt="image not found" class="img-thumbnail" >
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    @endif
                                    <div class="separator-item"></div>
                                    <div class="summary-actions">
                                        <a href="{{ url('contract-pdf/').'/'.hashid_encode($contract->id) }}" class="btn btn-qoutation btn-sm active">Download</a>
                                        @if($contract->is_signed == 'Not Signed')
                                        <a href="#" class="btn btn-qoutation btn-sm active btn-sign">Sign</a>
                                        @endif
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>



{{--                    <div class="row submission-sections">--}}
{{--                        <button class="offset-md-1 offset-lg-1 btn btn-qoutation btn-sm active btn-submit float-end" type="submit">Save Estimate</button>--}}
{{--                    </div>--}}
                    </form>
                </div>
            </div>
                @endforeach
        </div>

    </div>




    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Contract Sign</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success  alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <strong>{{ $message }}</strong>
                        </div>
                    @endif
                    <form method="POST" action="{{ url('sign-contract') }}" id="ContractSignForm">
                        @csrf
                        <input type="hidden" name="id" id="contract_id" value="{{$contract->id}}">
                        <div class="col-md-12">
                            <label class="" for="">Signature:</label>
                            <br/>
                            <div id="sig" ></div>
                            <br/>
                            <button id="clear" class="btn btn-danger btn-sm">Clear Signature</button>
                            <textarea id="signature64" name="signed" style="display: none"></textarea>
                        </div>
                        <br/>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit"  class="btn btn-primary btn-change">Submit</button>
                        </div>
                    </form>


                </div>

            </div>
        </div>
    </div>
    @endisset
{{--    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>--}}
    <script>
        $(document).ready(function() {
            var sig = null;
            var fallbackCanvas = null;
            var fallbackCtx = null;
            var isDrawing = false;
            var usingFallbackPad = false;
            var hasFallbackStroke = false;

            function getCanvasPoint(e) {
                var rect = fallbackCanvas.getBoundingClientRect();
                var source = (e.touches && e.touches[0]) || (e.changedTouches && e.changedTouches[0]) || e;
                return {
                    x: source.clientX - rect.left,
                    y: source.clientY - rect.top
                };
            }

            function beginDraw(e) {
                e.preventDefault();
                isDrawing = true;
                hasFallbackStroke = true;
                var p = getCanvasPoint(e);
                fallbackCtx.beginPath();
                fallbackCtx.moveTo(p.x, p.y);
            }

            function draw(e) {
                if (!isDrawing) return;
                e.preventDefault();
                var p = getCanvasPoint(e);
                fallbackCtx.lineTo(p.x, p.y);
                fallbackCtx.stroke();
                $("#signature64").val(fallbackCanvas.toDataURL("image/png"));
            }

            function endDraw(e) {
                if (!isDrawing) return;
                e.preventDefault();
                isDrawing = false;
                fallbackCtx.closePath();
                $("#signature64").val(fallbackCanvas.toDataURL("image/png"));
            }

            function initFallbackSignaturePad() {
                if (fallbackCanvas) return true;

                var $sig = $('#sig');
                $sig.html('<canvas id="sig-canvas" width="600" height="200"></canvas>');
                fallbackCanvas = document.getElementById('sig-canvas');
                if (!fallbackCanvas) return false;

                fallbackCtx = fallbackCanvas.getContext('2d');
                fallbackCtx.strokeStyle = '#111';
                fallbackCtx.lineWidth = 2;
                fallbackCtx.lineCap = 'round';
                fallbackCtx.lineJoin = 'round';

                fallbackCanvas.addEventListener('mousedown', beginDraw, { passive: false });
                fallbackCanvas.addEventListener('mousemove', draw, { passive: false });
                fallbackCanvas.addEventListener('mouseup', endDraw, { passive: false });
                fallbackCanvas.addEventListener('mouseleave', endDraw, { passive: false });
                fallbackCanvas.addEventListener('touchstart', beginDraw, { passive: false });
                fallbackCanvas.addEventListener('touchmove', draw, { passive: false });
                fallbackCanvas.addEventListener('touchend', endDraw, { passive: false });

                usingFallbackPad = true;
                return true;
            }

            function initSignaturePad() {
                if (typeof $.fn.signature !== 'function') {
                    return initFallbackSignaturePad();
                }
                if ($('#sig').data('signature')) {
                    return true;
                }
                sig = $('#sig').signature({syncField: '#signature64', syncFormat: 'PNG'});
                usingFallbackPad = false;
                return true;
            }



            $(".btn-sign").click(function(){

                $('#myModal').modal('show');
            });

            $('#myModal').on('shown.bs.modal', function () {
                var ready = initSignaturePad();
                // Ensure canvas is visible and responsive inside modal
                if (ready && $('#sig').data('signature')) {
                    $('#sig').signature('resize');
                }
            });

            $('#clear').click(function(e) {
                e.preventDefault();
                if (usingFallbackPad && fallbackCanvas && fallbackCtx) {
                    fallbackCtx.clearRect(0, 0, fallbackCanvas.width, fallbackCanvas.height);
                    hasFallbackStroke = false;
                } else if ($('#sig').data('signature')) {
                    $('#sig').signature('clear');
                }
                $("#signature64").val('');
            });

            $('#ContractSignForm').on('submit', function(e) {

                e.preventDefault();
                if (!$('#sig').data('signature') && !usingFallbackPad) {
                    var ready = initSignaturePad();
                    if (!ready) {
                        toastr.error('Signature pad is not ready. Please refresh and try again.');
                        return;
                    }
                }

                if (usingFallbackPad && !hasFallbackStroke) {
                    toastr.error('Please add your signature before submitting.');
                    return;
                }

                if (!$("#signature64").val()) {
                    toastr.error('Please add your signature before submitting.');
                    return;
                }

                let formData = new FormData($('#ContractSignForm')[0])

                $.ajax({
                    type: "POST",
                    url: '{{ url('sign-contract') }}',
                    data: formData ,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('.btn-submit').text('Saving...');
                        $(".btn-submit").prop("disabled", true);
                    },
                    success: function(data) {
                        console.log(data);
                        if (data.success) {
                            $('#ContractSignForm')[0].reset();
                            $('.close').click();
                            toastr.success(data.success);
                            window.location.reload();
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

                    error: function() {

                        toastr.error('any technical error');
                        $('.btn-submit').text('Save');
                        $(".btn-submit").prop("disabled", false);
                    }
                });


            });




        });
    </script>

@endsection