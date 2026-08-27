    <!-- FOOTER AREA START -->
    
    <!-- BRAND LOGO AREA START -->
    <!-- ============ STICKY MOBILE BAR ============ -->
    <div class="wh-mobilebar">
        <a href="tel:+971565018785"><i class="fas fa-phone"></i> Call</a>
        <a href="https://wa.me/971565018785" class="wa"><i class="fab fa-whatsapp"></i> WhatsApp</a>
        <a href="{{ url('/contact-us') }}#ct-quote"><i class="fas fa-file-invoice-dollar"></i> Quote</a>
    </div>

</div>

<footer class="pv-footer">
  <div class="sk-container">
    <div class="pv-foot-grid">
      <div class="pv-foot-brand">
        <a href="{{ url('/') }}" class="pv-foot-logo">
          <img src="{{ asset('sk-assets/assets/images/frontend/front-logo.png') }}" alt="StorageKeys" width="223" height="64" decoding="async">
        </a>
        <p>StorageKeys provides secure, flexible self storage solutions for individuals and businesses across Dubai, Sharjah and the UAE. From personal belongings and luggage to business inventory and warehouse space, we offer convenient storage units tailored to your needs.</p>
        <div class="pv-social">
          <a href="https://www.facebook.com/storagekeysllc" target="_blank" rel="noopener" aria-label="Facebook"><i class="fab fa-facebook-f" aria-hidden="true"></i></a>
          <a href="https://www.linkedin.com/company/storage-keys-uae" target="_blank" rel="noopener" aria-label="LinkedIn"><i class="fab fa-linkedin-in" aria-hidden="true"></i></a>
          <a href="https://wa.me/971565018785" aria-label="WhatsApp"><i class="fab fa-whatsapp" aria-hidden="true"></i></a>
        </div>
      </div>
      <div class="pv-foot-col">
        <span class="h5">Company</span>
        <div class="pv-foot-links">
          <a href="{{ url('/about-us') }}">About Us</a>
          <a href="{{ url('/storage-options') }}">Storage Options</a>
          <a href="{{ url('/booking') }}">Booking</a>
          <a href="{{ url('/shop') }}">Shop</a>
          <a href="{{ url('/contact-us') }}">Contact Us</a>
          <a href="{{ url('/blogs') }}">Blogs</a>
        </div>
      </div>
      <div class="pv-foot-col pv-foot-support">
        <span class="h5">Support</span>
        <div class="pv-foot-links">
          <a href="{{ url('/privacy-policy') }}">Privacy Policy</a>
          <a href="{{ url('/security-policy') }}">Security Policy</a>
          <a href="{{ url('/support-policy') }}">Support Policy</a>
          <a href="{{ url('/cookie-policy') }}">Cookie Policy</a>
          <a href="{{ url('/terms-of-service') }}">Terms of Service</a>
          <a href="{{ url('/frequently-asked-questions') }}">FAQs</a>
        </div>
      </div>
      <div class="pv-foot-col pv-foot-contact">
        <span class="h5">Get in Touch</span>
        <p><i class="fas fa-map-marker-alt"></i> Sharjah &amp; Dubai, United Arab Emirates</p>
        <p><i class="fas fa-phone"></i> <a href="tel:+971565018785">+971 56 501 8785</a></p>
        <p><i class="fas fa-phone"></i> Toll Free: <a href="tel:8005397">800 5397</a></p>
        <p><i class="fas fa-envelope"></i> <a href="mailto:sales@storagekeys.com">sales@storagekeys.com</a></p>
      </div>
    </div>
    <div class="pv-copy">© StorageKeys 2026 — All rights reserved.</div>
  </div>
</footer>  
  
    <!-- All JS Plugins -->
    <script src="{{ asset('sk-assets/js/frontend/plugins.js') }}"></script>
    <!-- Main JS -->
    <script src="{{ asset('sk-assets/js/frontend/main.js') }}"></script>
    @include('ui.includes.page-js')

    <script src="{{ asset('sk-assets/js/common.js') }}"></script>
    <script src="{{ asset('sk-assets/js/toastr.min.js') }}"></script>

    <!--Start of Tawk.to Script-->
{{--    <script type="text/javascript">--}}
{{--        var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();--}}
{{--        (function(){--}}
{{--            var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];--}}
{{--            s1.async=true;--}}
{{--            s1.src='https://embed.tawk.to/688a194a76f67519325e5aa5/1j1dn5je4';--}}
{{--            s1.charset='UTF-8';--}}
{{--            s1.setAttribute('crossorigin','*');--}}
{{--            s0.parentNode.insertBefore(s1,s0);--}}
{{--        })();--}}
{{--    </script>--}}
    <!--End of Tawk.to Script-->

    <script>
        var wa_btnSetting = {"btnColor":"#16BE45","ctaText":"","cornerRadius":40,"marginBottom":20,"marginLeft":20,"marginRight":20,"btnPosition":"right","whatsAppNumber":"971565018785","welcomeMessage":"Hi there!\nHow can I help you?","zIndex":999999,"btnColorScheme":"light"};

        function loadWhatsAppWidget() {
            if (window._waEmbed) {
                _waEmbed(wa_btnSetting);
                return;
            }

            var s = document.createElement('script');
            s.src = 'https://d2mpatx37cqexb.cloudfront.net/delightchat-whatsapp-widget/embeds/embed.min.js';
            s.async = true;
            s.onload = function () {
                if (window._waEmbed) {
                    _waEmbed(wa_btnSetting);
                }
            };
            document.body.appendChild(s);
        }

        if ('requestIdleCallback' in window) {
            requestIdleCallback(loadWhatsAppWidget, { timeout: 3000 });
        } else {
            window.addEventListener('load', function () {
                setTimeout(loadWhatsAppWidget, 1500);
            });
        }
    </script>


