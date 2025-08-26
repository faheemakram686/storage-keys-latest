@extends('ui.layouts.frontend2')
@section('title', '| Contact-Us')
@section('content')

    <div class="ltn__utilize-overlay"></div>

    <!-- BREADCRUMB AREA START -->
    <div class="ltn__breadcrumb-area text-left bg-overlay-white-30 bg-image "  data-bg="{{ asset('sk-assets/assets/images/frontend/bg/Inner_Small_Banner_2.jpg') }}">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ltn__breadcrumb-inner">
                        <h1 class="page-title">Contact Us</h1>
                        <div class="ltn__breadcrumb-list">
                            <ul>
                                <li><a href="index.html"><span class="ltn__secondary-color"><i class="fas fa-home"></i></span> Home</a></li>
                                <li>Contact</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- BREADCRUMB AREA END -->

    <!-- CONTACT ADDRESS AREA START -->
    <div class="ltn__contact-address-area mb-90">
        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    <div class="ltn__contact-address-item ltn__contact-address-item-3 box-shadow">
                        <div class="ltn__contact-address-icon">
                            <img src="{{ asset('sk-assets/assets/images/frontend/icons/10.png') }}" alt="Icon Image">
                        </div>
                        <h3>Email Address</h3>
                        <p>info@storagekeys.com<br></p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="ltn__contact-address-item ltn__contact-address-item-3 box-shadow">
                        <div class="ltn__contact-address-icon">
                            <img src="{{ asset('sk-assets/assets/images/frontend/icons/11.png') }}" alt="Icon Image">
                        </div>
                        <h3>Phone Number</h3>
                        <p>+971 56 501 8785<br></p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="ltn__contact-address-item ltn__contact-address-item-3 box-shadow">
                        <div class="ltn__contact-address-icon">
                            <img src="{{ asset('sk-assets/assets/images/frontend/icons/12.png') }}" alt="Icon Image">
                        </div>
                        <h3>Office Address</h3>
                        <p>Storage Keys, Plot # 4202 - Sharjah - Dubai - United Arab Emirates</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- CONTACT ADDRESS AREA END -->
    
    <!-- CONTACT MESSAGE AREA START -->
    <div class="ltn__contact-message-area mb-50">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ltn__form-box contact-form-box box-shadow white-bg">
                        <h4 class="title-2">Get A Quote</h4>
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form id="contact-form" action="{{ route('inquiry.store') }}" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-item input-item-name ltn__custom-icon">
                                        <input type="text" name="name" placeholder="Enter your name" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-item input-item-email ltn__custom-icon">
                                        <input type="email" name="email" placeholder="Enter email address" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-item">
                                        <select class="nice-select" name="storage_type" required>
                                            <option value="">Select Storage Type</option>
                                            <option value="Personal Storage">Personal Storage</option>
                                            <option value="Business Storage">Business Storage</option>
                                            <option value="Warehouse Storage">Warehouse Storage</option>
                                            <option value="Moving">Moving</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-item input-item-phone ltn__custom-icon">
                                        <input type="text" name="phone" placeholder="Enter phone number" required>
                                    </div>
                                </div>
                            </div>
                            <div class="input-item input-item-textarea ltn__custom-icon">
                                <textarea name="message" placeholder="Enter message"></textarea>
                            </div>
                            <div class="btn-wrapper mt-0">
                                <button class="btn theme-btn-1 btn-effect-1 text-uppercase" type="submit">get a free service</button>
                            </div>
                            <p class="form-messege mb-0 mt-20"></p>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- CONTACT MESSAGE AREA END -->

{{--    <!-- GOOGLE MAP AREA START -->--}}
{{--    <div class="google-map" style="height: 500px;">--}}
{{--       --}}
{{--        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d9334.271551495209!2d-73.97198251485975!3d40.668170674982946!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c25b0456b5a2e7%3A0x68bdf865dda0b669!2sBrooklyn%20Botanic%20Garden%20Shop!5e0!3m2!1sen!2sbd!4v1590597267201!5m2!1sen!2sbd" width="100%" height="450" frameborder="0" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>--}}
{{--        <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d7211.064523474084!2d55.63168!3d25.353472!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x2db4222312dfc94f!2sStorage%20Keys%20Sharjah!5e0!3m2!1sen!2sus!4v1668893743747!5m2!1sen!2sus" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"--}}
{{--                referrerpolicy="no-referrer-when-downgrade"></iframe>--}}
{{--    </div>--}}
{{--    <!-- GOOGLE MAP AREA END -->--}}
    <!-- BLOG AREA START (blog-3) -->
    <div class="ltn__blog-area pt-40 pb-20">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-area ltn__section-title-2 text-center">
                        <h6 class="section-subtitle ltn__secondary-color"><span><i class="fas fa-square-full"></i></span> Geographic Location</h6>
                        <h1 class="section-title">Map Location</h1>
                    </div>
                </div>
            </div>
            <iframe
                    src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d7211.064523474084!2d55.63168!3d25.353472!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x2db4222312dfc94f!2sStorage%20Keys%20Sharjah!5e0!3m2!1sen!2sus!4v1668893743747!5m2!1sen!2sus"
                    width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </div>

@endsection