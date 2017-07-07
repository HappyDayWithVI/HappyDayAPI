<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

// $app->get('/', function () use ($app) {
//     return $app->version();
// });

// speech
$app->get('message/{message}', 'SpeechController@interpretSpeech');

// weather
$app->get('weather/{city}', 'WeatherController@getWeather');
$app->get('weather/{city}/week', 'WeatherController@getWeeklyWeather');

// tvshow
$app->get('tvshow/genre/{genre}', 'TvshowController@getTvshowByGenre');

$app->get('user/{id}', 'UserController@show');


$app->get('/', ['prefix' => 'api/v1', 'middleware' => 'cors'], function() {
    return 'You did it!';
});