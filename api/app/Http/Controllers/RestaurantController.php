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
                // var_dump($restaurant_item);
            }

            $address = "";
            foreach ($restaurant_item->location->display_address as $el) {
                $address .= $el." ";
            }

            $categories = "";
            foreach ($restaurant_item->categories as $category) {
                $categories .= $category->title." / ";
            }

            $address = substr($address, 0, -1);
            $categories = substr($categories, 0, -3);

            if (!isset($restaurant_item->price)) {
                $restaurant_item->price = "";
            }

            $restaurant = ['name' => $restaurant_item->name, 'is_closed' => $restaurant_item->is_closed, 'image' => $restaurant_item->image_url, 'rating' => ($restaurant_item->rating*2), 'price' => $restaurant_item->price, 'adress' => $address, 'categories' => $categories];
            array_push($restaurants, $restaurant);
            $i++;
        }


        return ['id' => "5-1", 'result' => $restaurants];
    }

    public function getBestRestaurantByCity($city){
        $data_restaurant_url = file_get_contents(RESTAURANT_BASEURL.'businesses/search?term=food&location='.$city.'&sort=2&locale='.LANG_CODE, false, $this->context);
        $data_restaurant = json_decode($data_restaurant_url);

        $restaurants = array();

        $i = 1;

        foreach ($data_restaurant->businesses as $restaurant_item) {

            if ($i == 1) {
            }

            $address = "";
            foreach ($restaurant_item->location->display_address as $el) {
                $address .= $el." ";
            }

            $categories = "";
            foreach ($restaurant_item->categories as $category) {
                $categories .= $category->title." / ";
            }

            $address = substr($address, 0, -1);
            $categories = substr($categories, 0, -3);

            if (!isset($restaurant_item->price)) {
                $restaurant_item->price = "";
            }

            $restaurant = ['name' => $restaurant_item->name, 'is_closed' => $restaurant_item->is_closed, 'image' => $restaurant_item->image_url, 'rating' => ($restaurant_item->rating*2), 'price' => $restaurant_item->price, 'adress' => $address, 'categories' => $categories];
            array_push($restaurants, $restaurant);
            $i++;
        }       



        return ['id' => "5-2", 'result' => $restaurants];
    }
}