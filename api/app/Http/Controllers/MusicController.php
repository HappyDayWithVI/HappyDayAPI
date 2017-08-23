<?php

namespace App\Http\Controllers;

class MusicController extends Controller
{

  public function __construct(){
  }

  /*public function getGeneralToken(){
    $token = file_get_contents("https://accounts.spotify.com/authorize/?client_id=5fe01282e44241328a84e7c5cc169165&response_type=code&redirect_uri=https%3A%2F%2Fexample.com%2Fcallback&scope=user-read-private%20user-read-email");
  }*/

  public function getNewRealease($country, $limit)
  {
      $token = 'BQDzBsAeFYefDnPyS4T3Mv6zaRxrgusLbfs-ps2pHREh5ncFqKvROK-15jPE-r5TjGJIHmXAh0k_uElpgJ4BwrIDzfzj0sYW85y7O4vMIH7mz69sXGxvnHK93-9IJacpygvTZVw_WF9mME9eicOWc4D7tB3xi5jw9VVuEqg2qXfw_WrEOd8SAH3WkX3XWgp0fLvkI3oKkODXjN9PGodae6M0oQETtnELaWOQccjT1Sb-EI0kKs6sp9awxHzir4w_LALEn7wb7YCQjeX4nNHEZU_266AmiriZ5Iyp1yiIt-a2TraAFIW4pYXgVF9Xg5b-qA';
      $curl = curl_init();
      $url = "https://api.spotify.com/v1/browse/new-releases?country=".$country."&limit=".$limit;
      curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
      curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
      curl_setopt($curl,CURLOPT_URL, $url);
      $header = array('Accept: application/json', 'Content-Type: application/json','Authorization: Bearer '.$token);
      curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
      $result = json_decode(curl_exec($curl),true);
      print_r($result);
      curl_close($curl);
      /*$data_artists = array();
      $data_image = array();*/
      foreach($result as $key => $albums)
      {

        /*foreach($albums['items'] as $k2 => $artists)
        {
          $data_artists[$artists['id']]=$artists['artists'][0];
        }*/
        foreach($albums['items'] as $k3 => $element)
        {
          var_dump($element['name']);
          var_dump($element['id']);
          var_dump($element['uri']);
          var_dump($element['artists'][0]);
          var_dump($element['external_urls']['spotify']);
          $data_newreleases[] = array('album_id' => $element['id'],
                                      'album_name' => $element['name'],
                                      'album_url' => $element['external_urls']['spotify'],
                                      'album_uri' => $element['uri']
                                    );
        }
      }
      //var_dump($data_artists);
      /*foreach ($result as $key => $albums) {
        foreach ($albums['items'] as $k2 => $artists) {
            $data_artists[$artists['id']] = $artists['artists'][0];
        }
      }*/

      var_dump($data_newreleases);

  }
}
