@php
    use App\Support\Content;
    use App\Support\Seo;

    $process    = Content::process();
    $objections = Content::objections();
    $weekOne    = Content::weekOne();
    $canonical  = route('how-it-works');

    $howTo = [
        '@type'       => 'HowTo',
        '@id'         => $canonical.'#howto',
        'name'        => 'How VirtuaCore places remote staff',
        'description' => 'The four stages from first call to a placed remote team member.',
        'step'        => [],
    ];
    foreach ($process as $i => $s) {
        $howTo['step'][] = [
            '@type'    => 'HowToStep',
            'position' => $i + 1,
            'name'     => $s['title'],
            'text'     => $s['body'],
        ];
    }

    $faqPairs = [];
    foreach ($objections as $o) {
        $faqPairs[] = [$o['q'], $o['a']];
    }
@endphp

<x-layout
    title="How placement works"
    description="Scope, shortlist, onboard, run. How VirtuaCore writes the job down, tests candidates against it, and reports weekly against a measure agreed up front."
    :crumbs="[
        ['name' => 'Home',         'url' => route('home')],
        ['name' => 'How it works', 'url' => $canonical],
    ]"
    :schema="[$howTo, Seo::faqPage($faqPairs, $canonical)]">

<main id="main">

  <section class="vc-hero--compact">
    <x-hero-video compact />

    <div class="vc-wrap vc-wrap--wide">
      <nav class="vc-breadcrumb" aria-label="Breadcrumb">
        <a href="{{ route('home') }}">Home</a>
        <x-icon name="caret-right" />
        <span aria-current="page">How it works</span>
      </nav>

      <h1 class="vc-display" style="margin-top:1.75rem;max-width:15ch;">
        Write the job down <em>first</em>
      </h1>
      <p class="vc-deck" style="margin-top:1.25rem;">
        Most outsourcing fails on the setup, not the person. Four stages, and the first one
        is the one everybody skips.
      </p>

      <p class="vc-answer" style="margin-top:2.25rem;">
        VirtuaCore places remote staff in four stages. Scope produces a written brief covering
        tasks, hours, tools and the measure of success. Shortlist tests candidates against that
        brief and sends you three. Onboard delivers access, tone and escalation guides in week
        one. Run reports weekly against the agreed measure.
      </p>
    </div>
  </section>

  {{-- Horizontal pan. Four stages that genuinely happen in order, so the scroll moves
       through them in order -- the numbering is a real sequence, not decoration on
       unordered content. Under reduced motion the GSAP chunk is never downloaded and this
       degrades to an ordinary snap-scrolling row with the same DOM order. --}}
  <section class="vc-hscene" data-scroll-scene="horizontal" aria-label="The four stages of placement">
    <div class="vc-section--tight">
      <div class="vc-wrap vc-wrap--wide">
        <div class="vc-hscene__rail" aria-hidden="true">
          <div class="vc-hscene__progress" data-scroll-progress></div>
        </div>
      </div>

      <div class="vc-hscene__viewport" style="margin-top:2rem;">
        <ol class="vc-hscene__track" data-scroll-track style="list-style:none;">
          @foreach ($process as $step)
            <li class="vc-hscene__panel">
              <article class="vc-card" style="padding:clamp(1.75rem,3vw,2.5rem);height:100%;display:flex;flex-direction:column;gap:1.25rem;">
                <div style="display:flex;align-items:baseline;gap:0.9rem;">
                  <span class="tnum" style="font-family:var(--font-mono);font-size:2.25rem;font-weight:600;color:var(--accent-text);line-height:1;">
                    {{ $loop->iteration }}
                  </span>
                  <span class="vc-eyebrow">{{ $step['step'] }}</span>
                </div>

                <h2 class="vc-h3" style="font-size:1.3rem;">{{ $step['title'] }}</h2>
                <p style="color:var(--text-muted);line-height:1.7;">{{ $step['body'] }}</p>

                <ul class="vc-list" style="margin-top:auto;padding-top:1rem;border-top:1px solid var(--hairline);">
                  @foreach ($step['detail'] as $d)
                    <li>
                      <x-icon name="check" class="vc-icon vc-icon--sm" />
                      <span style="font-size:0.92rem;">{{ $d }}</span>
                    </li>
                  @endforeach
                </ul>
              </article>
            </li>
          @endforeach
        </ol>
      </div>
    </div>
  </section>

  <section class="vc-section" style="background:var(--surface-1);border-block:1px solid var(--hairline);">
    <div class="vc-wrap vc-wrap--wide">
      <div class="vc-split vc-split--sticky">
        <div>
          <p class="vc-eyebrow">Week one</p>
          <h2 class="vc-h2" style="margin-top:1rem;max-width:16ch;">
            Onboarding is a deliverable, not a login
          </h2>
          <p style="margin-top:1.25rem;color:var(--text-muted);line-height:1.7;max-width:44ch;">
            Handing over credentials and hoping is how a placement becomes a supervision job.
            These four artefacts are produced in the first week and you approve them before
            normal work starts.
          </p>
        </div>

        <div>
          @foreach ($weekOne as $item)
            <div class="vc-reveal" style="display:flex;gap:1.15rem;padding:1.5rem 0;@if (! $loop->first) border-top:1px solid var(--hairline); @endif">
              <span class="vc-icon-badge" style="flex:none;width:2.75rem;height:2.75rem;">
                <x-icon :name="$item['icon']" class="vc-icon vc-icon--sm" />
              </span>
              <div>
                <h3 class="vc-h3" style="font-size:1.08rem;">{{ $item['title'] }}</h3>
                <p style="margin-top:0.5rem;color:var(--text-muted);line-height:1.65;max-width:50ch;">{{ $item['body'] }}</p>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </div>
  </section>

  <section class="vc-section">
    <div class="vc-wrap vc-wrap--narrow">
      <h2 class="vc-h2">The four questions everyone asks</h2>
      <div class="vc-faq" style="margin-top:2rem;">
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

  <section class="vc-section" id="start">
    <div class="vc-wrap vc-wrap--wide">
      <div class="vc-cta">
        <div class="vc-split" style="align-items:center;">
          <div>
            <h2 class="vc-h2" style="max-width:16ch;">Start with the scope</h2>
            <p style="margin-top:1.15rem;color:var(--text-muted);line-height:1.7;max-width:46ch;">
              Thirty minutes to write the job down properly. You keep the scope whether or not
              you go ahead with us.
            </p>
          </div>
          <x-lead-form intent="consult" />
        </div>
      </div>
    </div>
  </section>

</main>
</x-layout>
