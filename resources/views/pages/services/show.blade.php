@php use App\Support\Seo; @endphp

<x-layout
    :title="$svc['title']"
    :description="$svc['meta']"
    :crumbs="[
        ['name' => 'Home',     'url' => route('home')],
        ['name' => 'Services', 'url' => route('services')],
        ['name' => $svc['nav'], 'url' => route('services.show', $slug)],
    ]"
    :schema="[
        Seo::service($svc, route('services.show', $slug)),
        Seo::faqPage($svc['faqs'], route('services.show', $slug)),
    ]">

<main id="main">

  <section class="vc-hero--compact">
    <x-hero-video compact clip="services" />

    <div class="vc-wrap vc-wrap--wide">
      <nav class="vc-breadcrumb" aria-label="Breadcrumb">
        <a href="{{ route('home') }}">Home</a>
        <x-icon name="caret-right" />
        <a href="{{ route('services') }}">Services</a>
        <x-icon name="caret-right" />
        <span aria-current="page">{{ $svc['nav'] }}</span>
      </nav>

      <div style="display:flex;align-items:flex-start;gap:1.25rem;margin-top:1.75rem;">
        <span class="vc-icon-badge" style="flex:none;"><x-icon :name="$svc['icon']" /></span>
        <div>
          <h1 class="vc-display" style="font-size:clamp(1.85rem,3.7vw,2.85rem);max-width:24ch;">
            {{ $svc['h1'] }}
          </h1>
          <p class="vc-deck" style="margin-top:1.15rem;">{{ $svc['deck'] }}</p>
        </div>
      </div>

      <div class="vc-hero__actions" style="margin-top:1.75rem;">
        <a class="vc-btn vc-btn--primary" href="#start">Book a call</a>
        <a class="vc-btn vc-btn--ghost" href="{{ route('how-it-works') }}">How placement works</a>
      </div>
    </div>
  </section>

  {{-- The answer-first block: deliberately the first substantive text on the page, and
       written to stand alone, because this is the passage an AI assistant quotes. It has
       to make sense without the surrounding page. --}}
  <section class="vc-section--tight">
    <div class="vc-wrap vc-wrap--wide">
      <p class="vc-answer">{{ $svc['answer'] }}</p>
    </div>
  </section>

  <section class="vc-section" style="background:var(--surface-1);border-block:1px solid var(--hairline);">
    <div class="vc-wrap vc-wrap--wide">
      <h2 class="vc-h2" style="max-width:20ch;">Who you actually get</h2>
      <p class="vc-deck" style="margin-top:0.9rem;">
        Distinct roles with distinct scopes. Hiring "a VA" is how the work stays undefined.
      </p>

      <div class="vc-grid vc-grid--3" style="margin-top:2.5rem;">
        @foreach ($svc['roles'] as $role => $desc)
          <div class="vc-card vc-reveal" style="padding:1.75rem;">
            <h3 class="vc-h3" style="font-size:1.1rem;">{{ $role }}</h3>
            <p style="margin-top:0.7rem;color:var(--text-muted);line-height:1.68;">{{ $desc }}</p>
          </div>
        @endforeach
      </div>
    </div>
  </section>

  <section class="vc-section">
    <div class="vc-wrap vc-wrap--wide">
      <div class="vc-split">
        <div>
          <h2 class="vc-h2" style="font-size:clamp(1.6rem,3vw,2.2rem);max-width:16ch;">
            What stops landing on you
          </h2>
          <ul class="vc-list" style="margin-top:1.75rem;">
            @foreach ($svc['outcomes'] as $outcome)
              <li>
                <x-icon name="x-circle" class="vc-icon vc-icon--sm" />
                <span style="color:var(--text-body);">{{ $outcome }}</span>
              </li>
            @endforeach
          </ul>
        </div>

        <div>
          <h2 class="vc-h2" style="font-size:clamp(1.6rem,3vw,2.2rem);max-width:16ch;">
            What they are accountable for
          </h2>
          <div style="margin-top:1.75rem;">
            @foreach ($svc['owns'] as $n => $own)
              <div style="display:flex;gap:1rem;padding:1rem 0;@if ($n > 0) border-top:1px solid var(--hairline); @endif">
                <x-icon name="check" class="vc-icon vc-icon--sm" />
                <span style="line-height:1.6;">{{ $own }}</span>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="vc-section--tight" style="border-block:1px solid var(--hairline);">
    <div class="vc-wrap vc-wrap--wide">
      <div style="display:flex;flex-wrap:wrap;gap:1.5rem;align-items:baseline;justify-content:space-between;">
        <h2 class="vc-h3" style="font-size:1.1rem;">Tools they already work in</h2>
        <p class="vc-hint" style="max-width:38ch;">No licence cost to us, no training week to you.</p>
      </div>
      <div style="display:flex;flex-wrap:wrap;gap:0.5rem;margin-top:1.25rem;">
        @foreach ($svc['tools'] as $tool)
          <span class="vc-chip">{{ $tool }}</span>
        @endforeach
      </div>
    </div>
  </section>

  <section class="vc-section">
    <div class="vc-wrap vc-wrap--narrow">
      <h2 class="vc-h2">Questions about this role</h2>
      <div class="vc-faq" style="margin-top:2rem;">
        @foreach ($svc['faqs'] as $n => [$q, $a])
          <details class="vc-faq__item" @if ($n === 0) open @endif>
            <summary class="vc-faq__q">
              <span>{{ $q }}</span>
              <x-icon name="caret-down" class="vc-icon vc-icon--sm" />
            </summary>
            <div class="vc-faq__a">{{ $a }}</div>
          </details>
        @endforeach
      </div>
    </div>
  </section>

  <section class="vc-section--tight" style="background:var(--surface-1);border-block:1px solid var(--hairline);">
    <div class="vc-wrap vc-wrap--wide">
      <h2 class="vc-h3" style="font-size:1.1rem;">Other roles</h2>
      <div class="vc-grid vc-grid--3" style="margin-top:1.5rem;">
        @foreach ($related as $rSlug => $r)
          <a class="vc-card vc-card--interactive" href="{{ route('services.show', $rSlug) }}"
             style="padding:1.4rem;display:flex;gap:0.9rem;align-items:flex-start;">
            <x-icon :name="$r['icon']" />
            <span>
              <strong style="display:block;color:var(--text-strong);font-weight:560;">{{ $r['name'] }}</strong>
              <span style="display:block;margin-top:0.3rem;font-size:0.88rem;color:var(--text-muted);line-height:1.5;">
                {{ Seo::meta($r['deck'], 70) }}
              </span>
            </span>
          </a>
        @endforeach
      </div>
    </div>
  </section>

  <section class="vc-section" id="start">
    <div class="vc-wrap vc-wrap--wide">
      <div class="vc-cta">
        <div class="vc-split" style="align-items:center;">
          <div>
            <h2 class="vc-h2" style="max-width:16ch;">Scope the role before you hire for it</h2>
            <p style="margin-top:1.15rem;color:var(--text-muted);line-height:1.7;max-width:46ch;">
              Thirty minutes to write down the job properly. You keep the scope whether or not
              you go ahead with us.
            </p>
          </div>
          <x-lead-form
              :title="'Talk about '.Str::lower($svc['nav'])"
              sub="We reply within one business day."
              cta="Book a call"
              intent="consult"
              :interest="$slug" />
        </div>
      </div>
    </div>
  </section>

</main>
</x-layout>
