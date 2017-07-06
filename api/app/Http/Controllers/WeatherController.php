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
        // connect to api + get data
        $data_weather_url = file_get_contents('http://api.openweathermap.org/data/2.5/weather?q='.$ville.',fr&appid=91b254a2e825b2cda95cdeeff959e009&units=metric&lang=fr');
        $data_weather = json_decode($data_weather_url);

        // select city name, desc, temp and group
        $city_name = $data_weather->name;
        $desc_weather = $data_weather->weather[0]->description;
        $actual_temp = $data_weather->main->temp;

        // group will be used to select activity
        $group_weather = substr($data_weather->weather[0]->id, 0, 1);
        
        return response()->json(['city' => $city_name, 'temp' => $actual_temp, 'desc' => $desc_weather]);
    }

    public function getWeeklyWeather($ville){
        // connect to api + get data
        $data_weather_url = file_get_contents('http://api.openweathermap.org/data/2.5/forecast?q='.$ville.',fr&appid=91b254a2e825b2cda95cdeeff959e009');

        $data_weather = json_decode($data_weather_url);

        echo "<pre>";
        var_dump($data_weather);
        echo "</pre>";

        // select city name, desc, temp and group
        $city_name = $data_weather->city->name;
        echo $city_name;
        //$desc_weather = $data_weather->weather[0]->description;
        //$actual_temp = $data_weather->main->temp;

        // group will be used to select activity
        //$group_weather = substr($data_weather->weather[0]->id, 0, 1);

        

        // return response()->json(['city' => $city_name, 'temp' => $actual_temp, 'desc' => $desc_weather]);
    }
}