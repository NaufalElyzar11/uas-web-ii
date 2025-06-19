<?php

use CodeIgniter\Router\RouteCollection;
use App\Controllers\Home;

/**
 * @var RouteCollection $routes
 */

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');

$routes->group('admin', ['filter' => 'admin'], function ($routes) {
    $routes->get('/', 'Admin\Dashboard::index');
    $routes->get('dashboard', 'Admin\Dashboard::index');

    $routes->get('wisata', 'Admin\Wisata::index');
    $routes->get('wisata/create', 'Admin\Wisata::create');
    $routes->post('wisata/store', 'Admin\Wisata::store');
    $routes->get('wisata/edit/(:num)', 'Admin\Wisata::edit/$1');
    $routes->post('wisata/update/(:num)', 'Admin\Wisata::update/$1');
    $routes->get('wisata/delete/(:num)', 'Admin\Wisata::delete/$1');
    $routes->post('wisata/delete-image/(:num)/(:any)', 'Admin\Wisata::deleteImage/$1/$2');

    $routes->get('users', 'Admin\Users::index');
    $routes->get('users/edit/(:num)', 'Admin\Users::edit/$1');
    $routes->post('users/update/(:num)', 'Admin\Users::update/$1');
    $routes->get('users/delete/(:num)', 'Admin\Users::delete/$1');

    $routes->get('berita', 'Admin\Berita::index');
    $routes->get('berita/create', 'Admin\Berita::create');
    $routes->post('berita/store', 'Admin\Berita::store');
    $routes->get('berita/edit/(:num)', 'Admin\Berita::edit/$1');
    $routes->post('berita/update/(:num)', 'Admin\Berita::update/$1');
    $routes->get('berita/delete/(:num)', 'Admin\Berita::delete/$1');
    
    $routes->get('bookings', 'Admin\Booking::index');
    $routes->get('bookings/update-status/(:num)/(:segment)', 'Admin\Booking::updateStatus/$1/$2');


    $routes->get('reviews', 'Admin\Review::index');
    $routes->get('reviews/delete/(:num)', 'Admin\Review::delete/$1');
});

$routes->group('', ['filter' => 'auth'], function ($routes) {
    $routes->get('dashboard', 'Dashboard::index');
    $routes->get('booking/create/(:num)', 'Booking::create/$1');
    $routes->post('booking/store', 'Booking::store');
    $routes->get('riwayat', 'User::riwayat');
    $routes->get('wishlist', 'User::wishlist');
    $routes->post('wishlist/toggle', 'User::toggleWishlist');
    $routes->get('profile', 'User::profile');
    $routes->post('profile/update', 'User::updateProfile');
});

$routes->get('destinasi', 'Destinasi::index');
$routes->get('destinasi/detail/(:num)', 'Destinasi::detail/$1');
$routes->get('destinasi/search', 'Destinasi::search');
$routes->post('destinasi/review/add', 'Destinasi::addReview');
$routes->post('destinasi/review/delete/(:num)', 'Destinasi::deleteReview/$1');


$routes->get('auth/login', 'Auth::login');
$routes->post('auth/login', 'Auth::processLogin');
$routes->get('auth/register', 'Auth::register');
$routes->post('auth/register', 'Auth::processRegister');
$routes->get('auth/logout', 'Auth::logout');
/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 */
