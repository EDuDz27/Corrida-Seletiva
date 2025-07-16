<?php

require_once 'router.php';

$basePath = '/corridaseletiva';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if (strpos($uri, $basePath) === 0) {
    $uri = substr($uri, strlen($basePath));
}

$uri = rtrim($uri, '/') ?: '/';

add('/',                    'HomeController',           'index');
add('/home',                'HomeController',           'index');
add('/admin',               'AdminController',          'index');
add('/admin_form',          'AdminController',          'showForm');
add('/admin_login',         'AdminController',          'login');
add('/logout',              'AdminController',          'logout');
add('/vagas',               'VagasController',          'index');
add('/curriculos',          'CurriculosController',     'index');

route($uri);