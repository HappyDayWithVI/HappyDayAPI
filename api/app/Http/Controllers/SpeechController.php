<?php

namespace App\Http\Controllers;

class SpeechController extends Controller{

    public function interpretSpeech($message){
    	// function will return general display or not
    	$message = str_replace("%20", " ", $message);
    	if ($message == "que puis-je faire") {
    		echo "Liste d'activité";
    	}else{
    		$message_item = explode(" ", $message);

    		if (in_array("meteo", $message_item)) {
    			if (in_array("a", $message_item)) {
    				$pos_city = array_search("a", $message_item)+1;
    				$city = $message_item[$pos_city];
    			}else{
    				// get default user city
    				$city = "Lyon";
    			}

    			if (in_array("semaine", $message_item)) {
    				$res = app('App\Http\Controllers\WeatherController')->getWeeklyWeather($city);
    			}else{
    				$res = app('App\Http\Controllers\WeatherController')->getWeather($city);
    			}

    			return response()->json($res);
    		}else if(in_array("serie", $message_item)){
                if (in_array("genre", $message_item)) {

                    foreach ($message_item as $word) {
                        if ($word != "genre" && $word != "serie" ) {
                            $genre = $word;
                            break;
                        }
                    }

                    $res = app('App\Http\Controllers\TvshowController')->getTvshowByGenre($genre);

                    return response()->json($res);
                }else{
                    $name = "";
                    foreach ($message_item as $word) {
                        if ($word != "serie" ) {
                            $name .= $word."+";
                        }
                    }


                    $res = app('App\Http\Controllers\TvshowController')->getTvshowByName($name);

                    return response()->json($res);
                }
            }
    	}

        // if general get weather
    }

    //
}
