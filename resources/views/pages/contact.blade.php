@php
    use App\Support\Content;

    $promises  = Content::company()['promises'];
    $addr      = config('site.address');
    $phone     = config('site.phone');
    $hours     = config('site.hours');
    $canonical = route('contact');
@endphp

<x-layout
    title="Contact VirtuaCore"
    description="Book a scoping call. Thirty minutes produces a written scope covering tasks, hours, tools and the measure of success, and you keep it whether or not you work with us."
    :crumbs="[
        ['name' => 'Home',    'url' => route('home')],
        ['name' => 'Contact', 'url' => $canonical],
    ]"
    :schema="[[
        '@type'         => 'ContactPage',
        '@id'           => $canonical.'#contact',
        'url'           => $canonical,
        'isPartOf'      => ['@id' => url('/#website')],
        'mainEntity'    => ['@id' => url('/#organization')],
    ]]">

<main id="main">

  <section class="vc-hero--compact">
    <x-hero-video compact />

    <div class="vc-wrap vc-wrap--wide">
      <nav class="vc-breadcrumb" aria-label="Breadcrumb">
        <a href="{{ route('home') }}">Home</a>
        <x-icon name="caret-right" />
        <span aria-current="page">Contact</span>
      </nav>

      <div class="vc-split" style="margin-top:1.75rem;align-items:start;">

        <div>
          <h1 class="vc-display" style="font-size:clamp(2rem,4.4vw,3.2rem);max-width:14ch;">
            Tell us what is <em>eating your week</em>
          </h1>
          <p class="vc-deck" style="margin-top:1.15rem;">
            Thirty minutes on a call produces a written scope. You keep it whether or not you
            work with us.
          </p>

          <ul class="vc-list" style="margin-top:2rem;">
            @foreach ($promises as $promise)
              <li>
                <x-icon :name="$promise['icon']" class="vc-icon vc-icon--sm" />
                <span>{{ $promise['text'] }}</span>
              </li>
            @endforeach
          </ul>

          <div class="vc-hairline" style="margin-top:2.5rem;padding-top:2rem;display:grid;gap:1.5rem;">
            <div>
              <h2 class="vc-eyebrow">Direct</h2>
              <ul class="vc-foot__list" style="margin-top:0.8rem;">
                <li><a class="vc-link" href="mailto:{{ config('site.email') }}">{{ config('site.email') }}</a></li>
                <li><a class="vc-link" href="tel:{{ $phone['e164'] }}">{{ $phone['display'] }}</a></li>
              </ul>
            </div>

            <div>
              <h2 class="vc-eyebrow">Office</h2>
              {{-- Hours come from config and carry the zone. The source template hardcoded
                   them here WITHOUT one, which is the same ambiguity the WordPress site
                   had and unactionable for a prospect in another zone. --}}
              <address style="margin-top:0.8rem;font-style:normal;line-height:1.7;color:var(--text-muted);font-size:0.94rem;">
                {{ $addr['street'] }}<br>
                {{ $addr['locality'] }}, {{ $addr['region'] }} {{ $addr['postal'] }}<br>
                {{ $hours['days'] }}, {{ $hours['open'] }} to {{ $hours['close'] }} {{ $hours['tz'] }}
              </address>
            </div>
          </div>
        </div>

        <div id="start">
          <x-lead-form
              title="Book a call"
              sub="Or write below and we will reply by email."
              cta="Book a call"
              intent="contact" />
        </div>

      </div>
    </div>
  </section>

</main>
</x-layout>
