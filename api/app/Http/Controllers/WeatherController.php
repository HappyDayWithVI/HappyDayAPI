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
        $data_weather_url = file_get_contents(WEATHER_BASEURL.'weather?q='.$ville.',fr&appid='.WEATHER_KEY.'&units=metric&lang=fr');
        $data_weather = json_decode($data_weather_url);

        // select city name, desc, temp and group
        $city_name = $data_weather->name;
        $desc_weather = $data_weather->weather[0]->description;
        $actual_temp = $data_weather->main->temp;

        // group will be used to select activity
        $group_weather = substr($data_weather->weather[0]->id, 0, 1);
        
        return ['id' => 2, 'result' => ['city' => $city_name, 'temp' => $actual_temp, 'desc' => $desc_weather]];
    }

    public function getWeeklyWeather($ville){
        // connect to api + get data
        $data_weather_url = file_get_contents(WEATHER_BASEURL.'forecast/daily?q='.$ville.',fr&appid='.WEATHER_KEY.'&cnt=7&units=metric&lang=fr');
        
        $data_weather = json_decode($data_weather_url);


        $week_weather = array();

        // For each day of the week
        for ($i=0; $i < count($data_weather->list); $i++) {

            // Get the day, the temp and weather desc
            $weather = ["date" => date("d-m-Y", $data_weather->list[$i]->dt), "temp" => $data_weather->list[$i]->temp->day, "desc" => $data_weather->list[$i]->weather[0]->description];

            array_push($week_weather, $weather);
        }

        // select city name
        $city_name = $data_weather->city->name;        

        return ['id' => 2, 'result' => ['city' => $city_name, 'week' => [$week_weather]]];
    }
}