<?php

namespace App\Http\Controllers;


class TvshowController extends Controller{

    public function __construct(){
    }

    /**
     * Retrieve the user for the given ID.
     *
     * @param  int  $id
     * @return Response
     */
    public function getTvshowByGenre($genre){
        if (strpos($genre, ",") != false) {
            $genres = explode(',', $genre);
        }else{
            $genres = $genre;

            $data_tvshow_url = file_get_contents(TVSHOW_BASEURL.'?genre='.$genres);

            $data_tvshow = json_decode($data_tvshow_url);

            $shows_by_genre = array();

            for ($i=0; $i < count($data_tvshow); $i++) {

                $name = $data_tvshow[$i]->name;
                $resume = $data_tvshow[$i]->resume;


                $show = ["name" => $data_tvshow[$i]->name, "resume" => $data_tvshow[$i]->resume];

                array_push($shows_by_genre, $show);
            }
            
            return ['id' => '2-1', 'result' => ['genre' => $genre, 'shows' => $shows_by_genre]];
        }
    }

    public function getTvshowByName($nameSearch){
        $nameSearch = str_replace("+", " ", $nameSearch);
        $data_tvshow_url = file_get_contents(TVSHOW_BASEURL.'?serie='.$nameSearch);

        $data_tvshow = json_decode($data_tvshow_url);

        // var_dump($data_tvshow);

        $shows_by_name = array();

        for ($i=0; $i < count($data_tvshow); $i++) {

            $name = $data_tvshow[$i]->name;
            $resume = $data_tvshow[$i]->resume;


            $show = ["name" => $data_tvshow[$i]->name, "resume" => $data_tvshow[$i]->resume];

            array_push($shows_by_name, $show);
        }
        
        return ['id' => '2-2', 'result' => ['name' => $nameSearch, 'shows' => $shows_by_name]];
    }

    
}