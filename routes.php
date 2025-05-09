<?php
/**
 * [ 
 *      'method' => 'GET',   
 *      'url' => '/',                 
 *      'handler' => 'UtenteController@index',          
 *      'authorized' => true, 
 *      'role' => ['admin', 'user'], 
 *      'view' => 'utenti.index' ],
 */
return [
    [ 'method' => 'GET',    'url' => '/',                      'handler' => 'HomeController@index', 'view'=> 'home'],
   
    [ 'method' => 'GET',    'url' => '/login',                 'handler' => 'Auth/AuthController@showLogin' , 'view'=> 'login'],
    [ 'method' => 'POST',   'url' => '/login',                 'handler' => 'Auth/AuthController@login' ],
    [ 'method' => 'GET',    'url' => '/logout',                'handler' => 'Auth/AuthController@logout' ],
    [ 'method' => 'POST',   'url' => '/delete',                'handler' => 'Auth/AuthController@deleteUser' ],
    [ 'method' => 'GET',    'url' => '/register',              'handler' => 'Auth/AuthController@showRegister' , 'view' => 'registrer'],
    [ 'method' => 'POST',   'url' => '/register',              'handler' => 'Auth/AuthController@register' ],

     // CRUD Utenti
    [ 'method' => 'GET',   'url' => '/utenti',                 'handler' => 'UtenteController@index',       'authorized' => true, 'role' => ['admin'], 'view' => 'utenti.index' ],
    [ 'method' => 'GET',   'url' => '/utenti/create',          'handler' => 'UtenteController@create',      'authorized' => true, 'role' => ['admin'], 'view' => 'utenti.create' ],
    [ 'method' => 'POST',  'url' => '/utenti',                 'handler' => 'UtenteController@store',       'authorized' => true, 'role' => ['admin'] ],
    [ 'method' => 'GET',   'url' => '/utenti/show/{id}',       'handler' => 'UtenteController@show',        'authorized' => true, 'role' => ['admin'], 'view' => 'utenti.show' ],
    [ 'method' => 'GET',   'url' => '/utenti/edit/{id}',       'handler' => 'UtenteController@edit',        'authorized' => true, 'role' => ['admin'], 'view' => 'utenti.edit' ],
    [ 'method' => 'POST',  'url' => '/utenti/update/{id}',     'handler' => 'UtenteController@update',      'authorized' => true, 'role' => ['admin'] ],
    [ 'method' => 'POST',  'url' => '/utenti/delete/{id}',     'handler' => 'UtenteController@destroy',     'authorized' => true, 'role' => ['admin'] ],

    // CRUD Eventi
    [ 'method' => 'GET',   'url' => '/eventi',                 'handler' => 'EventoController@index',       'authorized' => true, 'role' => ['admin'], 'view' => 'eventi.index'],
    [ 'method' => 'GET',   'url' => '/eventi/create',          'handler' => 'EventoController@create',      'authorized' => true, 'role' => ['admin'], 'view' => 'eventi.create'],
    [ 'method' => 'POST',  'url' => '/eventi',                 'handler' => 'EventoController@store',       'authorized' => true, 'role' => ['admin']],
    [ 'method' => 'GET',   'url' => '/eventi/show/{id}',       'handler' => 'EventoController@show',        'authorized' => true, 'role' => ['admin'], 'view' => 'eventi.show'],
    [ 'method' => 'GET',   'url' => '/eventi/edit/{id}',       'handler' => 'EventoController@edit',        'authorized' => true, 'role' => ['admin'], 'view' => 'eventi.edit'],
    [ 'method' => 'POST',  'url' => '/eventi/update/{id}',     'handler' => 'EventoController@update',      'authorized' => true, 'role' => ['admin']],
    [ 'method' => 'POST',  'url' => '/eventi/delete/{id}',     'handler' => 'EventoController@destroy',     'authorized' => true, 'role' => ['admin']],
    [ 'method' => 'POST',  'url' => '/eventi/update-media/{id}','handler' => 'EventoController@updateMedia', 'authorized' => true, 'role' => ['admin']]
];
