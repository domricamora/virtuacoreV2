@php
    use App\Support\Content;

    $authors   = Content::authorServices();
    $canonical = route('for-authors');

    $listItems = [];
    foreach (array_keys($authors) as $i => $s) {
        $listItems[] = [
            '@type'    => 'ListItem',
            'position' => $i + 1,
            'name'     => $authors[$s]['name'],
            'url'      => route('authors.show', $s),
        ];
    }

    // Six services, six cells, deliberately uneven so it does not read as a plain grid.
    $spans = ['vc-bento__md', 'vc-bento__md', 'vc-bento__sm', 'vc-bento__sm', 'vc-bento__sm', 'vc-bento__lg'];
@endphp

<x-layout
    title="Publishing and digital media for authors"
    description="Screen-adaptation pitches, book trailers, editorial reviews, festival submissions, author social packages and websites. A separate track with its own team."
    :crumbs="[
        ['name' => 'Home',        'url' => route('home')],
        ['name' => 'For authors', 'url' => $canonical],
    ]"
    :schema="[[
        '@type'      => 'CollectionPage',
        '@id'        => $canonical.'#collection',
        'name'       => 'Publishing and digital media for authors',
        'url'        => $canonical,
        'isPartOf'   => ['@id' => url('/#website')],
        'mainEntity' => [
            '@type'           => 'ItemList',
            'numberOfItems'   => count($authors),
            'itemListElement' => $listItems,
        ],
    ]]">

<main id="main">

  <section class="vc-section" style="position:relative;">
    <div class="vc-hero__aura" aria-hidden="true" style="opacity:0.5;"></div>
    <div class="vc-wrap vc-wrap--wide" style="position:relative;">
      <nav class="vc-breadcrumb" aria-label="Breadcrumb">
        <a href="{{ route('home') }}">Home</a>
        <x-icon name="caret-right" />
        <span aria-current="page">For authors</span>
      </nav>

      <h1 class="vc-display" style="margin-top:1.75rem;max-width:16ch;">
        Get the book <em>in front of people</em>
      </h1>
      <p class="vc-deck" style="margin-top:1.25rem;">
        Screen pitches, trailers, reviews, festival submissions, social and websites. Run by
        the team that does this rather than the one that staffs businesses.
      </p>
    </div>
  </section>

  <section class="vc-section--tight">
    <div class="vc-wrap vc-wrap--wide">
      <div class="vc-bento">
        @foreach ($authors as $slug => $svc)
          @php $tinted = in_array($loop->index, [0, 5], true); @endphp
          <a class="vc-card vc-card--interactive {{ $spans[$loop->index] ?? 'vc-bento__sm' }} vc-reveal"
             href="{{ route('authors.show', $slug) }}"
             @if ($tinted) style="background:radial-gradient(ellipse 90% 130% at 100% 0%, var(--glow-accent), transparent 60%), var(--surface-1);" @endif>
            <div class="vc-svc">
              <span class="vc-icon-badge"><x-icon :name="$svc['icon']" /></span>
              <div>
                <h2 class="vc-svc__title">{{ $svc['name'] }}</h2>
                <p style="margin-top:0.5rem;color:var(--text-muted);line-height:1.6;font-size:0.94rem;">
                  {{ $svc['deck'] }}
                </p>
              </div>
              <span class="vc-more" style="margin-top:auto;">
                Read more
                <x-icon name="arrow-right" class="vc-icon vc-icon--sm" />
              </span>
            </div>
          </a>
        @endforeach
      </div>
    </div>
  </section>

  {{-- The honest note. This is the most defensible thing on the page and must not be
       softened into a claim: no pitch guarantees an adaptation, no review service
       guarantees a favourable review, no submission service guarantees a festival place.
       llms.txt repeats it so an assistant summarising the site inherits the caveats
       rather than dropping them. --}}
  <section class="vc-section" style="background:var(--surface-1);border-block:1px solid var(--hairline);">
    <div class="vc-wrap vc-wrap--narrow">
      <h2 class="vc-h2" style="max-width:20ch;">What none of this guarantees</h2>
      <div class="vc-prose" style="margin-top:1.5rem;color:var(--text-body);">
        <p>
          No pitch package guarantees an adaptation. No review service guarantees a good
          review. No submission service guarantees a festival place. Anyone promising those
          is selling you something they do not control.
        </p>
        <p>
          What these services do is remove the reasons a decision-maker stops reading:
          material in the wrong format, a book with no editorial credibility, a submission
          that missed the window. The decision stays with them. The preparation is ours.
        </p>
      </div>
    </div>
  </section>

  <section class="vc-section--tight" style="border-bottom:1px solid var(--hairline);">
    <div class="vc-wrap vc-wrap--wide">
      <div style="display:flex;flex-wrap:wrap;gap:1.5rem;align-items:center;justify-content:space-between;">
        <div>
          <h2 class="vc-h3" style="font-size:1.15rem;">Running a business rather than writing a book?</h2>
          <p style="margin-top:0.45rem;color:var(--text-muted);max-width:52ch;line-height:1.6;">
            Remote staffing for sales, admin, support, project management and marketing.
          </p>
        </div>
        <a class="vc-btn vc-btn--ghost" href="{{ route('services') }}">
          See staffing services
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
            <h2 class="vc-h2" style="max-width:18ch;">Tell us where the book is up to</h2>
            <p style="margin-top:1.15rem;color:var(--text-muted);line-height:1.7;max-width:46ch;">
              What exists, what does not, and what you are trying to reach.
            </p>
          </div>
          <x-lead-form intent="author" />
        </div>
      </div>
    </div>
  </section>

</main>
</x-layout>
