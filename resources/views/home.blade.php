<x-layout
    title="Hire vetted remote staff"
    description="Hire vetted remote staff for sales, admin, customer support, project management and marketing. Scoped in writing, tested on skills, replaced if wrong."
    body-class="vc-page-home">

@push('preload')
<link rel="preload" as="image" href="{{ asset('images/hero-poster.webp') }}" type="image/webp" fetchpriority="high">
@endpush

<main id="main">

  <section class="vc-hero">
    <div class="vc-hero__aura" aria-hidden="true"></div>

    <div class="vc-wrap vc-wrap--wide">
      <div class="vc-hero__grid">

        <div class="vc-hero__copy">
          <p class="vc-eyebrow">Remote staffing</p>

          <h1 class="vc-display" style="margin-top:1rem;">
            Hire remote staff who take the work <em>off your desk</em>
          </h1>

          <p class="vc-deck" style="margin-top:1.35rem;">
            Sales, admin, support, project management and marketing. Scoped in writing,
            vetted on skills, replaced if wrong.
          </p>

          <div class="vc-hero__actions">
            <a class="vc-btn vc-btn--primary" href="{{ route('contact') }}">Book a call</a>
            <a class="vc-btn vc-btn--ghost" href="{{ route('services') }}">See services</a>
          </div>
        </div>

        {{-- The poster paints immediately and is the LCP image. The video mounts over it
             only when the hero is near the viewport AND the session qualifies: reduced
             motion, Save-Data, 2g or low memory all stop at the poster, having fetched
             none of the 1.9 MB. Sources stay in data-src until that gate passes, which is
             what makes preload="none" actually mean none. --}}
        <div class="vc-hero__visual">
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

          {{-- WCAG 2.2.2. Hidden by script only when the video will never play, because
               then there is nothing to pause. --}}
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
        </div>

      </div>
    </div>
  </section>

</main>
</x-layout>
