<?php

namespace App\Http\Controllers;

use App\Wheather;

class WeatherController extends Controller {

    public function __construct(){
        $this->week_day_fr = ["lundi", "mardi", "mercredi", "jeudi", "vendredi", "samedi", "dimanche"];
        $this->week_day_fr_short = ["lun.", "mar.", "mer.", "jeu.", "ven.", "sam.", "dim."];
    }



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
        $actual_temp = round($data_weather->main->temp);
        $icon = $data_weather->weather[0]->icon;

        // group will be used to select activity
        $group_weather = substr($data_weather->weather[0]->id, 0, 1);
        

        return ['id' => "1-1", 'result' => ['city' => $city_name, "date" => date("d-m-Y"), 'day' => $this->week_day_fr[date('N')-1], 'short_day' => $this->week_day_fr_short[date('N')-1], 'temp' => $actual_temp, 'max_temp' => round($data_weather->main->temp_max), 'min_temp' => round($data_weather->main->temp_min), 'desc' => $desc_weather, 'icon' => substr($icon, 0, -1)]];
    }

    public function getWeeklyWeather($ville){
        // connect to api + get data
        $data_weather_url = file_get_contents(WEATHER_BASEURL.'forecast/daily?q='.$ville.',fr&appid='.WEATHER_KEY.'&cnt=7&units=metric&lang=fr');
        
        $data_weather = json_decode($data_weather_url);

        $week_weather = array();

        // For each day of the week
        for ($i=0; $i < count($data_weather->list); $i++) {

            // Get the day, the temp and weather desc
            $weather = ["date" => date("d-m-Y", $data_weather->list[$i]->dt), 'day' => $this->week_day_fr[date('N', $data_weather->list[$i]->dt)-1], 'short_day' => $this->week_day_fr_short[date('N', $data_weather->list[$i]->dt)-1],  "temp" => round($data_weather->list[$i]->temp->day), 'max_temp' => round($data_weather->list[$i]->temp->max), 'min_temp' => round($data_weather->list[$i]->temp->min),  "desc" => $data_weather->list[$i]->weather[0]->description, 'icon' => substr($data_weather->list[$i]->weather[0]->icon, 0, -1)];

            array_push($week_weather, $weather);
        }

        // select city name
        $city_name = $data_weather->city->name;    
        
        return ['id' => "1-2", 'result' => ['city' => $city_name, 'week' => $week_weather]];
    }
}