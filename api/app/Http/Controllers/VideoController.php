<?php

namespace App\Http\Controllers;

use Google_Client; 
use Google_Service_YouTube;

class VideoController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function getVideo(){
        // https://www.googleapis.com/youtube/v3/videos?chart=mostPopular&regionCode=FR&part=snippet,contentDetails,statistics&videoCategoryId=23&key=AIzaSyAIcMCymINb4Lagmc7CEnSAVSXGXp2KW3I

    }

    //
}
