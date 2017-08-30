<?php

namespace App\Http\Controllers;

class SpeechController extends Controller{

    public function interpretSpeech($message){
        // function will return general display or not
        //$message = str_replace("%20", " ", $message);

        $message = urldecode($message);

        if ($message == "que puis-je faire") {
            $res['message'] = 'Va dormir, sérieux tu dois être tellement fatigué !';
        }else{
            $message_item = explode(" ", $message);

            if (in_array("meteo", $message_item) || in_array("météo", $message_item) ) {
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
                    $res['message'] = "Aujourd'hui à ". $res['result']['city'] .". " . $res['result']['week'][0]['day']. ". il fait ". $res['result']['week'][0]['temp'] ." degré. et ". $res['result']['week'][0]['desc'];

                    $alt = $res;
                    unset( $alt['result']['week'][0] );
                    $message = '';
                    foreach ($alt['result']['week'] as $key => $value) {
                        $message .= '. '. $value['day'] .'. il fera '. $value['temp'] .' degré. avec '. $value['desc'];
                    }

                    $res['message'] .= $message;
                }else{
                    $res = app('App\Http\Controllers\WeatherController')->getWeather($city);
                    
                    $res['message'] = "Aujourd'hui à ". $res['result']['city'] .". " . $res['result']['day']. ". il fait ". $res['result']['temp'] ." degré. et ". $res['result']['desc']; 
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
            }else{
                $res['message'] = "Je n'ai pas compris ce que tu me demande";
            }
        }

        return response()->json($res);

        // if general get weather
    }

    //
}