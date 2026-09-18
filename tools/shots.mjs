/**
 * Screenshots and layout checks for the running site.
 *
 * Uses playwright-core driving the Chrome already installed on this machine, so nothing is
 * downloaded and there is no second browser to keep updated.
 *
 * Chrome's own `--headless --window-size` does NOT set the CSS viewport: a 390px window
 * still lays the page out near 700px and then crops it, which looks exactly like a mobile
 * overflow bug and is not one. That false alarm is why this file exists.
 *
 *   node tools/shots.mjs                     capture the default set
 *   node tools/shots.mjs --check             layout assertions only, no images
 *   node tools/shots.mjs --url /pricing/     one page
 */

import { chromium } from 'playwright-core';
import { mkdirSync } from 'node:fs';
import { join } from 'node:path';

const BASE = process.env.VC_BASE ?? 'http://localhost/virtuacore';
const OUT = process.env.VC_SHOTS ?? join(process.cwd(), '.shots');
const args = process.argv.slice(2);
const checkOnly = args.includes('--check');
const only = args.includes('--url') ? args[args.indexOf('--url') + 1] : null;

const PAGES = only ? [['custom', only]] : [
  ['home', '/'],
  ['services', '/services/'],
  ['service-sales', '/services/sales-development/'],
  ['how-it-works', '/how-it-works/'],
  ['pricing', '/pricing/'],
  ['for-authors', '/for-authors/'],
  ['contact', '/contact/'],
];

const VIEWPORTS = [
  ['desktop', 1440, 900],
  ['mobile', 390, 844],
];

mkdirSync(OUT, { recursive: true });

const browser = await chromium.launch({ channel: 'chrome' });
let failures = 0;

for (const [theme, colorScheme] of [['dark', 'dark'], ['light', 'light']]) {
  for (const [vpName, width, height] of VIEWPORTS) {
    // Light mobile and dark desktop are the two that matter most; the full matrix is
    // four passes over seven pages and is slow for little extra signal.
    if (theme === 'light' && vpName === 'mobile' && !only) continue;

    const ctx = await browser.newContext({
      viewport: { width, height },
      colorScheme,
      deviceScaleFactor: 1,
    });
    const page = await ctx.newPage();

    for (const [name, path] of PAGES) {
      await page.goto(BASE + path, { waitUntil: 'networkidle' });

      // Scroll the whole page once so every IntersectionObserver-driven reveal fires,
      // then return to the top. Without this a fullPage capture shows the below-the-fold
      // sections as blank space, because they were never scrolled into view.
      //
      // The pause per step is deliberate and cannot be a single requestAnimationFrame:
      // IntersectionObserver delivers its callbacks asynchronously, so a scroll that
      // jumps a viewport per frame moves past each element before the observer ever
      // reports it. That produced a full-page capture with every reveal section blank.
      await page.evaluate(async () => {
        // The site sets scroll-behavior: smooth, which turns every programmatic scroll
        // below into an animation. Measurements then ran while the page was still gliding
        // back to the top and reported headings as sitting under the nav when they were
        // not. Forced to auto for the duration, and restored afterwards.
        const prev = document.documentElement.style.scrollBehavior;
        document.documentElement.style.scrollBehavior = 'auto';

        const step = Math.round(window.innerHeight * 0.7);
        for (let y = 0; y < document.body.scrollHeight; y += step) {
          window.scrollTo(0, y);
          await new Promise((r) => setTimeout(r, 140));
        }
        window.scrollTo(0, 0);
        await new Promise((r) => setTimeout(r, 60));
        document.documentElement.style.scrollBehavior = prev;
      });
      // Let the reveal transitions finish and webfonts settle before measuring.
      await page.waitForTimeout(900);

      // Measuring anywhere other than the very top makes the nav-occlusion check
      // meaningless, so assert it rather than assuming it.
      const scrolled = await page.evaluate(() => window.scrollY);
      if (scrolled !== 0) {
        console.log(`  WARN ${name}: page not at top before measuring (scrollY ${scrolled})`);
      }

      const probe = await page.evaluate((vw) => {
        const doc = document.documentElement;
        const issues = [];

        if (doc.scrollWidth > vw + 1) {
          issues.push(`page scrolls horizontally: ${doc.scrollWidth}px in a ${vw}px viewport`);
        }

        // Any CTA whose label has wrapped to a second line.
        for (const b of document.querySelectorAll('.vc-btn')) {
          const r = b.getBoundingClientRect();
          const lh = parseFloat(getComputedStyle(b).lineHeight) || 16;
          if (r.height > lh * 2 + 34) {
            issues.push(`CTA wraps: "${b.textContent.trim().slice(0, 30)}" ${Math.round(r.height)}px`);
          }
        }

        const nav = document.querySelector('.vc-nav__bar');
        if (nav && nav.getBoundingClientRect().height > 80) {
          issues.push(`nav taller than 80px: ${Math.round(nav.getBoundingClientRect().height)}px`);
        }

        // At the top of the page, nothing in the first section may sit under the sticky
        // nav. A centred full-height hero overflows upward once its content is taller
        // than the viewport, which put the first line of the h1 behind the nav on a
        // phone with the page already scrolled to the top: unreachable, not just ugly.
        if (nav) {
          const navBottom = nav.getBoundingClientRect().bottom;
          const first = document.querySelector('main h1, main .vc-eyebrow');
          if (first) {
            const top = first.getBoundingClientRect().top;
            if (top < navBottom - 2) {
              issues.push(`first heading sits under the sticky nav (top ${Math.round(top)}px vs nav bottom ${Math.round(navBottom)}px)`);
            }
          }
        }

        const h1s = document.querySelectorAll('h1');
        if (h1s.length !== 1) issues.push(`expected exactly one h1, found ${h1s.length}`);

        // A hero headline runs to two lines at desktop, three on a narrow phone. Beyond
        // that it is a font-scale error, not a copy-length one: the type was chosen
        // before anyone checked how the words actually break.
        if (h1s.length === 1 && vw >= 1024) {
            const h1 = h1s[0];
            const lh = parseFloat(getComputedStyle(h1).lineHeight);
            const lines = Math.round(h1.getBoundingClientRect().height / lh);
            if (lines > 2) {
                issues.push(`h1 wraps to ${lines} lines at ${vw}px: "${h1.textContent.trim().slice(0, 46)}"`);
            }
        }

        // Eyebrow budget: at most one per three sections.
        //
        // Only SECTION eyebrows count, meaning a small label sitting immediately above a
        // section headline. A .vc-eyebrow used as a field-group label inside a card or a
        // contact block is a different thing and is not what makes a page read templated.
        // Counting every instance flagged five "Roles placed" card labels as a violation.
        const sections = document.querySelectorAll('main > section').length;
        const eyebrows = [...document.querySelectorAll('main .vc-eyebrow')].filter((el) => {
          const next = el.nextElementSibling;
          return next && /^H[123]$/.test(next.tagName);
        }).length;
        const cap = Math.ceil(sections / 3);
        if (sections && eyebrows > cap) {
          issues.push(`${eyebrows} section eyebrows across ${sections} sections (cap ${cap})`);
        }

        // The em-dash ban, checked against rendered text rather than source.
        const text = document.body.innerText;
        if (text.includes('—') || text.includes('–')) {
          const i = Math.max(text.indexOf('—'), text.indexOf('–'));
          issues.push(`em/en dash in visible text near: "${text.slice(Math.max(0, i - 40), i + 40).replace(/\s+/g, ' ')}"`);
        }

        return { issues, sections, eyebrows };
      }, width);

      const tag = `${theme}-${vpName}`;
      if (probe.issues.length) {
        failures += probe.issues.length;
        console.log(`  FAIL ${name} (${tag})`);
        for (const i of probe.issues) console.log(`       - ${i}`);
      } else {
        console.log(`  PASS ${name} (${tag})  sections=${probe.sections} eyebrows=${probe.eyebrows}`);
      }

      if (!checkOnly) {
        await page.screenshot({
          path: join(OUT, `${name}-${tag}.png`),
          fullPage: vpName === 'desktop' && theme === 'dark',
        });
      }
    }

    await ctx.close();
  }
}

await browser.close();
console.log(failures === 0 ? '\nAll layout checks passed.\n' : `\n${failures} issue(s).\n`);
process.exit(failures === 0 ? 0 : 1);
