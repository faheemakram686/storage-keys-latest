@extends('ui.layouts.frontend2')
@section('title', '| Checkout')
@section('content')


    <!-- Utilize Mobile Menu End -->

    <div class="ltn__utilize-overlay"></div>

    <!-- BREADCRUMB AREA START -->
    <div class="ltn__breadcrumb-area text-left bg-overlay-white-30 bg-image "  data-bg="{{ asset('sk-assets/assets/images/frontend/bg/Inner_Small_Banner_3.jpg') }}">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ltn__breadcrumb-inner">
                        <h1 class="page-title">Checkout</h1>
                        <div class="ltn__breadcrumb-list">
                            <ul>
                                <li><a href="{{url('/')}}"><span class="ltn__secondary-color"><i class="fas fa-home"></i></span> Home</a></li>
                                <li>Checkout</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- BREADCRUMB AREA END -->

    <!-- WISHLIST AREA START -->
    <div class="ltn__checkout-area mb-105">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ltn__checkout-inner">
                        <div class="ltn__checkout-single-content mt-50">
                            <h4 class="title-2">Billing Details</h4>
                            <div class="ltn__checkout-single-content-info">
                                <form action="{{route('order.save')}}" method="post" id="place_order" >
                                    @csrf
                                    <input type="hidden" name="customer_id" value="{{Auth::user()->customer_id}}">
                                    <h6>Personal Information</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="input-item input-item-name ltn__custom-icon">
                                                <input type="text" name="first_name" placeholder="First name" value="{{Auth::user()->first_name}}" required disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-item input-item-name ltn__custom-icon">
                                                <input type="text" name="last_name" placeholder="Last name" value="{{Auth::user()->last_name}}" required disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-item input-item-email ltn__custom-icon">
                                                <input type="email" name="email" placeholder="email address" value="{{Auth::user()->email}}"  required disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-item input-item-phone ltn__custom-icon">
                                                <input type="text" name="phone" placeholder="phone number" value="{{Auth::user()->phone}}" required disabled>
                                            </div>
                                        </div>
{{--                                        <div class="col-md-6">--}}
{{--                                            <div class="input-item input-item-website ltn__custom-icon">--}}
{{--                                                <input type="text" name="company" placeholder="Company name (optional)">--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                        <div class="col-md-6">--}}
{{--                                            <div class="input-item input-item-website ltn__custom-icon">--}}
{{--                                                <input type="text" name="company_address" placeholder="Company address (optional)">--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
                                    </div>
{{--                                    <div class="row">--}}
{{--                                        <div class="col-lg-4 col-md-6">--}}
{{--                                            <h6>Country</h6>--}}
{{--                                            <div class="input-item">--}}
{{--                                                <select name="country" class="nice-select" required>--}}
{{--                                                    <option value="">Select Country</option>--}}
{{--                                                    <option value="United Arab Emirates">United Arab Emirates (UAE)</option>--}}
{{--                                                    <option value="Saudi Arabia" >Saudi Arabia</option>--}}
{{--                                                    <option value="Australia">Australia</option>--}}
{{--                                                    <option value="Canada">Canada</option>--}}
{{--                                                    <option value="China">China</option>--}}
{{--                                                    <option value="United Kingdom">United Kingdom (UK)</option>--}}
{{--                                                    <option value="United States">United States (US)</option>--}}
{{--                                                </select>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                        <div class="col-lg-12 col-md-12">--}}
{{--                                            <h6>Address</h6>--}}
{{--                                            <div class="row">--}}
{{--                                                <div class="col-md-6">--}}
{{--                                                    <div class="input-item">--}}
{{--                                                        <input type="text" name="address1" placeholder="House number and street name" required>--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
{{--                                                <div class="col-md-6">--}}
{{--                                                    <div class="input-item">--}}
{{--                                                        <input type="text" name="address2" placeholder="Apartment, suite, unit etc. (optional)">--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                        <div class="col-lg-4 col-md-6">--}}
{{--                                            <h6>Town / City</h6>--}}
{{--                                            <div class="input-item">--}}
{{--                                                <input type="text" placeholder="City" name="city" required>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                        <div class="col-lg-4 col-md-6">--}}
{{--                                            <h6>State </h6>--}}
{{--                                            <div class="input-item">--}}
{{--                                                <input type="text" placeholder="State" name="state" required>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                        <div class="col-lg-4 col-md-6">--}}
{{--                                            <h6>Zip</h6>--}}
{{--                                            <div class="input-item">--}}
{{--                                                <input type="text" placeholder="Zip" name="zip" required>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                    <p><label class="input-info-save mb-0"><input type="checkbox" name="agree"> Create an account?</label></p>--}}
                                    <h6>Order Notes (optional)</h6>
                                    <div class="input-item input-item-textarea ltn__custom-icon">
                                        <textarea name="notes" placeholder="Notes about your order, e.g. special notes for delivery."></textarea>
                                    </div>
                                    <input name="payment_method" type="hidden" value="cash on delivery">
                                    <input name="total_amount" type="hidden" value="{{Cart::getTotal()}}">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="ltn__checkout-payment-method mt-50">
                        <h4 class="title-2">Payment Method</h4>
                        <div id="checkout_accordion_1">
                            <!-- card -->
                            <div class="card">
                                <h5 class="ltn__card-title" data-toggle="collapse" data-target="#faq-item-2-2" aria-expanded="true">
                                    Cash on delivery
                                </h5>
                                <div id="faq-item-2-2" class="collapse show" data-parent="#checkout_accordion_1">
                                    <div class="card-body">
                                        <p>Pay with cash upon delivery.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="ltn__payment-note mt-30 mb-30">
                            <p>Your personal data will be used to process your order, support your experience throughout this website, and for other purposes described in our privacy policy.</p>
                        </div>
                        <button  form="place_order" class="btn btn-submit theme-btn-1 btn-effect-1 text-uppercase" type="submit">Place order</button>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="shoping-cart-total mt-50">
                        <h4 class="title-2">Cart Totals</h4>
                        <table class="table">
                            <tbody>
                            @foreach ($cartItems as $item)
                            <tr>
                                <td>{{ $item->name }}<strong>× {{ $item->quantity }}</strong></td>
                                <td>{{ $item->price * $item->quantity }}</td>
                            </tr>
                            @endforeach
                            <tr>
                                <td><strong>Order Total</strong></td>
                                <td><strong>{{Cart::getTotal()}}</strong></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- WISHLIST AREA START -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        $(document).ready(function() {



            $('#place_order').on('submit', function(e) {
                e.preventDefault();
                let formData = new FormData($('#place_order')[0])

                $.ajax({
                    type: "post",
                    url: '{{ route('order.save') }}',
                    data: formData,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('.btn-submit').text('Saving...');
                        $(".btn-submit").prop("disabled", true);
                    },
                    success: function(data) {
                        if (data.success) {
                            $('#place_order')[0].reset();
                            toastr.success(data.success);
                            window.location.href = "{{ url('shop')}}";
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