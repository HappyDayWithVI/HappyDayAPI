<?php

namespace App\Http\Controllers;

class SpeechController extends WeatherController{

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
    				$res = $this->getWeeklyWeather($city);
    			}else{
    				$res = $this->getWeather($city);
    			}

    			return response()->json($res);

    		

    		}
    	}

        // if general get weather
    }

    //
}
