@php
    $addr  = config('site.address');
    $legal = config('site.legal');
@endphp

<x-layout
    title="Privacy policy"
    description="What VirtuaCore collects, how IP addresses are hashed before storage, how long records are kept, and how to ask for your data."
    robots="index,follow"
    :crumbs="[
        ['name' => 'Home',    'url' => route('home')],
        ['name' => 'Privacy', 'url' => route('privacy')],
    ]">

<main id="main">
  <section class="vc-section">
    <div class="vc-wrap vc-wrap--narrow">
      <nav class="vc-breadcrumb" aria-label="Breadcrumb">
        <a href="{{ route('home') }}">Home</a>
        <x-icon name="caret-right" />
        <span aria-current="page">Privacy</span>
      </nav>

      <h1 class="vc-display" style="margin-top:1.5rem;font-size:clamp(2rem,4vw,2.9rem);">Privacy policy</h1>
      <p class="vc-hint" style="margin-top:0.9rem;">
        Last updated {{ \Carbon\Carbon::parse($legal['updated'])->format('j F Y') }}.
      </p>

      <div class="vc-prose" style="margin-top:2.5rem;">

        <h2 class="vc-h3" style="margin-top:2.5rem;">What we collect</h2>
        <p>
          @if (config('site.features.analytics'))
            Two things. If you submit a form, we store what you typed: your name, email address,
            and optionally your phone number, company and message. If you simply read the site,
            we record a pageview.
          @else
            One thing. If you submit a form, we store what you typed: your name, email address,
            and optionally your phone number, company and message. If you simply read the site
            without contacting us, we do not record anything about your visit.
          @endif
        </p>

        <h2 class="vc-h3" style="margin-top:2.5rem;">How we handle your IP address</h2>
        <p>
          We do not store it. Before anything is written down, your IP address is combined with
          a secret value and put through a one-way hash. What we keep is the result, which
          cannot be reversed back into an address. It lets us tell two submissions apart
          without knowing who either sender is.
        </p>

        @if (config('site.features.analytics'))
          <h2 class="vc-h3" style="margin-top:2.5rem;">Analytics</h2>
          <p>
            Our own analytics run on this server. They record the page path, the referring site,
            your browser and operating system, an approximate location, and the hashed identifier
            described above. There is no advertising network involved and nothing is sold or
            shared.
          </p>
          <p>
            We honour the Do Not Track and Global Privacy Control signals. If your browser sends
            either, no pageview is recorded at all, both in the browser and again on the server.
          </p>
        @endif

        <h2 class="vc-h3" style="margin-top:2.5rem;">Cookies</h2>
        <p>
          One session cookie, so that form submissions can be protected against cross-site
          request forgery. It expires when you close your browser. If you switch the site
          between light and dark, that preference is stored in your browser and never sent
          to us.
        </p>

        <h2 class="vc-h3" style="margin-top:2.5rem;">How long we keep things</h2>
        <p>
          Enquiries are kept while we may still reasonably need them for the conversation you
          started, and then deleted on request.
        </p>

        <h2 class="vc-h3" style="margin-top:2.5rem;">Who else sees it</h2>
        <p>
          Our web host, which necessarily processes requests on our behalf. Nobody else. We do
          not sell data, we do not share it with advertisers, and we do not run third-party
          trackers on this site.
        </p>

        <h2 class="vc-h3" style="margin-top:2.5rem;">Your rights</h2>
        <p>
          You can ask what we hold about you, ask for it to be corrected, or ask for it to be
          deleted. Email
          <a class="vc-link" href="mailto:{{ config('site.email') }}">{{ config('site.email') }}</a>
          and we will respond within 30 days.
        </p>

        <h2 class="vc-h3" style="margin-top:2.5rem;">Contact</h2>
        <p>
          {{ $legal['legal_name'] }}<br>
          {{ $addr['street'] }}, {{ $addr['locality'] }}, {{ $addr['region'] }} {{ $addr['postal'] }}<br>
          <a class="vc-link" href="mailto:{{ config('site.email') }}">{{ config('site.email') }}</a>
        </p>

        <p class="vc-hint" style="margin-top:2.5rem;">
          This policy describes how the site works. It is not legal advice, and it should be
          reviewed by a qualified adviser before launch.
        </p>
      </div>
    </div>
  </section>
</main>
</x-layout>
