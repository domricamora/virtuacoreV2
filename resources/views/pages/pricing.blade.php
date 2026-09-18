@php
    use App\Support\Content;
    use App\Support\Seo;

    $pricing   = Content::pricing();
    $models    = $pricing['models'];
    $drivers   = $pricing['drivers'];
    $faqs      = $pricing['faqs'];
    $canonical = route('pricing');
@endphp

{{-- No rate numbers here. None have been confirmed as publishable, and every model's
     'rate' is null, so the card renders "Quoted after scoping" instead. Adding real
     pricing later is filling in that key, not a redesign. Do not invent a plausible
     figure: a prospect quotes a wrong number back to you on a call. --}}
<x-layout
    title="Pricing and engagement models"
    description="One monthly figure per person, covering management, equipment and cover. No recruitment fee, no placement fee, no charge to replace a wrong placement."
    :crumbs="[
        ['name' => 'Home',    'url' => route('home')],
        ['name' => 'Pricing', 'url' => $canonical],
    ]"
    :schema="[Seo::faqPage($faqs, $canonical)]">

<main id="main">

  <section class="vc-hero--compact">
    <x-hero-video compact clip="pricing" />

    <div class="vc-wrap vc-wrap--wide">
      <nav class="vc-breadcrumb" aria-label="Breadcrumb">
        <a href="{{ route('home') }}">Home</a>
        <x-icon name="caret-right" />
        <span aria-current="page">Pricing</span>
      </nav>

      <h1 class="vc-display" style="margin-top:1.75rem;max-width:16ch;">
        One figure a month. <em>No placement fee.</em>
      </h1>
      <p class="vc-deck" style="margin-top:1.25rem;">
        The person, their management, their equipment and their cover. Quoted after the
        scoping call, before you commit to anything.
      </p>

      <p class="vc-answer" style="margin-top:2.25rem;">
        VirtuaCore charges one monthly rate per person that covers the staff member, their
        management, their equipment and cover for their leave. There is no recruitment fee,
        no placement fee, and no charge to replace a placement that was not right. Rates are
        quoted after a scoping call because they depend on the role and the hours.
      </p>
    </div>
  </section>

  <section class="vc-section--tight">
    <div class="vc-wrap vc-wrap--wide">
      <div class="vc-grid vc-grid--3">
        @foreach ($models as $m)
          <div class="vc-card"
               style="padding:clamp(1.6rem,3vw,2.1rem);display:flex;flex-direction:column;gap:1.15rem;@if ($m['recommended']) border-color:var(--accent);box-shadow:var(--shadow-lift); @endif">

            <div style="display:flex;align-items:center;justify-content:space-between;gap:1rem;">
              <h2 class="vc-h3" style="font-size:1.25rem;">{{ $m['name'] }}</h2>
              @if ($m['recommended'])
                <span class="vc-chip" style="border-color:var(--accent);color:var(--accent-text);">Most chosen</span>
              @endif
            </div>

            <p class="tnum" style="font-family:var(--font-mono);font-size:0.86rem;color:var(--text-muted);">
              {{ $m['hours'] }}
            </p>

            @if ($m['rate'] === null)
              <p style="font-size:1.35rem;font-weight:600;color:var(--text-strong);letter-spacing:-0.02em;">
                Quoted after scoping
              </p>
            @else
              <p class="vc-stat__value tnum">{{ $m['rate'] }}</p>
            @endif

            <p style="color:var(--text-muted);line-height:1.6;font-size:0.94rem;">{{ $m['best'] }}</p>

            <ul class="vc-list" style="margin-top:0.5rem;">
              @foreach ($m['notes'] as $note)
                <li>
                  <x-icon name="check" class="vc-icon vc-icon--sm" />
                  <span style="font-size:0.93rem;">{{ $note }}</span>
                </li>
              @endforeach
            </ul>

            <a class="vc-btn {{ $m['recommended'] ? 'vc-btn--primary' : 'vc-btn--ghost' }} vc-btn--block"
               style="margin-top:auto;" href="#start">Book a call</a>
          </div>
        @endforeach
      </div>
    </div>
  </section>

  <section class="vc-section" style="background:var(--surface-1);border-block:1px solid var(--hairline);">
    <div class="vc-wrap vc-wrap--wide">
      <h2 class="vc-h2" style="max-width:20ch;">What moves the number</h2>
      <p style="margin-top:0.9rem;color:var(--text-muted);line-height:1.7;max-width:52ch;">
        So you can estimate before the call, rather than being surprised on it.
      </p>

      <div class="vc-grid vc-grid--2" style="margin-top:2.5rem;">
        @foreach ($drivers as $d)
          <div class="vc-reveal" style="padding:1.5rem 0;border-top:2px solid var(--accent);">
            <h3 class="vc-h3" style="font-size:1.1rem;">{{ $d['title'] }}</h3>
            <p style="margin-top:0.6rem;color:var(--text-muted);line-height:1.68;max-width:52ch;">{{ $d['body'] }}</p>
          </div>
        @endforeach
      </div>
    </div>
  </section>

  <section class="vc-section">
    <div class="vc-wrap vc-wrap--narrow">
      <h2 class="vc-h2">Pricing questions</h2>
      <div class="vc-faq" style="margin-top:2rem;">
        @foreach ($faqs as $n => [$q, $a])
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

  <section class="vc-section" id="start">
    <div class="vc-wrap vc-wrap--wide">
      <div class="vc-cta">
        <div class="vc-split" style="align-items:center;">
          <div>
            <h2 class="vc-h2" style="max-width:16ch;">Get a quote against a real scope</h2>
            <p style="margin-top:1.15rem;color:var(--text-muted);line-height:1.7;max-width:46ch;">
              The scoping call is free and you keep the written scope either way. It is also
              the only way to compare us against anyone else fairly.
            </p>
          </div>
          <x-lead-form intent="quote" cta="Get a quote" />
        </div>
      </div>
    </div>
  </section>

</main>
</x-layout>
