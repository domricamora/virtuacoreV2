<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

/**
 * Routes are named and linked with route()/url(), never hardcoded absolute paths. The
 * source build needed an .htaccess %{ENV:VCBASE} trick because a hardcoded mount point is
 * correct in exactly one place and silently breaks in the other. Laravel removes the need
 * for that trick; it must not be reintroduced in another form.
 */
Route::view('/', 'home')->name('home');

// Placeholders so route() resolves while the pages are ported. Each becomes a real view
// in the page-port phase.
Route::view('/services', 'home')->name('services');
Route::view('/contact', 'home')->name('contact');
