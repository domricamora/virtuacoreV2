@props(['class' => 'vc-brand'])

{{-- Both variants ship in the markup and CSS picks one. The supplied mark is white ink,
     which disappears on the light theme's paper; the ink variant is derived from it at
     build time. Swapping by script would flash the wrong mark before hydration. --}}
<a class="{{ $class }}" href="{{ route('home') }}" aria-label="{{ config('site.name') }} — home">
  <img class="vc-brand__logo vc-brand__logo--light"
       src="{{ asset('images/logo-440.png') }}"
       srcset="{{ asset('images/logo-440.png') }} 440w, {{ asset('images/logo-880.png') }} 880w"
       sizes="150px" width="440" height="79" alt="{{ config('site.name') }}" decoding="async">
  <img class="vc-brand__logo vc-brand__logo--ink"
       src="{{ asset('images/logo-440-ink.png') }}"
       srcset="{{ asset('images/logo-440-ink.png') }} 440w, {{ asset('images/logo-880-ink.png') }} 880w"
       sizes="150px" width="440" height="79" alt="" aria-hidden="true" decoding="async">
</a>
