@php
    use App\Support\Content;

    $co        = Content::company();
    $addr      = config('site.address');
    $phone     = config('site.phone');
    $hours     = config('site.hours');
    $canonical = route('about');
@endphp

<x-layout
    title="About VirtuaCore"
    description="VirtuaCore places vetted remote specialists and writes the job down so they can be held to it. Three departments, one standard, and a scope you approve before anyone starts."
    :crumbs="[
        ['name' => 'Home',  'url' => route('home')],
        ['name' => 'About', 'url' => $canonical],
    ]">

<main id="main">

  <section class="vc-hero--compact">
    <x-hero-video compact clip="about" />

    <div class="vc-wrap vc-wrap--wide">
      <nav class="vc-breadcrumb" aria-label="Breadcrumb">
        <a href="{{ route('home') }}">Home</a>
        <x-icon name="caret-right" />
        <span aria-current="page">About</span>
      </nav>

      <h1 class="vc-display" style="margin-top:1.75rem;max-width:17ch;">
        Room to breathe, with <em>enough structure</em> to hold
      </h1>
      <p class="vc-deck" style="margin-top:1.25rem;">
        We place specialists who can carry real responsibility, and we write the job down so
        they can be held to it.
      </p>
    </div>
  </section>

  <section class="vc-section--tight">
    <div class="vc-wrap vc-wrap--narrow">
      <div class="vc-prose" style="font-size:1.06rem;color:var(--text-body);">
        @foreach ($co['story'] as $para)
          <p>{{ $para }}</p>
        @endforeach
      </div>
    </div>
  </section>

  {{-- Mission and vision as two panels rather than a card grid: two statements are not a
       collection, and boxing them would imply there are more. --}}
  <section class="vc-section" style="background:var(--surface-1);border-block:1px solid var(--hairline);">
    <div class="vc-wrap vc-wrap--wide">
      <div class="vc-grid vc-grid--2">
        <div style="padding:1.75rem 0;border-top:2px solid var(--accent);">
          <h2 class="vc-h3" style="font-size:1.15rem;">{{ $co['mission']['title'] }}</h2>
          <p style="margin-top:0.85rem;color:var(--text-muted);line-height:1.72;max-width:48ch;">
            {{ $co['mission']['body'] }}
          </p>
        </div>
        <div style="padding:1.75rem 0;border-top:2px solid var(--brand);">
          <h2 class="vc-h3" style="font-size:1.15rem;">{{ $co['vision']['title'] }}</h2>
          <p style="margin-top:0.85rem;color:var(--text-muted);line-height:1.72;max-width:48ch;">
            {{ $co['vision']['body'] }}
          </p>
        </div>
      </div>
    </div>
  </section>

  <section class="vc-section">
    <div class="vc-wrap vc-wrap--wide">
      <h2 class="vc-h2" style="max-width:20ch;">Three departments, one standard</h2>
      <div class="vc-grid vc-grid--3" style="margin-top:2.5rem;">
        @foreach ($co['departments'] as $dept)
          <div class="vc-card vc-reveal" style="padding:1.85rem;">
            <span class="vc-icon-badge"><x-icon :name="$dept['icon']" /></span>
            <h3 class="vc-h3" style="margin-top:1.2rem;font-size:1.12rem;">{{ $dept['title'] }}</h3>
            <p style="margin-top:0.7rem;color:var(--text-muted);line-height:1.68;">{{ $dept['body'] }}</p>
          </div>
        @endforeach
      </div>
    </div>
  </section>

  {{-- Stats sit behind a feature flag and it is OFF. Every counter on the WordPress site
       rendered a literal zero, which is worse than showing nothing: it reads as a company
       with no clients. Turn the flag on only when there are real figures. --}}
  @if (config('site.features.stats'))
    <section class="vc-section--tight" style="border-block:1px solid var(--hairline);">
      <div class="vc-wrap vc-wrap--wide">
        <div class="vc-grid vc-grid--4">
          @foreach (config('site.stats', []) as $stat)
            <div>
              <p class="vc-stat__value tnum">{{ $stat['value'] }}</p>
              <p class="vc-stat__label">{{ $stat['label'] }}</p>
            </div>
          @endforeach
        </div>
      </div>
    </section>
  @endif

  <section class="vc-section--tight" style="border-top:1px solid var(--hairline);">
    <div class="vc-wrap vc-wrap--wide">
      <div class="vc-grid vc-grid--3">
        <div>
          <h2 class="vc-eyebrow">Registered office</h2>
          <p style="margin-top:0.75rem;line-height:1.7;color:var(--text-body);">
            {{ $addr['street'] }}<br>
            {{ $addr['locality'] }}, {{ $addr['region'] }} {{ $addr['postal'] }}
          </p>
        </div>

        <div>
          <h2 class="vc-eyebrow">Get in touch</h2>
          <p style="margin-top:0.75rem;line-height:1.9;">
            <a class="vc-link" href="mailto:{{ config('site.email') }}">{{ config('site.email') }}</a><br>
            <a class="vc-link" href="tel:{{ $phone['e164'] }}">{{ $phone['display'] }}</a>
          </p>
        </div>

        <div>
          <h2 class="vc-eyebrow">Hours</h2>
          <p style="margin-top:0.75rem;line-height:1.7;color:var(--text-body);">
            {{ $hours['days'] }}<br>
            {{ $hours['open'] }} to {{ $hours['close'] }} {{ $hours['tz'] }}
          </p>
        </div>
      </div>
    </div>
  </section>

  <section class="vc-section" id="start">
    <div class="vc-wrap vc-wrap--wide">
      <div class="vc-cta">
        <div class="vc-split" style="align-items:center;">
          <div>
            <h2 class="vc-h2" style="max-width:16ch;">Find out what we would take on</h2>
            <p style="margin-top:1.15rem;color:var(--text-muted);line-height:1.7;max-width:46ch;">
              Tell us what is eating your week. We will tell you plainly which parts of it a
              remote specialist can carry and which parts stay with you.
            </p>
          </div>
          <x-lead-form intent="contact" />
        </div>
      </div>
    </div>
  </section>

</main>
</x-layout>
