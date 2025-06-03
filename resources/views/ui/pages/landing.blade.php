@extends('ui.layouts.frontend')
@section('title', '| Home')
@section('content')

   

    <div class="ltn__utilize-overlay"></div>



    <div class="ltn__slider-area ltn__slider-4 position-relative  ltn__primary-bg">
        <div class="ltn__slide-one-active----- slick-slide-arrow-1----- slick-slide-dots-1----- arrow-white----- ltn__slide-animation-active">
            <video autoplay muted loop id="myVideo">
                <source src="{{ asset('sk-assets/assets/images/frontend/Header_Image.mp4') }}" type="video/mp4">
            </video>
            <!-- ltn__slide-item -->
            <div class="ltn__slide-item ltn__slide-item-2 ltn__slide-item-7 bg-image--- bg-overlay-theme-black-20" data-bg="img/slider/41.jpg">
                <div class="ltn__slide-item-inner text-center">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-12 align-self-center">
                                <div class="slide-item-info">
                                    <div class="slide-item-info-inner ltn__slide-animation">
                                        <h6 class="slide-sub-title white-color animated"><span><i class="fas fa-home"></i></span>  Great Experience In Storages</h6>
                                        <h1 class="slide-title text-uppercase white-color animated ">In search of secure <br> storage facilities?</h1>

                                        <div class="btn-wrapper animated">
                                            <a href="{{ url('/booking') }}" class="theme-btn-1 btn btn-effect-1">Book Now</a>
                                            <a href="{{ url('/about-us') }}" class="theme-btn-2 btn btn-effect-2">Learn More</a>
                                        </div>
                                        <!-- <h1 class="slide-title text-uppercase white-color animated ">Find Your Dream <br> House By Us</h1> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>





    <!-- ABOUT US AREA END -->

    <!-- FEATURE START -->
    <div class="ltn__feature-area section-bg-1 bg-image pt-120">
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
                            <h3><a href="service-details.html">Personal Storage</a></h3>
                            <p>over 1 million+ homes for sale available
                                on the website, we can match you with a
                                house you will want to call home.</p>
                            <a class="ltn__service-btn ltn__service-btn-2" href="service-details.html">Service Details <i class="flaticon-right-arrow"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">                            
                    <div class="ltn__feature-item ltn__feature-item-6 box-shadow-1">
                        <div class="ltn__feature-icon">
                            <span><i class="flaticon-excavator"></i></span>
                        </div>
                        <div class="ltn__feature-info">
                            <h3><a href="service-details.html">Business Storage</a></h3>
                            <p>over 1 million+ homes for sale available
                                on the website, we can match you with a
                                house you will want to call home.</p>
                            <a class="ltn__service-btn ltn__service-btn-2" href="service-details.html">Service Details <i class="flaticon-right-arrow"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">                            
                    <div class="ltn__feature-item ltn__feature-item-6 box-shadow-1">
                        <div class="ltn__feature-icon">
                            <span><i class="icon-repair"></i></span>
                        </div>
                        <div class="ltn__feature-info">
                            <h3><a href="service-details.html">Warehouse Storage</a></h3>
                            <p>over 1 million+ homes for sale available
                                on the website, we can match you with a
                                house you will want to call home.</p>
                            <a class="ltn__service-btn ltn__service-btn-2" href="service-details.html">Service Details <i class="flaticon-right-arrow"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">                            
                    <div class="ltn__feature-item ltn__feature-item-6 box-shadow-1">
                        <div class="ltn__feature-icon">
                            <span><i class="flaticon-excavator"></i></span>
                        </div>
                        <div class="ltn__feature-info">
                            <h3><a href="service-details.html">Moving</a></h3>
                            <p>over 1 million+ homes for sale available
                                on the website, we can match you with a
                                house you will want to call home.</p>
                            <a class="ltn__service-btn ltn__service-btn-2" href="service-details.html">Service Details <i class="flaticon-right-arrow"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- FEATURE END -->

    <!-- PROGRESS BAR AREA START -->
    <div class="ltn__progress-bar-area section-bg-1 pt-120 pb-10">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-area ltn__section-title-2 text-center">
                        <h6 class="section-subtitle ltn__secondary-color"><span><i class="fas fa-square-full"></i></span> Our Success Statistics</h6>
                        <h1 class="section-title">Our Success Statistics</h1>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="ltn__progress-bar-wrap">
                        <div class="ltn__progress-bar-inner">
                            <div class="row">
                                <div class="col-lg-3 col-sm-6">
                                    <div class="ltn__progress-bar-item-2 text-center">
                                        <div class="progress" data-value='78'>
                                            <span class="progress-left">
                                                <span class="progress-bar border-primary"></span>
                                            </span>
                                            <span class="progress-right">
                                                <span class="progress-bar border-primary"></span>
                                            </span>
                                            <div class="progress-value">
                                                <div class="progress-count">78<sup class="small">%</sup></div>
                                            </div>
                                        </div>
                                        <div class="ltn__progress-info">
                                            <h3>Project Done</h3>
                                            <p>Construction Simulator</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-6">
                                    <div class="ltn__progress-bar-item-2 text-center">
                                        <div class="progress" data-value='62'>
                                            <span class="progress-left">
                                                <span class="progress-bar border-danger"></span>
                                            </span>
                                            <span class="progress-right">
                                                <span class="progress-bar border-danger"></span>
                                            </span>
                                            <div class="progress-value">
                                                <div class="progress-count">62<sup class="small">%</sup></div>
                                            </div>
                                        </div>
                                        <div class="ltn__progress-info">
                                            <h3>Country Cover</h3>
                                            <p>Construction Simulator</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-6">
                                    <div class="ltn__progress-bar-item-2 text-center">
                                        <div class="progress" data-value='50'>
                                            <span class="progress-left">
                                                <span class="progress-bar border-success"></span>
                                            </span>
                                            <span class="progress-right">
                                                <span class="progress-bar border-success"></span>
                                            </span>
                                            <div class="progress-value">
                                                <div class="progress-count">50<sup class="small">%</sup></div>
                                            </div>
                                        </div>
                                        <div class="ltn__progress-info">
                                            <h3>Completed Co.</h3>
                                            <p>Construction Simulator</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-6">
                                    <div class="ltn__progress-bar-item-2 text-center">
                                        <div class="progress" data-value='90'>
                                            <span class="progress-left">
                                                <span class="progress-bar border-warning"></span>
                                            </span>
                                            <span class="progress-right">
                                                <span class="progress-bar border-warning"></span>
                                            </span>
                                            <div class="progress-value">
                                                <div class="progress-count">90<sup class="small">%</sup></div>
                                            </div>
                                        </div>
                                        <div class="ltn__progress-info">
                                            <h3>Happy Clients</h3>
                                            <p>Construction Simulator</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- PROGRESS BAR AREA END -->

    <!-- IMAGE SLIDER AREA START (img-slider-3) -->
    <div class="ltn__img-slider-area pb-100">
        <div class="container-fluid">
            <div class="row ltn__image-slider-3-active slick-arrow-1 slick-arrow-1-inner">
                <div class="col-lg-12">
                    <div class="ltn__img-slide-item-3">
                        <a href="{{ asset('sk-assets/assets/images/frontend/web/Image_1.png') }}" data-rel="lightcase:myCollection">
                            <img src="{{ asset('sk-assets/assets/images/frontend/web/Image_1.png') }}" alt="Image">
                        </a>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="ltn__img-slide-item-3">
                        <a href="{{ asset('sk-assets/assets/images/frontend/web/Image_2.png') }}" data-rel="lightcase:myCollection">
                            <img src="{{ asset('sk-assets/assets/images/frontend/web/Image_2.png') }}" alt="Image">
                        </a>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="ltn__img-slide-item-3">
                        <a href="{{ asset('sk-assets/assets/images/frontend/web/Image_3.png') }}" data-rel="lightcase:myCollection">
                            <img src="{{ asset('sk-assets/assets/images/frontend/web/Image_3.png') }}" alt="Image">
                        </a>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="ltn__img-slide-item-3">
                        <a href="{{ asset('sk-assets/assets/images/frontend/web/Image_4.png') }}" data-rel="lightcase:myCollection">
                            <img src="{{ asset('sk-assets/assets/images/frontend/web/Image_4.png') }}" alt="Image">
                        </a>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="ltn__img-slide-item-3">
                        <a href="{{ asset('sk-assets/assets/images/frontend/web/Image_5.png') }}" data-rel="lightcase:myCollection">
                            <img src="{{ asset('sk-assets/assets/images/frontend/web/Image_5.png') }}" alt="Image">
                        </a>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="ltn__img-slide-item-3">
                        <a href="{{ asset('sk-assets/assets/images/frontend/web/Image_6.png') }}" data-rel="lightcase:myCollection">
                            <img src="{{ asset('sk-assets/assets/images/frontend/web/Image_6.png') }}" alt="Image">
                        </a>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="ltn__img-slide-item-3">
                        <a href="{{ asset('sk-assets/assets/images/frontend/web/Image_7.png') }}" data-rel="lightcase:myCollection">
                            <img src="{{ asset('sk-assets/assets/images/frontend/web/Image_7.png') }}" alt="Image">
                        </a>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="ltn__img-slide-item-3">
                        <a href="{{ asset('sk-assets/assets/images/frontend/web/Image_8.png') }}" data-rel="lightcase:myCollection">
                            <img src="{{ asset('sk-assets/assets/images/frontend/web/Image_8.png') }}" alt="Image">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- IMAGE SLIDER AREA END -->

    <!-- TESTIMONIAL AREA START (testimonial-8) -->
    <div class="ltn__testimonial-area section-bg-1--- pt-115--- pb-75">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-area ltn__section-title-2 text-center">
                        <h6 class="section-subtitle ltn__secondary-color"><span><i class="fas fa-square-full"></i></span> Client's Testimonial</h6>
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
                                        <h5>Jacob William</h5>
                                        <label>Selling Agents</label>
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
                                Precious ipsum dolor sit amet
                                consectetur adipisicing elit, sed dos
                                mod tempor incididunt ut labore et
                                dolore magna aliqua. Ut enim ad min
                                veniam, quis nostrud Precious ips
                                um dolor sit amet, consecte</p>
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
                                        <h5>Jacob William</h5>
                                        <label>Selling Agents</label>
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
                                Precious ipsum dolor sit amet
                                consectetur adipisicing elit, sed dos
                                mod tempor incididunt ut labore et
                                dolore magna aliqua. Ut enim ad min
                                veniam, quis nostrud Precious ips
                                um dolor sit amet, consecte</p>
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
                                        <h5>Jacob William</h5>
                                        <label>Selling Agents</label>
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
                                Precious ipsum dolor sit amet
                                consectetur adipisicing elit, sed dos
                                mod tempor incididunt ut labore et
                                dolore magna aliqua. Ut enim ad min
                                veniam, quis nostrud Precious ips
                                um dolor sit amet, consecte</p>
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
                                        <h5>Jacob William</h5>
                                        <label>Selling Agents</label>
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
                                Precious ipsum dolor sit amet
                                consectetur adipisicing elit, sed dos
                                mod tempor incididunt ut labore et
                                dolore magna aliqua. Ut enim ad min
                                veniam, quis nostrud Precious ips
                                um dolor sit amet, consecte</p>
                        </div>
                    </div>
                </div>
                <!--  -->
            </div>
        </div>
    </div>
    <!-- TESTIMONIAL AREA END -->

    <!-- CALL TO ACTION START (call-to-action-6) -->
    <div class="ltn__call-to-action-area call-to-action-6 before-bg-left-skew ltn__secondary-bg bg-image pt-110 pb-110" data-bg="img/bg/33.jpg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="call-to-action-inner call-to-action-inner-6 p-0 text-center---">
                        
                        <div class="section-title-area ltn__section-title-2--- mb-0 text-color-white">
                            <h1 class="section-title">Looking for free Consultation?</h1>
                            <p>We are always Ready To Help You</p>
                        </div>
                        <div class="btn-wrapper">
                            <a class="btn btn-effect-4 btn-white font-weight-bold" href="{{ url('/booking') }}">Book Now <i class="icon-next"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- CALL TO ACTION END -->

    <!-- BLOG AREA START (blog-3) -->
    <div class="ltn__blog-area pt-120 pb-70">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-area ltn__section-title-2 text-center">
                        <h6 class="section-subtitle ltn__secondary-color"><span><i class="fas fa-square-full"></i></span> News & Blogs</h6>
                        <h1 class="section-title">See Our Latest News <br> & Read Blogs</h1>
                    </div>
                </div>
            </div>
            <div class="row  ltn__blog-slider-one-active slick-arrow-1 ltn__blog-item-3-normal">
                <!-- Blog Item -->
                <div class="col-lg-12">
                    <div class="ltn__blog-item ltn__blog-item-3">
                        <div class="ltn__blog-img">
                            <a href="blog-details.html"><img src="{{ asset('sk-assets/assets/images/frontend/blog/Image_8.png') }}" alt="#"></a>
                        </div>
                        <div class="ltn__blog-brief">
                            <div class="ltn__blog-meta">
                                <ul>
                                    <li class="ltn__blog-author">
                                        <a href="#"><i class="far fa-user"></i>by: Admin</a>
                                    </li>
                                </ul>
                            </div>
                            <h3 class="ltn__blog-title"><a href="blog-details.html">What You Need To Consider When Renting a Long-Term Storage Unit</a></h3>
                            <div class="ltn__blog-meta-btn">
                                <div class="ltn__blog-meta">
                                    <ul>
                                        <li class="ltn__blog-date"><i class="far fa-calendar-alt"></i>June 24, 2021</li>
                                    </ul>
                                </div>
                                <div class="ltn__blog-btn">
                                    <a href="blog-details.html">Read more</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Blog Item -->
                <div class="col-lg-12">
                    <div class="ltn__blog-item ltn__blog-item-3">
                        <div class="ltn__blog-img">
                            <a href="blog-details.html"><img src="{{ asset('sk-assets/assets/images/frontend/blog/Image_8.png') }}" alt="#"></a>
                        </div>
                        <div class="ltn__blog-brief">
                            <div class="ltn__blog-meta">
                                <ul>
                                    <li class="ltn__blog-author">
                                        <a href="#"><i class="far fa-user"></i>by: Admin</a>
                                    </li>
                                </ul>
                            </div>
                            <h3 class="ltn__blog-title"><a href="blog-details.html">Tips: How An Organized Home Can Build A Better Relationship</a></h3>
                            <div class="ltn__blog-meta-btn">
                                <div class="ltn__blog-meta">
                                    <ul>
                                        <li class="ltn__blog-date"><i class="far fa-calendar-alt"></i>July 23, 2021</li>
                                    </ul>
                                </div>
                                <div class="ltn__blog-btn">
                                    <a href="blog-details.html">Read more</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Blog Item -->
                <div class="col-lg-12">
                    <div class="ltn__blog-item ltn__blog-item-3">
                        <div class="ltn__blog-img">
                            <a href="blog-details.html"><img src="{{ asset('sk-assets/assets/images/frontend/blog/Image_8.png') }}" alt="#"></a>
                        </div>
                        <div class="ltn__blog-brief">
                            <div class="ltn__blog-meta">
                                <ul>
                                    <li class="ltn__blog-author">
                                        <a href="#"><i class="far fa-user"></i>by: Admin</a>
                                    </li>
                                </ul>
                            </div>
                            <h3 class="ltn__blog-title"><a href="blog-details.html">Why deep cleaning your new home before moving is important</a></h3>
                            <div class="ltn__blog-meta-btn">
                                <div class="ltn__blog-meta">
                                    <ul>
                                        <li class="ltn__blog-date"><i class="far fa-calendar-alt"></i>May 22, 2021</li>
                                    </ul>
                                </div>
                                <div class="ltn__blog-btn">
                                    <a href="blog-details.html">Read more</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- BLOG AREA START (blog-3) -->
    <div class="ltn__blog-area pt-40 pb-20">
        {{-- <div class="container">8 --}}
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
        {{-- </div> --}}
    </div>

    
    <!-- MODAL AREA START (Quick View Modal) -->
    <div class="ltn__modal-area ltn__quick-view-modal-area">
        <div class="modal fade" id="quick_view_modal" tabindex="-1">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            <!-- <i class="fas fa-times"></i> -->
                        </button>
                    </div>
                    <div class="modal-body">
                         <div class="ltn__quick-view-modal-inner">
                             <div class="modal-product-item">
                                <div class="row">
                                    <div class="col-lg-6 col-12">
                                        <div class="modal-product-img">
                                            <img src="img/product/4.png" alt="#">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-12">
                                        <div class="modal-product-info">
                                            <div class="product-ratting">
                                                <ul>
                                                    <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                    <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                    <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                    <li><a href="#"><i class="fas fa-star-half-alt"></i></a></li>
                                                    <li><a href="#"><i class="far fa-star"></i></a></li>
                                                    <li class="review-total"> <a href="#"> ( 95 Reviews )</a></li>
                                                </ul>
                                            </div>
                                            <h3><a href="product-details.html">3 Rooms Manhattan</a></h3>
                                            <div class="product-price">
                                                <span>$34,900</span>
                                                <del>$36,500</del>
                                            </div>
                                            <hr>
                                            <div class="modal-product-brief">
                                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dignissimos repellendus repudiandae incidunt quidem pariatur expedita, quo quis modi tempore non.</p>
                                            </div>
                                            <div class="modal-product-meta ltn__product-details-menu-1 d-none">
                                                <ul>
                                                    <li>
                                                        <strong>Categories:</strong> 
                                                        <span>
                                                            <a href="#">Parts</a>
                                                            <a href="#">Car</a>
                                                            <a href="#">Seat</a>
                                                            <a href="#">Cover</a>
                                                        </span>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="ltn__product-details-menu-2 d-none">
                                                <ul>
                                                    <li>
                                                        <div class="cart-plus-minus">
                                                            <input type="text" value="02" name="qtybutton" class="cart-plus-minus-box">
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <a href="#" class="theme-btn-1 btn btn-effect-1" title="Add to Cart" data-toggle="modal" data-target="#add_to_cart_modal">
                                                            <i class="fas fa-shopping-cart"></i>
                                                            <span>ADD TO CART</span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <!-- <hr> -->
                                            <div class="ltn__product-details-menu-3">
                                                <ul>
                                                    <li>
                                                        <a href="#" class="" title="Wishlist" data-toggle="modal" data-target="#liton_wishlist_modal">
                                                            <i class="far fa-heart"></i>
                                                            <span>Add to Wishlist</span>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#" class="" title="Compare" data-toggle="modal" data-target="#quick_view_modal">
                                                            <i class="fas fa-exchange-alt"></i>
                                                            <span>Compare</span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <hr>
                                            <div class="ltn__social-media">
                                                <ul>
                                                    <li>Share:</li>
                                                    <li><a href="#" title="Facebook"><i class="fab fa-facebook-f"></i></a></li>
                                                    <li><a href="#" title="Twitter"><i class="fab fa-twitter"></i></a></li>
                                                    <li><a href="#" title="Linkedin"><i class="fab fa-linkedin"></i></a></li>
                                                    <li><a href="#" title="Instagram"><i class="fab fa-instagram"></i></a></li>
                                                    
                                                </ul>
                                            </div>
                                            <label class="float-right mb-0"><a class="text-decoration" href="product-details.html"><small>View Details</small></a></label>
                                        </div>
                                    </div>
                                </div>
                             </div>
                         </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- MODAL AREA END -->

    <!-- MODAL AREA START (Add To Cart Modal) -->
    <div class="ltn__modal-area ltn__add-to-cart-modal-area">
        <div class="modal fade" id="add_to_cart_modal" tabindex="-1">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                         <div class="ltn__quick-view-modal-inner">
                             <div class="modal-product-item">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="modal-product-img">
                                            <img src="img/product/1.png" alt="#">
                                        </div>
                                         <div class="modal-product-info">
                                            <h5><a href="product-details.html">Brake Conversion Kit</a></h5>
                                            <p class="added-cart"><i class="fa fa-check-circle"></i>  Successfully added to your Cart</p>
                                            <div class="btn-wrapper">
                                                <a href="cart.html" class="theme-btn-1 btn btn-effect-1">View Cart</a>
                                                <a href="checkout.html" class="theme-btn-2 btn btn-effect-2">Checkout</a>
                                            </div>
                                         </div>
                                         <!-- additional-info -->
                                         <div class="additional-info d-none">
                                            <p>We want to give you <b>10% discount</b> for your first order, <br>  Use discount code at checkout</p>
                                            <div class="payment-method">
                                                <img src="img/icons/payment.png" alt="#">
                                            </div>
                                         </div>
                                    </div>
                                </div>
                             </div>
                         </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- MODAL AREA END -->

    <!-- MODAL AREA START (Wishlist Modal) -->
    <div class="ltn__modal-area ltn__add-to-cart-modal-area">
        <div class="modal fade" id="liton_wishlist_modal" tabindex="-1">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                         <div class="ltn__quick-view-modal-inner">
                             <div class="modal-product-item">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="modal-product-img">
                                            <img src="img/product/7.png" alt="#">
                                        </div>
                                         <div class="modal-product-info">
                                            <h5><a href="product-details.html">Brake Conversion Kit</a></h5>
                                            <p class="added-cart"><i class="fa fa-check-circle"></i>  Successfully added to your Wishlist</p>
                                            <div class="btn-wrapper">
                                                <a href="wishlist.html" class="theme-btn-1 btn btn-effect-1">View Wishlist</a>
                                            </div>
                                         </div>
                                         <!-- additional-info -->
                                         <div class="additional-info d-none">
                                            <p>We want to give you <b>10% discount</b> for your first order, <br>  Use discount code at checkout</p>
                                            <div class="payment-method">
                                                <img src="img/icons/payment.png" alt="#">
                                            </div>
                                         </div>
                                    </div>
                                </div>
                             </div>
                         </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
@endsection
    <!--Start of Tawk.to Script-->


