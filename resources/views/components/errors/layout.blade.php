@props(['code', 'title', 'message'])

<x-layout :title="$title" robots="noindex,nofollow">
<main id="main">
  <section class="vc-section" style="position:relative;min-height:60dvh;display:grid;align-items:center;">
    <div class="vc-hero__aura" aria-hidden="true" style="opacity:0.4;"></div>
    <div class="vc-wrap vc-wrap--narrow" style="position:relative;">

      <p class="tnum" style="font-family:var(--font-mono);font-size:3.5rem;font-weight:600;color:var(--accent-text);line-height:1;">
        {{ $code }}
      </p>

      <h1 class="vc-display" style="margin-top:1rem;font-size:clamp(1.8rem,4vw,2.6rem);max-width:18ch;">
        {{ $title }}
      </h1>

      <p class="vc-deck" style="margin-top:1.15rem;max-width:52ch;">{{ $message }}</p>

      {{-- A dead end is worse than the error. Give the visitor the routes they most
           likely wanted rather than only an apology. --}}
      <div class="vc-hero__actions" style="margin-top:2rem;">
        <a class="vc-btn vc-btn--primary" href="{{ route('home') }}">Back to the homepage</a>
        <a class="vc-btn vc-btn--ghost" href="{{ route('services') }}">See services</a>
      </div>

      <div class="vc-hairline" style="margin-top:2.5rem;padding-top:1.5rem;">
        <p class="vc-hint">
          Looking for something specific?
          <a class="vc-link" href="{{ route('contact') }}">Tell us what you need</a>
          or email <a class="vc-link" href="mailto:{{ config('site.email') }}">{{ config('site.email') }}</a>.
        </p>
      </div>

    </div>
  </section>
</main>
</x-layout>
