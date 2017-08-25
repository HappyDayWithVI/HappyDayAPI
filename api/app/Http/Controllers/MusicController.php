<?php

namespace App\Http\Controllers;

class MusicController extends Controller
{

  public function __construct(){
  }


  public function getGeneralToken()
  {
    $session = new \SpotifyWebAPI\Session(
      'b31ce69c7dea4451a591ceafbcc72d19',
      '6a2c48a4f79446f68aecde6b5f006f4a'
    );

    $api = new \SpotifyWebAPI\SpotifyWebAPI();
    $session->requestCredentialsToken();
    $accessToken = $session->getAccessToken();

    return $accessToken;
  }

  private function getAlbumTracks($id_album)
  {
    $token = $this->getGeneralToken();
    $curl = curl_init();
    $url = MUSIC_URL_API."v1/albums/".$id_album."/tracks";
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl,CURLOPT_URL, $url);
    $header = array('Accept: application/json', 'Content-Type: application/json','Authorization: Bearer '.$token);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $result = json_decode(curl_exec($curl),true);
    //var_dump($result);
    curl_close($curl);
    $data_tracks = array();
    foreach($result as $key => $items)
    {
      if (is_array($items))
      {
        foreach($items as $k2 => $element)
        {
          $data_tracks[]=array('track_id'=> $element['id'],
                              'track_name'=> $element['name'],
                              'track_url'=> $element['external_urls']['spotify'],
                              'track_mp3'=> $element['preview_url'],
                              'track_uri'=> $element['uri'],
                              'track_duration'=>$element['duration_ms'],
                              'track_album_artist_id'=> $element['artists'][0]['id'],
                              'track_artist'=> $element['artists'][0]['name'],
                              'track_artist_url' => $element['artists'][0]['external_urls']['spotify'],
                              'track_artist_uri' => $element['artists'][0]['uri']

                            );
        }
      }
    }
    return($data_tracks);
  }


  public function getNewRealease($country, $limit)
  {
      $token = $this->getGeneralToken();
      $curl = curl_init();
      $url = MUSIC_URL_API."v1/browse/new-releases?country=".$country."&limit=".$limit;
      curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
      curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
      curl_setopt($curl,CURLOPT_URL, $url);
      $header = array('Accept: application/json', 'Content-Type: application/json','Authorization: Bearer '.$token);
      curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
      $result = json_decode(curl_exec($curl),true);
      print_r($result);
      curl_close($curl);
      foreach($result as $key => $albums)
      {
        foreach($albums['items'] as $k2 => $element)
        {
          $data_newreleases[] = array('album_id' => $element['id'],
                                      'album_name' => $element['name'],
                                      'album_url' => $element['external_urls']['spotify'],
                                      'album_uri' => $element['uri'],
                                      'album_big_picture' => $element['images'][0]['url'],
                                      'album_picture' => $element['images'][1]['url'],
                                      'album_mini' => $element['images'][2]['url'],
                                      'album_artist_id'=> $element['artists'][0]['id'],
                                      'album_artist'=> $element['artists'][0]['name'],
                                      'album_artist_url' => $element['artists'][0]['external_urls']['spotify'],
                                      'album_artist_uri' => $element['artists'][0]['uri']
                                    );
        }
      }
      var_dump($data_newreleases);
      return $data_newreleases;
  }

  public function getSearch($type, $elementsought)
  {
    $token = $this->getGeneralToken();
    $curl = curl_init();
    $url = MUSIC_URL_API."v1/search?q=".$elementsought."&type=".$type."&limit=1";
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl,CURLOPT_URL, $url);
    $header = array('Accept: application/json', 'Content-Type: application/json','Authorization: Bearer '.$token);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $result = json_decode(curl_exec($curl),true);
    //var_dump($result);
    curl_close($curl);
    $data_sought=array();
    if($type == "album")
    {
      foreach($result as $key => $albums)
      {
        foreach($albums['items'] as $k2 => $element)
        {
          //$data_tracks = getAlbumTracks($element['id']);
          $data_sought[] = array('album_id' => $element['id'],
                                  'album_name' => $element['name'],
                                  'album_url' => $element['external_urls']['spotify'],
                                  'album_uri' => $element['uri'],
                                  'album_big_picture' => $element['images'][0]['url'],
                                  'album_picture' => $element['images'][1]['url'],
                                  'album_mini' => $element['images'][2]['url'],
                                  'album_artist_id'=> $element['artists'][0]['id'],
                                  'album_artist'=> $element['artists'][0]['name'],
                                  'album_artist_url' => $element['artists'][0]['external_urls']['spotify'],
                                  'album_artist_uri' => $element['artists'][0]['uri'],
                                  'album_tracks'=> self::getAlbumTracks($element['id'])
                                );
        }
      }
    }
    else if($type == "artist")
    {
      $i = 0;
      foreach($result as $key => $artists)
      {
        foreach($artists['items'] as $k2 => $element)
        {

          foreach($element['genres'] as $k3 => $genre)
          {
            $i = $i + 1;
            $index = "genre_".$i;
            $data_genre[] = array($index => $genre);
          }
          $data_sought[]=array('artist_id' => $element['id'],
                                'artist_name' => $element['name'],
                                'artist_url' => $element['external_urls']['spotify'],
                                'artist_uri' => $element['uri'],
                                'artist_big_picture' => $element['images'][0]['url'],
                                'artist_picture' => $element['images'][1]['url'],
                                'artist_little' => $element['images'][2]['url'],
                                'artist_mini'=> $element['images'][3]['url'],
                                'artist_genres'=> $data_genre
                              );
        }
      }
    }
    else if($type == "track")
    {
        foreach($result as $key => $tracks)
        {
            foreach($tracks['items'] as $k2 => $element)
            {

              $data_sought[]=array('track_id'=> $element['id'],
                                  'track_name'=> $element['name'],
                                  'track_url'=> $element['external_urls']['spotify'],
                                  'track_mp3'=> $element['preview_url'],
                                  'track_uri'=> $element['uri'],
                                  'track_duration'=>$element['duration_ms'],
                                  'track_album_id'=> $element['album']['id'],
                                  'track_album_name'=> $element['album']['name'],
                                  'track_album_link'=> $element['album']['external_urls']['spotify'],
                                  'track_album_big_picture' => $element['album']['images'][0]['url'],
                                  'track_album_picture' => $element['album']['images'][1]['url'],
                                  'track_album_mini' => $element['album']['images'][2]['url'],
                                  'track_album_artist_id'=> $element['album']['artists'][0]['id'],
                                  'track_album_artist'=> $element['album']['artists'][0]['name'],
                                  'track_album_artist_url' => $element['album']['artists'][0]['external_urls']['spotify'],
                                  'track_album_artist_uri' => $element['album']['artists'][0]['uri'],
                                  'track_album_artist_id'=> $element['artists'][0]['id'],
                                  'track_artist'=> $element['artists'][0]['name'],
                                  'track_artist_url' => $element['artists'][0]['external_urls']['spotify'],
                                  'track_artist_uri' => $element['artists'][0]['uri']

                                );
            }

        }
    }
    else if($type == "playlist")
    {
        $data_sought[]=$result;
    }
    var_dump($data_sought[0]['album_tracks'][0]);
    //return $data_sought;
  }

}
