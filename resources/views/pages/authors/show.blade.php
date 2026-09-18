@php use App\Support\Seo; @endphp

{{-- One template for every author service. Their shapes differ: most carry `includes`,
     social-media-kits carries `tiers`, film-submissions adds `requires`. Each block
     renders only when its key is present, so a new service declares what it has and gets
     a coherent page without a template edit. --}}
<x-layout
    :title="$svc['title']"
    :description="$svc['meta']"
    :crumbs="[
        ['name' => 'Home',        'url' => route('home')],
        ['name' => 'For authors', 'url' => route('for-authors')],
        ['name' => $svc['nav'],   'url' => route('authors.show', $slug)],
    ]"
    :schema="[
        Seo::service($svc, route('authors.show', $slug)),
        Seo::faqPage($svc['faqs'], route('authors.show', $slug)),
    ]">

<main id="main">

  <section class="vc-hero--compact">
    <x-hero-video compact />

    <div class="vc-wrap vc-wrap--wide">
      <nav class="vc-breadcrumb" aria-label="Breadcrumb">
        <a href="{{ route('home') }}">Home</a>
        <x-icon name="caret-right" />
        <a href="{{ route('for-authors') }}">For authors</a>
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
        <a class="vc-btn vc-btn--primary" href="#start">Talk to us</a>
        <a class="vc-btn vc-btn--ghost" href="{{ route('for-authors') }}">All author services</a>
      </div>
    </div>
  </section>

  <section class="vc-section--tight">
    <div class="vc-wrap vc-wrap--wide">
      <p class="vc-answer">{{ $svc['answer'] }}</p>
    </div>
  </section>

  @isset($svc['includes'])
    <section class="vc-section" style="background:var(--surface-1);border-block:1px solid var(--hairline);">
      <div class="vc-wrap vc-wrap--wide">
        <h2 class="vc-h2" style="max-width:20ch;">What you get</h2>
        <div style="margin-top:2rem;max-width:62ch;">
          @foreach ($svc['includes'] as $item)
            <div style="display:flex;gap:1rem;padding:1rem 0;@if (! $loop->first) border-top:1px solid var(--hairline); @endif">
              <x-icon name="check" class="vc-icon vc-icon--sm" />
              <span style="line-height:1.6;">{{ $item }}</span>
            </div>
          @endforeach
        </div>
      </div>
    </section>
  @endisset

  @isset($svc['tiers'])
    <section class="vc-section" style="background:var(--surface-1);border-block:1px solid var(--hairline);">
      <div class="vc-wrap vc-wrap--wide">
        <h2 class="vc-h2" style="max-width:20ch;">Two levels</h2>
        <div class="vc-split" style="margin-top:2.25rem;gap:clamp(1.5rem,4vw,3rem);align-items:start;">
          @foreach ($svc['tiers'] as $tier => $items)
            <div class="vc-card" style="padding:clamp(1.5rem,3vw,2.25rem);">
              <h3 class="vc-h3" style="font-size:1.15rem;">{{ $tier }}</h3>
              <div style="margin-top:1.25rem;">
                @foreach ($items as $item)
                  <div style="display:flex;gap:0.9rem;padding:0.8rem 0;@if (! $loop->first) border-top:1px solid var(--hairline); @endif">
                    <x-icon name="check" class="vc-icon vc-icon--sm" />
                    <span style="line-height:1.55;font-size:0.95rem;">{{ $item }}</span>
                  </div>
                @endforeach
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </section>
  @endisset

  @isset($svc['requires'])
    <section class="vc-section--tight" style="border-block:1px solid var(--hairline);">
      <div class="vc-wrap vc-wrap--wide">
        <div style="display:flex;flex-wrap:wrap;gap:1.5rem;align-items:baseline;justify-content:space-between;">
          <h2 class="vc-h3" style="font-size:1.1rem;">What we need from you first</h2>
          <p class="vc-hint" style="max-width:38ch;">Nothing starts until these exist.</p>
        </div>
        <div style="display:flex;flex-wrap:wrap;gap:0.5rem;margin-top:1.25rem;">
          @foreach ($svc['requires'] as $req)
            <span class="vc-chip">{{ $req }}</span>
          @endforeach
        </div>
      </div>
    </section>
  @endisset

  <section class="vc-section">
    <div class="vc-wrap vc-wrap--narrow">
      <h2 class="vc-h2">Questions about this service</h2>
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
      <h2 class="vc-h3" style="font-size:1.1rem;">Other author services</h2>
      <div class="vc-grid vc-grid--3" style="margin-top:1.5rem;">
        @foreach ($related as $rSlug => $r)
          <a class="vc-card vc-card--interactive" href="{{ route('authors.show', $rSlug) }}"
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
            <h2 class="vc-h2" style="max-width:18ch;">Tell us where the book is up to</h2>
            <p style="margin-top:1.15rem;color:var(--text-muted);line-height:1.7;max-width:46ch;">
              What exists, what does not, and what you are trying to reach. We will tell you
              plainly whether this is the right service for it.
            </p>
          </div>
          <x-lead-form
              :title="'Talk about '.Str::lower($svc['nav'])"
              sub="We reply within one business day."
              cta="Talk to us"
              intent="author"
              :interest="$slug" />
        </div>
      </div>
    </div>
  </section>

</main>
</x-layout>
