<!-- Google Tag Manager -->
@if($deferTags ?? false)
{{-- Tag scripts (~500 KB of JS) load on the visitor's first interaction, or 3s after the page has loaded,
     so they don't compete with first paint. Events pushed before then queue in dataLayer as usual. --}}
<script>
window.dataLayer = window.dataLayer || [];
dataLayer.push({'gtm.start': new Date().getTime(), event: 'gtm.js'});
window.skLoadTag = (function (w, d) {
    var queue = [], done = false, triggers = ['scroll', 'pointerdown', 'keydown', 'touchstart'];
    function inject(src) {
        var s = d.createElement('script');
        s.async = true;
        s.src = src;
        d.head.appendChild(s);
    }
    function run() {
        if (done) return;
        done = true;
        triggers.forEach(function (t) { w.removeEventListener(t, run); });
        queue.forEach(inject);
    }
    triggers.forEach(function (t) { w.addEventListener(t, run, { passive: true }); });
    w.addEventListener('load', function () { setTimeout(run, 3000); });
    return function (src) { done ? inject(src) : queue.push(src); };
})(window, document);
skLoadTag('https://www.googletagmanager.com/gtm.js?id=GTM-TFQVW88D');
</script>
@else
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-TFQVW88D');</script>
@endif
<!-- End Google Tag Manager -->
