<?php

namespace App\Http\Controllers;

class TvguideController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(){
        date_default_timezone_set('Europe/Paris');
    }

    public function getTvGuideTonigtByTime(){
    	$data_guide_url = TVGUIDE_BASEURL.''.date("d-m-Y").'.xml';
	    $data_guide = (array) simplexml_load_file($data_guide_url, null, LIBXML_NOCDATA);
    	
    	$time = strtotime(date("G:i:s"));

	    $programs = array();

	    $prev_diff = "";
	    $actual_channel = "";

	    foreach ($data_guide['channel']->item as $em) {
	    	$data = explode(' | ', $em->title);

	    	if ($actual_channel == "") {
	    		$actual_channel = $data[0];
	    	}else{
	    		if ($actual_channel != $data[0]) {
	    			$prev_diff = "";
	    			$program = [ 'channel' => $actual_channel, 'title' => trim($saved_data['title']), 'time' => $saved_data['time'] ];
	    			array_push($programs, $program);
	    			unset($saved_data);
	    			$actual_channel = $data[0];
	    		}
	    	}

			$diff = str_replace("-", "", $time - strtotime($data[1].":00"));

	    	
	    	if ($prev_diff == "") {
				$saved_data = ['title' => $data[2], 'time' => $data[1] ];
				$prev_diff = $diff;
	    	}else{
	    		if ($diff < $prev_diff) {
	    			$saved_data = ['title' => $data[2], 'time' => $data[1] ];
	    			$prev_diff = $diff;
	    		}
	    	
	    	}
	    }

	    return ['id' => '6-1', 'result' => $programs];
    }


    public function getTvGuideTonigt(){
    	$data_guide_url = TVGUIDE_BASEURL.''.date("d-m-Y").'.xml';
	    $data_guide = (array) simplexml_load_file($data_guide_url, null, LIBXML_NOCDATA);
    	
    	$night_time = strtotime("21:00:00");

	    $programs = array();

	    $prev_diff = "";
	    $actual_channel = "";

	    foreach ($data_guide['channel']->item as $em) {
	    	$data = explode(' | ', $em->title);

	    	if (substr($data[1], 0, 2) == "20" || substr($data[1], 0, 2) == "21") {
		    	if ($actual_channel == "") {
		    		$actual_channel = $data[0];
		    	}else{
		    		if ($actual_channel != $data[0]) {
		    			$prev_diff = "";
		    			$program = [ 'channel' => $actual_channel, 'title' => trim($saved_data['title']), 'time' => $saved_data['time'] ];
		    			array_push($programs, $program);
		    			unset($saved_data);
		    			$actual_channel = $data[0];
		    		}
		    	}

				$diff = $night_time - strtotime($data[1].":00");
		    	
		    	if ($prev_diff == "") {
					$saved_data = ['title' => $data[2], 'time' => $data[1] ];
		    	}else{
		    		if ($diff < $prev_diff) {
		    			$saved_data = ['title' => $data[2], 'time' => $data[1] ];
		    			$prev_diff = $diff;
		    		}
		    	}
	    	}

	    }

	    return ['id' => '6-2', 'result' => $programs];





    }

    //
}