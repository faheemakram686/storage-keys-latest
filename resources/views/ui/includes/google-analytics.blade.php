<!-- Google tag (gtag.js) -->
@unless($deferTags ?? false)
<script async src="https://www.googletagmanager.com/gtag/js?id=G-50RLY19Z5F"></script>
@endunless
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-50RLY19Z5F');
@if($deferTags ?? false)
  skLoadTag('https://www.googletagmanager.com/gtag/js?id=G-50RLY19Z5F');
@endif
</script>
