@php
    use App\Support\Content;

    $services = Content::services();
    $authors  = Content::authorServices();
    $phone    = config('site.phone');
    $addr     = config('site.address');
    $hours    = config('site.hours');
@endphp

<footer class="vc-foot">
  <div class="vc-wrap vc-wrap--wide vc-section--tight">
    <div class="vc-foot__grid">

      <div>
        <x-brand />
        <p style="margin-top:1rem;max-width:32ch;line-height:1.6;color:var(--text-muted);font-size:0.93rem;">
          Vetted remote staff for growing businesses. Scoped in writing, replaced if wrong.
        </p>

        <ul class="vc-foot__list" style="margin-top:1.5rem;">
          <li>
            <a href="mailto:{{ config('site.email') }}" style="display:inline-flex;gap:0.5rem;align-items:center;">
              <x-icon name="envelope-simple" class="vc-icon vc-icon--sm" />{{ config('site.email') }}
            </a>
          </li>
          {{-- Display string and tel: link are two views of ONE config value. The WordPress
               site showed 307-333-8809 but linked tel:+1-800-456-478-23, an Elementor
               placeholder that is not valid E.164 and cannot be dialled. --}}
          <li>
            <a href="tel:{{ $phone['e164'] }}" style="display:inline-flex;gap:0.5rem;align-items:center;">
              <x-icon name="phone" class="vc-icon vc-icon--sm" />{{ $phone['display'] }}
            </a>
          </li>
          <li style="display:flex;gap:0.5rem;align-items:flex-start;color:var(--text-muted);font-size:0.93rem;">
            <x-icon name="map-pin" class="vc-icon vc-icon--sm" />
            <span>{{ $addr['street'] }}<br>{{ $addr['locality'] }}, {{ $addr['region'] }} {{ $addr['postal'] }}</span>
          </li>
        </ul>
      </div>

      <div>
        <h2 class="vc-foot__title">Hire remote staff</h2>
        <ul class="vc-foot__list">
          @foreach ($services as $footSlug => $footSvc)
            <li><a href="{{ route('services.show', $footSlug) }}">{{ $footSvc['nav'] }}</a></li>
          @endforeach
          <li><a href="{{ route('services') }}">All services</a></li>
        </ul>
      </div>

      <div>
        <h2 class="vc-foot__title">For authors</h2>
        <ul class="vc-foot__list">
          @foreach ($authors as $footSlug => $footSvc)
            <li><a href="{{ route('authors.show', $footSlug) }}">{{ $footSvc['nav'] }}</a></li>
          @endforeach
        </ul>
      </div>

      <div>
        <h2 class="vc-foot__title">Company</h2>
        {{-- Only links that go somewhere real. The WordPress footer pointed Contact,
             Connect, Subscribe, Terms, Sitemap, Careers, Newsroom, Case Studies and
             Disclosures all at "#", which is worse than omitting them. --}}
        <ul class="vc-foot__list">
          <li><a href="{{ route('how-it-works') }}">How it works</a></li>
          <li><a href="{{ route('pricing') }}">Pricing</a></li>
          <li><a href="{{ route('about') }}">About</a></li>
          <li><a href="{{ route('contact') }}">Contact</a></li>
          <li><a href="{{ route('privacy') }}">Privacy</a></li>
          <li><a href="{{ route('terms') }}">Terms</a></li>
        </ul>

        @php $social = array_filter(config('site.social')) @endphp
        @if ($social)
          <ul class="vc-foot__list" style="display:flex;gap:1rem;margin-top:1.25rem;">
            @foreach ($social as $network => $url)
              <li>
                <a href="{{ $url }}" rel="me noopener" target="_blank">
                  <x-icon :name="$network.'-logo'" :label="Str::title($network)" class="vc-icon vc-icon--sm" />
                </a>
              </li>
            @endforeach
          </ul>
        @endif
      </div>
    </div>

    <div class="vc-foot__bottom vc-hairline" style="margin-top:3rem;padding-top:1.5rem;">
      <p>&copy; {{ date('Y') }} {{ config('site.name') }}. All rights reserved.</p>
      {{-- The zone is stated, not implied: a prospect in another zone cannot act on an
           unqualified opening time. --}}
      <p>{{ $hours['days'] }}, {{ $hours['open'] }} to {{ $hours['close'] }} {{ $hours['tz'] }}</p>
    </div>
  </div>
</footer>
