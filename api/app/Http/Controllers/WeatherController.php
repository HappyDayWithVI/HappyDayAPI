<?php

namespace App\Http\Controllers;

use App\Wheather;

class WeatherController extends Controller
{
    /**
     * Retrieve the user for the given ID.
     *
     * @param  int  $id
     * @return Response
     */
    public function getWeather($ville){
        $data_weather_url = file_get_contents('http://api.openweathermap.org/data/2.5/weather?q='.$ville.',fr&appid=91b254a2e825b2cda95cdeeff959e009&units=metric&lang=fr');
        $data_weather = json_decode($data_weather_url);

        $city_name = $data_weather->name;
        $desc_weather = $data_weather->weather[0]->description;
        $actual_temp = $data_weather->main->temp;
        $group_weather = substr($data_weather->weather[0]->id, 0, 1);

        // http://api.openweathermap.org/data/2.5/weather?q=London,uk&appid=91b254a2e825b2cda95cdeeff959e009
        // return "Quelle est la météo à ".$ville." ?";
        return response()->json(['city' => $city_name, 'temp' => $actual_temp, 'desc' => $desc_weather]);
    }
}