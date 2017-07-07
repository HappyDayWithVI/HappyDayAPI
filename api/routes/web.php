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
$app->get('tvshow/search/{name}', 'TvshowController@getTvshowByName');

$app->get('user/{id}', 'UserController@show');



$app->group(['middleware' => 'cors'], function($app){
	$app->get('message/{message}', 'SpeechController@interpretSpeech');
});
