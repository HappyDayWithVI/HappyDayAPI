<?php

namespace App\Http\Controllers;

class MusicController extends Controller
{

  public function __construct(){
  }


  public function getGeneralToken()
  {
    $session = new \SpotifyWebAPI\Session(
      MUSIC_CLIENT_ID,
      MUSIC_CLIENT_SECRET
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
    $data_tracks = json_decode(curl_exec($curl),true);
    curl_close($curl);
    $result = array();
    foreach($data_tracks as $key => $items)
    {
      if (is_array($items))
      {
        foreach($items as $k2 => $element)
        {
          $result[]=array('id'=> $element['id'],
                          'name'=> $element['name'],
                          'url'=> $element['external_urls']['spotify'],
                          'track'=> $element['preview_url'],
                          'uri'=> $element['uri'],
                          'duration'=>$element['duration_ms'],
                          'artist_id'=> $element['artists'][0]['id'],
                          'artist_name'=> $element['artists'][0]['name'],
                          'artist_url' => $element['artists'][0]['external_urls']['spotify'],
                          'artist_uri' => $element['artists'][0]['uri']);
        }
      }
    }
    return($result);
  }


  private function getArtistAlbum($id_artist)
  {
    $token = $this->getGeneralToken();
    $curl = curl_init();
    $url = MUSIC_URL_API."v1/artists/".$id_artist."/albums";
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl,CURLOPT_URL, $url);
    $header = array('Accept: application/json', 'Content-Type: application/json','Authorization: Bearer '.$token);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $data_albums = json_decode(curl_exec($curl),true);
    curl_close($curl);
    $artist_albums = array();
    foreach($data_albums as $key => $items)
    {
      if (is_array($items))
      {
        foreach($items as $k2 => $element)
        {
            $album[]=array('id' => $element['id'],
                          'name' => $element['name'],
                          'url' => $element['external_urls']['spotify'],
                          'uri' => $element['uri'],
                          'big_picture' => $element['images'][0]['url'],
                          'picture' => $element['images'][1]['url'],
                          'mini_picture' => $element['images'][2]['url']);
            array_push($artist_albums, $album);
        }

      }
    }
    return($artist_albums);
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
      $data_newreleases = json_decode(curl_exec($curl),true);
      curl_close($curl);
      $result = array();
      foreach($data_newreleases as $key => $albums)
      {
        foreach($albums['items'] as $k2 => $element)
        {
          $result[] = array('id' => $element['id'],
                            'name' => $element['name'],
                            'url' => $element['external_urls']['spotify'],
                            'uri' => $element['uri'],
                            'big_picture' => $element['images'][0]['url'],
                            'picture' => $element['images'][1]['url'],
                            'mini' => $element['images'][2]['url'],
                            'artist_id'=> $element['artists'][0]['id'],
                            'artist_name'=> $element['artists'][0]['name'],
                            'artist_url' => $element['artists'][0]['external_urls']['spotify'],
                            'artist_uri' => $element['artists'][0]['uri']);
        }
      }
      return($result);
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
    $data_sought = json_decode(curl_exec($curl),true);
    curl_close($curl);
    $result = array();
    if($type == "album")
    {
      foreach($data_sought as $key => $albums)
      {
        foreach($albums['items'] as $k2 => $element)
        {
          $result[] = array('id' => $element['id'],
                                  'name' => $element['name'],
                                  'url' => $element['external_urls']['spotify'],
                                  'uri' => $element['uri'],
                                  'big_picture' => $element['images'][0]['url'],
                                  'picture' => $element['images'][1]['url'],
                                  'album_mini' => $element['images'][2]['url'],
                                  'artist_id'=> $element['artists'][0]['id'],
                                  'artist_name'=> $element['artists'][0]['name'],
                                  'artist_url' => $element['artists'][0]['external_urls']['spotify'],
                                  'artist_uri' => $element['artists'][0]['uri'],
                                  'tracks'=> self::getAlbumTracks($element['id']));
        }
      }
    }
    else if($type == "artist")
    {
      $i = 0;
      //$artist = array();
      //$genres = array();
      foreach($data_sought as $key => $artists)
      {
        foreach($artists['items'] as $k2 => $element)
        {

          foreach($element['genres'] as $k3 => $genre)
          {
            $i = $i + 1;
            $index = "genre_".$i;
            $list_genres = array($index => $genre);
            //array_push($genres, $genre);
          }
          //$albums = self::getArtistAlbum($element['id']);
          $result[]=array('name' => $element['name'],
                              'url' => $element['external_urls']['spotify'],
                              'uri' => $element['uri'],
                              'big_picture' => $element['images'][0]['url'],
                              'picture' => $element['images'][1]['url'],
                              'little_picture' => $element['images'][2]['url'],
                              'mini_picture'=> $element['images'][3]['url'],
                              'genres'=> $list_genres,
                              'albums'=> self::getArtistAlbum($element['id']));
          //array_push($artist, $artist_info);

          //$result = ['id'=>$element['id'], 'result'=>['artist'=>$artist, 'genre'=>$genres, 'albums'=>$albums]];
        }
      }
    }
    else if($type == "track")
    {
        foreach($data_sought as $key => $tracks)
        {
            foreach($tracks['items'] as $k2 => $element)
            {

              $result[]=array('id'=> $element['id'],
                              'name'=> $element['name'],
                              'url'=> $element['external_urls']['spotify'],
                              'track'=> $element['preview_url'],
                              'uri'=> $element['uri'],
                              'duration'=>$element['duration_ms'],
                              'album_id'=> $element['album']['id'],
                              'album_name'=> $element['album']['name'],
                              'album_url'=> $element['album']['external_urls']['spotify'],
                              'album_big_picture' => $element['album']['images'][0]['url'],
                              'album_picture' => $element['album']['images'][1]['url'],
                              'album_mini' => $element['album']['images'][2]['url'],
                              'album_artist_id'=> $element['album']['artists'][0]['id'],
                              'album_artist'=> $element['album']['artists'][0]['name'],
                              'album_artist_url' => $element['album']['artists'][0]['external_urls']['spotify'],
                              'album_artist_uri' => $element['album']['artists'][0]['uri'],
                              'album_artist_id'=> $element['artists'][0]['id'],
                              'track_artist_name'=> $element['artists'][0]['name'],
                              'track_artist_url' => $element['artists'][0]['external_urls']['spotify'],
                              'track_artist_uri' => $element['artists'][0]['uri']);
            }

        }
    }
    else if($type == "playlist")
    {
        foreach($data_sought as $key => $playlist)
        {
          foreach($playlist['items'] as $k2 => $element)
          {
            $result[]=array('id'=> $element['id'],
                            'name'=> $element['name'],
                            'url'=> $element['external_urls']['spotify'],
                            'uri'=> $element['uri'],
                            'image'=> $element['images'][0]['url'],
                            'owner_id'=> $element['owner']['id'],
                            'owner_name'=> $element['owner']['display_name'],
                            'owner_url'=> $element['owner']['external_urls']['spotify'],
                            'owner_uri'=> $element['owner']['uri']);
          }
        }

    }
    return($result);
  }

}
