<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;

class TvshowController extends Controller{

    public $token;

    public function __construct(){
        // GET TOKEN FROM TVSHOW API
        $client = new \GuzzleHttp\Client();

        $req = $client->request('POST', 'https://api.thetvdb.com/login', [
            'json' => ['apikey' => '17CFE9A03C551B87']
        ]);
        $res = json_decode($req->getBody());

        $this->token = $res->token;
    }


    public function getTvshowByName($name){
        $client = new \GuzzleHttp\Client();

        $request = $client->request('GET', 'https://api.thetvdb.com/search/series?name='.$name, [
            'headers' => [
                'Authorization'     => 'Bearer '.$this->token,
                'Accept-Language'   => 'fr'
            ]
        ]);
                
        $res = json_decode($request->getBody()->getContents());

        $series_search = array();

        foreach ($res->data as $value) {
            $tvshow_name = $value->seriesName;
            $tvshow_channel = $value->network;
            $tvshow_firsttime = $value->firstAired;
            $tvshow_desc = $value->overview;
            $tvshow_status = $value->status;           

            $tvshow = ["name" => $tvshow_name, "desc" => $tvshow_desc, "channel" => $tvshow_channel, "first time" => $tvshow_firsttime, "status" => $tvshow_status];

            array_push($series_search, $tvshow);
        }

        return ['id' => 3, 'result' => [$series_search]];

    }


    public function getTvshowByGenre($genre){

        // in $this->endpoint I have https://172.17.8.111:443
        $client = new \GuzzleHttp\Client();
        // $client->setDefaultOption('headers/Authorization', 'Bearer '.$this->token);

        // $request = $client->get('https://api.thetvdb.com/search/series?name=penny');


        $request = $client->request('GET', 'https://api.thetvdb.com/search/series?name=penny', [
            'headers' => [
                'Authorization'     => 'Bearer '.$this->token,
                'Accept-Language'   => 'fr'
            ]
        ]);
                
        var_dump($request->getBody()->getContents());


        // if (strpos($genre, ",") != false) {
        //     $genres = explode(',', $genre);
        // }else{
        //     $genres = $genre;
        // }

        // echo  "Regarder des séries ? <br>";

        // foreach ($genres as $val) {
        //     echo $val;
        //     echo "<br>";
        // }

    }

    
}