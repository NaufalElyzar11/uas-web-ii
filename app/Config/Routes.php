<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home'); // Default controller sekarang Home
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false); // Selalu set false untuk keamanan

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// Rute Publik: Bisa diakses siapa saja tanpa login
$routes->get('/', 'Home::index'); 
$routes->get('destinasi', 'Destinasi::index');
$routes->get('destinasi/search', 'Destinasi::search');
$routes->get('destinasi/detail/(:num)', 'Destinasi::detail/$1');

$routes->group('auth', function ($routes) {
    $routes->get('login', 'Auth::login');
    $routes->post('doLogin', 'Auth::doLogin'); 
    $routes->get('register', 'Auth::register');
    $routes->post('doRegister', 'Auth::doRegister'); 
    $routes->get('logout', 'Auth::logout');
});

$routes->group('', ['filter' => 'auth'], function ($routes) {
    $routes->get('dashboard', 'Dashboard::index');

    $routes->post('destinasi/addReview', 'Destinasi::addReview');
    $routes->get('destinasi/review/delete/(:num)', 'Destinasi::deleteReview/$1');

    $routes->get('wishlist', 'Wishlist::index');
    $routes->get('wishlist/add/(:num)', 'Wishlist::add/$1');
    $routes->get('wishlist/remove/(:num)', 'Wishlist::remove/$1');

    $routes->get('booking', 'Booking::index');
    $routes->get('booking/pembelian/(:num)', 'Booking::create/$1');
    $routes->post('booking/store', 'Booking::store');

    $routes->get('riwayat', 'Riwayat::index');
    $routes->get('riwayat/delete/(:num)', 'Riwayat::delete/$1');
    $routes->get('riwayat/cancel/(:num)', 'Riwayat::cancel/$1');
    $routes->get('riwayat/tiket/(:num)', 'Riwayat::showTicket/$1'); 

    $routes->get('profile', 'Profile::index');
    $routes->post('profile/update', 'Profile::update');
    $routes->post('profile/change-password', 'Profile::changePassword');
    $routes->post('profile/updatePreferences', 'Profile::updatePreferences');
});

$routes->group('admin', ['filter' => 'admin'], function ($routes) {
    $routes->get('/', 'Admin\Dashboard::index');
    $routes->get('dashboard', 'Admin\Dashboard::index');
    $routes->get('users', 'Admin\Users::index');
    $routes->get('wisata', 'Admin\Wisata::index');
    $routes->get('review', 'Admin\Review::index');
    $routes->get('booking', 'Admin\Booking::index');
    $routes->get('berita', 'Admin\Berita::index');
    $routes->get('berita/detail/(:num)', 'Berita::detail/$1');
    $routes->get('wisata/create', 'Admin\Wisata::create');
    $routes->post('wisata/store', 'Admin\Wisata::store');
    $routes->get('wisata/edit/(:num)', 'Admin\Wisata::edit/$1');
    $routes->post('wisata/update/(:num)', 'Admin\Wisata::update/$1');
    $routes->post('wisata/delete/(:num)', 'Admin\Wisata::delete/$1');
    $routes->post('wisata/delete-image/(:num)/(:segment)', 'Admin\Wisata::deleteImage/$1/$2');

    $routes->post('users/store', 'Admin\Users::store');
    $routes->get('users/edit/(:num)', 'Admin\Users::edit/$1');
    $routes->post('users/update/(:num)', 'Admin\Users::update/$1');
    $routes->post('users/delete/(:num)', 'Admin\Users::delete/$1');

    $routes->post('review/delete/(:num)', 'Admin\Review::delete/$1');

    $routes->post('booking/delete/(:num)', 'Admin\Booking::delete/$1');

    $routes->get('berita/create', 'Admin\Berita::create');
    $routes->post('berita/store', 'Admin\Berita::store');
    $routes->get('berita/edit/(:num)', 'Admin\Berita::edit/$1');
    $routes->post('berita/update/(:num)', 'Admin\Berita::update/$1');
    $routes->post('berita/delete/(:num)', 'Admin\Berita::delete/$1');
});