 <!-- Font Icons css -->
 <link rel="stylesheet" href="{{ asset('sk-assets/css/frontend/font-icons.css') }}">
    <!-- plugins css -->
    <link rel="stylesheet" href="{{ asset('sk-assets/css/frontend/plugins.css') }}">
    <link rel="stylesheet" href="{{ asset('sk-assets/css/frontend/booking.css') }}">
    <!-- Main Stylesheet -->
    <link rel="stylesheet" href="{{ asset('sk-assets/css/frontend/style.css') }}">
    <!-- Responsive css -->
    <link rel="stylesheet" href="{{ asset('sk-assets/css/frontend/responsive.css') }}">

    <link rel="stylesheet" href="{{ asset('sk-assets/css/custom.css') }}" />
 <link rel="stylesheet" href="{{ asset('sk-assets/css/toastr.css') }}"/>


    <header class="ltn__header-area ltn__header-5 ltn__header-logo-and-mobile-menu-in-mobile ltn__header-transparent gradient-color-2">
        <!-- ltn__header-top-area start -->
        <div class="ltn__header-top-area top-area-color-white">
            <div class="container">
                <div class="row">
                    <div class="col-md-7">
                        <div class="ltn__top-bar-menu">
                            <ul>
                                <li><a href="mailto:info@stroagekeys.com?Subject=Flower%20greetings%20to%20you"><i class="icon-mail"></i> info@stroagekeys.com</a></li>
                                <li><a href="locations.html"><i class="icon-placeholder"></i> Storage Keys, Plot # 4202 - Sharjah - United Arab Emirates</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="top-bar-right text-right">
                            <div class="ltn__top-bar-menu">
                                <ul>
                                    <li>
                                        <!-- ltn__social-media -->
                                        <div class="ltn__social-media">
                                            <ul>
{{--                                                @dd(Auth::getDefaultDriver())--}}
                                                @if (\Auth::user())
{{--                                                    <li><a href="/admin" title="Panel"><i class="fa fa-user"></i>Admin area</a></li>--}}
                                                @if(Auth::getDefaultDriver() == 'contact')
                                                    <li><a href="{{route('customer.dashboard')}}" title="Panel"><i class="fa fa-user"></i> Dashboard</a></li>
                                                    <li><a href="#"  onclick="event.preventDefault(); document.getElementById('logout-form').submit();" title="Logout"><i class="fa fa-sign-out"></i> Logout</a></li>
                                                    <form id="logout-form" action="{{ route('all.logout') }}" method="POST" class="d-none">@csrf</form>
                                                    @endif
                                                @endif
                                                @guest
                                                    <li><a href="{{url('admin')}}" title="Admin Area"><i class="fa fa-lock"></i> Admin area</a></li>
                                                    <li><a href="{{ route('customer.login') }}" title="Login"><i class="fa fa-lock"></i> Customer Login</a></li>
                                                    <li><a href="{{route('customer.register')}}" title="Register"><i class="fa fa-user"></i> Register</a></li>
                                                @endguest
                                            </ul>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- ltn__header-top-area end -->
        
        <!-- ltn__header-middle-area start -->
        <div class="ltn__header-middle-area ltn__header-sticky ltn__sticky-bg-black">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="site-logo-wrap">
                            <div class="site-logo">
                                <a href="index.html"><img src="{{ asset('sk-assets/assets/images/frontend/front-logo.png') }}" alt="Logo"></a>
                            </div>
                            <div class="get-support clearfix d-none">
                                <div class="get-support-icon">
                                    <i class="icon-call"></i>
                                </div>
                                <div class="get-support-info">
                                    <h6>Get Support</h6>
                                    <h4><a href="tel:+123456789">123-456-789-10</a></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col header-menu-column menu-color-white">
                        <div class="header-menu d-none d-xl-block">
                            <nav>
                                <div class="ltn__main-menu">
                                    <ul>
                                        <li><a href="{{ url('/') }}">Home</a></li>
                                        <li><a href="{{ url('/about-us') }}">About Us</a></li>
                                        <li><a href="{{ url('/storage-options') }}">Storage Options</a></li>
                                        <li><a href="{{ url('/shop') }}">Shop</a></li>
                                        <li><a href="{{ url('/booking') }}">Booking</a></li>
                                        <li><a href="{{ url('/blogs') }}">Blogs</a></li>
                                        <li><a href="{{ url('/contact-us') }}">Contact Us</a></li>
                                        <li class="special-link">
                                            <a href="{{ url('/booking') }}">Book Now</a>
                                        </li>

                                    </ul>
                                </div>
                            </nav>
                        </div>
                    </div>
                    <div class="ltn__header-options ltn__header-options-2 ">
                        <!-- Mobile Menu Button -->
                        <div class="mobile-menu-toggle d-xl-none">
                            <a href="#ltn__utilize-mobile-menu" class="ltn__utilize-toggle">
                                <svg viewBox="0 0 800 600">
                                    <path d="M300,220 C300,220 520,220 540,220 C740,220 640,540 520,420 C440,340 300,200 300,200" id="top"></path>
                                    <path d="M300,320 L540,320" id="middle"></path>
                                    <path d="M300,210 C300,210 520,210 540,210 C740,210 640,530 520,410 C440,330 300,190 300,190" id="bottom" transform="translate(480, 320) scale(1, -1) translate(-480, -318) "></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- ltn__header-middle-area end -->
    </header>
     <!-- Utilize Mobile Menu Start -->
     <div id="ltn__utilize-mobile-menu" class="ltn__utilize ltn__utilize-mobile-menu">
        <div class="ltn__utilize-menu-inner ltn__scrollbar">
            <div class="ltn__utilize-menu-head">
                <div class="site-logo">
                    <a href="index.html"><img src="{{ asset('sk-assets/assets/images/frontend/logo.png') }}" alt="Logo"></a>
                </div>
                <button class="ltn__utilize-close">×</button>
            </div>
            <div class="ltn__utilize-menu-search-form">
                <form action="#">
                    <input type="text" placeholder="Search...">
                    <button><i class="fas fa-search"></i></button>
                </form>
            </div>
            <div class="ltn__utilize-menu">
                <ul>

                <li><a href="{{ url('/') }}">Home</a></li>
                                        <li><a href="{{ url('/storage-options') }}">Storage Options</a></li>
                                        <li><a href="{{ url('/shop') }}">Shop</a></li>
                                        <li><a href="{{ url('/booking') }}">Booking</a></li>
                                        <li><a href="{{ url('/blogs') }}">Blogs</a></li>
                                        <li><a href="{{ url('/about-us') }}">About Us</a></li>
                                        <li><a href="{{ url('/contact-us') }}">Contact Us</a></li>
                                        </ul>
            </div>
            <div class="ltn__utilize-buttons ltn__utilize-buttons-2">
                <ul>
                    <li>
                        <a href="account.html" title="My Account">
                            <span class="utilize-btn-icon">
                                <i class="far fa-user"></i>
                            </span>
                            My Account
                        </a>
                    </li>
                    <li>
                        <a href="wishlist.html" title="Wishlist">
                            <span class="utilize-btn-icon">
                                <i class="far fa-heart"></i>
                                <sup>3</sup>
                            </span>
                            Wishlist
                        </a>
                    </li>
                    <li>
                        <a href="cart.html" title="Shoping Cart">
                            <span class="utilize-btn-icon">
                                <i class="fas fa-shopping-cart"></i>
                                <sup>5</sup>
                            </span>
                            Shoping Cart
                        </a>
                    </li>
                </ul>
            </div>
            <div class="ltn__social-media-2">
                <ul>
                    <li><a href="#" title="Facebook"><i class="fab fa-facebook-f"></i></a></li>
                    <li><a href="#" title="Twitter"><i class="fab fa-twitter"></i></a></li>
                    <li><a href="#" title="Linkedin"><i class="fab fa-linkedin"></i></a></li>
                    <li><a href="#" title="Instagram"><i class="fab fa-instagram"></i></a></li>
                </ul>
            </div>
        </div>
    </div>
    <!-- Utilize Mobile Menu End -->
    
<!-- Utilize Cart Menu Start -->
<div id="ltn__utilize-cart-menu" class="ltn__utilize ltn__utilize-cart-menu">
        <div class="ltn__utilize-menu-inner ltn__scrollbar">
            <div class="ltn__utilize-menu-head">
                <span class="ltn__utilize-menu-title">Cart</span>
                <button class="ltn__utilize-close">×</button>
            </div>
            <div class="mini-cart-product-area ltn__scrollbar">
                <div class="mini-cart-item clearfix">
                    <div class="mini-cart-img">
                        <a href="#"><img src="{{ asset('sk-assets/assets/images/frontend/product/1.png') }}" alt="Image"></a>
                        <span class="mini-cart-item-delete"><i class="icon-cancel"></i></span>
                    </div>
                    <div class="mini-cart-info">
                        <h6><a href="#">Wheel Bearing Retainer</a></h6>
                        <span class="mini-cart-quantity">1 x $65.00</span>
                    </div>
                </div>
                <div class="mini-cart-item clearfix">
                    <div class="mini-cart-img">
                        <a href="#"><img src="{{ asset('sk-assets/assets/images/frontend/product/2.png') }}" alt="Image"></a>
                        <span class="mini-cart-item-delete"><i class="icon-cancel"></i></span>
                    </div>
                    <div class="mini-cart-info">
                        <h6><a href="#">Brake Conversion Kit</a></h6>
                        <span class="mini-cart-quantity">1 x $85.00</span>
                    </div>
                </div>
                <div class="mini-cart-item clearfix">
                    <div class="mini-cart-img">
                        <a href="#"><img src="{{ asset('sk-assets/assets/images/frontend/product/3.png') }}" alt="Image"></a>
                        <span class="mini-cart-item-delete"><i class="icon-cancel"></i></span>
                    </div>
                    <div class="mini-cart-info">
                        <h6><a href="#">OE Replica Wheels</a></h6>
                        <span class="mini-cart-quantity">1 x $92.00</span>
                    </div>
                </div>
                <div class="mini-cart-item clearfix">
                    <div class="mini-cart-img">
                        <a href="#"><img src="{{ asset('sk-assets/assets/images/frontend/product/4.png') }}" alt="Image"></a>
                        <span class="mini-cart-item-delete"><i class="icon-cancel"></i></span>
                    </div>
                    <div class="mini-cart-info">
                        <h6><a href="#">Shock Mount Insulator</a></h6>
                        <span class="mini-cart-quantity">1 x $68.00</span>
                    </div>
                </div>
            </div>
            <div class="mini-cart-footer">
                <div class="mini-cart-sub-total">
                    <h5>Subtotal: <span>$310.00</span></h5>
                </div>
                <div class="btn-wrapper">
                    <a href="cart.html" class="theme-btn-1 btn btn-effect-1">View Cart</a>
                    <a href="cart.html" class="theme-btn-2 btn btn-effect-2">Checkout</a>
                </div>
                <p>Free Shipping on All Orders Over $100!</p>
            </div>

        </div>
    </div>
    <!-- Utilize Cart Menu End -->