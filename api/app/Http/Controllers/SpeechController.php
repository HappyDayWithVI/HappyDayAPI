<?php

namespace App\Http\Controllers;

class SpeechController extends Controller{

    public function interpretSpeech($message){
        // function will return general display or not
        //$message = str_replace("%20", " ", $message);

        $message = urldecode($message);

        if ($message == "que puis-je faire") {
            echo "Liste d'activité";
        }else{
            $message_item = explode(" ", $message);

            if (in_array("meteo", $message_item)) {
                if (in_array("a", $message_item) || in_array("à", $message_item)) {
                    if (in_array("a", $message_item)) {
                        $pos_city = array_search("a", $message_item)+1;
                    }else{
                        $pos_city = array_search("à", $message_item)+1;
                    }

                    $city = $message_item[$pos_city];
                }else{
                    if ((count($message_item) > 1 && !in_array("semaine", $message_item) || count($message_item) > 2 && in_array("semaine", $message_item))) {

                        $city = "";

                        foreach ($message_item as $el) {
                            if ($el != "semaine" && $el != "meteo" ) {
                                $city .= $el." ";
                            }
                        }
                    }else{
                        // get default user city
                        $city = "Lyon";
                    }
                }

                if (in_array("semaine", $message_item)) {
                    $res = app('App\Http\Controllers\WeatherController')->getWeeklyWeather($city);
                }else{
                    $res = app('App\Http\Controllers\WeatherController')->getWeather($city);
                }

            }else if(in_array("serie", $message_item)){
                if (in_array("genre", $message_item)) {

                    foreach ($message_item as $word) {
                        if ($word != "genre" && $word != "serie" ) {
                            $genre = $word;
                            break;
                        }
                    }

                    $res = app('App\Http\Controllers\TvshowController')->getTvshowByGenre($genre);
                }else if (in_array("personnage", $message_item)) {

                    $name = "";
                    foreach ($message_item as $word) {
                        if ($word != "personnage" && $word != "serie" ) {
                            $name .= $word."+";
                        }
                    }

                    $res = app('App\Http\Controllers\TvshowController')->getCharacterOfTvshowByName($name);
                }else if (in_array("avec", $message_item)) {

                    $acteur = "";

                    foreach ($message_item as $word) {
                        if ($word != "avec" && $word != "serie" ) {
                            $acteur .= $word."+";
                        }
                    }

                    $res = app('App\Http\Controllers\TvshowController')->getTvshowByActor($acteur);
                }else{
                    $name = "";
                    foreach ($message_item as $word) {
                        if ($word != "serie" ) {
                            $name .= $word."+";
                        }
                    }

                    $name = rtrim($name,"+");

                    $res = app('App\Http\Controllers\TvshowController')->getTvshowByName($name);
                }
            }else if(in_array("film", $message_item)){
                if (in_array("genre", $message_item)) {

                    foreach ($message_item as $word) {
                        if ($word != "genre" && $word != "film" ) {
                            $genre = $word;
                            break;
                        }
                    }

                    $res = app('App\Http\Controllers\MoviesController')->getMoviesByGenre($genre);
                }elseif (in_array("nouveau", $message_item)) {

                    $res = app('App\Http\Controllers\MoviesController')->getMovies();

                }else if (in_array("personnage", $message_item)) {

                    $name = "";
                    foreach ($message_item as $word) {
                        if ($word != "personnage" && $word != "film" ) {
                            $name .= $word."+";
                        }
                    }

                    $res = app('App\Http\Controllers\MoviesController')->getActorByMovieName($name);
                }else if (in_array("avec", $message_item)) {

                    $acteur = "";

                    foreach ($message_item as $word) {
                        if ($word != "avec" && $word != "film" ) {
                            $acteur .= $word."+";
                        }
                    }

                    $res = app('App\Http\Controllers\MoviesController')->getMovieByActor($acteur);
                }else{
                    $name = "";
                    foreach ($message_item as $word) {
                        if ($word != "film" ) {
                            $name .= $word."+";
                        }
                    }

                    $name = rtrim($name,"+");

                    $res = app('App\Http\Controllers\MoviesController')->getMovieByTitle($name);
                }
            }else if(in_array("livre", $message_item)){
                if (in_array("de", $message_item)) {
                    $author = "";

                    foreach ($message_item as $word) {
                        if ($word != "de" && $word != "livre" ) {
                            $author .= $word."+";
                        }
                    }

                    $res = app('App\Http\Controllers\BookController')->getBookByAuthor($author);
                }else{
                    $name = "";
                    foreach ($message_item as $word) {
                        if ($word != "livre" ) {
                            $name .= $word."+";
                        }
                    }

                    $name = rtrim($name,"+");

                    $res = app('App\Http\Controllers\BookController')->getBookByName($name);
                }
            }else if(in_array("programme", $message_item)){
                if (in_array("soir", $message_item)) {
                    $res = app('App\Http\Controllers\TvguideController')->getTvGuideTonigt();
                }else{
                    $res = app('App\Http\Controllers\TvguideController')->getTvGuideByTime();
                }
            }else if(in_array("restaurant", $message_item)){
                if (in_array("meilleur", $message_item)) {
                    $city = "";
                    foreach ($message_item as $word) {
                        if ($word != "meilleur" || $word != "restaurant" || $word != "a" || $word != "à" ) {
                            $city .= $word."+";
                        }
                    }

                    $res = app('App\Http\Controllers\RestaurantController')->getBestRestaurantByCity($city);
                }else{

                    if ($key = array_search('à', $message_item)) {
                        $assumed_city = $message_item[$key+1];
                    }else if($key = array_search('a', $message_item)){
                        $assumed_city = $message_item[$key+1];
                    }
                    unset($message_item[$key+1]);

                    $type= "";

                    foreach ($message_item as $word) {
                        if ($word != "restaurant" || $word != "a" || $word != "à" ) {
                            $type .= $word."+";
                        }
                    }

                    $res = app('App\Http\Controllers\RestaurantController')->getRestaurantByName($type, $assumed_city);
                }
            }else if(in_array("musique", $message_item)){
              if (in_array("nouveauté",$message_item)){
                $country = "FR";
                $res = app('App\Http\Controllers\MusicController')->getNewRealease($country);
              }
            }
        }

        return response()->json($res);

        // if general get weather
    }

    //
}
