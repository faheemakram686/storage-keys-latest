@extends('ui.layouts.frontend')
@section('title', '| Bookings')
@section('content')
    @isset($data)
        @foreach ($data['su'] as $su)
    <div class="justify-content-center checkout-page">
        <div class="container">
            <div class="row">
                <div class="offset-lg-1 offset-md-1 col-12 col-sm-12 col-md-11 col-lg-11 greeting-user">
                    <h3 class="animated fadeIn" style="color:#FF8820;" >{{$su->warehouse->loc->city->city_name}} <span class="area-name">-{{$su->warehouse->loc->loc_name}}- {{$su->warehouse->name}} - {{$su->storage_unit_name}}</span> </h3>
                </div>
{{--                <div class="offset-lg-1 offset-md-1 col-12 col-sm-12 col-md-11 col-lg-11 selected-plot-message"> <p> {{$su->warehouse->loc->loc_name}}- {{$su->warehouse->name}} - {{$su->storage_unit_name}}</p></div>--}}
            </div>

            <div class="row">
                <div class="col-12 col-sm-12 col-md-12 col-lg-12 details-section">
                    <form method="post" action="{{ url('save-lead') }}" id="LeadForm">
                        @csrf
                        <input type="hidden" name="id" value="{{$su->id}}">

                    <div class="row reservations-sections">
                        <div class="offset-sm-2 offset-md-2 offset-lg-2 offset-1 col-8 col-sm-6 col-md-4 col-lg-4 term-section-header">
                            Contact Information</div>
                        <div class="offset-md-1 offset-lg-1 col-12 col-sm-12 col-md-10 col-lg-10 term-section-body">
                            <div class="row">
                                <div class="offset-lg-1 offset-md-1 col-12 col-sm-12 col-md-10 col-lg-10">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-check">
                                                <input class="form-check-input" name="type" type="radio" value="individual"  id="ind" />
                                                <label class="check-container" for="ind">For Individual?</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-check">
                                                <input class="form-check-input" name="type" type="radio" value="company"  id="com" />
                                                <label class="check-container" for="com">For Company?</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-3">

                                        <div class="col-6">
                                            <label class="">Requested date</label>
                                            <input type="date" class="form-control" name="r_date" style="height:35px;" required>
                                        </div>
                                        <div class="col-6" id="companyfeild">
                                            <label class="">Company Name</label>
                                            <input type="text" class="form-control" name="company_name" style="height:35px;" >
                                        </div>

                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-6">
                                            <label class="">First Name</label>
                                            <input type="text" class="form-control" name="f_name" style="height:35px;" required>
                                        </div>
                                        <div class="col-6">
                                            <label class="">Last Name</label>
                                            <input type="text" class="form-control" name="l_name" style="height:35px;" required>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-6">
                                            <label class="">Email</label>
                                            <input type="email" class="form-control" name="email" style="height:35px;" required>
                                        </div>
                                        <div class="col-6">
                                            <label class="">Phone</label>
                                            <input type="text" class="form-control" name="phone" style="height:35px;" required>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-6">
                                            <label class="">Mobile 1</label>
                                            <input type="text" class="form-control" name="mobile1" style="height:35px;">
                                        </div>
                                        <div class="col-6">
                                            <label class="">Mobile 2</label>
                                            <input type="text" class="form-control" name="mobile2" style="height:35px;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row reservations-sections">
                        <div class="offset-sm-2 offset-md-2 offset-lg-2 offset-1 col-8 col-sm-6 col-md-4 col-lg-4 term-section-header">
                            Select term length</div>
                        <div class="offset-md-1 offset-lg-1 col-12 col-sm-12 col-md-10 col-lg-10 term-section-body">
                            <div class="row">
                                <div class="offset-lg-1 offset-md-1 col-12 col-sm-12 col-md-10 col-lg-10">
                                    <p>Select longer periods to enjoy massive savings!</p>
                                    @isset($data['term_length'])
                                        @foreach($data['term_length'] as $term_length)
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-check">
                                                <input class="form-check-input" name="price" type="radio" value="{{$term_length->id}}"  id="flexCheckDefault" />
                                                <label class="check-container" for="flexCheckDefault">{{$term_length->title}}</label>
                                            </div>
                                        </div>
                                        @if($term_length->term_period == 1)
                                        <div class="col-6">
                                            <p class="no-bottom-margin text-right">Fixed Price</p>
                                        </div>
                                        @else
                                            <div class="col-6">
{{--                                                <p class="no-bottom-margin text-right">AED {{$su->price}}/mo</p>--}}
{{--                                                <p class="no-bottom-margin text-right"><del>AED {{$su->price}}/mo</del></p>--}}
                                                <p class="no-bottom-margin text-right on-sale-text">On Sale (Save {{$term_length->discount_percentage}}%)</p>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="separator"></div>
                                        @endforeach
                                    @endisset

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row padlock-sections">
                        <div class="offset-sm-2 offset-md-2 offset-lg-2 offset-1 col-8 col-sm-6 col-md-4 col-lg-4 padlock-section-header">
                            Addons</div>
                        <div class="offset-md-1 offset-lg-1 col-12 col-sm-12 col-md-10 col-lg-10 padlock-section-body">
                            <div class="row">
                                <div class="offset-lg-1 offset-md-1 col-12 col-sm-12 col-md-10 col-lg-10">
                                    <div class="row">
                                @isset($data['addon'])
                                    @foreach ($data['addon'] as $addon)
                                        <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-checkbox">
                                            <div class="form-check">
                                                <input class="form-check-input" name="addon[]" id="addon_id" type="checkbox" value="{{$addon->id}}"  id="flexCheckDefault" />
                                                <label class="check-container" for="flexCheckDefault">{{$addon->name}}</label>
                                            </div>
                                        </div>
                                    @endforeach
                                @endisset
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row insurance-sections">
                        <div class="offset-sm-2 offset-md-2 offset-lg-2 offset-1 col-8 col-sm-6 col-md-4 col-lg-4 insurance-section-header">
                            Insurance</div>
                        <div class="offset-md-1 offset-lg-1 col-12 col-sm-12 col-md-10 col-lg-10 insurance-section-body">
                            <div class="row">
                                <div class="offset-lg-1 offset-md-1 col-12 col-sm-12 col-md-10 col-lg-10">
                                    <p>Insure your goods</p>
                                    <div class="separator"></div>
                                    <div class="row">
                                        <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                            <div class="form-check">
                                                <input class="form-check-input" name="insurance" type="radio" value="cover"  id="flexCheckDefault" />
                                                <label class="check-container" for="flexCheckDefault">Choose your own cover (100 AED per 100,000 AED cover)</label>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                            <p class="text-right">AED 25.00/mo</p>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                            <input type="text" class="form-control" placeholder="Enter value of your goods" name="goodsval" style="height:35px;">
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                            <p class="text-right">Cover AED 25000.00</p>
                                        </div>
                                    </div>
                                    <div class="separator"></div>

                                    <div class="form-check">
                                        <input class="form-check-input" name="insurance" type="radio" value="nothanks"  id="flexCheckDefault" />
                                        <label class="check-container" for="flexCheckDefault">No Thanks</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row terms-conditions-sections">
                        <div class="offset-sm-2 offset-md-2 offset-lg-2 offset-1 col-8 col-sm-6 col-md-4 col-lg-4 terms-conditions-section-header">
                            TERMS &amp; CONDITIONS
                        </div>
                        <div class="offset-md-1 offset-lg-1 col-12 col-sm-12 col-md-10 col-lg-7 terms-conditions-section-body">
                            <div class="row">
                                <div class="offset-lg-1 offset-md-1 col-12 col-sm-12 col-md-10 col-lg-10">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="terms" value="agree"  id="flexCheckDefault" required />
                                        <label class="check-container" for="flexCheckDefault">I agree to the standard terms and conditions of storage
                                            keys<a href="#" data-toggle="modal" data-target="#quick_view_modal" class="form-link"> (click here)</a></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row submission-sections">
                        <button class="offset-md-1 offset-lg-1 btn btn-qoutation btn-sm active btn-submit float-end" type="submit">SUBMIT</button>
                    </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
        @endforeach
    @endisset

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        $(document).ready(function() {

            $("#companyfeild").hide();

            $("input[name='type']").click(function() {
                if ($("#com").is(":checked")) {
                    $("#companyfeild").show();
                } else {
                    $("#companyfeild").hide();
                }
            });

            $('#LeadForm').on('submit', function(e) {
                e.preventDefault();

                if (!$('input[name="terms"]').is(':checked')) {
                    toastr.error('Please agree to the terms and conditions.');
                    return false;
                }

               var formData=$('#LeadForm').serialize()
                $.ajax({
                    type: "get",
                    url: '{{ url('/save-lead') }}',
                    data: formData,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('.btn-submit').text('Saving...');
                        $(".btn-submit").prop("disabled", true);
                    },
                    success: function(data) {
                        if (data.success) {
                            $('#LeadForm')[0].reset();
                            toastr.success(data.message);
                            window.location.href = "{{ url('booking')}}";
                        }else {
                            toastr.error(data.message);
                        }
                        if (data.errors) {
                            // toastr.error(data.errors);
                            $('.btn-submit').text('Save');
                            $(".btn-submit").prop("disabled", false);
                        }
                    },
                    complete: function(data) {
                        $(".btn-submit").html("Save");
                        $(".btn-submit").prop("disabled", false);
                    },
                    error: function(xhr) {
                        toastr.error('any technical error');
                        $('.btn-submit').text('Save');
                        $(".btn-submit").prop("disabled", false);
                    }
                });
            });
        });
    </script>

    <!-- MODAL AREA START (Quick View Modal) -->
    <!-- PREMIUM TERMS MODAL -->
    <div class="modal fade" id="quick_view_modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content" style="border: none; border-radius: 16px; box-shadow: 0 20px 40px rgba(0,0,0,0.2); overflow: hidden; font-family: 'Inter', sans-serif;">
                <div class="modal-header" style="background: linear-gradient(135deg, #FF8820 0%, #FF6B00 100%); border: none; padding: 25px 30px; position: relative;">
                    <h4 class="modal-title" style="color: #ffffff; font-weight: 700; margin: 0; font-size: 1.4rem; letter-spacing: -0.02em;">Terms & Conditions</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="position: absolute; right: 20px; top: 50%; transform: translateY(-50%); background: rgba(255,255,255,0.2); border: none; width: 36px; height: 36px; border-radius: 50%; color: white; display: flex; align-items: center; justify-content: center; opacity: 1; transition: all 0.3s ease; cursor: pointer;">
                        <span aria-hidden="true" style="font-size: 24px; line-height: 1;">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="padding: 40px; background: #ffffff;">
                    <div style="max-height: 450px; overflow-y: auto; padding-right: 15px; custom-scrollbar;">
                        <div style="font-size: 15px; line-height: 1.8; color: #4A5568; text-align: justify;">
                            @isset($data['terms_conditions'][9])
                                <div class="terms-content">
                                    {!! $data['terms_conditions'][9]->value !!}
                                </div>
                            @else
                                <div style="text-align: center; padding: 40px; color: #A0AEC0;">
                                    <em class="icon ni ni-info-fill" style="font-size: 48px; display: block; margin-bottom: 15px;"></em>
                                    <p>Terms and conditions content is currently unavailable.</p>
                                </div>
                            @endisset
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="background: #F7FAFC; border-top: 1px solid #EDF2F7; padding: 20px 40px; display: flex; justify-content: flex-end;">
                    <button type="button" class="btn" data-dismiss="modal" style="background: #2D3748; color: white; padding: 12px 30px; border-radius: 8px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; border: none; transition: background 0.3s ease;">Close Document</button>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Custom Scrollbar for Premium Feel */
        [custom-scrollbar]::-webkit-scrollbar {
            width: 6px;
        }
        [custom-scrollbar]::-webkit-scrollbar-track {
            background: #F1F5F9;
            border-radius: 10px;
        }
        [custom-scrollbar]::-webkit-scrollbar-thumb {
            background: #CBD5E0;
            border-radius: 10px;
        }
        [custom-scrollbar]::-webkit-scrollbar-thumb:hover {
            background: #A0AEC0;
        }
        .modal-content {
            animation: modalSlideUp 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }
        @keyframes modalSlideUp {
            from { transform: translateY(30px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .terms-content p { margin-bottom: 15px; }
        .terms-content ul, .terms-content ol { margin-bottom: 15px; padding-left: 20px; }
        .terms-content li { margin-bottom: 8px; }
        .terms-content h1, .terms-content h2, .terms-content h3 { margin-top: 20px; margin-bottom: 10px; color: #2D3748; }
        .form-link { color: #FF8820; font-weight: 600; text-decoration: none; transition: color 0.3s ease; }
        .form-link:hover { color: #FF6B00; text-decoration: underline; }
    </style>
    <!-- MODAL AREA END -->
@endsection