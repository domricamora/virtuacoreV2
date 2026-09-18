<?php

declare(strict_types=1);

use App\Support\Content;
use Illuminate\Support\Facades\Route;

/**
 * Routes are named and linked with route(), never hardcoded paths. The source build needed
 * an .htaccess %{ENV:VCBASE} trick because a hardcoded mount point is correct in exactly
 * one place and silently breaks in the other. Laravel removes the need for that trick, and
 * it must not be reintroduced in another form.
 *
 * Pages still being ported render the stub view. The ROUTES are real and named now, so the
 * chrome can link to all of them and nothing needs rewiring as each page lands.
 */

Route::view('/', 'home')->name('home');

Route::view('/services', 'pages.services.index')->name('services');
Route::view('/how-it-works', 'pages.how-it-works')->name('how-it-works');
Route::view('/pricing', 'pages.pricing')->name('pricing');
Route::view('/for-authors', 'pages.authors.index')->name('for-authors');
Route::view('/about', 'pages.about')->name('about');
Route::view('/contact', 'pages.contact')->name('contact');
Route::view('/privacy-policy', 'pages.privacy')->name('privacy');
Route::view('/terms', 'pages.terms')->name('terms');

/**
 * Detail pages are driven entirely by the content layer: one array entry creates the page.
 * The slug is constrained to the keys that actually exist rather than matched loosely and
 * checked inside, so an unknown slug is a 404 from routing and never reaches a view.
 */
Route::get('/services/{slug}', fn (string $slug) => view('pages.services.show', [
    'slug'    => $slug,
    'svc'     => Content::service($slug),
    // Three siblings, excluding the one being viewed.
    'related' => array_slice(array_diff_key(Content::services(), [$slug => true]), 0, 3, true),
]))->whereIn('slug', array_keys(Content::services()))->name('services.show');

Route::get('/for-authors/{slug}', fn (string $slug) => view('pages.authors.show', [
    'slug'    => $slug,
    'svc'     => Content::authorService($slug),
    'related' => array_slice(array_diff_key(Content::authorServices(), [$slug => true]), 0, 3, true),
]))->whereIn('slug', array_keys(Content::authorServices()))->name('authors.show');

/**
 * Machine-facing endpoints, all generated from the content layer so a new service appears
 * in every one of them without a hand edit.
 */
Route::get('/sitemap.xml', [\App\Http\Controllers\SeoController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt',  [\App\Http\Controllers\SeoController::class, 'robots'])->name('robots');
Route::get('/llms.txt',    [\App\Http\Controllers\SeoController::class, 'llms'])->name('llms');

Route::post('/lead', [\App\Http\Controllers\LeadController::class, 'store'])
    ->middleware('throttle:8,1')
    ->name('lead.store');

/**
 * 301s from the WordPress site.
 *
 * These URLs are indexed today and carry ranking with them; letting them 404 throws that
 * away. Laravel matches with and without the trailing slash, so each entry covers both
 * forms of the old URL.
 *
 * Everything here is PERMANENT on purpose. A 302 tells a crawler to keep the old URL,
 * which is the opposite of what a migration needs.
 */
$wordpressRedirects = [
    // Real pages that had real traffic.
    'about-us-2'   => 'about',
    'about-us'     => 'about',
    'our-services' => 'services',
    'our-service'  => 'services',
    'contacts'     => 'contact',

    // Elementor theme demo pages that were published by accident and indexed anyway.
    // They point at the nearest genuine equivalent rather than all dumping on the
    // homepage, which reads as a soft 404 to a crawler.
    'our-services-3'     => 'services',
    'our-services-4'     => 'services',
    'services-with-icon' => 'services',
    'our-team'           => 'about',
    'our-team-2'         => 'about',
    'our-team-3'         => 'about',
    'our-team-2-2'       => 'about',
    'portfolio'          => 'services',
    'portfolio-grid'     => 'services',
    'portfolio-masonry'  => 'services',
    'portfolio-carousel' => 'services',
    'faqs'               => 'how-it-works',
    'home-2'             => 'home',
    'home-2-2'           => 'home',
    'coming-soon'        => 'home',
    'sample-page'        => 'home',
    'blog-2'             => 'home',

    // WooCommerce pages from a shop that never opened.
    'shop'       => 'home',
    'shop-2'     => 'home',
    'cart-2'     => 'home',
    'checkout-2' => 'home',
    'my-account-2' => 'home',
];

// route() must NOT be called here. Route files are executed to REGISTER routes, and the
// name lookup table is not built until that pass completes, so resolving a name at
// registration time throws RouteNotFoundException. A closure defers it to request time.
foreach ($wordpressRedirects as $old => $routeName) {
    $to = fn () => redirect()->route($routeName, [], 301);
    Route::get('/'.$old, $to);
    Route::get('/'.$old.'/', $to);
}

// WordPress sitemaps: point crawlers at the real one rather than 404ing a file they
// already have on their schedule.
foreach (['sitemap_index.xml', 'wp-sitemap.xml', 'post-sitemap.xml', 'page-sitemap.xml'] as $old) {
    Route::get('/'.$old, fn () => redirect()->route('sitemap', [], 301));
}

/**
 * WordPress infrastructure paths. These attract constant bot traffic that would otherwise
 * fill the 404 log and hide real broken links in the noise. 410 Gone is the honest answer:
 * it says the resource is permanently removed, which stops well-behaved crawlers retrying,
 * where a 301 to the homepage would be a soft 404.
 */
Route::any('/wp-admin/{any?}', fn () => abort(410))->where('any', '.*');
Route::any('/wp-login.php', fn () => abort(410));
Route::any('/xmlrpc.php', fn () => abort(410));
Route::any('/wp-cron.php', fn () => abort(410));
Route::get('/feed/{any?}', fn () => abort(410))->where('any', '.*');
Route::get('/comments/feed/{any?}', fn () => abort(410))->where('any', '.*');
