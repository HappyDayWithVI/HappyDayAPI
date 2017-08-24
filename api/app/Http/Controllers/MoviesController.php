<?php

namespace App\Http\Controllers;

use App\Movies;

class MoviesController extends Controller
{

    public function getGenres($id = false)
    {
         $data_movie_url = file_get_contents(MOVIES_BASEURL .'genre/movie/list?api_key=ca23af59f66f1506ef3c055712aa6341&language='. LANG_CODE);
         $data_movie = json_decode($data_movie_url);

         $data = array();

         foreach ($data_movie->genres as $key => $value) {
             $data[ $value->id ] = $value->name;
         }

         if( $id && isset( $data[$id] ) ){
            return response()->json( array('name' => $data[$id], 'id' => $id) );
         }else{
             return response()->json( $data );
         }

    }

    public function getMovies()
    {
        $data_movie_url = file_get_contents(MOVIES_BASEURL .'discover/movie?api_key='. MOVIES_KEY .'&sort_by=popularity.desc&include_video=true&year=2018&language='. LANG_CODE);
        $data_movie = json_decode($data_movie_url);

        $data = $data_movie->results;

        foreach ($data as $key => $value) {
            if( empty($value->release_date) || !preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $value->release_date) ){
                $value->release_date = 'INCONNU';
            }else{
                $value->release_date = $this->getDate($value->release_date);
            }
        }

        return response()->json($data_movie);
    }

    public function getActorsByID($id){
        $id = urldecode( $id );
        $data_movie_url = file_get_contents(MOVIES_BASEURL .'movie/'. $id .'/credits?api_key='. MOVIES_KEY .'&language='. LANG_CODE);
        $data_movie = json_decode($data_movie_url);

        return response()->json($data_movie->cast);
    }


    /**
     * Retrieve the user for the given ID.
     *
     * @param  int  $id
     * @return Response
     */
    public function getMovieByTitle($title){
        // connect to api + get data
        $data_movie_url = file_get_contents(MOVIES_BASEURL .'search/movie?api_key='. MOVIES_KEY .'&language='. LANG_CODE .'&query='. ucfirst($title) );
        $data_movie = json_decode($data_movie_url);



        // select city name, desc, temp and group
        // $city_name = $data_movie->name;
        // $desc_movie = $data_movie->movie[0]->description;
        // $actual_temp = $data_movie->main->temp;

        // // group will be used to select activity
        // $group_movie = substr($data_movie->movie[0]->id, 0, 1);

        $data = $data_movie->results;
        $result = '';

        foreach ($data as $key => $value) {
            if( empty($value->release_date) || !preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $value->release_date) ){
                $value->release_date = 'INCONNU';
            }else{
                $value->release_date = $this->getDate($value->release_date);
            }
        }

        // https://api.themoviedb.org/3/genre/movie/list?api_key=ca23af59f66f1506ef3c055712aa6341&language=fr

        // var_dump( $data, $data_movie, $result );
        return response()->json($data_movie);
    }

    private function getDate($date_string)
    {
        $date = explode('-', $date_string);
        $month = strtolower(date('F', strtotime($date_string)));
        switch ($month) {
            case 'january':
                $month = 'janvier';
                break;

            case 'february':
                $month = 'février';
                break;

            case 'march':
                $month = 'mars';
                break;

            case 'april':
                $month = 'avril';
                break;

            case 'may':
                $month = 'mai';
                break;

            case 'june':
                $month = 'juin';
                break;

            case 'july':
                $month = 'juillet';
                break;

            case 'august':
                $month = 'août';
                break;

            case 'september':
                $month = 'septembre';
                break;

            case 'october':
                $month = 'octobre';
                break;

            case 'november':
                $month = 'novembre';
                break;

            case 'december':
                $month = 'décembre';
                break;

            default:
                $month = 'janvier';
                break;
        }
        return $date[2] .' '. ucfirst($month) .' '. $date[0];
    }

}
