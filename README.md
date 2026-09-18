# VirtuaCore

Marketing site for **virtuacore.net** — remote staffing for business owners, plus a
publishing and digital-media line for authors.

Laravel 13 + Tailwind v4. **No database is required to render any page.**

---

## Getting it running

Local PHP is 7.4 on PATH, which Laravel will not run on. Every shell needs this first:

```powershell
$env:PATH = "C:\wamp64\bin\php\php8.3.14;$env:PATH"
php --version   # must say 8.3.14
```

Then:

```powershell
composer install
npm install
copy .env.example .env
php artisan key:generate
npm run build
php artisan serve      # http://127.0.0.1:8000
```

Day to day: `npm run dev` for hot reloading, `php artisan test` before committing.

---

## Where to change things

**Content is data, never markup.** Views render it; they do not restate it.

| What | Where |
|---|---|
| Phone, email, address, hours, socials, feature flags | `config/site.php` |
| Services, author services, process, FAQs, pricing, homepage copy | `resources/content/*.php` |
| Page templates | `resources/views/pages/` |
| Shared chrome (nav, footer, head, forms) | `resources/views/components/` |
| Design tokens — palette, type scale, both themes | `resources/css/app.css` |
| Section styling | `resources/css/components.css` |

**Adding a service is one array entry** in `resources/content/services.php`. That creates
its page, nav entry, footer link, sitemap row, `llms.txt` section, lead-form option and
schema. There is no per-service template to edit.

### Swapping a hero video

Each page has its own clip. Drop a 1080p source in, then encode to
`public/video/<name>-1080.mp4`, `-720.mp4` and `public/images/<name>-poster.webp`, and set
`<x-hero-video clip="<name>" />` on the page. Record the source and licence in
`docs/media-credits.md` before committing — that file is the audit trail.

---

## The rule that governs this codebase

**Nothing on this site claims something that is not true.**

The WordPress build it replaces rendered every stat counter as a literal `0` and carried
five testimonials that read as placeholder names over stock photography. A zeroed counter
says "no clients"; an invented review is a lie a prospect can check.

So: stats, testimonials and the logo wall sit behind flags in `config/site.php` that are
**off**. `/pricing/` publishes no rates — every model's `rate` is `null` and the card says
"Quoted after scoping". The schema emits no `AggregateRating` or `Review`. `/for-authors/`
states plainly what it does not guarantee.

Tests enforce each of these. Turn a flag on only when real, attributable data exists.

---

## Deployment

Shared LiteSpeed hosting, PHP 8.3, deployed over FTP.

**The FTP account is not chrooted** — it lands in the home directory, and the site is in
`public_html/`. Check this before any upload; it has changed once already, and uploads went
to the wrong place for an entire debugging session as a result.

Server layout:

```
public_html/
  index.php      <- bootstraps ./vc_app/ AND calls usePublicPath(__DIR__)
  .htaccess      <- canonical host, security headers, front controller
  build/ images/ video/ favicon.ico
  vc_app/        <- the application, not web-reachable
```

Two things about that layout are load-bearing, and both have caused a production 500:

1. **`index.php` must call `$app->usePublicPath(__DIR__)`.** `public_path()` otherwise
   resolves to `basePath/public`, which does not exist here, and Vite's manifest lookup
   fails on every page. `tools/prepare-production-index.php` applies both this and the
   bootstrap path rewrite.
2. **Never deploy `bootstrap/cache/*.php`.** Those are generated per environment. A
   `services.php` built on a dev machine makes Laravel ignore `.env` entirely, which
   presents as a 500 with no usable error and no log.

**FTPS note:** this server aborts FTPS data transfers with `451 Error during read from
data connection` *after* the bytes are sent, producing silent zero-byte files that
directory listings report as present. Use `curl --ftp-ssl-control` — credentials stay
encrypted, file bytes go plain — or upload a zip and extract server-side. Verify by
**size**, not presence.

GitHub Actions (`.github/workflows/deploy.yml`) does all of this automatically once the
`FTP_*` secrets are set.

---

## Mail

Enquiries go to the addresses in `config/site.php` → `lead_recipients`, through the
server's own MTA (`MAIL_MAILER=sendmail`), so no external SMTP account is needed.

The order in `LeadController` is deliberate: **the lead is written to its log channel
before any send is attempted.** Mail is the part most likely to fail on shared hosting, and
the failure mode has to be "nobody was emailed", never "the enquiry is gone". Both the team
notification and the enquirer's acknowledgement are sent in separate try blocks so one
cannot suppress the other. Every enquiry is also in `storage/logs/leads-*.log`.

---

## Testing

```powershell
php artisan test          # 131 tests
vendor\bin\pint --test    # style
npm run build             # must succeed before deploying
```

Many of the tests exist for failures that are invisible rather than loud — a page that
renders with an empty body, a sitemap assertion that silently checks one URL instead of
twenty, share images that are all the same file, a form that reports a network error
because the client and server disagree about content negotiation. If one looks
over-specific, read its comment before deleting it.

---

## Further reading

- `CLAUDE.md` — architecture and the constraints that are easy to lose
- `docs/SESSION.md` — current state, what is deliberately unfinished
- `docs/media-credits.md` — every third-party asset, with licence
- `docs/PLAN.md` — the original specification
