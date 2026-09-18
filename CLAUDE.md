# VirtuaCore V2

Marketing site for **virtuacore.net**: remote staffing for business owners, plus a
secondary publishing and digital-media line for authors.

This is a **port**, not a greenfield build. It reimplements `c:\wamp64\www\virtuacore`
(repo `domricamora/virtuacore`, live at `virtuacore.net/latest/`) on Laravel + Blade.
That build is vanilla PHP and is the source of truth for **design, copy and content**.
Read its `CLAUDE.md` before changing anything visual — it records why decisions were made,
and several of them are load-bearing bug fixes rather than preferences.

---

## Stack

| | |
|---|---|
| Laravel | 13.32.0 |
| PHP | 8.3 — **local PATH defaults to 7.4.33, which fails Composer's platform check** |
| Tailwind | v4 via `@tailwindcss/vite` (NOT the PostCSS plugin) |
| JS | Alpine.js; raw WebGL2 hero ported from the source build |
| Fonts | Self-hosted Geist + Geist Mono, latin subset, from `@fontsource-variable` |
| Icons | `@phosphor-icons/core`, compiled to one `<use>` sprite |
| Server | LiteSpeed, PHP 8.3.33, shared hosting, FTP deploy |

**Open every shell that runs php/composer/artisan with:**

```powershell
$env:PATH = "C:\wamp64\bin\php\php8.3.14;$env:PATH"
php --version   # must echo 8.3.14
```

## Commands

```powershell
php artisan serve        # http://127.0.0.1:8000
php artisan test         # Pest
vendor\bin\pint --test   # style
npm run dev / npm run build
```

---

## Rules

### 1. Nothing on this site claims something that is not true

Inherited from the source build, and non-negotiable. The WordPress site it replaces
rendered every stat counter as a literal `0` and carried five testimonials that read as
placeholder names over stock headshots. A zeroed counter says "no clients"; an invented
review is a lie a prospect can check.

Stats, testimonials and the logo wall stay **behind config flags, all off**, until real
data exists. The same rule bans `AggregateRating`/`Review` JSON-LD, keeps `/pricing/` free
of unconfirmed rates, and requires `/for-authors/` to state what the services do **not**
guarantee.

### 2. Content lives in `config/site.php` + `resources/content/`

Never hardcode copy in a Blade view. Adding a service is one array entry that produces its
page, nav entry, footer link, sitemap row, `llms.txt` section, lead-form option and schema.

### 3. Nothing may depend on where the app is mounted

Laravel's `route()` / `url()` helpers handle this — use them, never hardcoded absolute
paths. The source build needed an `.htaccess` `%{ENV:VCBASE}` trick for exactly this
reason; the port must not reintroduce a mount-point assumption in its place.

---

## Bugs the source build already paid for — do not reintroduce

- **`@font-face` URLs resolve against the OUTPUT css file**, not the source. Written as
  `../assets/fonts/` they resolved one level too deep, every face 404'd, and the site
  rendered in the fallback stack while the preload still fetched the real file. Nothing
  looked broken, which is why it survived several reviews.
- **The WebGL hero's `gl_PointSize` is computed from canvas height**, not a constant. A
  fixed value overlapped the dots into one blown-out disc — invisible on dark, obvious on
  light.
- **The hero scene is selected per theme, not flipped.** Dark uses additive blending, light
  uses normal compositing, each with its own poster. Switching theme requires a remount.
- **The reveal animation's hidden state is scoped to `.js`**, set before first paint.
  Content must never need JavaScript to become visible.
- **`window.addEventListener('scroll')` is banned.** Use IntersectionObserver, ScrollTrigger
  or CSS scroll-driven animations.

---

## Deployment

Shared hosting, FTP. **The FTP account is chrooted to the web root** — `/../` == `/` — so
the app source cannot live outside the docroot. Target layout:

```
/            Laravel public/ contents
/vc_app/     Laravel app source, hardened .htaccess "require all denied"
/wp_old/ /latest/ /staging/   pre-existing, never modified
```

FTPS works **only with cert verification relaxed** (the cert is the host's, not
`ftp.virtuacore.net`). Never fall back to plain FTP — it sends the password in cleartext.

Secrets live in `.env` and GitHub Actions secrets only. Never commit them.

---

## Testing

The source build's harness is worth porting, not reinventing:

- Render every page with `E_ALL` promoted to failure — `php -l` cannot see a template bug.
- Browser checks via Playwright: no horizontal scroll, no CTA wrapping to two lines, nav
  under 80px, exactly one `h1` per page, at most one section eyebrow per three sections,
  and zero em-dashes in rendered text. Dark desktop, dark mobile, light desktop.
- Chrome's `--headless --window-size` does **not** set the CSS viewport. Use Playwright's
  viewport or a 390px window lays out near 700px and looks like a mobile overflow bug.
