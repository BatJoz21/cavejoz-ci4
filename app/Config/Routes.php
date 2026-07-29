<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');
$routes->get('/register', 'Auths::openRegisterPage');
$routes->post('/register', 'Auths::registerNewUser');
$routes->get('/login', 'Auths::openLoginPage');
$routes->post('/login', 'Auths::userLogin');
$routes->get('session-test', function () {
    dd(session()->get());
});

$routes->group('/', ['filter' => 'jwtauth'], function($routes) {
    $routes->post('/logout', 'Auths::userLogout');
    
    $routes->get('search', 'Searches::search');
});
