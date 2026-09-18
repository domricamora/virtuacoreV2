# Session state — 2026-09-18

Where the port stands, what is deliberately unfinished, and what is blocked on someone else.

## Done

13 URLs live, all returning 200 with one `h1` and JSON-LD each. **86 tests, 727 assertions.**

| Layer | State |
|---|---|
| Laravel 13.32 scaffold, repo, CI workflow | done |
| Design system (Tailwind v4, Geist, Phosphor sprite) | ported |
| Content layer (8 sets, verified identical to source) | done |
| Nav, footer, SEO head, JSON-LD | done |
| All 11 page templates (13 URLs) | done |
| Per-page hero videos + posters | done |
| sitemap.xml, robots.txt, llms.txt | generated from content |
| Error pages 403/404/419/500 | done |
| WordPress 301 map + 410s | done |
| Lead form (validation, honeypot, rate limit, durable log) | done |

## Not done

- **Tracking** — blocked on the analytics decision. The privacy policy is generated from
  `config('site.features.analytics')` (currently `false`) and a test fails if the policy
  and the flag disagree, so turning tracking on requires updating both together.
- **Lead dashboard** — would be the only thing needing a database. Pages render without one.
- **Cutover** — see below.

## The deploy workflow is deliberately not armed

`.github/workflows/deploy.yml` is written and YAML-validated, but the `FTP_*` secrets are
**not set**, so a push does not deploy. To arm it, set `FTP_SERVER`, `FTP_USERNAME`,
`FTP_PASSWORD`, `FTP_APP_DIR`, `FTP_PUBLIC_DIR` via `gh secret set`.

The owner has since moved WordPress out of the web root into `/wp_old/`, so
virtuacore.net now serves a host parking page. That removes the risk the caution was
protecting against — there is no longer a live site at the root to break. Keep `/wp_old/`
until the 301s are proven live, because the old image URLs are still indexed.

## Known gap: author-page footage

`/for-authors/` and its six detail pages show the general laptop clip as a placeholder.
They should show writing or manuscripts — the author line is a different audience from the
staffing line, which is the whole argument of the page. Pexels' download pages sit behind a
Cloudflare challenge and the CDN filenames are not derivable from a video ID, so no writing
clip could be sourced automatically.

**To fix:** drop a 1080p writing clip into `resources/source-media/video/` and it can be
encoded and wired in one step. See `docs/media-credits.md`.

## Blocked on the owner

1. **SMTP credentials.** Leads are validated and written durably to their own log channel,
   but nothing emails anyone. Until SMTP exists, `storage/logs/leads.log` is the inbox.
2. **Analytics** — GA4, Plausible, or none.

Non-blocking: LinkedIn and X URLs; and whether the "mixed organic and seeded" follower
copy on `/for-authors/social-media-kits` stays — it advertises non-organic follower growth,
which is against Meta's terms.

## The rule that governs everything here

Nothing on this site claims something that is not true. Stats, testimonials and the logo
wall stay behind flags that are off. Pricing publishes no rates. The schema emits no
`AggregateRating` or `Review`. `/for-authors/` states plainly what it does not guarantee.
Tests enforce each of these, because every one of them was a real defect on the site this
replaces.
