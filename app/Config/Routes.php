<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/register', 'Auths::openRegisterPage');
$routes->post('/register', 'Auths::registerNewUser');
$routes->get('/login', 'Auths::openLoginPage');
$routes->post('/login', 'Auths::userLogin');
$routes->get('/session-test', function () {
    dd(session()->get());
});

$routes->group('/', ['filter' => 'jwtauth'], function($routes) {
    $routes->get('/ws-ticket', 'WebSockets::getWebSocketTicket');

    $routes->get('home', 'Home::index');
    $routes->get('', 'Posts::feeds');
    $routes->post('logout', 'Auths::userLogout');

    $routes->get('search', 'Searches::search');

    $routes->get('friends', 'Friendships::index');
    $routes->post('friends', 'Friendships::addFriendAUser');
    $routes->patch('friends', 'Friendships::acceptFriendRequest');
    $routes->delete('friends', 'Friendships::rejectRemoveFriendRequest');
    $routes->post('block', 'Friendships::blockAUser');

    $routes->get('profile', 'Users::openMyProfile');
    $routes->get('profile/edit', 'Users::edit');
    $routes->patch('profile', 'Users::update');
    $routes->get('profile/(:segment)', 'Users::openUserProfile/$1');
    $routes->get('avatar/(:segment)', 'Users::getUserAvatar/$1');

    $routes->get('posts/create', 'Posts::new');
    $routes->post('posts', 'Posts::create');
    $routes->get('posts/(:num)/edit', 'Posts::edit/$1');
    $routes->patch('posts/(:num)', 'Posts::update/$1');
    $routes->post('posts/(:num)/delete', 'Posts::delete/$1');

    $routes->post('posts/(:num)/like', 'Likes::toggleLikeOnPost/$1');
    $routes->get('posts/(:num)/like', 'Likes::isUserLikedThisPost/$1');

    $routes->get('posts/(:num)/comments', 'Posts::view/$1');
    $routes->post('posts/(:num)/comments', 'Comments::create/$1');
    $routes->post('posts/(:num)/comments/(:num)/delete', 'Comments::deleteComment/$1/$2');

    $routes->get('notifications', 'Notifications::index');
    $routes->get('notifications/latest', 'Notifications::latestNotification');
    $routes->patch('notifications/mark-all-read', 'Notifications::markAllRead');
    $routes->get('notifications/(:num)/visit', 'Notifications::markReadNotification/$1');

    $routes->get('chat', 'Conversations::getConversationList');
    $routes->get('chat/(:num)', 'Conversations::openConversation/$1');
    $routes->post('chat/(:num)/message', 'Messages::sendMessage/$1');

    $routes->get('feeds/posts/(:num)', 'Posts::loadMorePostsForFeed/$1');
    $routes->get('users/(:num)/posts/(:num)', 'Posts::loadMorePosts/$1/$2');
    $routes->get('content/image/(:segment)', 'Posts::getPostContentImage/$1');
});
