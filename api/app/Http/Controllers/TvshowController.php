<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;

class TvshowController extends Controller{

    public $token;

    public function __construct(){
        $this->token = "bonjour";


        $client = new \GuzzleHttp\Client();
        $res = $client->request('GET', 'https://api.github.com/user', [
            'auth' => ['user', 'pass']
        ]);

        echo $res->getStatusCode();
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
        }

        echo  "Regarder des séries ? <br>";

        foreach ($genres as $val) {
            echo $val;
            echo "<br>";
        }
    }

    
}