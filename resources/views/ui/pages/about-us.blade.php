@extends('ui.layouts.frontend')
@section('title', '| About-Us')
@section('content')


    <div class="ltn__utilize-overlay"></div>

    <!-- BREADCRUMB AREA START -->
    <div class="ltn__breadcrumb-area text-left bg-overlay-white-30 bg-image "  data-bg="{{ asset('sk-assets/assets/images/frontend/bg/Inner_Small_Banner_1.jpg') }}">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ltn__breadcrumb-inner">
                        <h1 class="page-title">About Us</h1>
                        <div class="ltn__breadcrumb-list">
                            <ul>
                                <li><a href="index.html"><span class="ltn__secondary-color"><i class="fas fa-home"></i></span> Home</a></li>
                                <li>About Us</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- BREADCRUMB AREA END -->

    <!-- ABOUT US AREA START -->
    <div class="ltn__about-us-area pt-120--- pb-20">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 align-self-center">
                    <div class="about-us-img-wrap about-img-left">
                        <img src="{{ asset('sk-assets/assets/images/frontend/web/Image_7.png') }}" alt="About Us Image">
                    </div>
                </div>
                <div class="col-lg-6 align-self-center">
                    <div class="about-us-info-wrap">
                        <div class="section-title-area ltn__section-title-2">
                            <h6 class="section-subtitle ltn__secondary-color"><span><i class="fas fa-square-full"></i></span> About Us</h6>
                            <h1 class="section-title">Solutions For your needs!</h1>
                            <p>We began operations as an LLC. in the UAE in 2016. Our founders wanted to eliminate the sweat, cost, and hassle from the
                            experience of moving and storage in UAE to make it feel like a breeze. They set out to build a storage company in
                            Sharjah that offers premium services to clients at reduced cost.</p>
                        </div>
                        <p>
                            <ul>
                                <li>Highly competitive rates</li>
                                <li>Climate-controlled and spotless individual storage units</li>
                                <li>24/7 access to your pod</li>
                                <li>Accessible UAE warehouse via Emirates Bypass Road</li>
                                <li>State-of-the-art IP security and surveillance by fully-vetted professionals</li>
                                <li>Automatic and comprehensive insurance coverage</li>
                                <li>Flexible lease options and easily scalable storage solutions</li>
                                <li>Wide loading/unloading bays to accommodate items of all sizes</li>
                            </ul>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ABOUT US AREA END -->

    <!-- FEATURE START -->
    <div class="ltn__feature-area section-bg-1--- pt-120 pb-90">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-area ltn__section-title-2 text-center">
                        <h6 class="section-subtitle ltn__secondary-color"><span><i class="fas fa-square-full"></i></span> Our Services</h6>
                        <h1 class="section-title">Storage Solution</h1>
                    </div>
                </div>
            </div>
            <div class="row align-self-center">
                <div class="col-lg-3 col-sm-6">                            
                    <div class="ltn__feature-item ltn__feature-item-6 box-shadow-1">
                        <div class="ltn__feature-icon">
                            <span><i class="flaticon-apartment"></i></span>
                        </div>
                        <div class="ltn__feature-info">
                            <h3><a href="#">Personal Storage</a></h3>
                            <p>over 1 million+ homes for sale available
                                on the website, we can match you with a
                                house you will want to call home.</p>
                            <a class="ltn__service-btn ltn__service-btn-2" href="#">Service Details <i class="flaticon-right-arrow"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">                            
                    <div class="ltn__feature-item ltn__feature-item-6 box-shadow-1">
                        <div class="ltn__feature-icon">
                            <span><i class="flaticon-excavator"></i></span>
                        </div>
                        <div class="ltn__feature-info">
                            <h3><a href="#">Business Storage</a></h3>
                            <p>over 1 million+ homes for sale available
                                on the website, we can match you with a
                                house you will want to call home.</p>
                            <a class="ltn__service-btn ltn__service-btn-2" href="#">Service Details <i class="flaticon-right-arrow"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">                            
                    <div class="ltn__feature-item ltn__feature-item-6 box-shadow-1">
                        <div class="ltn__feature-icon">
                            <span><i class="icon-repair"></i></span>
                        </div>
                        <div class="ltn__feature-info">
                            <h3><a href="#">Warehouse Storage</a></h3>
                            <p>over 1 million+ homes for sale available
                                on the website, we can match you with a
                                house you will want to call home.</p>
                            <a class="ltn__service-btn ltn__service-btn-2" href="#">Service Details <i class="flaticon-right-arrow"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">                            
                    <div class="ltn__feature-item ltn__feature-item-6 box-shadow-1">
                        <div class="ltn__feature-icon">
                            <span><i class="flaticon-excavator"></i></span>
                        </div>
                        <div class="ltn__feature-info">
                            <h3><a href="#">Moving</a></h3>
                            <p>over 1 million+ homes for sale available
                                on the website, we can match you with a
                                house you will want to call home.</p>
                            <a class="ltn__service-btn ltn__service-btn-2" href="#">Service Details <i class="flaticon-right-arrow"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- FEATURE END -->
{{-- 
    <!-- VIDEO AREA START -->
    <div class="ltn__video-popup-area ltn__video-popup-margin---">
        <div class="ltn__video-bg-img ltn__video-popup-height-600--- bg-overlay-black-30 bg-image bg-fixed ltn__animation-pulse1" data-bg="img/bg/37.jpg">
            <a class="ltn__video-icon-2 ltn__video-icon-2-border---" href="https://www.youtube.com/embed/X7R-q9rsrtU?autoplay=1&showinfo=0" data-rel="lightcase:myCollection">
                <i class="fa fa-play"></i>
            </a>
        </div>
    </div>
    <!-- VIDEO AREA END --> --}}

    <!-- CALL TO ACTION START (call-to-action-6) -->
    <div class="ltn__call-to-action-area call-to-action-6 before-bg-bottom---" data-bg="img/1.jpg--">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="call-to-action-inner call-to-action-inner-6 ltn__secondary-bg text-center---">
                        <div class="coll-to-info text-color-white">
                            <h1>Looking for a secure storage?</h1>
                            <p>We can help you realize your secure storage</p>
                        </div>
                        <div class="btn-wrapper">
                            <a class="btn btn-effect-3 btn-white" href="{{url('booking')}}">Get A Quote <i class="icon-next"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- CALL TO ACTION END -->

    <!-- TESTIMONIAL AREA START (testimonial-8) -->
    <div class="ltn__testimonial-area section-bg-1--- pt-120 pb-70">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-area ltn__section-title-2 text-center">
                        <h6 class="section-subtitle ltn__secondary-color"><span><i class="fas fa-square-full"></i></span> Client,s Testimonial</h6>
                        <h1 class="section-title">Client's Feedback</h1>
                    </div>
                </div>
            </div>
            <div class="row ltn__testimonial-slider-6-active slick-arrow-1">
                <div class="col-lg-4">
                    <div class="ltn__testimonial-item ltn__testimonial-item-7 ltn__testimonial-item-8">
                        <div class="ltn__testimoni-info">
                            <div class="ltn__testimoni-author-ratting">
                                <div class="ltn__testimoni-info-inner">
                                    <div class="ltn__testimoni-img">
                                        <img src="{{ asset('sk-assets/assets/images/frontend/testimonial/1.jpg') }}" alt="#">
                                    </div>
                                    <div class="ltn__testimoni-name-designation">
                                        <h5>Nadeem Baig</h5>
                                        <label>Local Guide</label>
                                    </div>
                                </div>
                                <div class="ltn__testimoni-rating">
                                    <div class="product-ratting">
                                        <ul>
                                            <li><a href="#"><i class="fas fa-star"></i></a></li>
                                            <li><a href="#"><i class="fas fa-star"></i></a></li>
                                            <li><a href="#"><i class="fas fa-star"></i></a></li>
                                            <li><a href="#"><i class="fas fa-star"></i></a></li>
                                            <li><a href="#"><i class="fas fa-star"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <p>
                                Best place to store your commercial and household items. Climate controlled environment, CCTV camera and above of all you can access your storage space 24/7 with nice and welcoming staff.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="ltn__testimonial-item ltn__testimonial-item-7 ltn__testimonial-item-8">
                        <div class="ltn__testimoni-info">
                            <div class="ltn__testimoni-author-ratting">
                                <div class="ltn__testimoni-info-inner">
                                    <div class="ltn__testimoni-img">
                                        <img src="{{ asset('sk-assets/assets/images/frontend/testimonial/2.jpg') }}" alt="#">
                                    </div>
                                    <div class="ltn__testimoni-name-designation">
                                        <h5>Ashik S</h5>
                                        <label>Local Guide</label>
                                    </div>
                                </div>
                                <div class="ltn__testimoni-rating">
                                    <div class="product-ratting">
                                        <ul>
                                            <li><a href="#"><i class="fas fa-star"></i></a></li>
                                            <li><a href="#"><i class="fas fa-star"></i></a></li>
                                            <li><a href="#"><i class="fas fa-star"></i></a></li>
                                            <li><a href="#"><i class="fas fa-star"></i></a></li>
                                            <li><a href="#"><i class="fas fa-star"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <p>
                                I have been storing my business goods with them for over 6 months now. The staff at the location as well as the admin staffs are very friendly.
                                The facility is well maintained and the location is great.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="ltn__testimonial-item ltn__testimonial-item-7 ltn__testimonial-item-8">
                        <div class="ltn__testimoni-info">
                            <div class="ltn__testimoni-author-ratting">
                                <div class="ltn__testimoni-info-inner">
                                    <div class="ltn__testimoni-img">
                                        <img src="{{ asset('sk-assets/assets/images/frontend/testimonial/3.jpg') }}" alt="#">
                                    </div>
                                    <div class="ltn__testimoni-name-designation">
                                        <h5>Muhammed Alshebli</h5>
                                        <label>Local Guide</label>
                                    </div>
                                </div>
                                <div class="ltn__testimoni-rating">
                                    <div class="product-ratting">
                                        <ul>
                                            <li><a href="#"><i class="fas fa-star"></i></a></li>
                                            <li><a href="#"><i class="fas fa-star"></i></a></li>
                                            <li><a href="#"><i class="fas fa-star"></i></a></li>
                                            <li><a href="#"><i class="fas fa-star"></i></a></li>
                                            <li><a href="#"><i class="fas fa-star"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <p>The arrangement of the storage rooms is wonderful and clean, and the place is equipped with public safety means, in addition to observing the implementation of sterilization and spraying of pesticides as a proactive protection against insects.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="ltn__testimonial-item ltn__testimonial-item-7 ltn__testimonial-item-8">
                        <div class="ltn__testimoni-info">
                            <div class="ltn__testimoni-author-ratting">
                                <div class="ltn__testimoni-info-inner">
                                    <div class="ltn__testimoni-img">
                                        <img src="{{ asset('sk-assets/assets/images/frontend/testimonial/4.jpg') }}" alt="#">
                                    </div>
                                    <div class="ltn__testimoni-name-designation">
                                        <h5>pooja nayal</h5>
                                        <label>Local Guide</label>
                                    </div>
                                </div>
                                <div class="ltn__testimoni-rating">
                                    <div class="product-ratting">
                                        <ul>
                                            <li><a href="#"><i class="fas fa-star"></i></a></li>
                                            <li><a href="#"><i class="fas fa-star"></i></a></li>
                                            <li><a href="#"><i class="fas fa-star"></i></a></li>
                                            <li><a href="#"><i class="fas fa-star"></i></a></li>
                                            <li><a href="#"><i class="fas fa-star"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <p>
                                We took the warehouse for a long time and their services are very good, fast and very friendly yet professional staff. Thank you</p>
                        </div>
                    </div>
                </div>
                <!--  -->
            </div>
        </div>
    </div>
    <!-- TESTIMONIAL AREA END -->


@endsection