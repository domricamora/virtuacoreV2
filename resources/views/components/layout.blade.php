@props([
    'title'       => null,
    'description' => null,
    'bodyClass'   => null,
    'crumbs'      => [],
    'schema'      => [],
    'ogImage'     => null,
    'robots'      => 'index,follow,max-image-preview:large,max-snippet:-1',
])

@php
    use App\Support\Seo;

    $brand     = config('site.name');
    $full      = $title ? (str_contains($title, $brand) ? $title : "{$title} | {$brand}") : $brand;
    $canonical = url()->current();
    $desc      = $description ? Seo::meta($description) : null;
    $og        = $ogImage ?: asset('images/hero-poster.jpg');

    $graph = Seo::graph(array_merge(
        (array) $schema,
        [Seo::breadcrumbs((array) $crumbs)]
    ));
@endphp
<!doctype html>
<html lang="en" class="vc-html">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">

<title>{{ $full }}</title>
@if ($desc)<meta name="description" content="{{ $desc }}">@endif
<meta name="robots" content="{{ $robots }}">
<link rel="canonical" href="{{ $canonical }}">
<link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
<link rel="apple-touch-icon" href="{{ asset('images/logo-square.png') }}">
<meta name="google-site-verification" content="{{ config('site.google_site_verification') }}">

<meta property="og:type" content="website">
<meta property="og:site_name" content="{{ $brand }}">
<meta property="og:title" content="{{ $full }}">
@if ($desc)<meta property="og:description" content="{{ $desc }}">@endif
<meta property="og:url" content="{{ $canonical }}">
<meta property="og:image" content="{{ $og }}">
<meta name="twitter:card" content="summary_large_image">

{{-- Set the theme before first paint. Without this the page flashes the wrong theme.
     The .js class is also what lets the reveal animation hide content safely: the hidden
     state is scoped to .js, so a blocked script leaves everything visible rather than
     stranding whole sections at opacity 0 forever. Content must never need JavaScript to
     become visible. --}}
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

@stack('preload')

<script type="application/ld+json">{!! $graph !!}</script>

@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body @class(['vc-body', $bodyClass])>
<a class="vc-skip" href="#main">Skip to content</a>

<x-site.nav />

{{ $slot }}

<x-site.footer />
</body>
</html>
