@props(['compact' => false])

{{-- Full-bleed hero video, shared by every marketing page.

     The same encoded clip is reused rather than one per page: after the first page view
     it is in the browser cache, so a second page costs nothing, where a second clip would
     cost another 1.9 MB. The compact variant is shorter — an inner page does not need a
     full-height panel before its content.

     The loading gate is the whole point. Sources stay in data-src until reduced-motion,
     Save-Data, 2g and low-memory checks pass AND the hero is near the viewport. A visitor
     who never qualifies sees only the 46 KB poster and fetches none of the video. --}}
<div class="vc-hero__video">
  <video data-hero-video
         muted loop playsinline preload="none"
         poster="{{ asset('images/hero-poster.webp') }}"
         aria-hidden="true" tabindex="-1">
    <source data-src="{{ asset('video/hero-720.mp4') }}"  type="video/mp4" media="(max-width: 1023px)">
    <source data-src="{{ asset('video/hero-1080.mp4') }}" type="video/mp4">
  </video>
</div>

<div class="vc-hero__poster vc-hero__poster--video" aria-hidden="true"
     style="background-image:url('{{ asset('images/hero-poster.webp') }}');"></div>

<div class="vc-hero__scrim" aria-hidden="true"></div>

{{-- WCAG 2.2.2: content moving for more than five seconds needs a mechanism to stop it.
     Hidden by script only when the video will never play, because then there is nothing
     to pause. --}}
<button type="button" class="vc-hero__toggle" data-hero-toggle
        data-state="paused" aria-pressed="true" aria-label="Play background video">
  <svg class="vc-hero__pause" viewBox="0 0 16 16" aria-hidden="true">
    <rect x="3" y="2" width="4" height="12" rx="1"></rect>
    <rect x="9" y="2" width="4" height="12" rx="1"></rect>
  </svg>
  <svg class="vc-hero__play" viewBox="0 0 16 16" aria-hidden="true">
    <path d="M4 2.5v11a.5.5 0 0 0 .76.43l9-5.5a.5.5 0 0 0 0-.86l-9-5.5A.5.5 0 0 0 4 2.5Z"></path>
  </svg>
</button>
