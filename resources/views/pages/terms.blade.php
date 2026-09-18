@php
    $addr  = config('site.address');
    $legal = config('site.legal');
@endphp

<x-layout
    title="Terms of use"
    description="The terms covering use of the VirtuaCore website. Engagements are governed by a separate written agreement."
    :crumbs="[
        ['name' => 'Home',  'url' => route('home')],
        ['name' => 'Terms', 'url' => route('terms')],
    ]">

<main id="main">
  <section class="vc-section">
    <div class="vc-wrap vc-wrap--narrow">
      <nav class="vc-breadcrumb" aria-label="Breadcrumb">
        <a href="{{ route('home') }}">Home</a>
        <x-icon name="caret-right" />
        <span aria-current="page">Terms</span>
      </nav>

      <h1 class="vc-display" style="margin-top:1.5rem;font-size:clamp(2rem,4vw,2.9rem);">Terms of use</h1>
      <p class="vc-hint" style="margin-top:0.9rem;">
        Last updated {{ \Carbon\Carbon::parse($legal['updated'])->format('j F Y') }}.
      </p>

      <div class="vc-prose" style="margin-top:2.5rem;">

        <h2 class="vc-h3" style="margin-top:2.5rem;">These terms cover the website only</h2>
        <p>
          Using this site means accepting what follows. If VirtuaCore places staff with you or
          delivers author services, that work is governed by a separate written agreement, and
          where the two disagree, that agreement wins.
        </p>

        <h2 class="vc-h3" style="margin-top:2.5rem;">What the site is</h2>
        <p>
          A description of services and a way to start a conversation. Nothing here is an offer
          capable of acceptance, and no engagement exists until both sides sign a written
          agreement. Descriptions of scope, coverage, timelines and guarantees explain how we
          normally work; the specifics of your engagement are the ones in your agreement.
        </p>

        <h2 class="vc-h3" style="margin-top:2.5rem;">Pricing</h2>
        <p>
          No rates are published on this site. Any figure given to you is a quote against a
          specific written scope, valid for the period stated on it.
        </p>

        <h2 class="vc-h3" style="margin-top:2.5rem;">What we do not guarantee</h2>
        <p>
          Author services in particular carry outcomes we do not control, and we say so on the
          pages themselves. A screen-adaptation pitch does not guarantee an adaptation. An
          editorial review does not guarantee a favourable review. A festival submission does
          not guarantee selection. Follower figures on social packages are targets based on
          past work, not commitments.
        </p>

        <h2 class="vc-h3" style="margin-top:2.5rem;">Your submissions</h2>
        <p>
          Send us accurate information and do not send anyone else's confidential material
          through the contact form. Do not use the form for bulk or automated messages. What
          you submit is handled as described in the
          <a class="vc-link" href="{{ route('privacy') }}">privacy policy</a>.
        </p>

        <h2 class="vc-h3" style="margin-top:2.5rem;">Acceptable use</h2>
        <p>
          Do not attempt to break, overload, scrape at volume, or gain unauthorised access to
          this site or the systems behind it. Automated crawling is welcome within the limits
          set in our <a class="vc-link" href="{{ url('robots.txt') }}">robots.txt</a>.
        </p>

        <h2 class="vc-h3" style="margin-top:2.5rem;">Intellectual property</h2>
        <p>
          The content, design, wordmark and logo on this site belong to VirtuaCore. You may
          quote and link to pages with attribution. You may not reproduce the site wholesale or
          present its content as your own. Third-party names and logos mentioned belong to their
          respective owners and appear only to describe tools our staff work in.
        </p>

        <h2 class="vc-h3" style="margin-top:2.5rem;">External links</h2>
        <p>
          Where we link out, we do not control the destination and are not responsible for it.
        </p>

        <h2 class="vc-h3" style="margin-top:2.5rem;">Liability</h2>
        <p>
          The site is provided as it is. To the extent the law allows, we are not liable for
          loss arising from relying on it. Nothing here limits liability for fraud or for
          anything that cannot lawfully be limited.
        </p>

        <h2 class="vc-h3" style="margin-top:2.5rem;">Changes</h2>
        <p>
          We may update these terms. The date at the top reflects the current version, and
          continued use of the site after a change means accepting it.
        </p>

        <h2 class="vc-h3" style="margin-top:2.5rem;">Governing law</h2>
        <p>
          These terms are governed by the laws of the State of Wyoming, United States.
        </p>

        <h2 class="vc-h3" style="margin-top:2.5rem;">Contact</h2>
        <p>
          {{ $legal['legal_name'] }}<br>
          {{ $addr['street'] }}, {{ $addr['locality'] }}, {{ $addr['region'] }} {{ $addr['postal'] }}<br>
          <a class="vc-link" href="mailto:{{ config('site.email') }}">{{ config('site.email') }}</a>
        </p>

        <p class="vc-hint" style="margin-top:2.5rem;">
          Not legal advice. Have these reviewed by a qualified adviser before launch.
        </p>
      </div>
    </div>
  </section>
</main>
</x-layout>
