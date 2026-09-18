# Session state — 2026-09-19

**The site is live at https://www.virtuacore.net and working.**

## Done

All 13 URLs return 200. **131 tests, 889 assertions.**

| Layer | State |
|---|---|
| Laravel 13.32, design system, content layer | done |
| 13 pages, nav, footer, SEO head, JSON-LD | done |
| Per-page hero videos (7 clips) + posters | done |
| sitemap.xml, robots.txt, llms.txt | generated from content |
| Error pages 403/404/419/500 | done |
| WordPress 301 map + 410s for infrastructure paths | done, verified live |
| Lead form: validation, honeypot, rate limit, durable log | done |
| Email to admin@ + info@, plus acknowledgement to enquirer | done |
| Security headers, canonical host redirect | done, verified live |
| Deployed to production over FTP | done |

## Not done

- **Tracking / analytics.** Still needs a decision: GA4, Plausible, or none. The privacy
  policy is generated from `config('site.features.analytics')` (currently `false`) and a
  test fails if the policy and the flag disagree, so both move together.
- **Lead dashboard.** Would be the only thing needing a database. Enquiries are currently
  read from `storage/logs/leads-*.log` and from email.
- **Lighthouse audit.** Never run against production.

## Needs the owner

1. **Confirm mail actually arrives.** Delivery through the server's MTA is the one part
   that cannot be verified from here. Submit the form once and check both addresses.
2. **Rotate the FTP password.** It was shared in chat and this repo is public.
3. **GitHub Actions is blocked by billing** — every run fails with "your account is locked
   due to a billing issue". Not a code problem. Until it is resolved, deploys are manual
   over FTP. The workflow itself is ready and its secrets are still unset.
4. LinkedIn and X URLs (Facebook and Instagram are wired).
5. Whether "mixed organic and seeded" on `/for-authors/social-media-kits` stays — it
   advertises non-organic follower growth, which is against Meta's terms.

## Traps this deployment already fell into

Recorded because each cost real time and none announced itself.

- **The FTP account is not chrooted.** It lands in the home directory; the site is in
  `public_html/`. This changed mid-session, and an entire debugging pass was applied to a
  stray `/home/sodncqbe/vc_app/` while the live app sat elsewhere. **Check the path before
  every upload.**
- **FTPS silently truncates.** This server aborts data transfers with `451` *after* the
  bytes are sent, leaving zero-byte files that listings report as present. Use
  `curl --ftp-ssl-control`, and verify by **size**, never presence.
- **Never deploy `bootstrap/cache/*.php`.** A `services.php` generated on a dev machine
  makes Laravel ignore `.env` entirely — a 500 with no log and no usable error.
- **`index.php` needs `usePublicPath(__DIR__)`.** Without it `public_path()` resolves to
  `basePath/public`, which does not exist in the split layout, and Vite's manifest lookup
  fails on every page.
- **`expectsJson()` recognises only `XMLHttpRequest` or `Accept: application/json`.** The
  form sent `X-Requested-With: 'fetch'`, got a 302 to HTML, and told every visitor their
  connection had failed.

## The rule that governs this codebase

Nothing on this site claims something that is not true. Stats, testimonials and the logo
wall stay behind flags that are off. Pricing publishes no rates. The schema emits no
`AggregateRating` or `Review`. `/for-authors/` states what it does not guarantee. Tests
enforce each one, because every one was a real defect on the site this replaces.
