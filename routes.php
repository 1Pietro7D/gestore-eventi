<?php
return [
    [ 'method' => 'GET', 'url' => '/', 'handler' => 'HomeController@index'],
    [ 'method' => 'GET', 'url' => '/utenti', 'handler' => 'UtenteController@index', 'authorized' => true, 'role' => ['admin', 'user']  ],
    [ 'method' => 'GET',  'url' => '/utenti/show/{id}', 'handler' => 'UtenteController@show', 'authorized' => true, 'role' => ['admin', 'user']  ],
    [ 'method' => 'GET', 'url' => '/login', 'handler' => 'Auth/AuthController@showLogin' ],
    [ 'method' => 'POST', 'url' => '/login', 'handler' => 'Auth/AuthController@login' ],
    [ 'method' => 'GET', 'url' => '/logout', 'handler' => 'Auth/AuthController@logout' ],
    [ 'method' => 'POST', 'url' => '/delete', 'handler' => 'Auth/AuthController@deleteUser', 'Auth/authorized' => true ],
    [ 'method' => 'GET', 'url' => '/register', 'handler' => 'Auth/AuthController@showRegister' ],
    [ 'method' => 'POST', 'url' => '/register', 'handler' => 'Auth/AuthController@register' ],
];
