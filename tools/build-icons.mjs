/**
 * Builds assets/img/icons.svg, a single sprite of the icons the site actually uses.
 *
 * Icons come from @phosphor-icons/core (MIT). They are never hand-drawn: a hand-rolled
 * path drifts from the family's stroke weight and optical sizing, and there is no reason
 * to redraw work that ships as a dependency.
 *
 * The sprite is referenced with <use href="icons.svg#i-name">, so the browser fetches one
 * cacheable file regardless of how many icons a page renders.
 *
 * To add an icon: put its Phosphor slug in ICONS below and run `npm run icons`.
 */
import { readFileSync, writeFileSync, mkdirSync, existsSync } from 'node:fs';
import { dirname, join } from 'node:path';
import { fileURLToPath } from 'node:url';

const ROOT = join(dirname(fileURLToPath(import.meta.url)), '..');
const SRC = join(ROOT, 'node_modules', '@phosphor-icons', 'core', 'assets');
const OUT = join(ROOT, 'assets', 'img', 'icons.svg');

/** [phosphorSlug, weight] - weight defaults to 'regular'. */
const ICONS = [
  // service marks
  'target', 'calendar-check', 'headset', 'kanban', 'megaphone-simple',
  // author service marks
  'film-slate', 'film-reel', 'star', 'trophy', 'instagram-logo', 'globe',
  // process + trust
  'clipboard-text', 'users-three', 'rocket-launch', 'chart-line-up',
  'shield-check', 'clock', 'seal-check', 'handshake',
  // interface
  'arrow-right', 'arrow-up-right', 'arrow-up', 'arrow-left',
  'caret-down', 'caret-right', 'check', 'check-circle', 'x', 'x-circle',
  'plus', 'minus', 'list', 'quotes', 'warning-circle', 'info',
  'magnifying-glass', 'sun', 'moon',
  // contact
  'envelope-simple', 'phone', 'map-pin',
  // social
  'linkedin-logo', 'facebook-logo', 'x-logo',
  // dashboard
  'eye', 'cursor-click', 'device-mobile', 'desktop', 'device-tablet',
  'globe-hemisphere-west', 'sign-out', 'robot', 'link-simple', 'calendar-blank',
];

const symbols = [];
const missing = [];

for (const entry of ICONS) {
  const [slug, weight = 'regular'] = Array.isArray(entry) ? entry : [entry];
  const file = join(SRC, weight, `${slug}.svg`);

  if (!existsSync(file)) {
    missing.push(`${weight}/${slug}`);
    continue;
  }

  const raw = readFileSync(file, 'utf8');
  const viewBox = raw.match(/viewBox="([^"]+)"/)?.[1] ?? '0 0 256 256';
  const inner = raw.replace(/^[\s\S]*?<svg[^>]*>/, '').replace(/<\/svg>\s*$/, '').trim();

  symbols.push(`<symbol id="i-${slug}" viewBox="${viewBox}" fill="currentColor">${inner}</symbol>`);
}

if (missing.length) {
  // Fail loudly. A silently missing icon renders as an empty box that is easy to miss
  // in a section you were not looking at.
  console.error(`build-icons: ${missing.length} icon(s) not found in @phosphor-icons/core:`);
  for (const m of missing) console.error(`  - ${m}`);
  process.exit(1);
}

mkdirSync(dirname(OUT), { recursive: true });
writeFileSync(
  OUT,
  `<svg xmlns="http://www.w3.org/2000/svg" style="display:none">${symbols.join('')}</svg>\n`,
  'utf8'
);

const kb = (Buffer.byteLength(readFileSync(OUT)) / 1024).toFixed(1);
console.log(`build-icons: ${symbols.length} icons -> assets/img/icons.svg (${kb} KB)`);
