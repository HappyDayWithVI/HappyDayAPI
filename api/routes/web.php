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

$app->get('/', function () use ($app) {
    return $app->version();
});

// speech
$app->get('message/{message}', 'SpeechController@interpretSpeech');

// weather
$app->get('weather/{city}', 'WeatherController@getWeather');
$app->get('weather/{city}/week', 'WeatherController@getWeeklyWeather');

// tvshow
$app->get('tvshow/genre/{genre}', 'TvshowController@getTvshowByGenre');
$app->get('tvshow/name/{nameSearch}', 'TvshowController@getTvshowByName');
$app->get('tvshow/character/{name}', 'TvshowController@getCharacterOfTvshowByName');
$app->get('tvshow/actor/{name}', 'TvshowController@getTvshowByActor');

// Movies
$app->get('movies', 'MoviesController@getMovies');
$app->get('movies/genres', 'MoviesController@getGenres');
// $app->get('movies/genre/{id}', 'MoviesController@getGenres');
$app->get('movies/genre/{name}', 'MoviesController@getMoviesByGenre');
$app->get('movie/{title}', 'MoviesController@getMovieByTitle');
$app->get('movie/cast/{title}', 'MoviesController@getActorByMovieName');
$app->get('movie/actor/{name}', 'MoviesController@getMovieByActor');
$app->get('movie/detail/{id}', 'MoviesController@getMovieDetail');


// book
$app->get('book/title/{title}', 'BookController@getBookByName');
$app->get('book/author/{author}', 'BookController@getBookByAuthor');
$app->get('book/category/{category}', 'BookController@getBookByCategory');
$app->get('book/isbn/{isbn}', 'BookController@getBookByISBN');
$app->get('book/editor/{editor}', 'BookController@getBookByPublisher');

// user
$app->get('user/{id}', 'UserController@show');
