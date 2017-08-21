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

            // For each day of the week
            for ($i=0; $i < count($data_tvshow); $i++) {

                $name = $data_tvshow[$i]->name;
                $resume = $data_tvshow[$i]->resume;


                $show = ["name" => $data_tvshow[$i]->name, "resume" => $data_tvshow[$i]->resume];

                array_push($shows_by_genre, $show);
            }
            
            return ['id' => 4, 'result' => ['genre' => $genre, 'shows' => $shows_by_genre]];
        }
    }

    
}