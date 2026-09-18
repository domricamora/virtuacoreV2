<?php

declare(strict_types=1);

/**
 * Rewrite public/index.php for the deployed layout.
 *
 * The FTP account is chrooted to the web root, so the application cannot live above it:
 * public/ contents go to the web root and the app to ./vc_app/ beside them. Two things
 * follow from that, and both have bitten this deploy already:
 *
 *   1. index.php's "one level up" bootstrap paths are wrong; they must point at vc_app/.
 *   2. public_path() resolves to basePath/public, which does not exist in this layout, so
 *      Vite's manifest lookup fails and every page 500s. usePublicPath() fixes it.
 *
 * Run at deploy time rather than committed, so local development and `artisan serve`
 * keep working against the normal layout.
 *
 * Usage: php tools/prepare-production-index.php [path/to/index.php]
 */

$file = $argv[1] ?? __DIR__.'/../public/index.php';

if (! is_file($file)) {
    fwrite(STDERR, "not found: {$file}\n");
    exit(1);
}

$src = file_get_contents($file);
$out = str_replace("__DIR__.'/../", "__DIR__.'/vc_app/", $src);

$anchor = "\$app = require_once __DIR__.'/vc_app/bootstrap/app.php';";

if (! str_contains($out, $anchor)) {
    fwrite(STDERR, "bootstrap line not found; index.php layout changed\n");
    exit(1);
}

if (! str_contains($out, 'usePublicPath')) {
    $out = str_replace(
        $anchor,
        $anchor."\n\n"
        ."// The app sits BESIDE the web root, not above it, so its public path is this\n"
        ."// directory rather than basePath/public. Vite's manifest lookup depends on it.\n"
        ."\$app->usePublicPath(__DIR__);",
        $out
    );
}

if (str_contains($out, "__DIR__.'/../")) {
    fwrite(STDERR, "stale '../' paths remain after rewrite\n");
    exit(1);
}

file_put_contents($file, $out);
echo "index.php prepared for the split layout\n";
exit(0);
