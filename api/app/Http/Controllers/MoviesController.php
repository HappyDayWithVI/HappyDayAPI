<?php

namespace App\Http\Controllers;

use App\Movies;

class MoviesController extends Controller
{

    public function getGenres($id = false){
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

    public function getMovies(){
        $data_movie_url = file_get_contents(MOVIES_BASEURL .'discover/movie?api_key='. MOVIES_KEY .'&sort_by=popularity.desc&include_video=true&year=2018&language='. LANG_CODE);
        $data_movie = json_decode($data_movie_url);

        $data = $data_movie->results;

        $m = 1;
        $movies = array();

        foreach ($data as $key => $value) {

            if ($value->overview != "" && $value->adult == false) {            
                $movie = ["id" => $m, "name" => $value->title, "image" => "http://image.tmdb.org/t/p/w185".$value->poster_path, "resume" => $value->overview];

                if( empty($value->release_date) || !preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $value->release_date) ){
                    $value->release_date = 'INCONNU';
                }else{
                    $value->release_date = $this->getDate($value->release_date);
                }

                array_push($movies, $movie);
                $m++;
            }
        }

        return ['id' => '3-1', 'result' => ["movies" => $movies]]; 
    }

    public function getActorByMovieName($title){
        $data_to_get_id_url = file_get_contents(MOVIES_BASEURL .'search/movie?api_key='. MOVIES_KEY .'&language='. LANG_CODE .'&query='. ucfirst($title));
        $data_to_get_id_url = json_decode($data_to_get_id_url);
        $id = $data_to_get_id_url->results[0]->id;
        
        $id = urldecode( $id );


        $data_movie_url = file_get_contents(MOVIES_BASEURL .'movie/'. $id .'/credits?api_key='. MOVIES_KEY .'&language='. LANG_CODE);
        $data_movie = json_decode($data_movie_url);


        $characters = array();

        $c = 1;


       for ($i=0; $i < count($data_movie->cast); $i++) {


           $character = ["id" => $c, "name_actor" => $data_movie->cast[$i]->name, "name_character" => $data_movie->cast[$i]->character, "image" => "https://image.tmdb.org/t/p/w300_and_h450_bestv2/".$data_movie->cast[$i]->profile_path];

           array_push($characters, $character);
           $c++;
       }


       return ['id' => '3-2', 'result' => ["movie_data" => ["movie" => $data_to_get_id_url->results[0]->title, "image" => "https://image.tmdb.org/t/p/w780/".$data_to_get_id_url->results[0]->backdrop_path], "character_data" => $characters]]; 

        
    }

    public function getMovieByActor($name){
        $data_actor_url = file_get_contents(MOVIES_BASEURL .'search/person?api_key='. MOVIES_KEY .'&language='. LANG_CODE .'&query='. ucfirst($name).'&include_adult=false');
        $data_actor = json_decode($data_actor_url);

        $id_actor = $data_actor->results[0]->id;

        $data_movie_of_actor_url = file_get_contents(MOVIES_BASEURL .'person/'.$id_actor.'/movie_credits?api_key='. MOVIES_KEY .'&language='. LANG_CODE.'&include_adult=false');
        $data_movie_of_actor = json_decode($data_movie_of_actor_url);

        $roles = array();
        $m = 1;

        for ($i=0; $i < count($data_movie_of_actor->cast); $i++) {

            $role = ["id" => $m, "movie" => $data_movie_of_actor->cast[$i]->title, "character" => $data_movie_of_actor->cast[$i]->character, "image" => "https://image.tmdb.org/t/p/w780/".$data_movie_of_actor->cast[$i]->poster_path];

            array_push($roles, $role);
            $m++;
        }

        return ['id' => '3-4', 'result' => ["actor" => str_replace("%20", " ", $name), "role_data" => $roles]];
    }


    public function getMovieByTitle($title){
        // connect to api + get data
        $data_movie_url = file_get_contents(MOVIES_BASEURL .'search/movie?api_key='. MOVIES_KEY .'&language='. LANG_CODE .'&query='. ucfirst($title) );
        $data_movie = json_decode($data_movie_url);

        $data = $data_movie->results;
        $result = '';
        $movies = array();

        $m = 1;

        foreach ($data as $key => $value) {

            if ($value->overview != "" && $value->adult == false) {            

                $movie = ["id" => $m, "name" => $value->title, "image" => "http://image.tmdb.org/t/p/w185".$value->poster_path, "resume" => $value->overview];

                if( empty($value->release_date) || !preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $value->release_date) ){
                    $value->release_date = 'INCONNU';
                }else{
                    $value->release_date = $this->getDate($value->release_date);
                }

                array_push($movies, $movie);
                $m++;
            }
        }

        // return response()->json($data_movie);
        return ['id' => '3-2', 'result' => [$movies]];
    }

    public function getMoviesByGenre($genre){
        // echo $genre;

        // echo $this->getGenres();

        $data_genre_url = file_get_contents(MOVIES_BASEURL .'genre/movie/list?api_key='. MOVIES_KEY .'&language='. LANG_CODE);
        $data_genre = json_decode($data_genre_url);

        foreach ($data_genre->genres as $possible_genre) {
            if (strtolower($possible_genre->name) == $genre) {
                $id = $possible_genre->id;
            }
        }

        $data_movies_url = file_get_contents(MOVIES_BASEURL .'genre/'.$id.'/movies?api_key='. MOVIES_KEY .'&language='. LANG_CODE.'&language=fr-FR&include_adult=false&sort_by=created_at.asc');

        $data_movies = json_decode($data_movies_url);
        // echo "<pre>";
        // var_dump($data_movies);
        // echo "</pre>";

        $movies = array();
        $m = 1;

        foreach ($data_movies->results as $key => $value) {

            // var_dump($value);

            if ($value->overview != "" && $value->adult == false) {            

                $movie = ["id" => $m, "name" => $value->title, "image" => "http://image.tmdb.org/t/p/w185".$value->poster_path, "resume" => $value->overview];

                if( empty($value->release_date) || !preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $value->release_date) ){
                    $value->release_date = 'INCONNU';
                }else{
                    $value->release_date = $this->getDate($value->release_date);
                }

                array_push($movies, $movie);
                $m++;
            }
        }

        return ['id' => '3-5', 'result' => [$movies]];

    }

    private function getDate($date_string){
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
