<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Default route to auth
$routes->get('/', 'Auth::login');

// Auth routes
$routes->group('auth', function($routes) {
    $routes->get('login', 'Auth::login');
    $routes->post('doLogin', 'Auth::doLogin');
    $routes->get('register', 'Auth::register');
    $routes->post('doRegister', 'Auth::doRegister');
    $routes->get('logout', 'Auth::logout');
});

// Dashboard routes
$routes->get('/dashboard', 'Dashboard::index');

// Destinasi routes
$routes->get('destinasi', 'Destinasi::index');
$routes->get('destinasi/search', 'Destinasi::search');
$routes->get('destinasi/detail/(:num)', 'Destinasi::detail/$1');

// Wishlist routes
$routes->get('wishlist', 'Wishlist::index');
$routes->get('wishlist/add/(:num)', 'Wishlist::add/$1');
$routes->get('wishlist/remove/(:num)', 'Wishlist::remove/$1');

// Booking routes
$routes->get('booking', 'Booking::index');
$routes->get('booking/create/(:num)', 'Booking::create/$1');
$routes->post('booking/store', 'Booking::store');
$routes->get('booking/complete-payment/(:num)', 'Booking::completePayment/$1');

// Booking history routes
$routes->get('booking', 'Booking::index');
$routes->get('booking/pembelian/(:num)', 'Booking::create/$1');
$routes->post('booking/store', 'Booking::store');

// Profile routes
$routes->get('profile', 'Profile::index');
$routes->post('profile/update', 'Profile::update');
$routes->post('profile/change-password', 'Profile::changePassword');
$routes->post('profile/updatePreferences', 'Profile::updatePreferences');

// Review routes
$routes->post('destinasi/addReview', 'Destinasi::addReview');