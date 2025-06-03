<?php
/**
 * [ 
 *      'method' => 'GET',   
 *      'url' => '/',                 
 *      'handler' => 'UtenteController@index',          
 *      'authorized' => true, 
 *      'role' => ['admin', 'user'], 
 *      'view' => 'utenti.index' ],
 *      'cache' => ['home_cache', 60]
 * ]
 */
return [
    [ 'method' => 'GET',    'url' => '/',                      'handler' => 'HomeController@index', 'view'=> 'home' ],
   
    [ 'method' => 'GET',    'url' => '/login',                 'handler' => 'Auth/AuthController@showLogin' , 'view'=> 'login'],
    [ 'method' => 'POST',   'url' => '/login',                 'handler' => 'Auth/AuthController@login' ],
    [ 'method' => 'GET',    'url' => '/logout',                'handler' => 'Auth/AuthController@logout' ],
    [ 'method' => 'POST',   'url' => '/delete',                'handler' => 'Auth/AuthController@deleteUser' ],
    [ 'method' => 'GET',    'url' => '/register',              'handler' => 'Auth/AuthController@showRegister' , 'view' => 'register'],
    [ 'method' => 'POST',   'url' => '/register',              'handler' => 'Auth/AuthController@register' ],
    // Verifica OTP post-registrazione
    [ 'method' => 'GET',    'url' => '/verifica-otp',          'handler' => 'Auth/AuthController@showVerificaOtp', 'view' => 'verifica-otp'],
    [ 'method' => 'POST',   'url' => '/verifica-otp',          'handler' => 'Auth/AuthController@verificaOtp' ],
     // CRUD Utenti
    [ 'method' => 'GET',   'url' => '/utenti',                 'handler' => 'UtenteController@index',       'authorized' => true, 'role' => ['superadmin'], 'view' => 'utenti.index' ],
    [ 'method' => 'GET',   'url' => '/utenti/create',          'handler' => 'UtenteController@create',      'authorized' => true, 'role' => ['superadmin'], 'view' => 'utenti.create' ],
    [ 'method' => 'POST',  'url' => '/utenti/store',           'handler' => 'UtenteController@store',       'authorized' => true, 'role' => ['superadmin'] ],
    [ 'method' => 'GET',   'url' => '/utenti/show/{id}',       'handler' => 'UtenteController@show',        'authorized' => true, 'role' => ['superadmin'], 'view' => 'utenti.show' ],
    [ 'method' => 'GET',   'url' => '/utenti/edit/{id}',       'handler' => 'UtenteController@edit',        'authorized' => true, 'role' => ['superadmin'], 'view' => 'utenti.edit' ],
    [ 'method' => 'POST',  'url' => '/utenti/update/{id}',     'handler' => 'UtenteController@update',      'authorized' => true, 'role' => ['superadmin'] ],
    [ 'method' => 'POST',  'url' => '/utenti/delete/{id}',     'handler' => 'UtenteController@destroy',     'authorized' => true, 'role' => ['superadmin'] ],

    // CRUD Eventi
    [ 'method' => 'GET',   'url' => '/eventi',                 'handler' => 'EventoController@index',       'authorized' => true, 'role' => ['superadmin', 'admin'], 'view' => 'eventi.index'],
    [ 'method' => 'GET',   'url' => '/eventi/create',          'handler' => 'EventoController@create',      'authorized' => true, 'role' => ['superadmin', 'admin'], 'view' => 'eventi.create'],
    [ 'method' => 'POST',  'url' => '/eventi/store',           'handler' => 'EventoController@store',       'authorized' => true, 'role' => ['superadmin', 'admin']],
    [ 'method' => 'GET',   'url' => '/eventi/show/{id}',       'handler' => 'EventoController@show',       /* 'authorized' => true, 'role' => ['superadmin', 'admin','user'],*/ 'view' => 'eventi.show'],
    [ 'method' => 'GET',   'url' => '/eventi/edit/{id}',       'handler' => 'EventoController@edit',        'authorized' => true, 'role' => ['superadmin', 'admin'], 'view' => 'eventi.edit'],
    [ 'method' => 'POST',  'url' => '/eventi/update/{id}',     'handler' => 'EventoController@update',      'authorized' => true, 'role' => ['superadmin', 'admin']],
    [ 'method' => 'POST',  'url' => '/eventi/delete/{id}',     'handler' => 'EventoController@destroy',     'authorized' => true, 'role' => ['superadmin', 'admin']],
    [ 'method' => 'POST',  'url' => '/eventi/{id}/iscriviti',  'handler' => 'EventoController@iscriviti',   'authorized' => true, 'role' => ['user', 'admin', 'superadmin'] ],
    [ 'method' => 'POST',  'url' => '/eventi/{id}/annulla-iscrizione', 'handler' => 'EventoController@annullaIscrizione', 'authorized' => true, 'role' => ['user', 'admin', 'superadmin']],

    // CRUD Categorie
    [ 'method' => 'GET',   'url' => '/categorie',                'handler' => 'CategorieController@index',    'authorized' => true, 'role' => ['superadmin'], 'view' => 'categorie.index' ],
    [ 'method' => 'GET',   'url' => '/categorie/create',         'handler' => 'CategorieController@create',   'authorized' => true, 'role' => ['superadmin'], 'view' => 'categorie.create' ],
    [ 'method' => 'POST',  'url' => '/categorie/store',          'handler' => 'CategorieController@store',    'authorized' => true, 'role' => ['superadmin'] ],
    [ 'method' => 'GET',   'url' => '/categorie/edit/{id}',      'handler' => 'CategorieController@edit',     'authorized' => true, 'role' => ['superadmin'], 'view' => 'categorie.edit' ],
    [ 'method' => 'POST',  'url' => '/categorie/update/{id}',    'handler' => 'CategorieController@update',   'authorized' => true, 'role' => ['superadmin'] ],
    [ 'method' => 'POST',  'url' => '/categorie/delete/{id}',    'handler' => 'CategorieController@destroy',  'authorized' => true, 'role' => ['superadmin'] ],
    // Rotte Pagamenti
    ['method' => 'GET',  'url' => '/carrello',                 'handler' => 'CartController@index',   'view' => 'cart.index'],
    ['method' => 'POST', 'url' => '/carrello/aggiungi/{id}',   'handler' => 'CartController@add'],
    ['method' => 'POST', 'url' => '/carrello/rimuovi/{id}',    'handler' => 'CartController@remove'],
    ['method' => 'GET',  'url' => '/checkout',                 'handler' => 'CheckoutController@index'],
    ['method' => 'GET',  'url' => '/checkout/successo',        'handler' => 'CheckoutController@success'],
    ['method' => 'GET',  'url' => '/checkout/cancellato',      'handler' => 'CheckoutController@cancel'],
];
