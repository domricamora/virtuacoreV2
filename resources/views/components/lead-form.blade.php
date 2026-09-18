@props([
    'title'    => 'Tell us what you need',
    'sub'      => 'A short call, then a written scope. No obligation.',
    'cta'      => 'Book a call',
    'intent'   => 'contact',
    'interest' => '',
    'compact'  => false,
])

@php
    use App\Support\Content;

    $services = Content::services();
    $authors  = Content::authorServices();
    // Unique per instance: a page can embed this twice, and duplicate ids break every
    // label's for= association, which is exactly the kind of a11y bug nothing surfaces.
    $fid = 'lf-'.substr(md5($intent.$interest.uniqid()), 0, 8);
@endphp

<form class="vc-card" style="padding:clamp(1.5rem,3vw,2.25rem);display:grid;gap:1.1rem;"
      method="post" action="{{ route('lead.store') }}" data-lead-form id="{{ $fid }}"
      data-thanks="Thank you. We will reply within one business day.">
  @csrf
  <input type="hidden" name="intent" value="{{ $intent }}">
  <input type="hidden" name="page" value="{{ request()->path() }}">

  {{-- Honeypot. Hidden from assistive tech too, so a screen reader is never told to fill
       it — an off-screen field a bot completes but a human cannot see. --}}
  <div style="position:absolute;left:-9999px;" aria-hidden="true">
    <label for="{{ $fid }}-website">Website</label>
    <input type="text" id="{{ $fid }}-website" name="vc_hp" tabindex="-1" autocomplete="off">
  </div>

  @if ($title !== '')
    <div>
      <h2 class="vc-h3">{{ $title }}</h2>
      @if ($sub !== '')<p class="vc-hint" style="margin-top:0.4rem;">{{ $sub }}</p>@endif
    </div>
  @endif

  @if (session('lead_status'))
    <p class="vc-hint" role="status" aria-live="polite"
       style="color:var(--text-strong);">{{ session('lead_status') }}</p>
  @endif

  <div class="vc-grid vc-grid--2" style="gap:1.1rem;">
    <div class="vc-field">
      <label class="vc-label" for="{{ $fid }}-name">Your name <span class="vc-req" aria-hidden="true">*</span></label>
      <input class="vc-input" type="text" id="{{ $fid }}-name" name="name" value="{{ old('name') }}"
             required autocomplete="name" enterkeyhint="next"
             @error('name') aria-invalid="true" aria-describedby="{{ $fid }}-name-err" @enderror>
      @error('name')<p class="vc-error" id="{{ $fid }}-name-err">{{ $message }}</p>@enderror
    </div>

    <div class="vc-field">
      <label class="vc-label" for="{{ $fid }}-email">Work email <span class="vc-req" aria-hidden="true">*</span></label>
      <input class="vc-input" type="email" id="{{ $fid }}-email" name="email" value="{{ old('email') }}"
             required autocomplete="email" inputmode="email" enterkeyhint="next"
             @error('email') aria-invalid="true" aria-describedby="{{ $fid }}-email-err" @enderror>
      @error('email')<p class="vc-error" id="{{ $fid }}-email-err">{{ $message }}</p>@enderror
    </div>
  </div>

  @unless ($compact)
    <div class="vc-grid vc-grid--2" style="gap:1.1rem;">
      <div class="vc-field">
        <label class="vc-label" for="{{ $fid }}-company">Company</label>
        <input class="vc-input" type="text" id="{{ $fid }}-company" name="company"
               value="{{ old('company') }}" autocomplete="organization">
      </div>
      <div class="vc-field">
        <label class="vc-label" for="{{ $fid }}-phone">Phone</label>
        <input class="vc-input" type="tel" id="{{ $fid }}-phone" name="phone"
               value="{{ old('phone') }}" autocomplete="tel" inputmode="tel">
        <p class="vc-hint">Optional. Useful if you would rather we call.</p>
      </div>
    </div>
  @endunless

  <div class="vc-field">
    <label class="vc-label" for="{{ $fid }}-interest">What do you need?</label>
    <select class="vc-select" id="{{ $fid }}-interest" name="interest">
      <option value="">Not sure yet</option>
      <optgroup label="Hire remote staff">
        @foreach ($services as $lfSlug => $lfSvc)
          <option value="{{ $lfSlug }}" @selected(old('interest', $interest) === $lfSlug)>{{ $lfSvc['name'] }}</option>
        @endforeach
      </optgroup>
      <optgroup label="For authors">
        @foreach ($authors as $lfSlug => $lfSvc)
          <option value="{{ $lfSlug }}" @selected(old('interest', $interest) === $lfSlug)>{{ $lfSvc['name'] }}</option>
        @endforeach
      </optgroup>
    </select>
  </div>

  <div class="vc-field">
    <label class="vc-label" for="{{ $fid }}-message">What would you hand over first?</label>
    <textarea class="vc-textarea" id="{{ $fid }}-message" name="message"
              placeholder="The tasks eating the most of your week">{{ old('message') }}</textarea>
    <p class="vc-hint">The more specific, the more useful the first call.</p>
  </div>

  <div style="display:grid;gap:0.75rem;">
    <button class="vc-btn vc-btn--primary vc-btn--block" type="submit">{{ $cta }}</button>
    <p class="vc-hint" data-form-status role="status" aria-live="polite" hidden></p>
    <p class="vc-hint">
      We reply within one business day. Your details are never sold or shared.
      <a class="vc-link" href="{{ route('privacy') }}">Privacy</a>
    </p>
  </div>
</form>
