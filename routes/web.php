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
Route::view('/how-it-works', 'pages.stub', ['heading' => 'How it works'])->name('how-it-works');
Route::view('/pricing', 'pages.stub', ['heading' => 'Pricing'])->name('pricing');
Route::view('/for-authors', 'pages.authors.index')->name('for-authors');
Route::view('/about', 'pages.stub', ['heading' => 'About'])->name('about');
Route::view('/contact', 'pages.stub', ['heading' => 'Contact'])->name('contact');
Route::view('/privacy-policy', 'pages.stub', ['heading' => 'Privacy policy'])->name('privacy');
Route::view('/terms', 'pages.stub', ['heading' => 'Terms'])->name('terms');

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

Route::post('/lead', [\App\Http\Controllers\LeadController::class, 'store'])
    ->middleware('throttle:8,1')
    ->name('lead.store');
