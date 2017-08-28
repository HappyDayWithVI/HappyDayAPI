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

        foreach ($data_restaurant->businesses as $restaurant_item) {
            $address = "";
            foreach ($restaurant_item->location->display_address as $el) {
                $address .= $el." ";
            }

            $address = substr($address, 0, -1);

            $restaurant = ['name' => $restaurant_item->name, 'is_closed' => $restaurant_item->is_closed, 'image' => $restaurant_item->image_url, 'rating' => ($restaurant_item->rating*2), 'price' => $restaurant_item->price, 'adress' => $address];
            array_push($restaurants, $restaurant);
        }       



        return ['id' => "5-1", 'result' => $restaurants];
    }
}