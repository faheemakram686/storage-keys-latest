<div class="pv-banner-note">
    <div class="sk-container">
            <div class="pv-banner-row">
                <div class="pv-banner-left">
                    <div class="ltn__top-bar-menu">
                        <ul>
                            <li><a href="mailto:sales@storagekeys.com"><i class="fas fa-envelope"></i> sales@storagekeys.com</a></li>
                            <li><a href="tel:+971565018785"><i class="fas fa-phone"></i> +971 56 501 8785</a></li>
                            <li><a href="tel:8005397"><i class="fas fa-phone"></i> Toll Free: 800 5397</a></li>
                        </ul>
                    </div>
                </div>
                <div class="pv-banner-right">
                                    <div class="ltn__social-media">
                                        <ul>
                                            @if (\Auth::user())
                                                @if(Auth::getDefaultDriver() == 'contact')
                                                    <li><a href="{{route('customer.dashboard')}}" title="Panel"><i
                                                                    class="fa fa-user"></i> Dashboard</a></li>
                                                    <li><a href="#"
                                                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                                           title="Logout"><i class="fa fa-sign-out-alt"></i> Logout</a></li>
                                                    <form id="logout-form" action="{{ route('all.logout') }}"
                                                          method="POST" class="d-none">@csrf</form>
                                                @endif
                                            @endif
                                            @guest
                                                <li><a href="{{ route('customer.login') }}" title="Customer Login"><i
                                                                class="fa fa-lock"></i> Customer Login</a></li>
                                                <li><a href="{{route('customer.register')}}" title="Register"><i
                                                                class="fa fa-user"></i> Register</a></li>
                                            @endguest
                                        </ul>
                                    </div>
                </div>
            </div>
        </div>
</div>
<header class="pv-header">
  <div class="sk-container pv-header-in">
    <a href="{{ url('/') }}" class="pv-logo"><img src="{{ asset('sk-assets/assets/images/frontend/front-logo.png') }}" alt="StorageKeys" width="223" height="64" decoding="async"></a>
    <nav class="pv-nav">
      <a href="{{ url('/') }}" class="{{ request()->is('/') ? 'active' : '' }}">Home</a>
      <a href="{{ url('/about-us') }}" class="{{ request()->is('about-us') ? 'active' : '' }}">About Us</a>
      <div class="pv-has-sub">
        <a href="{{ url('/storage-options') }}" class="{{ request()->is('storage-options', 'personal-storage', 'furniture-storage', 'box-storage', 'appliance-storage', 'residential-storage', 'business-storage', 'warehouse-storage', 'climate-controlled-storage', 'moving-services', 'luggage-storage', 'car-storage') ? 'active' : '' }}">Storage Options</a>
        <div class="pv-sub">
          <a href="{{ url('/personal-storage') }}" class="{{ request()->is('personal-storage') ? 'active' : '' }}">Personal Storage</a>
          <a href="{{ url('/residential-storage') }}" class="{{ request()->is('residential-storage') ? 'active' : '' }}">Residential Storage</a>
          <a href="{{ url('/furniture-storage') }}" class="{{ request()->is('furniture-storage') ? 'active' : '' }}">Furniture Storage</a>
          <a href="{{ url('/box-storage') }}" class="{{ request()->is('box-storage') ? 'active' : '' }}">Box Storage</a>
          <a href="{{ url('/appliance-storage') }}" class="{{ request()->is('appliance-storage') ? 'active' : '' }}">Appliance Storage</a>
          <a href="{{ url('/business-storage') }}" class="{{ request()->is('business-storage') ? 'active' : '' }}">Business Storage</a>
          <a href="{{ url('/warehouse-storage') }}" class="{{ request()->is('warehouse-storage') ? 'active' : '' }}">Warehouse Storage</a>
          <a href="{{ url('/climate-controlled-storage') }}" class="{{ request()->is('climate-controlled-storage') ? 'active' : '' }}">Climate Controlled Storage</a>
          <a href="{{ url('/moving-services') }}" class="{{ request()->is('moving-services') ? 'active' : '' }}">Moving Services</a>
          <a href="{{ url('/luggage-storage') }}" class="{{ request()->is('luggage-storage') ? 'active' : '' }}">Luggage Storage</a>
          <a href="{{ url('/car-storage') }}" class="{{ request()->is('car-storage') ? 'active' : '' }}">Car Storage</a>
        </div>
      </div>
      <a href="{{ url('/shop') }}" class="{{ request()->is('shop', 'product-details', 'cart', 'checkout') ? 'active' : '' }}">Shop</a>
      <a href="{{ url('/booking') }}" class="{{ request()->is('booking') ? 'active' : '' }}">Booking</a>
      <a href="{{ url('/blogs') }}" class="{{ request()->is('blogs', 'blogs/*', 'blog-details') ? 'active' : '' }}">Blogs</a>
      <a href="{{ url('/contact-us') }}" class="{{ request()->is('contact-us') ? 'active' : '' }}">Contact Us</a>
    </nav>
    <div class="pv-header-cta">
      <a href="{{ url('/booking') }}" class="pv-book">Book Now</a>
      @php
        $cartQty = 0;
        try { $cartQty = (int) \Cart::getTotalQuantity(); } catch (\Throwable $e) { $cartQty = 0; }
      @endphp
      <a href="{{ route('cart.list') }}" class="pv-cart" aria-label="Shopping cart{{ $cartQty ? ', ' . $cartQty . ' items' : '' }}">
        <i class="fas fa-shopping-cart" aria-hidden="true"></i>
        @if($cartQty > 0)
          <sup class="pv-cart-count">{{ $cartQty > 99 ? '99+' : $cartQty }}</sup>
        @endif
      </a>
    </div>
    <button type="button" class="pv-nav-toggle" aria-label="Open menu" aria-expanded="false">
      <i class="fas fa-bars"></i>
    </button>
  </div>
</header>

<!-- Utilize Mobile Menu Start -->
<!-- <div id="ltn__utilize-mobile-menu" class="ltn__utilize ltn__utilize-mobile-menu">
    <div class="ltn__utilize-menu-inner ltn__scrollbar">
        <div class="ltn__utilize-menu-head">
            <div class="site-logo">
                <a href="{{url('/')}}"><img src="{{ asset('sk-assets/assets/images/frontend/front-logo.png') }}"
                                            alt="Logo"></a>
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

        {{--        <div class="ltn__social-media-2">--}}
        {{--            <ul>--}}
        {{--                <li><a href="#" title="Facebook"><i class="fab fa-facebook-f"></i></a></li>--}}
        {{--                <li><a href="#" title="Twitter"><i class="fab fa-twitter"></i></a></li>--}}
        {{--                <li><a href="#" title="Linkedin"><i class="fab fa-linkedin"></i></a></li>--}}
        {{--                <li><a href="#" title="Instagram"><i class="fab fa-instagram"></i></a></li>--}}
        {{--            </ul>--}}
        {{--        </div>--}}
    </div>
</div> -->
<!-- Utilize Mobile Menu End -->

<!-- Utilize Cart Menu Start -->
<!-- <div id="ltn__utilize-cart-menu" class="ltn__utilize ltn__utilize-cart-menu">
    <div class="ltn__utilize-menu-inner ltn__scrollbar">
        <div class="ltn__utilize-menu-head">
            <span class="ltn__utilize-menu-title">Cart</span>
            <button class="ltn__utilize-close">×</button>
        </div>
        <div class="mini-cart-product-area ltn__scrollbar">
            <div class="mini-cart-item clearfix">
                <div class="mini-cart-img">
                    <a href="#"><img src="{{ asset('sk-assets/assets/images/frontend/product/1.png') }}"
                                     alt="Image"></a>
                    <span class="mini-cart-item-delete"><i class="icon-cancel"></i></span>
                </div>
                <div class="mini-cart-info">
                    <h6><a href="#">Wheel Bearing Retainer</a></h6>
                    <span class="mini-cart-quantity">1 x $65.00</span>
                </div>
            </div>
            <div class="mini-cart-item clearfix">
                <div class="mini-cart-img">
                    <a href="#"><img src="{{ asset('sk-assets/assets/images/frontend/product/2.png') }}"
                                     alt="Image"></a>
                    <span class="mini-cart-item-delete"><i class="icon-cancel"></i></span>
                </div>
                <div class="mini-cart-info">
                    <h6><a href="#">Brake Conversion Kit</a></h6>
                    <span class="mini-cart-quantity">1 x $85.00</span>
                </div>
            </div>
            <div class="mini-cart-item clearfix">
                <div class="mini-cart-img">
                    <a href="#"><img src="{{ asset('sk-assets/assets/images/frontend/product/3.png') }}"
                                     alt="Image"></a>
                    <span class="mini-cart-item-delete"><i class="icon-cancel"></i></span>
                </div>
                <div class="mini-cart-info">
                    <h6><a href="#">OE Replica Wheels</a></h6>
                    <span class="mini-cart-quantity">1 x $92.00</span>
                </div>
            </div>
            <div class="mini-cart-item clearfix">
                <div class="mini-cart-img">
                    <a href="#"><img src="{{ asset('sk-assets/assets/images/frontend/product/4.png') }}"
                                     alt="Image"></a>
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
                <span class="h5">Subtotal: <span>$310.00</span></span>
            </div>
            <div class="btn-wrapper">
                <a href="#" class="theme-btn-1 btn btn-effect-1">View Cart</a>
                <a href="#" class="theme-btn-2 btn btn-effect-2">Checkout</a>
            </div>
            <p>Free Shipping on All Orders Over $100!</p>
        </div>

    </div>
</div> -->
<!-- Utilize Cart Menu End -->

{{-- Site nav: mobile menu button + "Storage Options" submenu --}}
<script>
(function () {
    var MOBILE = '(max-width: 991px)';

    function ready(fn) {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', fn);
        } else {
            fn();
        }
    }

    ready(function () {
        var header = document.querySelector('.pv-header');
        if (!header) return;

        var nav = header.querySelector('.pv-nav');
        var toggle = header.querySelector('.pv-nav-toggle');

        function closeNav() {
            if (!nav || !toggle) return;
            nav.classList.remove('open');
            toggle.setAttribute('aria-expanded', 'false');
            toggle.innerHTML = '<i class="fas fa-bars"></i>';
        }

        if (nav && toggle) {
            toggle.addEventListener('click', function () {
                var isOpen = nav.classList.toggle('open');
                toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                toggle.innerHTML = isOpen ? '<i class="fas fa-times"></i>' : '<i class="fas fa-bars"></i>';
            });

            // Leaving mobile width with the panel open would strand the .open class
            window.addEventListener('resize', function () {
                if (!window.matchMedia(MOBILE).matches) closeNav();
            });
        }

        // On mobile the parent link expands its submenu instead of navigating;
        // on desktop it stays a normal link and the submenu opens on hover.
        var parents = header.querySelectorAll('.pv-has-sub');
        for (var i = 0; i < parents.length; i++) {
            (function (parent) {
                var link = parent.querySelector(':scope > a');
                if (!link) return;
                link.addEventListener('click', function (e) {
                    if (!window.matchMedia(MOBILE).matches) return;
                    e.preventDefault();
                    parent.classList.toggle('open');
                });
            })(parents[i]);
        }
    });
})();
</script>