@extends('ui.layouts.frontend')
@section('title', '| Contract')

@section('content')
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.css">

    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <link type="text/css" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/south-street/jquery-ui.css" rel="stylesheet">
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="{{ asset('sk-assets/js/frontend/jquery.signature.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('sk-assets/css/frontend/jquery.signature.css') }}">
    @isset($data)
{{--    {{dd($data)}}--}}
    <div class="justify-content-center checkout-page">
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
                    <div class="row reservations-sections">
                        <div class="offset-sm-2 offset-md-2 offset-lg-2 offset-1 col-8 col-sm-6 col-md-4 col-lg-4 term-section-header">
                            Contract Content</div>
                        <div class="offset-md-1 offset-lg-1 col-12 col-sm-12 col-md-9 col-lg-7 term-section-body">
                            <div class="row">
                                <div class=" col-12 col-sm-12 col-md-10 col-lg-12">
                                    @isset($contract->contractTemplate)
                                    {!! $contract->contractTemplate->temp_body !!}
                                    @endisset
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-12 col-md-3 col-lg-3  order-summary" >
                            <div class="row locations-section" >
                                <div class="offset-2 offset-md-2 offset-lg-2 col-6 col-sm-6 col-md-8 col-lg-7 order-section-header"> Summary</div>
                                <div class="col-12 order-section-body">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-check">
                                                <label class="check-container" for="flexCheckDefault"><b>{{$contract->customer->company_name}}</b></label>
                                                <label class="check-container" for="flexCheckDefault">{{$contract->customer->address}}</label>
                                                <label class="check-container" for="flexCheckDefault">{{$contract->customer->city}}</label>
                                                <label class="check-container" for="flexCheckDefault">{{$contract->customer->country}}</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="separator-item"></div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-check">
                                                <label class="check-container" for="flexCheckDefault">Contract Value</label>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 d-flex">
                                            <span class="no-bottom-margin mt-1 text-right">AED </span>
                                            <span class="no-bottom-margin mt-1 text-right ml-1"> {{$contract->contract_value}} </span>
                                        </div>
                                    </div>

                                    <div class="separator-item"></div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-check">
                                                <label class="check-container" for="flexCheckDefault"># Contract Number</label>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 d-flex">
                                            <span class="no-bottom-margin mt-1 text-right">{{$contract->id}}</span>

                                        </div>
                                    </div>
                                    <div class="separator-item"></div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-check">
                                                <label class="check-container" for="flexCheckDefault">Start Date</label>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 d-flex">
                                            <span class="no-bottom-margin mt-1 text-right">{{$contract->start_date}} </span>

{{--                                            <span class="no-bottom-margin mt-1 text-right">/mo</span>--}}
                                        </div>
                                    </div>
                                    <div class="separator-item"></div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-check">
                                                <label class="check-container" for="flexCheckDefault">End Date</label>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 d-flex">
                                            <span class="no-bottom-margin mt-1 text-right">{{$contract->end_date }} </span>
{{--                                            <span class="no-bottom-margin mt-1 sub_total text-right ml-1 "  id="subtotal">{{$data['lead'][0]->estimateAddon->sum('price') + $data['su'][0]->price +  (( $data['lead'][0]->insurence == 'nothanks')? 0 : 25) }} </span>--}}
{{--                                            <span class="no-bottom-margin mt-1 text-right">/mo</span>--}}
                                        </div>
                                    </div>
                                    @if($contract->is_signed == 'Signed')
                                    <div class="separator-item"></div>
                                    <div class="row">
                                        @if($contract->sign_image != null)
                                            <div class="col-lg-12">
                                                <div class="text-center">
                                                <img src="{{ asset('storage/uploads/contract_sign_images').'/'.$contract->sign_image}}" alt="image not found" class="img-thumbnail" >
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    @endif
                                    <div class="separator-item"></div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-check">
                                                <a href={{url('contract-pdf/').'/'.$contract->id}} class="btn btn-qoutation btn-sm active mt-1 text-right" style="padding:8px;background-color: #F58320; color: #fff3e9" >Download</a>
                                            </div>
                                        </div>
                                        @if($contract->is_signed == 'Not Signed')
                                        <div class="col-lg-6 d-flex">
                                            <a href="#" class="btn btn-qoutation btn-sm active mt-1 text-right btn-sign " style="padding:8px;background-color: #F58320;color: #fff3e9">Sign</a>
                                        </div>
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
    <script type="text/javascript">
        var sig = $('#sig').signature({syncField: '#signature64', syncFormat: 'PNG'});
        $('#clear').click(function(e) {
            e.preventDefault();
            sig.signature('clear');
            $("#signature64").val('');
        });
    </script>

    <script>
        $(document).ready(function() {



            $(".btn-sign").click(function(){

                $('#myModal').modal('show');
            });
            $('#ContractSignForm').on('submit', function(e) {

                e.preventDefault();

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