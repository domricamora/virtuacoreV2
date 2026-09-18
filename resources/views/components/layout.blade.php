@props(['title' => null, 'description' => null, 'bodyClass' => null])

@php
    $brand = config('site.name');
    $full  = $title ? (str_contains($title, $brand) ? $title : "{$title} | {$brand}") : $brand;
@endphp
<!doctype html>
<html lang="en" class="vc-html">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">

<title>{{ $full }}</title>
@if ($description)<meta name="description" content="{{ $description }}">@endif
<link rel="canonical" href="{{ url()->current() }}">
<meta name="google-site-verification" content="{{ config('site.google_site_verification') }}">

{{-- Set the theme before first paint. Without this the page flashes the wrong theme, and
     the .js class is what lets the reveal animation hide content safely: content must
     never need JavaScript to become visible, so the hidden state is scoped to .js and a
     blocked script simply leaves everything shown. --}}
<script>
(function () {
  var d = document.documentElement;
  d.classList.add('js');
  try {
    var t = localStorage.getItem('vc-theme');
    if (t === 'dark' || t === 'light') d.dataset.theme = t;
  } catch (e) {}
})();
</script>

{{-- The hero poster is the LCP image on the homepage, so it is preloaded rather than
     discovered when the browser reaches the markup. --}}
@stack('preload')

@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body @class(['vc-body', $bodyClass])>
<a class="vc-skip" href="#main">Skip to content</a>

{{ $slot }}

</body>
</html>
