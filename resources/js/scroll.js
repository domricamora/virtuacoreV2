/**
 * The horizontal scroll-pan scene, and nothing else.
 *
 * This is the only effect on the site that genuinely needs a scroll library: pinning a
 * section while converting vertical scroll into horizontal travel, and staying correct
 * across resize, is a lot of fiddly measurement to get right. GSAP ScrollTrigger is worth
 * its 45 KB here.
 *
 * The card stack deliberately does NOT live here. It is plain `position: sticky` in CSS,
 * which costs nothing, cannot desynchronise, and degrades on its own. That keeps the
 * homepage from loading this chunk at all: only /how-it-works/ pays for GSAP.
 *
 * The scene keeps its content in normal DOM order and normal tab order. The animation
 * moves what is already there; it never becomes the only way to reach it. Under reduced
 * motion this file is never loaded and the markup reads as an ordinary scrolling row.
 */

import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

/**
 * Vertical scroll drives horizontal travel. The wrapper pins at the top of the viewport
 * and the inner track slides by exactly its own overflow width.
 */
function horizontal(scene) {
  const track = scene.querySelector('[data-scroll-track]');
  if (!track) return;

  const distance = () => Math.max(0, track.scrollWidth - window.innerWidth);
  if (distance() <= 0) return; // Track already fits: nothing to pan.

  gsap.to(track, {
    x: () => -distance(),
    ease: 'none',
    scrollTrigger: {
      trigger: scene,
      start: 'top top',                  // pin the moment the section reaches the top
      end: () => '+=' + distance(),      // scroll length equals the horizontal travel
      pin: true,
      scrub: 1,
      anticipatePin: 1,
      invalidateOnRefresh: true,         // recompute on resize rather than keeping stale px
    },
  });

  // Progress bar, if the markup provides one.
  const bar = scene.querySelector('[data-scroll-progress]');
  if (bar) {
    gsap.to(bar, {
      scaleX: 1,
      ease: 'none',
      transformOrigin: 'left center',
      scrollTrigger: {
        trigger: scene,
        start: 'top top',
        end: () => '+=' + distance(),
        scrub: true,
      },
    });
  }
}

export function mountScenes(scenes) {
  const ctx = gsap.context(() => {
    scenes.forEach((scene) => {
      if (scene.dataset.scrollScene === 'horizontal') horizontal(scene);
    });
  });

  // Pinned layouts measure in pixels, so late-arriving webfonts and images shift them.
  // Recomputing once everything has loaded avoids a scene that is subtly off by a line.
  const refresh = () => ScrollTrigger.refresh();
  if (document.fonts && document.fonts.ready) document.fonts.ready.then(refresh);
  window.addEventListener('load', refresh, { once: true });

  return function destroy() {
    ctx.revert();
    window.removeEventListener('load', refresh);
  };
}
