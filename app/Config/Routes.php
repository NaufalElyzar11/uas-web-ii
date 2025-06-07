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
$routes->get('dashboard', 'Dashboard::index');
$routes->get('destinasi', 'Destinasi::index');
$routes->get('wishlist', 'Wishlist::index');
$routes->get('riwayat', 'Riwayat::index');
$routes->get('profile', 'Profile::index');
