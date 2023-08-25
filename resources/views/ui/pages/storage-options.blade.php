@extends('ui.layouts.frontend2')
@section('title', '| Storage-Options')
@section('content')
<div class="body-wrapper">
    <div class="ltn__utilize-overlay"></div>

    <!-- BREADCRUMB AREA START -->
    <div class="ltn__breadcrumb-area text-left bg-overlay-white-30 bg-image "  data-bg="{{ asset('sk-assets/assets/images/frontend/bg/Inner_Small_Banner_5.jpg') }}">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ltn__breadcrumb-inner">
                        <h1 class="page-title">Storage Options</h1>
                        <div class="ltn__breadcrumb-list">
                            <ul>
                                <li><a href="index.html"><span class="ltn__secondary-color"><i class="fas fa-home"></i></span> Home</a></li>
                                <li>Storage Options</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- BREADCRUMB AREA END -->

    <!-- FEATURE START -->
    <div class="ltn__blog-area ltn__blog-item-3-normal mb-100" >
        <div class="container">
            <div class="row align-self-center">
                <div class="col-lg-6 col-sm-6">
                    <div class="ltn__feature-item ltn__feature-item-6 box-shadow-1">
                        <div class="ltn__feature-info">
                            <h3><a href="service-details.html">Personal Storage</a></h3>
                            <p>Our individually lockable, climate-controlled personal storage units near you, in Sharjah and Dubai, are perfect for
                            unused furniture you’re not ready to part with, family heirlooms you don’t have space for, books and clothes you use
                            infrequently, or all the contents of your home when you’re shifting.</p>
                            <a class="ltn__service-btn ltn__service-btn-2" href="service-details.html">Service Details <i
                                    class="flaticon-right-arrow"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-sm-6">
                    <div class="ltn__feature-item ltn__feature-item-6 box-shadow-1">
                        <div class="ltn__feature-info">
                            <h3><a href="service-details.html">Business Storage</a></h3>
                            <p>It makes little sense to use your expensive office space to store unused equipment and furniture, archived documents, or
                            excess stock and accessories. At every growth stage, your UAE business can benefit from our affordable and scalable
                            business storage units in Sharjah, with easy access and delivery. We offer start-up business storage, small business
                            storage, and commercial storage units for rent for corporate companies.</p>
                            <a class="ltn__service-btn ltn__service-btn-2" href="service-details.html">Service Details <i
                                    class="flaticon-right-arrow"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-sm-6">
                    <div class="ltn__feature-item ltn__feature-item-6 box-shadow-1">
                        <div class="ltn__feature-info">
                            <h3><a href="service-details.html">Personal Storage</a></h3>
                            <p>With half of our 36,000 sq. ft. storage warehouse in Sharjah dedicated to businesses, we offer cost-effective
                            warehousing solutions, with scalable space, flexible leases, reliable security and easy accessibility.</p>
                            <a class="ltn__service-btn ltn__service-btn-2" href="service-details.html">Service Details <i class="flaticon-right-arrow"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-sm-6">
                    <div class="ltn__feature-item ltn__feature-item-6 box-shadow-1">
                        <div class="ltn__feature-info">
                            <h3><a href="service-details.html">Personal Storage</a></h3>
                            <p>Whether you’re shifting locally or internationally, our qualified team of professional movers and packers in Dubai will
                            carefully dismantle, efficiently pack, load, move and accurately reassemble all your things in your new home or office
                            space.</p>
                            <a class="ltn__service-btn ltn__service-btn-2" href="service-details.html">Service Details <i class="flaticon-right-arrow"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- FEATURE END -->
</div>

@endsection