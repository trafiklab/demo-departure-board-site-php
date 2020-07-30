<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

$router->get('/',
    [
        'uses' => 'HomeController@show',
        'as' => 'showHome',
    ]
);

$router->post('/search', [
        'uses' => 'StopLocationSearchController@search',
        'as' => 'searchStopLocation',
    ]
);

$router->get('/autocomplete/{query}', [
        'uses' => 'StopLocationSearchController@autocomplete',
        'as' => 'autocompleteStopLocation',
    ]
);

$router->get('/departures/{id}',
    [
        'uses' => 'TrafficBoardController@showDepartureBoard',
        'as' => 'showDepartureBoard',
    ]
)->where('id', '([0-9]+|,)+');

$router->get('/arrivals/{id}',
    [
        'uses' => 'TrafficBoardController@showArrivalBoard',
        'as' => 'showArrivalBoard',
    ]
)->where('id', '([0-9]+|,)+');