@php
    use App\Support\Content;
    use App\Support\Seo;

    $home       = Content::home();
    $services   = Content::services();
    $process    = Content::process();
    $objections = Content::objections();

    $faqPairs = [];
    foreach ($objections as $o) {
        $faqPairs[] = [$o['q'], $o['a']];
    }
@endphp

<x-layout
    title="Hire vetted remote staff"
    description="Hire vetted remote staff for sales, admin, customer support, project management and marketing. Scoped in writing, tested on skills, replaced if wrong."
    body-class="vc-page-home"
    :schema="[Seo::faqPage($faqPairs, route('home'))]">

@push('preload')
<link rel="preload" as="image" href="{{ asset('images/hero-poster.webp') }}" type="image/webp" fetchpriority="high">
@endpush

<main id="main">

  {{-- Full-bleed video hero. Copy stays LEFT aligned and width-capped over the footage:
       centred text over a full-bleed clip is the most templated hero on the web and it
       reads worst, because the measure runs to the viewport width. --}}
  <section class="vc-hero vc-hero--video">

    {{-- The poster paints immediately and is the LCP image. The video mounts over it only
         when the hero is near the viewport AND the session qualifies: reduced motion,
         Save-Data, 2g or low memory all stop here, having fetched none of the 1.9 MB.
         Sources stay in data-src until that gate passes, which is what makes
         preload="none" actually mean none. --}}
    <div class="vc-hero__video">
      <video data-hero-video
             muted loop playsinline preload="none"
             poster="{{ asset('images/hero-poster.webp') }}"
             aria-hidden="true" tabindex="-1">
        <source data-src="{{ asset('video/hero-720.mp4') }}"  type="video/mp4" media="(max-width: 1023px)">
        <source data-src="{{ asset('video/hero-1080.mp4') }}" type="video/mp4">
      </video>
    </div>

    <div class="vc-hero__poster" aria-hidden="true"
         style="--hero-poster-src:url('{{ asset('images/hero-poster.webp') }}');"></div>

    <div class="vc-hero__scrim" aria-hidden="true"></div>

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
      </div>
    </div>

    {{-- WCAG 2.2.2. Hidden by script only when the video will never play, because then
         there is nothing to pause. --}}
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

  </section>

  {{-- Outcome row: hairline-separated, no cards. Four short promises do not need four
       boxes, and boxing them would make them look like a feature grid rather than
       commitments. --}}
  <section class="vc-section--tight" style="border-block:1px solid var(--hairline);">
    <div class="vc-wrap vc-wrap--wide">
      <div class="vc-grid" style="grid-template-columns:repeat(auto-fit,minmax(210px,1fr));gap:2rem;">
        @foreach ($home['outcomes'] as $o)
          <div class="vc-reveal">
            <x-icon :name="$o['icon']" class="vc-icon vc-icon--lg" />
            <h2 class="vc-h3" style="margin-top:0.85rem;font-size:1.05rem;">{{ $o['title'] }}</h2>
            <p style="margin-top:0.4rem;font-size:0.93rem;color:var(--text-muted);line-height:1.55;">{{ $o['body'] }}</p>
          </div>
        @endforeach
      </div>
    </div>
  </section>

  {{-- Services as an uneven bento, not five equal cards. The lead role gets the large
       cell; the rest are secondary and look it. --}}
  <section class="vc-section">
    <div class="vc-wrap vc-wrap--wide">
      <h2 class="vc-h2" style="max-width:20ch;">Five roles that carry a business day</h2>
      <p class="vc-deck" style="margin-top:0.9rem;max-width:52ch;">
        Defined jobs with an agreed measure, not general assistants you have to invent work for.
      </p>

      @php $spans = ['vc-bento__lg', 'vc-bento__sm', 'vc-bento__sm', 'vc-bento__sm', 'vc-bento__sm']; @endphp

      <div class="vc-bento" style="margin-top:2.5rem;">
        @foreach ($services as $slug => $svc)
          <a class="vc-card vc-card--interactive {{ $spans[$loop->index] ?? 'vc-bento__sm' }} vc-reveal"
             href="{{ route('services.show', $slug) }}">
            <div class="vc-svc">
              <span class="vc-icon-badge"><x-icon :name="$svc['icon']" /></span>
              <div>
                <h3 class="vc-svc__title">{{ $svc['name'] }}</h3>
                <p style="margin-top:0.5rem;color:var(--text-muted);line-height:1.6;font-size:0.94rem;">
                  {{ $svc['deck'] }}
                </p>

                @if ($loop->first)
                  <div style="display:flex;flex-wrap:wrap;gap:0.4rem;margin-top:1.1rem;">
                    @foreach (array_keys($svc['roles']) as $role)
                      <span class="vc-chip">{{ $role }}</span>
                    @endforeach
                  </div>
                @endif
              </div>
              <span class="vc-more" style="margin-top:auto;">
                Read more
                <x-icon name="arrow-right" class="vc-icon vc-icon--sm" />
              </span>
            </div>
          </a>
        @endforeach
      </div>
    </div>
  </section>

  <section class="vc-section" style="background:var(--surface-1);border-block:1px solid var(--hairline);">
    <div class="vc-wrap vc-wrap--wide">
      <div class="vc-split vc-split--sticky">

        <div>
          <p class="vc-eyebrow">Why it holds</p>
          <h2 class="vc-h2" style="margin-top:1rem;max-width:16ch;">
            Most outsourcing fails on the setup, not the person
          </h2>
          <p style="margin-top:1.25rem;color:var(--text-muted);line-height:1.7;max-width:44ch;">
            A vague brief produces a hire nobody can evaluate, which turns into supervision,
            which is the job you were trying to hand over. Four things stop that.
          </p>
          <a class="vc-btn vc-btn--ghost" style="margin-top:1.75rem;" href="{{ route('how-it-works') }}">
            How placement works
            <x-icon name="arrow-right" class="vc-icon vc-icon--sm" />
          </a>
        </div>

        {{-- Numbered 01-04 because these ARE sequential: scope before screening before
             access before the guarantee. The same treatment on unordered content is one
             of the tells that a page was assembled rather than written. --}}
        <div class="vc-grid" style="gap:0;">
          @foreach ($home['pillars'] as $pillar)
            <div class="vc-reveal" style="display:flex;gap:1.25rem;padding:1.65rem 0;@if (! $loop->first) border-top:1px solid var(--hairline); @endif">
              <span class="tnum" style="font-family:var(--font-mono);font-size:0.8rem;color:var(--accent-text);padding-top:0.28rem;">
                {{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}
              </span>
              <div>
                <h3 class="vc-h3" style="font-size:1.12rem;">{{ $pillar['title'] }}</h3>
                <p style="margin-top:0.55rem;color:var(--text-muted);line-height:1.68;max-width:52ch;">{{ $pillar['body'] }}</p>
              </div>
            </div>
          @endforeach
        </div>

      </div>
    </div>
  </section>

  <section class="vc-section">
    <div class="vc-wrap vc-wrap--wide">
      <h2 class="vc-h2" style="max-width:18ch;">From first call to someone working</h2>

      <ol class="vc-grid" style="grid-template-columns:repeat(auto-fit,minmax(240px,1fr));margin-top:2.5rem;list-style:none;gap:0;">
        @foreach ($process as $step)
          <li class="vc-reveal vc-d{{ $loop->iteration }}" style="padding:1.75rem 1.5rem 1.75rem 0;border-top:2px solid var(--accent);">
            <p class="tnum" style="font-family:var(--font-mono);font-size:0.75rem;letter-spacing:0.16em;text-transform:uppercase;color:var(--accent-text);">
              {{ $step['step'] }}
            </p>
            <h3 class="vc-h3" style="margin-top:0.75rem;font-size:1.1rem;">{{ $step['title'] }}</h3>
            <p style="margin-top:0.6rem;color:var(--text-muted);line-height:1.6;font-size:0.94rem;">{{ $step['body'] }}</p>
          </li>
        @endforeach
      </ol>
    </div>
  </section>

  <section class="vc-section" style="background:var(--surface-1);border-block:1px solid var(--hairline);">
    <div class="vc-wrap vc-wrap--wide">
      <h2 class="vc-h2" style="max-width:22ch;">The four questions everyone asks</h2>
      <div class="vc-faq" style="margin-top:2rem;max-width:72ch;">
        @foreach ($objections as $n => $o)
          <details class="vc-faq__item" @if ($n === 0) open @endif>
            <summary class="vc-faq__q">
              <span>{{ $o['q'] }}</span>
              <x-icon name="caret-down" class="vc-icon vc-icon--sm" />
            </summary>
            <div class="vc-faq__a">{{ $o['a'] }}</div>
          </details>
        @endforeach
      </div>
    </div>
  </section>

  {{-- The second audience gets one clear door rather than being mixed into the staffing
       sections. Authors and business owners are buying different things. --}}
  <section class="vc-section">
    <div class="vc-wrap vc-wrap--wide">
      <div class="vc-card" style="padding:clamp(1.75rem,4vw,3rem);">
        <span class="vc-chip"><x-icon name="film-slate" class="vc-icon vc-icon--sm" /> Also for authors</span>
        <h2 class="vc-h2" style="margin-top:1.15rem;max-width:22ch;font-size:clamp(1.5rem,3vw,2.1rem);">
          Publishing and digital media, for writers rather than businesses
        </h2>
        <p style="margin-top:1rem;color:var(--text-muted);line-height:1.7;max-width:60ch;">
          Screen-adaptation pitches, book trailers, editorial reviews, festival submissions,
          author social and websites. A separate track with its own team.
        </p>
        <a class="vc-btn vc-btn--ghost" style="margin-top:1.5rem;" href="{{ route('for-authors') }}">
          See author services
          <x-icon name="arrow-right" class="vc-icon vc-icon--sm" />
        </a>
      </div>
    </div>
  </section>

  <section class="vc-section" id="start">
    <div class="vc-wrap vc-wrap--wide">
      <div class="vc-cta">
        <div class="vc-split" style="align-items:center;">
          <div>
            <h2 class="vc-h2" style="max-width:16ch;">Start with the scope, not the hire</h2>
            <p style="margin-top:1.15rem;color:var(--text-muted);line-height:1.7;max-width:46ch;">
              Thirty minutes to define the job properly. You get the written scope whether or
              not you go ahead with us.
            </p>

            <ul class="vc-list" style="margin-top:1.75rem;">
              @foreach ($home['cta_points'] as $point)
                <li>
                  <x-icon name="check-circle" class="vc-icon vc-icon--sm" />
                  <span>{{ $point }}</span>
                </li>
              @endforeach
            </ul>
          </div>

          <x-lead-form
              title="Tell us what you need"
              sub="We reply within one business day."
              cta="Book a call"
              intent="consult" />
        </div>
      </div>
    </div>
  </section>

</main>
</x-layout>
