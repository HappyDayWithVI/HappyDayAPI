<?php

namespace App\Http\Controllers;

class CineController extends Controller{
    
    public function __construct(){

    }

    public function getCineByKeywords($keyword)
    {
        // http://www.allocine.fr/_/localization/KEYWORD

        $data_cine_url = file_get_contents('http://www.allocine.fr/_/localization/'. $keyword);

        $result = json_decode($data_cine_url);

        $res_city = [];
        $res_theater = [];
        foreach ($result as $key => $value) {
            if( $value->type == 'city' ){
                $res_city[] = ['id' => $value->id, 'name' => $value->name, 'zip' => $value->zip_code];
            }elseif( $value->type == 'theater' ){
                $res_theater[] = ['id' => $value->id, 'name' => $value->name, 'zip' => $value->zip_code, 'code' => $value->id_theater_ac];
            }
        }
        $result_final = array('city' => $res_city, 'theater' => $res_theater);

        return response()->json( $result_final );
    }

    public function getCineByCity($city){
        $city = $this->getCineByKeywords($city)->original['city'];
        var_dump( $city );
    }

    public function getCineByName($name){
        $name = $this->getCineByKeywords($name)->original['theater'];
        var_dump( $name );
    }

    public function getMovieByKeywords($keyword)
    {
        // http://www.allocine.fr/_/localization/KEYWORD

        $data_cine_url = file_get_contents('http://essearch.allocine.net/fr/autocomplete?geo2=83093&q='. $keyword);

        $result = json_decode($data_cine_url);

        $res_city = [];
        $res_theater = [];
        foreach ($result as $key => $value) {
            if( $value->type == 'city' ){
                $res_city[] = ['id' => $value->id, 'name' => $value->name, 'zip' => $value->zip_code];
            }elseif( $value->type == 'theater' ){
                $res_theater[] = ['id' => $value->id, 'name' => $value->name, 'zip' => $value->zip_code, 'code' => $value->id_theater_ac];
            }
        }
        $result_final = array('city' => $res_city, 'theater' => $res_theater);

        return response()->json( $result_final );
    }}

}
