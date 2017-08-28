<?php

namespace App\Http\Controllers;

class RestaurantController extends Controller {
    private $opts;
    private $context;

    public function __construct(){
        $this->opts = [
            "http" => [ "header" => "Authorization: " .RESTAURANT_KEY ]
        ];

        $this->context = stream_context_create($this->opts);
    }

    /**
     * Retrieve the user for the given ID.
     *
     * @param  int  $id
     * @return Response
     */
    public function getRestaurantByName($name, $city){
        $data_restaurant_url = file_get_contents(RESTAURANT_BASEURL.'businesses/search?term='.$name.'&location='.$city.'&locale='.LANG_CODE, false, $this->context);
        $data_restaurant = json_decode($data_restaurant_url);

        $restaurants = array();

        $i = 1;

        foreach ($data_restaurant->businesses as $restaurant_item) {

            if ($i == 1) {
                var_dump($restaurant_item);
            }

            $restaurant = ['name' => $restaurant_item->name, 'image' => $restaurant_item->image_url];
            array_push($restaurants, $restaurant);
            $i++;
        }       



        return ['id' => "5-1", 'result' => $restaurants];
    }
}