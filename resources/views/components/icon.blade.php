@props(['name', 'label' => null, 'class' => 'vc-icon'])

{{-- One <use> reference into the Phosphor sprite built by tools/build-icons.mjs.
     Never hand-draw an icon path: it drifts from the family's stroke weight, and there is
     no reason to redraw work that ships as a dependency. Add a slug to ICONS and run
     `npm run icons` — a missing slug fails the build loudly rather than rendering an
     empty box. --}}
<svg class="{{ $class }}"
     @if ($label) role="img" aria-label="{{ $label }}" @else aria-hidden="true" focusable="false" @endif>
  <use href="{{ asset('images/icons.svg') }}#i-{{ $name }}"></use>
</svg>
