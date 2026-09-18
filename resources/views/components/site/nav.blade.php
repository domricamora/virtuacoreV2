@php
    use App\Support\Content;

    $services = Content::services();
    $authors  = Content::authorServices();

    /**
     * Loop variables here are NOT the caller's. In the source build these includes ran in
     * the including scope, so a bare $svc overwrote the calling page's own $svc and every
     * service page rendered the LAST nav item's content under the right <title>. Blade
     * components have their own scope, which removes that failure mode structurally
     * rather than by naming convention — the reason this is a component, not an include.
     */
@endphp

<div data-nav-sentinel aria-hidden="true"></div>

<header class="vc-nav">
  <div class="vc-nav__bar" data-nav-bar>
    <div class="vc-wrap vc-wrap--wide" style="display:flex;align-items:center;gap:1.5rem;width:100%;">

      <x-brand />

      <nav class="vc-nav__links" aria-label="Primary">
        <div class="vc-drop">
          <a class="vc-nav__link @if (request()->routeIs('services*')) is-active @endif"
             href="{{ route('services') }}"
             @if (request()->routeIs('services*')) aria-current="page" @endif
             aria-haspopup="true">Services</a>

          <div class="vc-drop__panel" role="group" aria-label="Services">
            @foreach ($services as $navSlug => $navSvc)
              <a class="vc-drop__item" href="{{ route('services.show', $navSlug) }}">
                <span class="vc-icon-badge" style="width:2.25rem;height:2.25rem;">
                  <x-icon :name="$navSvc['icon']" class="vc-icon vc-icon--sm" />
                </span>
                <span>
                  <strong>{{ $navSvc['nav'] }}</strong>
                  <span>{{ Str::limit($navSvc['deck'], 74) }}</span>
                </span>
              </a>
            @endforeach
          </div>
        </div>

        @foreach ([
            ['how-it-works', 'How it works'],
            ['pricing',      'Pricing'],
            ['for-authors',  'For authors'],
            ['about',        'About'],
        ] as [$navRoute, $navLabel])
          <a class="vc-nav__link @if (request()->routeIs($navRoute.'*')) is-active @endif"
             href="{{ route($navRoute) }}"
             @if (request()->routeIs($navRoute.'*')) aria-current="page" @endif>{{ $navLabel }}</a>
        @endforeach
      </nav>

      {{-- One CTA label for the contact intent across the whole site. Two links that both
           mean "talk to us" with different wording make the page feel written by two
           people. --}}
      <div class="vc-nav__actions">
        <button type="button" class="vc-iconbtn" data-theme-toggle aria-pressed="true" aria-label="Switch theme">
          <x-icon name="sun"  class="vc-icon vc-icon--sm vc-icon--sun" />
          <x-icon name="moon" class="vc-icon vc-icon--sm vc-icon--moon" />
        </button>
        <a class="vc-btn vc-btn--primary vc-btn--sm" href="{{ route('contact') }}">Book a call</a>
      </div>

      <button type="button" class="vc-nav__toggle" data-nav-toggle
              aria-expanded="false" aria-controls="vc-nav-panel" aria-label="Open menu">
        <x-icon name="list" />
      </button>
    </div>
  </div>

  <div class="vc-nav__panel" id="vc-nav-panel" data-open="false">
    <nav aria-label="Mobile">
      <a href="{{ route('services') }}">All services</a>

      <div class="vc-nav__group">
        <p class="vc-nav__grouplabel">Hire remote staff</p>
        @foreach ($services as $navSlug => $navSvc)
          <a href="{{ route('services.show', $navSlug) }}">{{ $navSvc['nav'] }}</a>
        @endforeach
      </div>

      <div class="vc-nav__group">
        <p class="vc-nav__grouplabel">For authors</p>
        @foreach ($authors as $navSlug => $navSvc)
          <a href="{{ route('authors.show', $navSlug) }}">{{ $navSvc['nav'] }}</a>
        @endforeach
      </div>

      <div class="vc-nav__group">
        <p class="vc-nav__grouplabel">Company</p>
        <a href="{{ route('how-it-works') }}">How it works</a>
        <a href="{{ route('pricing') }}">Pricing</a>
        <a href="{{ route('about') }}">About</a>
        <a href="{{ route('contact') }}">Contact</a>
      </div>

      <div style="display:flex;gap:0.75rem;align-items:center;margin-top:2rem;">
        <a class="vc-btn vc-btn--primary" href="{{ route('contact') }}" style="flex:1;">Book a call</a>
        <button type="button" class="vc-iconbtn" data-theme-toggle aria-pressed="true" aria-label="Switch theme">
          <x-icon name="sun"  class="vc-icon vc-icon--sm vc-icon--sun" />
          <x-icon name="moon" class="vc-icon vc-icon--sm vc-icon--moon" />
        </button>
      </div>
    </nav>
  </div>
</header>
