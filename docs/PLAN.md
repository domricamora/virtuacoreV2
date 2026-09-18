# VirtuaCore V2 — WordPress → Laravel + Tailwind Rebuild Plan

> **For Claude Code.** Put this file in the repo root. Work through the phases in order. At the end of each phase: run the checks listed, commit, push, and stop for review before starting the next phase. Anything marked **❓ ASK** must be confirmed with the owner before you guess.

---

## 0. Project summary

| Item | Value |
|---|---|
| Live site (source) | https://www.virtuacore.net/ (WordPress 6 + Elementor) |
| New stack | Laravel (latest stable, PHP 8.3+), Tailwind CSS v4 via Vite, Alpine.js, Blade components |
| Repo | `git@github.com:domricamora/virtuacoreV2.git` (`https://github.com/domricamora/virtuacoreV2.git`) |
| Hosting | Shared hosting, deployed over FTP (credentials provided later — **never commit them**) |
| Goal | Same brand look or better, same content cleaned up and improved, new royalty-free hero video, faster, more accessible, better SEO |

**Business:** VirtuaCore — "Virtual Support, Real Results." Virtual assistant staffing plus publishing and digital-media services for authors.
**Audience:** (1) small-business owners who want to offload admin/sales/support work, (2) self-published authors who want publishing, marketing and adaptation help.
**Primary job of the site:** get a qualified visitor to contact VirtuaCore.

---

## 1. Ground rules for this build

1. No database is required to render pages. Page content lives in versioned PHP data files so the site works on cheap shared hosting.
2. Everything the owner might edit (phone, email, address, hours, socials, services, testimonials, stats) lives in **one place**: `config/site.php` + `resources/content/*.php`.
3. Blade components for every repeated UI piece. No copy-pasted markup between pages.
4. Tailwind v4 with a small design-token layer (`@theme` in `resources/css/app.css`). No Bootstrap, no jQuery, no page builders.
5. Secrets only in `.env` (never committed) and GitHub Actions secrets.
6. Quality floor on every page: responsive 360px → 1920px, keyboard-navigable with visible focus, `prefers-reduced-motion` respected, WCAG AA contrast, Lighthouse ≥ 90 on all four categories (mobile).
7. Small, meaningful commits using Conventional Commits (`feat:`, `fix:`, `chore:`…).

---

## 2. Content inventory (from the live site)

Pull the full copy yourself with `curl`/Playwright during Phase 1 — this inventory is the map, not the final text.

### Global
- Logo: `/wp-content/uploads/2020/07/header-logo.png`; favicon `/wp-content/uploads/2025/04/favicon-300x300.png`
- Tagline: "Virtual Support, Real Results"
- Address: 30 N Gould St Ste N, Sheridan, WY 82801
- Phone shown: +1-307-333-8809
- Email: info@virtuacore.net
- Hours: Mon–Sat 8:00–18:00 (**❓ ASK** which time zone)
- Nav: Home, About Us, Services, Contacts
- Footer blurb: "We make this belief a reality by putting clients first, leading with exceptional ideas, doing the right thing, and giving back."
- Google site verification meta: `ttmbkDUsHXydqb42AtnapaihDHQ1Imb7Oc3TB6_0bw0` — **keep it**

### Home (`/`)
Hero (with background video) → "Here's how our services can benefit you" → About Our Company → VA Services (5) → stat counters → Publishing Services (Hollywood Producer's Pitch, Video Book Trailer, Professional Book Reviews) → stat counters → Digital Media Services (4) → stat counters → Testimonials (5) → footer.

### About (`/about-us-2/`)
Welcome/intro copy, Mission statement, Vision statement, 3 VA images, "how our services can benefit you", testimonials block, Team section (Book Production & Marketing Experts; Digital Marketing Specialists; General Virtual Assistance Professionals).

### Services (`/our-services/`)
- **VA Services:** Sales; Administrative Support; Project Management; Customer Support; Social Media & Marketing (each has an icon PNG under `/wp-content/uploads/2025/04/`)
- **Publishing Services:** Hollywood Producers Pitch (5 benefits); Video Book Trailer (7 benefits); Professional Book Reviews (6 on Services page, 9 on Home)
- **Digital Media Services:** Film Submissions; Social Media Kit; Social Media Premium; Website Creation
- 3 testimonials, 6 client-logo SVGs (`/wp-content/uploads/2020/09/01.svg` … `06.svg`)

### Contacts (`/contacts/`)
Corporate office block with image `/wp-content/uploads/2024/02/Branch_img-2.jpeg`, Google Map embed, contact form (name, email, subject, message).

### Testimonials (Home)
Thomas Jones (Small Business Owner), Jackson Cole Whitaker (Bestselling Author), Emily Grace Holloway (Consultant), Marcus Dean Callahan (Nonfiction Writer), Chloe Annette Kingsley (Debut Author).

---

## 3. Problems on the current site that the rebuild must fix

| # | Problem | Fix |
|---|---|---|
| 1 | Header/footer `tel:` links point to `+1-800-456-478-23` (theme placeholder, not a real number) while the visible number is +1-307-333-8809 | Single `site.phone` value used for both text and `tel:` link. **❓ ASK** confirm the correct number |
| 2 | About page testimonials are theme demo content: lorem ipsum, fake names (Oliver Simson, Mary Grey, Samanta Fox) and images hotlinked from `testerwp.com` | Delete. Reuse the real testimonials from `resources/content/testimonials.php` |
| 3 | Stat counters render "0", "0 +", "0 M+" (JS counter with no values / broken) and appear 3× on the home page | Show once, with real numbers. **❓ ASK** for real figures; if none, drop the stats band rather than invent numbers |
| 4 | "Free & Impartial Advice!" and "Great Clients Awesome Reviews / Phosfluorescently engage…" are leftover template text | Replace with on-brand copy |
| 5 | Footer links (Contact us, Connect, Subscribe, Terms of use, Sitemap, Careers, Newsroom, Case Studies, Disclosures) and all social icons go to `#` | Keep only links that go somewhere real. Add real Privacy Policy + Terms pages. **❓ ASK** for social URLs |
| 6 | Header search box on a 4-page brochure site | Remove |
| 7 | Newsletter "subscribe" with no working form | Either wire it up (Phase 6, optional) or remove. **❓ ASK** |
| 8 | Social Media Kit copy advertises "Mix dummy and organic" followers | Recommend removing the "dummy followers" wording — fake followers break Meta's terms and hurt credibility. **❓ ASK** owner for replacement wording |
| 9 | Messy slug `/about-us-2/` | New clean routes + 301 redirects from old URLs |
| 10 | Copy inconsistencies (Book Reviews has 9 benefits on Home, 6 on Services; "Hollywood Producer's Pitch" vs "Producers Pitch") | One content source → consistent everywhere |
| 11 | Heavy Elementor/WordPress payload | Static Blade + Vite; target < 200 KB CSS+JS gzipped excluding video |
| 12 | Testimonial photos are generic stock images (pxhere) | **❓ ASK** whether real client photos exist; otherwise use initials avatars rather than stock faces |

---

## 4. Phases

### Phase 0 — Repo & environment setup

1. Clone `https://github.com/domricamora/virtuacoreV2.git`. If the repo already has commits, inspect them and **❓ ASK** before overwriting anything.
2. Create a fresh Laravel app in the repo root (`composer create-project laravel/laravel .` into an empty dir, or merge carefully).
3. Install Tailwind v4 + Vite plugin (`@tailwindcss/vite`), Alpine.js (+ `@alpinejs/intersect`, `@alpinejs/collapse`).
4. Dev tooling: Pest, Laravel Pint, Prettier + `prettier-plugin-blade` + `prettier-plugin-tailwindcss`, Playwright (for screenshots/visual checks).
5. `.env.example` with every key the site needs (APP_*, MAIL_*, CONTACT_TO_ADDRESS, TURNSTILE_* optional). Session/cache/queue drivers = `file`/`sync` so no DB is required.
6. `.gitignore` covers `.env`, `/vendor`, `/node_modules`, `/public/build` (built in CI), `/storage/*.key`.
7. Write a `CLAUDE.md` with: stack, commands (`composer dev`, `npm run build`, `php artisan test`), folder conventions, and rule "content lives in config/site.php + resources/content".
8. Initial commit on `main`; do subsequent work on `feat/*` branches merged via PR (or directly on `main` if the owner prefers — **❓ ASK**).

**Done when:** `composer dev` serves the Laravel welcome page with Tailwind working; `php artisan test` passes.

---

### Phase 1 — Capture the current site (baseline)

1. Use Playwright to screenshot every page at 375, 768, 1280 and 1920 px widths → `docs/baseline/`.
2. Download the live CSS (Elementor kit CSS + theme CSS). Extract the real brand palette, fonts, font sizes and spacing → write `docs/brand-audit.md` with hex values and font families.
3. Download all real assets actually used (logo, favicon, service icons, team images, VA images, client-logo SVGs, office image) into `resources/source-media/` with a manifest. Skip theme demo images (anything from `testerwp.com`).
4. Identify the current hero video URL and note its size/length for comparison.
5. Save full page copy to `docs/content-snapshot/*.md`.

**Done when:** `docs/brand-audit.md`, screenshots and the asset manifest exist and are committed.

---

### Phase 2 — Design system

Follow this process: plan tokens → review the plan against the brief → build.

1. **Palette:** start from the extracted brand colours. Keep the brand recognisable, then refine for contrast (AA on all text) and a clear hierarchy: one primary, one accent, neutrals, one dark surface for the footer/stats. Define 4–6 named tokens in `@theme`.
2. **Type:** keep the brand's existing heading font if it's decent; otherwise pick one deliberate pairing (max two families, clearly distinct). Self-host fonts via `@fontsource` packages (no Google Fonts request). Set a real type scale and keep body line length under ~75 characters.
3. **Layout:** 12-col container, generous section rhythm. Service sections should NOT all be identical rounded cards — vary treatment by content type (VA services = compact icon grid; Publishing = feature blocks with benefit lists; Digital Media = package-style blocks).
4. **Motion:** one intentional moment (hero load). No fade-up on every section. Everything disabled under `prefers-reduced-motion`.
5. Avoid generic "AI template" tells: tracked all-caps eyebrow labels above every heading, one-word highlighted headlines, `→` on every link, 01/02/03 numbering on non-sequential content, identical shadowed cards everywhere.
6. Build a hidden `/styleguide` route (local env only) that renders every component.
7. Write the design decisions into `docs/design-system.md`.

**Components (`resources/views/components/`):** `layout.app`, `site.topbar`, `site.header` (sticky, mobile menu with Alpine, focus-trapped), `site.footer`, `hero.video`, `page-header` (title + breadcrumb), `section`, `service.icon-card`, `service.feature`, `service.package`, `testimonial.carousel` (accessible, pausable, no autoplay under reduced motion), `stats`, `cta.band`, `team.card`, `logo-strip`, `form.input`, `form.textarea`, `button`, `seo.meta`, `seo.jsonld`.

**Done when:** the styleguide renders all components at all breakpoints; Playwright screenshots saved to `docs/design/`; owner reviews before Phase 3.

---

### Phase 3 — Content layer

1. `config/site.php` — name, tagline, phone (display + E.164 for `tel:`), email, address, hours + timezone, map embed URL, socials, google verification code.
2. `resources/content/` PHP arrays (typed with small readonly DTOs in `app/Content/` if helpful):
   - `va-services.php` (5), `publishing-services.php` (3, each with benefits), `digital-media-services.php` (4), `testimonials.php` (5 real ones), `team.php` (3 teams), `stats.php` (empty until real numbers arrive), `company.php` (intro, mission, vision).
3. A `Content` service/facade (`Content::get('va-services')`) so views never `include` raw files.

**Copy improvements (do these, then list every change in `docs/copy-changes.md` for owner approval):**
- Tighten the long About copy into a punchy 2-paragraph intro; keep the voice ("guidance, not micromanagement" is good — keep it).
- Write a real hero headline + subhead + primary CTA ("Book a free consultation") + secondary CTA ("See our services").
- Split the site's two audiences clearly: "For businesses" (VA services) and "For authors" (Publishing + Digital Media).
- Rewrite service descriptions to be benefit-first and scannable; unify Book Reviews benefits (pick the best 6).
- Add a short "How it works" section (a real sequence: Tell us what you need → We match a specialist → Onboard & manage → Scale up) — numbered because it IS a sequence.
- Add a short FAQ (pricing model, time zones/coverage, contracts, onboarding time). **❓ ASK** for real answers before publishing; stub with TODO comments.
- Fix all issues from Section 3.

**Done when:** every page's content comes from the content layer; `docs/copy-changes.md` exists.

---

### Phase 4 — Pages & routes

| New route | Name | Old URL → 301 |
|---|---|---|
| `/` | home | `/` |
| `/about` | about | `/about-us-2/`, `/about-us/` |
| `/services` | services | `/our-services/` |
| `/services#va`, `#publishing`, `#digital-media` | anchors | — |
| `/contact` | contact | `/contacts/` |
| `/privacy-policy` | privacy | — |
| `/terms` | terms | — |
| `/sitemap.xml` | sitemap | `/wp-sitemap.xml`, `/sitemap_index.xml` |
| `/robots.txt` | static | — |

- Also 301 any `/wp-admin*`, `/wp-login.php`, `/feed*` to `/` (or return 410) so old bot traffic doesn't hit 404 logs.
- Old `/wp-content/uploads/...` image URLs that are indexed: map the important ones (logo, favicon) to new paths via redirect.
- Custom 404 and 500 pages in brand style.
- **Home order:** Hero (video) → short value statement + 2 audience paths → VA services → How it works → Publishing → Digital Media → Stats (only if real) → Testimonials → Logo strip → CTA band → Footer.
- **About:** page header → intro → mission/vision → team (3 cards) → testimonials → CTA.
- **Services:** page header → sticky in-page nav (VA / Publishing / Digital Media) → the three sections → FAQ → CTA.
- **Contact:** page header → contact details + hours + lazy-loaded map (click-to-load facade, so Google Maps isn't loaded on page view) → form.

**Done when:** all routes return 200, redirects return 301 (covered by Pest tests), pages match the approved design.

---

### Phase 5 — Hero video (royalty-free replacement)

Candidates (Pexels License: free for commercial use, no attribution required, can't be resold unmodified):

1. **Primary:** "A Group Of People Busy Working With Their Computers" by fauxels — https://www.pexels.com/video/a-group-of-people-busy-working-with-their-computers-3249672/ (collaborative team, fits the "VA team" story)
2. Alt: "People Working On Their Laptops In An Office" by cottonbro studio — https://www.pexels.com/video/people-working-on-their-laptops-in-an-office-3202364/
3. Alt: "Team Working Together" by Mikhail Nilov — https://www.pexels.com/video/team-working-together-7989439/

If you can't download from Pexels in this environment, ask the owner to download the 1080p file and drop it into `resources/source-media/video/`. Before finalising, preview the clip and confirm it has no visible third-party logos and the mood matches the palette. Record the source URL, author and licence in `docs/media-credits.md`.

**Encoding (ffmpeg):**
- Trim to a clean 10–15 s seamless loop, strip audio (`-an`).
- `hero-1080.mp4` (H.264, CRF ~28, `-movflags +faststart`), `hero-1080.webm` (VP9), `hero-720.mp4` for small screens. Target ≤ 3 MB for the 1080p MP4.
- `hero-poster.webp` / `.jpg` from a representative frame (also used as LCP image).

**Behaviour:**
- `<video autoplay muted loop playsinline preload="none" poster=...>` with WebM + MP4 sources.
- Dark gradient overlay tuned so hero text meets AA contrast.
- Show only the poster (no video) when `prefers-reduced-motion`, `Save-Data`, or viewport < 640px.
- Visible pause/play button (WCAG 2.2.2).

---

### Phase 6 — Forms & integrations

**Contact form**
- Livewire is optional; a plain POST with a Form Request + Alpine for UX is enough.
- Fields: name, email, phone (optional), "I'm a…" (Business / Author / Other), service interest (select), message.
- Validation with friendly, specific error messages; success state on the same page.
- Spam protection: honeypot + time-trap + `RateLimiter` (5/min per IP). Optional Cloudflare Turnstile behind a config flag.
- Sends a `Mailable` (Markdown mail, branded) to `CONTACT_TO_ADDRESS` via SMTP, and a short auto-reply to the sender. **❓ ASK** for SMTP credentials (host mailbox or a provider like Brevo/Postmark).
- Also appends each submission to `storage/logs/contact.log` (JSON lines) as a fallback if mail fails.
- Pest tests: validation, honeypot rejection, rate limit, mail dispatched (`Mail::fake()`).

**Newsletter (optional, ❓ ASK):** if kept, integrate with Mailchimp/Brevo API via a single-field form; otherwise remove from footer.

**Analytics (❓ ASK):** GA4 or Plausible, loaded only in production.

---

### Phase 7 — SEO, performance, accessibility

- `seo.meta` component: unique `<title>` and meta description per page, canonical, Open Graph + Twitter cards, per-page OG image (1200×630), keep Google verification meta.
- JSON-LD: `Organization` + `ProfessionalService` (name, address, phone, email, hours, sameAs socials), `BreadcrumbList` on inner pages, `FAQPage` on the FAQ.
- `sitemap.xml` generated from the route list; `robots.txt` pointing to it.
- Images: convert to WebP/AVIF at build time (or pre-convert with a script into `public/images/`), explicit `width`/`height`, `loading="lazy"` below the fold, `fetchpriority="high"` on the LCP poster.
- Fonts: self-hosted, `font-display: swap`, preload the heading font.
- HTTP: `.htaccess` in `public/` with gzip/brotli (if available), long cache headers for `/build/` and `/images/`, HTTPS + non-www→www (or reverse — match the current canonical `https://www.virtuacore.net`) redirects.
- Accessibility: skip link, landmarks, one `h1` per page, labelled form fields, focus rings, carousel controls with `aria-live="polite"`, menu `aria-expanded`.
- Run Lighthouse (mobile) on every page; fix until all scores ≥ 90. Save reports to `docs/lighthouse/`.

---

### Phase 8 — Deployment (FTP)

**❓ ASK the owner for:** FTP host/user/password/port (FTPS preferred), the server's PHP version (need 8.3+), whether the domain's document root can point to Laravel's `public/` folder, whether SSH or cPanel Terminal is available, and SMTP details.

**Server layout (preferred):**
```
/home/<user>/virtuacore/        ← whole Laravel app (app, bootstrap, config, vendor, storage, …)
/home/<user>/public_html/       ← domain docroot = contents of Laravel public/
```
If the docroot can be pointed at `/home/<user>/virtuacore/public`, do that instead and skip the split. If it must be `public_html`, copy `public/` contents there and edit `public_html/index.php` paths to `__DIR__.'/../virtuacore/...'`. Never expose `.env`, `vendor/` or `storage/` inside the docroot.

**GitHub Actions (`.github/workflows/deploy.yml`)** on push to `main`:
1. Checkout → setup PHP 8.3 → `composer install --no-dev --optimize-autoloader`.
2. Setup Node → `npm ci && npm run build`.
3. `php artisan test` must pass before deploying.
4. `php artisan route:cache && php artisan view:cache` (do NOT run `config:cache` in CI — it would bake CI env values).
5. Deploy with `SamKirkland/FTP-Deploy-Action` (uses a state file so only changed files upload), using repo secrets `FTP_SERVER`, `FTP_USERNAME`, `FTP_PASSWORD`, `FTP_SERVER_DIR`. Exclude `.git*`, `node_modules/`, `tests/`, `.env*`, `storage/logs/*`, `docs/`.
6. Two deploy targets if the split layout is used (app dir + public_html).

**First deploy checklist:**
- Create production `.env` on the server manually (`APP_ENV=production`, `APP_DEBUG=false`, new `APP_KEY`, mail settings).
- `storage/` and `bootstrap/cache/` writable (755/775).
- If SSH/terminal exists: `php artisan config:cache`, `php artisan optimize`. If not, skip config caching — the site doesn't need it.
- Keep media in `public/` so `storage:link` isn't required.
- **Cutover:** back up the full WordPress site (files + DB export) first, deploy V2 into a staging subdomain/subfolder, test everything, then switch the docroot. Keep the WP backup for at least 30 days.
- After go-live: submit the new sitemap in Google Search Console, test every old URL for its 301, test the contact form end-to-end, check SSL.

---

### Phase 9 — Handover

- `README.md`: local setup, how to edit content (which file holds what), how to swap the hero video, how deploy works, where secrets live.
- `docs/copy-changes.md`, `docs/media-credits.md`, `docs/design-system.md` final.
- Final Playwright screenshots next to the Phase 1 baseline in `docs/before-after/`.

**Optional Phase 10 (only if the owner wants it):** add a Filament admin panel with a MySQL/SQLite DB so services, testimonials and FAQs can be edited without code. Keep the content DTOs so the switch is a data-source change, not a rewrite.

---

## 5. Open questions for the owner (collect answers before Phase 3)

1. Correct phone number (the site links to `+1-800-456-478-23` but displays +1-307-333-8809).
2. Real figures for the stats band (support given, rating, money saved, awards) — or drop it?
3. Are the 5 testimonials real clients? Real photos available?
4. Social media URLs (X/Twitter, Facebook, LinkedIn, others).
5. Replacement wording for the "Mix dummy and organic" followers line.
6. Keep the newsletter? Which provider?
7. Business-hours time zone.
8. Prices/packages to show publicly, or "contact for quote"?
9. High-resolution / SVG version of the logo?
10. Hosting details: FTP (or FTPS/SFTP), PHP version, docroot control, SSH access, SMTP.
11. Analytics preference.
12. Work directly on `main`, or branches + PRs?
