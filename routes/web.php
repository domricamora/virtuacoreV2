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

Route::view('/services', 'pages.stub', ['heading' => 'Services'])->name('services');
Route::view('/how-it-works', 'pages.stub', ['heading' => 'How it works'])->name('how-it-works');
Route::view('/pricing', 'pages.stub', ['heading' => 'Pricing'])->name('pricing');
Route::view('/for-authors', 'pages.stub', ['heading' => 'For authors'])->name('for-authors');
Route::view('/about', 'pages.stub', ['heading' => 'About'])->name('about');
Route::view('/contact', 'pages.stub', ['heading' => 'Contact'])->name('contact');
Route::view('/privacy-policy', 'pages.stub', ['heading' => 'Privacy policy'])->name('privacy');
Route::view('/terms', 'pages.stub', ['heading' => 'Terms'])->name('terms');

/**
 * Detail pages are driven entirely by the content layer: one array entry creates the page.
 * The slug is constrained to the keys that actually exist rather than matched loosely and
 * checked inside, so an unknown slug is a 404 from routing and never reaches a view.
 */
Route::get('/services/{slug}', fn (string $slug) => view('pages.stub', [
    'heading' => Content::service($slug)['h1'],
]))->whereIn('slug', array_keys(Content::services()))->name('services.show');

Route::get('/for-authors/{slug}', fn (string $slug) => view('pages.stub', [
    'heading' => Content::authorService($slug)['h1'],
]))->whereIn('slug', array_keys(Content::authorServices()))->name('authors.show');
