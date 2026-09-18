@php
    use App\Support\Content;
    use App\Support\Seo;

    $services  = Content::services();
    $canonical = route('services');

    $listItems = [];
    foreach (array_keys($services) as $i => $s) {
        $listItems[] = [
            '@type'    => 'ListItem',
            'position' => $i + 1,
            'name'     => $services[$s]['name'],
            'url'      => route('services.show', $s),
        ];
    }
@endphp

<x-layout
    title="Remote staffing services"
    description="Five defined remote roles: sales development, executive admin, customer support, project management, and social and marketing. Scoped in writing before anyone starts."
    :crumbs="[
        ['name' => 'Home',     'url' => route('home')],
        ['name' => 'Services', 'url' => $canonical],
    ]"
    :schema="[[
        '@type'      => 'CollectionPage',
        '@id'        => $canonical.'#collection',
        'name'       => 'Remote staffing services',
        'url'        => $canonical,
        'isPartOf'   => ['@id' => url('/#website')],
        'about'      => ['@id' => url('/#organization')],
        'mainEntity' => [
            '@type'           => 'ItemList',
            'numberOfItems'   => count($services),
            'itemListElement' => $listItems,
        ],
    ]]">

<main id="main">

  <section class="vc-hero--compact">
    <x-hero-video compact />

    <div class="vc-wrap vc-wrap--wide">
      <nav class="vc-breadcrumb" aria-label="Breadcrumb">
        <a href="{{ route('home') }}">Home</a>
        <x-icon name="caret-right" />
        <span aria-current="page">Services</span>
      </nav>

      <h1 class="vc-display" style="margin-top:1.75rem;max-width:16ch;">
        Five roles, each with a <em>written scope</em>
      </h1>
      <p class="vc-deck" style="margin-top:1.25rem;">
        Not general assistants you have to invent work for. Defined jobs with an agreed
        measure, so the role can be judged rather than felt.
      </p>

      <p class="vc-answer" style="margin-top:2.25rem;">
        VirtuaCore places remote staff in five defined roles: sales development, executive
        and administrative support, customer support, project management, and social media
        and marketing. Every placement starts with a written scope covering tasks, hours,
        tools and the measure of success, agreed before candidate screening begins.
      </p>
    </div>
  </section>

  {{-- Sticky stack: each service pins in turn so it gets a full moment rather than
       competing in a grid. Pure CSS position:sticky, so this page loads no JavaScript
       for it and it degrades on its own. The n-of-5 counter is a position in a
       deliberate sequence, not decorative numbering on unordered content. --}}
  <section class="vc-section--tight">
    <div class="vc-wrap vc-wrap--wide">
      <div class="vc-stack">
        @foreach ($services as $slug => $svc)
          <div class="vc-stack__card">
            <article class="vc-card" style="padding:clamp(1.75rem,4vw,3rem);background:var(--surface-1);">
              <div class="vc-split" style="gap:clamp(1.5rem,4vw,3rem);align-items:start;">

                <div>
                  <div style="display:flex;align-items:center;gap:0.9rem;">
                    <span class="vc-icon-badge"><x-icon :name="$svc['icon']" /></span>
                    <span class="tnum" style="font-family:var(--font-mono);font-size:0.78rem;color:var(--text-muted);">
                      {{ $loop->iteration }} of {{ $loop->count }}
                    </span>
                  </div>

                  <h2 class="vc-h2" style="margin-top:1.25rem;font-size:clamp(1.5rem,3vw,2.1rem);max-width:16ch;">
                    {{ $svc['name'] }}
                  </h2>
                  <p style="margin-top:1rem;color:var(--text-muted);line-height:1.7;max-width:44ch;">
                    {{ $svc['deck'] }}
                  </p>

                  <a class="vc-btn vc-btn--ghost" style="margin-top:1.75rem;"
                     href="{{ route('services.show', $slug) }}">
                    Read more
                    <x-icon name="arrow-right" class="vc-icon vc-icon--sm" />
                  </a>
                </div>

                <div>
                  <h3 class="vc-eyebrow">Roles placed</h3>
                  <div style="margin-top:1.1rem;">
                    @foreach ($svc['roles'] as $role => $desc)
                      <div style="padding:0.9rem 0;@if (! $loop->first) border-top:1px solid var(--hairline); @endif">
                        <strong style="display:block;color:var(--text-strong);font-weight:560;font-size:0.98rem;">{{ $role }}</strong>
                        <span style="display:block;margin-top:0.3rem;color:var(--text-muted);font-size:0.9rem;line-height:1.55;">
                          {{ Seo::meta($desc, 110) }}
                        </span>
                      </div>
                    @endforeach
                  </div>
                </div>

              </div>
            </article>
          </div>
        @endforeach
      </div>
    </div>
  </section>

  <section class="vc-section--tight" style="border-block:1px solid var(--hairline);">
    <div class="vc-wrap vc-wrap--wide">
      <div style="display:flex;flex-wrap:wrap;gap:1.5rem;align-items:center;justify-content:space-between;">
        <div>
          <h2 class="vc-h3" style="font-size:1.15rem;">Writing a book rather than running a team?</h2>
          <p style="margin-top:0.45rem;color:var(--text-muted);max-width:52ch;line-height:1.6;">
            Publishing and digital media sit on their own track, with their own team.
          </p>
        </div>
        <a class="vc-btn vc-btn--ghost" href="{{ route('for-authors') }}">
          See author services
          <x-icon name="arrow-right" class="vc-icon vc-icon--sm" />
        </a>
      </div>
    </div>
  </section>

  <section class="vc-section" id="start">
    <div class="vc-wrap vc-wrap--wide">
      <div class="vc-cta">
        <div class="vc-split" style="align-items:center;">
          <div>
            <h2 class="vc-h2" style="max-width:16ch;">Start with the scope, not the hire</h2>
            <p style="margin-top:1.15rem;color:var(--text-muted);line-height:1.7;max-width:46ch;">
              Thirty minutes to write the job down properly. You keep the scope either way.
            </p>
          </div>
          <x-lead-form intent="consult" />
        </div>
      </div>
    </div>
  </section>

</main>
</x-layout>
