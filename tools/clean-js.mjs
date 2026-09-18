/**
 * Removes previously built JS from assets/js before esbuild writes a new set.
 *
 * Dynamic-import chunks carry a content hash in their filename, so every rebuild that
 * changes hero3d.js or scroll.js leaves the previous chunk behind. Nothing references the
 * old file, so nothing breaks, and that is exactly why it would accumulate unnoticed and
 * ship to production forever.
 */
import { readdirSync, rmSync, existsSync } from 'node:fs';
import { join, dirname } from 'node:path';
import { fileURLToPath } from 'node:url';

const dir = join(dirname(fileURLToPath(import.meta.url)), '..', 'assets', 'js');
if (!existsSync(dir)) process.exit(0);

let n = 0;
for (const f of readdirSync(dir)) {
  if (f.endsWith('.js') || f.endsWith('.js.map')) {
    rmSync(join(dir, f));
    n++;
  }
}
console.log(`clean-js: removed ${n} file(s)`);
