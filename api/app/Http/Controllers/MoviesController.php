<?php

namespace App\Http\Controllers;

use App\Movies;

class MoviesController extends Controller
{

    /**
     * Retrieve the user for the given ID.
     *
     * @param  int  $id
     * @return Response
     */
    public function getFilmTitle($title){
        // connect to api + get data
        $data_movie_url = file_get_contents(MOVIES_BASEURL .'search/movie?api_key='. MOVIES_KEY .'&language=fr&query='. ucfirst($title) );
        $data_movie = json_decode($data_movie_url);

        

        // select city name, desc, temp and group
        // $city_name = $data_movie->name;
        // $desc_movie = $data_movie->movie[0]->description;
        // $actual_temp = $data_movie->main->temp;

        // // group will be used to select activity
        // $group_movie = substr($data_movie->movie[0]->id, 0, 1);
        
        return response()->json($data_movie);
    }

}