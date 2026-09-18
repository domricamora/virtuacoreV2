/**
 * Copies the Geist variable font files the site actually serves into assets/fonts.
 *
 * Only the latin subsets are shipped. The cyrillic and latin-ext files roughly triple the
 * font payload for glyphs this site never renders, and a variable font is already one
 * file for every weight, so there is nothing else to trim.
 *
 * Fonts are self-hosted rather than linked from a font CDN: a third-party font host is an
 * extra DNS lookup and TLS handshake on the critical path, and it hands a third party a
 * log of every visitor.
 */
import { copyFileSync, mkdirSync, statSync } from 'node:fs';
import { dirname, join } from 'node:path';
import { fileURLToPath } from 'node:url';

const ROOT = join(dirname(fileURLToPath(import.meta.url)), '..');
const OUT = join(ROOT, 'assets', 'fonts');

const FILES = [
  ['@fontsource-variable/geist', 'geist-latin-wght-normal.woff2', 'geist-latin.woff2'],
  ['@fontsource-variable/geist', 'geist-latin-wght-italic.woff2', 'geist-latin-italic.woff2'],
  ['@fontsource-variable/geist-mono', 'geist-mono-latin-wght-normal.woff2', 'geist-mono-latin.woff2'],
];

mkdirSync(OUT, { recursive: true });

let total = 0;
for (const [pkg, from, to] of FILES) {
  const src = join(ROOT, 'node_modules', pkg, 'files', from);
  const dst = join(OUT, to);
  copyFileSync(src, dst);
  const kb = statSync(dst).size / 1024;
  total += kb;
  console.log(`build-fonts: ${to} (${kb.toFixed(1)} KB)`);
}
console.log(`build-fonts: ${FILES.length} files, ${total.toFixed(1)} KB total`);
