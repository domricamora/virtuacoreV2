# Media credits

Every third-party asset that ships in this repo, with its source and licence.
**Add an entry here before committing any new media.**

## Hero video

| | |
|---|---|
| Title | People Working On Their Laptops In An Office |
| Author | cottonbro studio |
| Source | https://www.pexels.com/video/people-working-on-their-laptops-in-an-office-3202364/ |
| File ID | `3202364` (direct: `videos.pexels.com/video-files/3202364/3202364-hd_1920_1080_25fps.mp4`) |
| Licence | Pexels License — free for commercial use, no attribution required, modification permitted. Redistribution of the unmodified file as stock is not. |
| Retrieved | 2026-09-18 |

Attribution is **not required** by the licence. It is recorded here so the provenance of
the file is auditable, not because the page must display it.

### Delivered variants

Source was 1920x1080, 25fps, 15.16s, 5.3 MB, single continuous shot (no scene cuts, which
is what makes it usable as a loop).

| File | Purpose | Size |
|---|---|---|
| `public/video/hero-1080.mp4` | primary, H.264 CRF 28, faststart, silent | 1.9 MB |
| `public/video/hero-720.mp4` | small screens, H.264 CRF 30, silent | 715 KB |
| `public/images/hero-poster.webp` | LCP image and reduced-motion fallback | 46 KB |
| `public/images/hero-poster.jpg` | fallback for the fallback | 82 KB |

Audio is stripped (`-an`) — the track is silent by construction, not merely muted, so a
browser that ignores `muted` still cannot make noise.

The loop cut is a hard cut, not a crossfade. Behind the hero's dark overlay, at the scale
the video plays, it is not perceptible; a crossfade would cost bitrate for no visible gain.

## Brand assets

`header-logo.png` and the favicon are VirtuaCore's own, recovered from the WordPress
install. Everything else on the old site was untouched pxhere stock and was not carried
over. A high-resolution or SVG logo is still outstanding — the PNG is soft on retina.
