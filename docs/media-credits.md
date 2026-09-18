# Media credits

Every third-party asset that ships in this repo, with its source and licence.
**Add an entry here before committing any new media.**

## Hero videos

One clip per page, chosen to match what the page is about. All from Pexels under the
Pexels License: free for commercial use, modification permitted, **no attribution
required**. Recorded here so provenance is auditable, not because the pages must display it.

| Page | Clip | Pexels ID | Author |
|---|---|---|---|
| Home | People Working On Their Laptops In An Office | [3202364](https://www.pexels.com/video/people-working-on-their-laptops-in-an-office-3202364/) | cottonbro studio |
| Services (+ detail) | People In A Conference Room For A Business Meeting | [3205624](https://www.pexels.com/video/people-in-a-conference-room-for-a-business-meeting-3205624/) | — |
| How it works | Men Planning Together While Using Whiteboard | [9365375](https://www.pexels.com/video/men-planning-together-while-using-whiteboard-9365375/) | — |
| Pricing | *(reuses the how-it-works clip)* | 9365375 | — |
| About | Office Team Having A Meeting | [8033854](https://www.pexels.com/video/office-team-having-a-meeting-8033854/) | — |
| Contact | Team Meeting | [7147921](https://www.pexels.com/video/team-meeting-7147921/) | — |
| For authors (+ detail) | **PLACEHOLDER — reuses the home clip** | 3202364 | cottonbro studio |

Retrieved 2026-09-18.

### Outstanding: the author pages need their own footage

`/for-authors/` and its six detail pages currently show the general laptop clip. They
should show writing, manuscripts or books — the author line is a different audience from
the staffing line, and that is the whole argument of the page.

No writing clip could be sourced automatically: Pexels' download pages sit behind a
Cloudflare challenge, and the CDN filenames are not derivable from the video ID. The
general clip is used as the least-wrong stand-in, because a conference room behind author
copy would actively misrepresent who the page is for.

**To fix:** download a 1080p writing/manuscript clip from Pexels, drop it in
`resources/source-media/video/`, and it can be encoded and wired in one step.

### Encoding

Every clip: trimmed to 15s maximum, audio stripped with `-an` (silent by construction, so
a browser ignoring `muted` still cannot make noise), H.264 CRF 28 at 1080p and CRF 30 at
720p, `-movflags +faststart`, plus a WebP poster from a representative frame.

| File | 1080p | 720p | Poster |
|---|---|---|---|
| `hero-*` | 1.9 MB | 715 KB | 46 KB |
| `services-*` | 2.4 MB | 958 KB | 133 KB |
| `how-it-works-*` | 1.8 MB | 716 KB | 47 KB |
| `about-*` | 1.2 MB | 494 KB | 100 KB |
| `contact-*` | 2.5 MB | 1.2 MB | 89 KB |

Per-page clips mean a visitor moving between pages downloads each one rather than reusing
a cached file. That is the cost of relevance, and it is why the loading gate matters more
now, not less: reduced-motion, Save-Data, 2g and low-memory sessions still fetch nothing.

## Brand assets

`header-logo.png` and the favicon are VirtuaCore's own, recovered from the WordPress
install. Everything else on the old site was untouched pxhere stock and was not carried
over. A high-resolution or SVG logo is still outstanding — the PNG is soft on retina.
