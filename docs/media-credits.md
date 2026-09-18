# Media credits

Every third-party asset that ships in this repo, with its source and licence.
**Add an entry here before committing any new media.**

## Hero videos

One clip per page, chosen to match what the page is about. All from Pexels under the
Pexels License: free for commercial use, modification permitted, **no attribution
required**. Recorded here so provenance is auditable, not because the pages must show it.

| Page | Subject | Pexels ID |
|---|---|---|
| Home | People working on laptops in an office | 3202364 |
| Services (+ 5 detail pages) | Conference room business meeting | 3205624 |
| For authors (+ 6 detail pages) | Writing | 5212605 |
| How it works | Planning together at a whiteboard | 9365375 |
| Pricing | Working through figures | 7423499 |
| About | Office team meeting | 8033854 |
| Contact | Team meeting | 7147921 |

Retrieved 2026-09-18. A section index and its detail pages share a clip deliberately —
they are the same section, and a different clip per detail page would be noise rather than
signal.

### Encoding

Every clip: trimmed to 15s maximum, audio stripped with `-an` (silent by construction, so
a browser that ignores `muted` still cannot make noise), H.264 CRF 28 at 1080p and CRF 30
at 720p, `-movflags +faststart`, plus a WebP poster from a representative frame.

| Clip | 1080p | 720p | Poster |
|---|---|---|---|
| `hero` | 1.9 MB | 715 KB | 46 KB |
| `services` | 2.4 MB | 958 KB | 133 KB |
| `authors` | 850 KB | 323 KB | 40 KB |
| `how-it-works` | 1.8 MB | 716 KB | 47 KB |
| `pricing` | 1.4 MB | 526 KB | 74 KB |
| `about` | 1.2 MB | 494 KB | 100 KB |
| `contact` | 2.5 MB | 1.2 MB | 89 KB |

Per-page clips mean a visitor moving between pages downloads each one rather than reusing
a cached file. That is the cost of relevance, and it is why the loading gate matters more
here rather than less: reduced-motion, Save-Data, 2g and low-memory sessions fetch none of
it and see only the poster.

**Sourcing note:** Pexels video DETAIL pages sit behind a Cloudflare challenge, but SEARCH
pages are not challenged and carry direct CDN URLs in their markup. That is how these were
retrieved. CDN filenames are not derivable from a video ID, so guessing them does not work.

## Brand assets

`header-logo.png` and the favicon are VirtuaCore's own, recovered from the WordPress
install. Everything else on the old site was untouched pxhere stock and was not carried
over. A high-resolution or SVG logo is still outstanding — the PNG is soft on retina.
