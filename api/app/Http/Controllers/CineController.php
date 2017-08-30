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
        return response()->json( $city );
    }

    public function getCineByName($name){
        $name = $this->getCineByKeywords($name)->original['theater'];
        return response()->json( $name );
    }

    public function getMovieByKeywords($keyword)
    {
        // http://www.allocine.fr/_/localization/KEYWORD

        $data_cine_url = file_get_contents('http://essearch.allocine.net/fr/autocomplete?geo2=83093&q='. $keyword);
        $result = json_decode($data_cine_url);

        $sponsored = [];
        $result_final = [];

        foreach ($result as $key => $value) {
            if( isset( $value->ad ) && $value->ad == true ){
                $sponsored[] = [
                    'type' => $value->entitytype,
                    'id' => $value->id,
                    'title' => $value->title1,
                    'thumbnail' => $value->thumbnail
                ];
            }else{
                if( isset($value->thumbnail) ){
                    $result_final[$value->entitytype][] = [
                        'id' => $value->id,
                        'title' => $value->title2,
                        'thumbnail' => str_replace('c_75_100', 'c_100_100', $value->thumbnail)
                    ];
                }

            }
        }

        return response()->json( $result_final );
    }

    public function getShowtimesByIDMovieAndIDNear($movie, $near)
    {
        $data_cine_url = file_get_contents('http://www.allocine.fr/_/showtimes/movie-'. $movie .'/near-'.$near);
        $result = json_decode($data_cine_url);

        $result_final = [];

        $movie_detail = $result->movies->$movie;
        $result_final['movie'] = [
            'id' => $movie_detail->id,
            'title' => $movie_detail->title,
            'image' => 'http://fr.web.img6.acsta.net/r_400_200'. $movie_detail->poster->file_name,
            'note' => round(($movie_detail->social->press_review_rating + $movie_detail->social->user_review_rating)/2, 2), // Moyenne de note Presse + Utilisateur
            'distributor' => $movie_detail->distributor->name,
            'genre' => $movie_detail->genre,
            'year' => date( 'Y', strtotime($movie_detail->releaseDate->date)),
            'date' => date( 'D j M Y', strtotime($movie_detail->releaseDate->date))
        ];

        foreach ($result->theaters as $ktheaters => $vtheaters) {
            $result_final[$vtheaters->id_ac] = [
                    'id' => $vtheaters->id,
                    'name' => $vtheaters->name,
                    'logo' => 'http://fr.web.img6.acsta.net/r_400_200'.$vtheaters->logo,
                    'address' => $vtheaters->address
            ];
        }

        foreach ($result->showtimes as $kshowtimes => $vshowtimes) {


            foreach ($vshowtimes as $kdate => $vdate) {
                foreach ($vdate->$movie['0']->showtimes as $key => $value) {
                    $movieStart = date( 'H', strtotime( $value->movieStart ) ).'h'.date( 'i', strtotime( $value->movieStart ) );
                    $movieEnd = date( 'H', strtotime( $value->movieEnd ) ).'h'.date( 'i', strtotime( $value->movieEnd ) );
                    $duration = date( 'H', (strtotime( $value->movieEnd ) - strtotime( $value->movieStart )) ).'h'.date( 'i', strtotime( $value->movieEnd ) - strtotime( $value->movieStart ) );

                    $result_final[$kshowtimes]['showtimes'][$kdate][] = [
                        'hasBooking' => $value->hasBooking,
                        'urlTicketing' => $value->urlTicketing,
                        'start' =>  $movieStart,
                        'end' => $movieEnd,
                        'duration' => $duration
                    ];
                }
            }

            // $result_final[$kshowtimes]['showtimes'] = [
            //     ''
            // ];
        }

        // var_dump( $result_final );

        return response()->json( $result_final );
    }

}
