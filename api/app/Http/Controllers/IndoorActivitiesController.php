<?php

namespace App\Http\Controllers;

class IndoorActivitiesController extends Controller {
    private $opts;
    private $context;

    public function __construct(){
        $this->opts = [
            "http" => [ "header" => "Authorization: " .MUSEUM_KEY ]
        ];

        $this->context = stream_context_create($this->opts);
    }

    /**
     * Retrieve the user for the given ID.
     *
     * @param  int  $id
     * @return Response
     */
    public function getMuseumByCity($city){
        $data_museum_url = file_get_contents(MUSEUM_BASEURL.'&sort_by=rating&location='.$city.'&locale='.LANG_CODE, false, $this->context);
        $data_museum = json_decode($data_museum_url);

        // var_dump($data_museum);

        $museums = array();

        foreach ($data_museum->businesses as $museum) {
            $address = "";
            foreach ($museum->location->display_address as $el) {
                $address .= $el." ";
            }
            $address = substr($address, 0, -1);


            array_push($museums, ['name' => $museum->name, 'image' => $museum->image_url, 'is_closed' => $museum->is_closed, "rating" => ($museum->rating*2), "adresse" => $address]);
        }

        

        return ['id' => "8-1", 'result' => $museums];
    }
}