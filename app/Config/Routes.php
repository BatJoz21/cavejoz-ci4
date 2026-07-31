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
$routes->get('/friends', 'Friendships::index');

$routes->group('/', ['filter' => 'jwtauth'], function($routes) {
    $routes->post('/logout', 'Auths::userLogout');

    $routes->get('search', 'Searches::search');

    $routes->post('friends', 'Friendships::addFriendAUser');
    $routes->patch('friends', 'Friendships::acceptFriendRequest');
    $routes->delete('friends', 'Friendships::rejectRemoveFriendRequest');
    $routes->post('block', 'Friendships::blockAUser');

    $routes->get('profile', 'Users::openMyProfile');
    $routes->get('profile/(:segment)', 'Users::openUserProfile/$1');
    $routes->get('avatar/(:segment)', 'Users::getUserAvatar/$1');

    $routes->get('users/(:num)/posts/(:num)', 'Posts::loadMorePosts/$1/$2');
    $routes->get('content/image/(:segment)', 'Posts::getPostContentImage/$1');
});
