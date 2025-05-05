<?php
return [
    [ 'method' => 'GET', 'url' => '/', 'handler' => 'HomeController@index', 'authorized' => true, 'role' => ['admin', 'user'] ],
    [ 'method' => 'GET', 'url' => '/ciao', 'handler' => 'CiaoController@index' ],
    [ 'method' => 'GET', 'url' => '/login', 'handler' => 'AuthController@showLogin' ],
    [ 'method' => 'POST', 'url' => '/login', 'handler' => 'AuthController@login' ],
    [ 'method' => 'GET', 'url' => '/logout', 'handler' => 'AuthController@logout' ],
    [ 'method' => 'POST', 'url' => '/delete', 'handler' => 'AuthController@deleteUser', 'authorized' => true ],
    [ 'method' => 'GET', 'url' => '/register', 'handler' => 'AuthController@showRegister' ],
    [ 'method' => 'POST', 'url' => '/register', 'handler' => 'AuthController@register' ],
];
