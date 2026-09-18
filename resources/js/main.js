/**
 * Entry bundle. Deliberately small and dependency-free.
 *
 * GSAP is never imported at the top level. It is dynamically imported only when a page
 * actually contains the section that needs it, and only when the device and the user's
 * motion preference say it is appropriate, so a page without a scroll scene never
 * downloads it. The hero video is gated the same way.
 */

const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)');

/** Saves data, tiny memory, or an explicit motion preference all mean: do not animate heavily. */
function wantsLightweight() {
  if (reduceMotion.matches) return true;
  const c = navigator.connection;
  if (c && (c.saveData || /^(slow-)?2g$/.test(c.effectiveType || ''))) return true;
  if (typeof navigator.deviceMemory === 'number' && navigator.deviceMemory <= 2) return true;
  return false;
}

/* ------------------------------------------------------------------ theme */

function initTheme() {
  const toggle = document.querySelector('[data-theme-toggle]');
  if (!toggle) return;

  const sync = () => {
    const explicit = document.documentElement.dataset.theme;
    const isDark = explicit
      ? explicit === 'dark'
      : !window.matchMedia('(prefers-color-scheme: light)').matches;
    toggle.setAttribute('aria-pressed', String(isDark));
    toggle.setAttribute('aria-label', isDark ? 'Switch to light theme' : 'Switch to dark theme');
  };

  toggle.addEventListener('click', () => {
    const explicit = document.documentElement.dataset.theme;
    const isDark = explicit
      ? explicit === 'dark'
      : !window.matchMedia('(prefers-color-scheme: light)').matches;
    const next = isDark ? 'light' : 'dark';
    document.documentElement.dataset.theme = next;
    try { localStorage.setItem('vc-theme', next); } catch { /* private mode */ }
    sync();
  });

  sync();
}

/* ------------------------------------------------------------------ navigation */

function initNav() {
  const toggle = document.querySelector('[data-nav-toggle]');
  const panel = document.getElementById('vc-nav-panel');
  if (!toggle || !panel) return;

  const setOpen = (open) => {
    toggle.setAttribute('aria-expanded', String(open));
    panel.dataset.open = String(open);
    document.body.style.overflow = open ? 'hidden' : '';
  };

  toggle.addEventListener('click', () => {
    setOpen(toggle.getAttribute('aria-expanded') !== 'true');
  });

  panel.addEventListener('click', (e) => {
    if (e.target.closest('a')) setOpen(false);
  });

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && toggle.getAttribute('aria-expanded') === 'true') {
      setOpen(false);
      toggle.focus();
    }
  });

  // Close when the layout returns to desktop, so a hidden panel cannot trap scroll.
  window.matchMedia('(min-width: 1024px)').addEventListener('change', (e) => {
    if (e.matches) setOpen(false);
  });

  // Condense the bar once the page has moved. IntersectionObserver on a sentinel rather
  // than a scroll listener: no work on the main thread per frame.
  const sentinel = document.querySelector('[data-nav-sentinel]');
  const bar = document.querySelector('[data-nav-bar]');
  if (sentinel && bar) {
    new IntersectionObserver(
      ([entry]) => bar.classList.toggle('is-stuck', !entry.isIntersecting),
      { rootMargin: '0px' }
    ).observe(sentinel);
  }
}

/* ------------------------------------------------------------------ scroll reveal */

function initReveal() {
  const items = document.querySelectorAll('.vc-reveal');
  if (!items.length) return;

  // Reduced motion: the CSS already forces these visible. Do not observe at all.
  if (reduceMotion.matches) {
    items.forEach((el) => el.classList.add('is-in'));
    return;
  }

  if (!('IntersectionObserver' in window)) {
    items.forEach((el) => el.classList.add('is-in'));
    return;
  }

  const io = new IntersectionObserver(
    (entries) => {
      for (const entry of entries) {
        if (!entry.isIntersecting) continue;
        entry.target.classList.add('is-in');
        io.unobserve(entry.target); // reveal once, then stop paying for it
      }
    },
    { threshold: 0.12, rootMargin: '0px 0px -8% 0px' }
  );

  items.forEach((el) => io.observe(el));
}

/* ------------------------------------------------------------------ count-up */

function initCounters() {
  const nodes = document.querySelectorAll('[data-count]');
  if (!nodes.length) return;

  const render = (el, value) => {
    const dp = Number(el.dataset.decimals || 0);
    const prefix = el.dataset.prefix || '';
    const suffix = el.dataset.suffix || '';
    el.textContent = prefix + value.toLocaleString('en-US', {
      minimumFractionDigits: dp,
      maximumFractionDigits: dp,
    }) + suffix;
  };

  // Reduced motion still needs the number, just not the animation.
  if (reduceMotion.matches || !('IntersectionObserver' in window)) {
    nodes.forEach((el) => render(el, Number(el.dataset.count || 0)));
    return;
  }

  const run = (el) => {
    const target = Number(el.dataset.count || 0);
    const duration = 1400;
    const start = performance.now();

    const tick = (now) => {
      const t = Math.min(1, (now - start) / duration);
      const eased = 1 - Math.pow(1 - t, 3); // easeOutCubic
      render(el, target * eased);
      if (t < 1) requestAnimationFrame(tick);
      else render(el, target);
    };
    requestAnimationFrame(tick);
  };

  const io = new IntersectionObserver(
    (entries) => {
      for (const entry of entries) {
        if (!entry.isIntersecting) continue;
        run(entry.target);
        io.unobserve(entry.target);
      }
    },
    { threshold: 0.5 }
  );

  nodes.forEach((el) => {
    render(el, 0);
    io.observe(el);
  });
}

/* ------------------------------------------------------------------ lead forms */

function initForms() {
  const forms = document.querySelectorAll('form[data-lead-form]');

  forms.forEach((form) => {
    form.addEventListener('submit', async (e) => {
      e.preventDefault();

      const button = form.querySelector('[type="submit"]');
      const status = form.querySelector('[data-form-status]');
      const original = button ? button.textContent : '';

      // Clear previous errors so a second attempt does not show stale messages.
      form.querySelectorAll('[data-error-for]').forEach((n) => { n.textContent = ''; n.hidden = true; });
      form.querySelectorAll('[aria-invalid]').forEach((n) => n.removeAttribute('aria-invalid'));

      if (button) { button.disabled = true; button.textContent = 'Sending...'; }
      if (status) { status.hidden = false; status.className = 'vc-hint'; status.textContent = 'Sending your message...'; }

      try {
        const res = await fetch(form.action, {
          method: 'POST',
          body: new FormData(form),
          headers: { 'X-Requested-With': 'fetch' },
        });
        const data = await res.json();

        if (data.ok) {
          const thanks = form.dataset.thanks || 'Thank you. We will be in touch within one business day.';
          form.innerHTML = `<div class="vc-flash vc-flash--ok" role="status">${thanks}</div>`;
          return;
        }

        if (data.field) {
          const input = form.querySelector(`[name="${data.field}"]`);
          const slot = form.querySelector(`[data-error-for="${data.field}"]`);
          if (input) { input.setAttribute('aria-invalid', 'true'); input.focus(); }
          if (slot) { slot.textContent = data.error; slot.hidden = false; }
          if (status) status.hidden = true;
        } else if (status) {
          status.className = 'vc-error';
          status.textContent = data.error || 'Something went wrong. Please try again.';
        }
      } catch {
        if (status) {
          status.className = 'vc-error';
          status.textContent = 'We could not reach the server. Please check your connection and try again.';
        }
      } finally {
        if (button) { button.disabled = false; button.textContent = original; }
      }
    });
  });
}

/* ------------------------------------------------------------------ analytics beacon */

function initTracking() {
  const el = document.querySelector('[data-track-endpoint]');
  if (!el) return;

  // Honour Do Not Track and Global Privacy Control without waiting for the server.
  if (navigator.doNotTrack === '1' || window.doNotTrack === '1' || navigator.globalPrivacyControl) return;

  const body = new URLSearchParams({
    path: location.pathname + location.search,
    title: document.title,
    referrer: document.referrer || '',
  });

  // keepalive so the request survives the user navigating away immediately.
  fetch(el.dataset.trackEndpoint, {
    method: 'POST',
    body,
    keepalive: true,
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
  }).catch(() => { /* analytics must never surface an error to the visitor */ });
}

/* ------------------------------------------------------------------ heavy modules */

/**
 * The hero background video.
 *
 * The poster is the LCP image and paints immediately from markup. The video carries
 * preload="none" and its sources sit in data-src, so a visitor who never qualifies for
 * it — reduced motion, Save-Data, 2g, low memory — does not fetch a single byte of the
 * 1.9 MB file. That gate is inherited from the WebGL hero this replaced and matters more
 * here, not less: a canvas cost 2.8 KB, a video costs megabytes.
 *
 * WCAG 2.2.2 requires a mechanism to pause moving content that plays for more than five
 * seconds, so the control is real markup, not decoration, and it is what the keyboard and
 * screen reader reach.
 */
function initHero() {
  const video = document.querySelector('[data-hero-video]');
  if (!video) return;

  const toggle = document.querySelector('[data-hero-toggle]');

  const setToggle = (playing) => {
    if (!toggle) return;
    toggle.setAttribute('aria-pressed', playing ? 'false' : 'true');
    toggle.setAttribute('aria-label', playing ? 'Pause background video' : 'Play background video');
    toggle.dataset.state = playing ? 'playing' : 'paused';
  };

  // A lightweight session keeps the poster as the final state. Nothing is fetched.
  if (wantsLightweight()) {
    if (toggle) toggle.hidden = true;
    return;
  }

  let loaded = false;
  const load = () => {
    if (loaded) return;
    loaded = true;
    video.querySelectorAll('source[data-src]').forEach((el) => {
      el.src = el.dataset.src;
      el.removeAttribute('data-src');
    });
    video.load();
    video.play().then(() => {
      video.dataset.playing = 'true';
      setToggle(true);
    }).catch(() => {
      // Autoplay refused. The poster is already correct, so there is nothing to say.
      setToggle(false);
    });
  };

  // Only fetch once the hero is actually on screen.
  const io = new IntersectionObserver((entries, obs) => {
    if (!entries.some((e) => e.isIntersecting)) return;
    obs.disconnect();
    load();
  }, { rootMargin: '200px' });
  io.observe(video);

  toggle?.addEventListener('click', () => {
    if (!loaded) { load(); return; }
    if (video.paused) {
      video.play().then(() => { video.dataset.playing = 'true'; setToggle(true); }).catch(() => {});
    } else {
      video.pause();
      video.dataset.playing = 'false';
      setToggle(false);
    }
  });

  // If the visitor turns reduced-motion on mid-session, stop rather than keep playing.
  reduceMotion.addEventListener?.('change', (e) => {
    if (e.matches && !video.paused) {
      video.pause();
      video.dataset.playing = 'false';
      setToggle(false);
    }
  });
}

function initScrollScenes() {
  const scenes = document.querySelectorAll('[data-scroll-scene]');
  if (!scenes.length) return;

  // Reduced motion: the markup is already readable as stacked sections. Do not load GSAP.
  if (reduceMotion.matches) {
    scenes.forEach((s) => s.setAttribute('data-scroll-scene-disabled', 'true'));
    return;
  }

  import('./scroll.js')
    .then((m) => m.mountScenes(scenes))
    .catch(() => {
      scenes.forEach((s) => s.setAttribute('data-scroll-scene-disabled', 'true'));
    });
}

/* ------------------------------------------------------------------ boot */

function boot() {
  initTheme();
  initNav();
  initReveal();
  initCounters();
  initForms();
  initHero();
  initScrollScenes();

  // Tracking last, and after paint, so it never competes with rendering.
  if ('requestIdleCallback' in window) requestIdleCallback(initTracking, { timeout: 2500 });
  else setTimeout(initTracking, 1200);
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', boot, { once: true });
} else {
  boot();
}
