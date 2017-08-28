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

            $t = 1;

            $data_tvshow_url = file_get_contents(TVSHOW_BASEURL.'?key='.TVSHOW_KEY.'&genre='.$genres);

            $data_tvshow = json_decode($data_tvshow_url);

            $shows_by_genre = array();

            for ($i=0; $i < count($data_tvshow); $i++) {

                $details = ["channel" => $data_tvshow[$i]->channel, "runtime" => $data_tvshow[$i]->runtime, "season" => $data_tvshow[$i]->season, "episode" => $data_tvshow[$i]->episode, "rating" => $data_tvshow[$i]->rating, "status" => $data_tvshow[$i]->status ];

                $show = ["id" => $t, "name" => $data_tvshow[$i]->name, "resume" => $data_tvshow[$i]->resume, "image" => $data_tvshow[$i]->image, "details" => $details];

                array_push($shows_by_genre, $show);
                $t++;
            }
            
            return ['id' => '2-1', 'result' => ['genre' => $genre, 'shows' => $shows_by_genre]];
        }
    }

    public function getTvshowByName($nameSearch){
        $nameSearch = str_replace("+", "%20", $nameSearch);
        $data_tvshow_url = file_get_contents(TVSHOW_BASEURL.'?key='.TVSHOW_KEY.'&serie='.$nameSearch);

        $t = 1;
        $data_tvshow = json_decode($data_tvshow_url);

        // var_dump($data_tvshow);

        $shows_by_name = array();

        for ($i=0; $i < count($data_tvshow); $i++) {

            $details = ["channel" => $data_tvshow[$i]->channel, "runtime" => $data_tvshow[$i]->runtime, "season" => $data_tvshow[$i]->season, "episode" => $data_tvshow[$i]->episode, "rating" => $data_tvshow[$i]->rating, "status" => $data_tvshow[$i]->status ];

            $show = ["id" => $t, "name" => $data_tvshow[$i]->name, "resume" => $data_tvshow[$i]->resume, "image" => $data_tvshow[$i]->image, "details" => $details];

            array_push($shows_by_name, $show);
            $t++;
        }
        
        return ['id' => '2-2', 'result' => ['name' => str_replace("%20", " ", $nameSearch), 'shows' => $shows_by_name]];
    }

    public function getCharacterOfTvshowByName($name){
        $name = str_replace("+", "%20", $name);

        $c = 1;

        $data_tvshow_url = file_get_contents(TVSHOW_BASEURL.'?key='.TVSHOW_KEY.'&?personnage='.$name);

        $data_tvshow = json_decode($data_tvshow_url);

        $tvshow_name = $data_tvshow[0]->serie_info->name;
        $tvshow_image = $data_tvshow[0]->serie_info->image;

        $characters = array();

        for ($i=1; $i < count($data_tvshow); $i++) {

            $character = ["id" => $c, "name_actor" => $data_tvshow[$i]->name_actor, "name_character" => $data_tvshow[$i]->name_character, "image" => $data_tvshow[$i]->image];

            array_push($characters, $character);
            $c++;
        }


        return ['id' => '2-3', 'result' => ["serie_data" => ["serie" => $tvshow_name, "image" => $tvshow_image], "character_data" => $characters]];
    }

    public function getTvshowByActor($name){
        $name = str_replace("+", "%20", $name);

        $a = 1;

        $data_tvshow_url = file_get_contents(TVSHOW_BASEURL.'?key='.TVSHOW_KEY.'&acteur='.$name);

        $data_tvshow = json_decode($data_tvshow_url);

//         var_dump($data_tvshow);

        $actor_name = ucwords($data_tvshow[0]);

        $tmp = array_shift($data_tvshow);

        $roles = array();

        for ($i=0; $i < count($data_tvshow); $i++) {
            $role = ["id" => $a, "tvshow" => $data_tvshow[$i]->show, "character" => $data_tvshow[$i]->role, "image" => $data_tvshow[$i]->image];

            array_push($roles, $role);
            $a++;
        }

        return ['id' => '2-4', 'result' => ["actor" => $actor_name, "role_data" => $roles]];
    }

    
}