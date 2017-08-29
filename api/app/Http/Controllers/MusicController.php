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
          $result[]=array('name'=> $element['name'],
                          'track'=> $element['preview_url'],
                          'uri'=> $element['uri'],
                          'duration'=>$element['duration_ms'],
                          'artist_name'=> $element['artists'][0]['name'],
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
    $result = array();
    foreach($data_albums as $key => $items)
    {
      if (is_array($items))
      {
        foreach($items as $k2 => $element)
        {
            $result[]=array('name' => $element['name'],
                          'uri' => $element['uri'],
                          'picture' => $element['images'][0]['url']);
        }

      }
    }
    return($result);
  }

  private function getPLaylistTrack($id_user, $id_playlist)
  {
    $token = $this->getGeneralToken();
    $curl = curl_init();
    $url = MUSIC_URL_API."v1/users/".$id_user."/playlists/".$id_playlist."/tracks";
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl,CURLOPT_URL, $url);
    $header = array('Accept: application/json', 'Content-Type: application/json','Authorization: Bearer '.$token);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $data_playlists = json_decode(curl_exec($curl),true);
    curl_close($curl);
    $result = array();
    foreach ($data_playlists as $key => $items) {
      if(is_array($items))
      {
        for ($i=0; $i < count($items); $i++)
        {
          $result[]=array('name'=> $items[$i]['track']['name'],
                          'track'=> $items[$i]['track']['preview_url'],
                          'uri'=> $items[$i]['track']['uri'],
                          'album_name'=> $items[$i]['track']['album']['name'],
                          'album_uri'=> $items[$i]['track']['album']['uri'],
                          'album_image'=> $items[$i]['track']['album']['images'][0]['url'],
                          'artist_name'=> $items[$i]['track']['artists'][0]['name'],
                          'artist_uri' => $items[$i]['track']['artists'][0]['uri']);
        }
      }
    }
    return($result);
  }


  public function getNewRealease($country)
  {
      $token = $this->getGeneralToken();
      $curl = curl_init();
      $url = MUSIC_URL_API."v1/browse/new-releases?country=".$country."&limit=10";
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
          $result[] = array('name' => $element['name'],
                            'uri' => $element['uri'],
                            'picture' => $element['images'][0]['url'],
                            'artist_name'=> $element['artists'][0]['name'],
                            'artist_uri' => $element['artists'][0]['uri']);
        }
      }
      return ['id' => '10-0', 'result' => ['country' => $country, 'new_releases' => $result]];
  }

  public function getSearchAlbum($elementsought)
  {
    $token = $this->getGeneralToken();
    $curl = curl_init();
    $url = MUSIC_URL_API."v1/search?q=".$elementsought."&type=album&limit=10";
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl,CURLOPT_URL, $url);
    $header = array('Accept: application/json', 'Content-Type: application/json','Authorization: Bearer '.$token);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $data_sought = json_decode(curl_exec($curl),true);
    curl_close($curl);
    $result = array();
      foreach($data_sought as $key => $albums)
      {
        foreach($albums['items'] as $k2 => $element)
        {
          $result[] = array('name' => $element['name'],
                            'uri' => $element['uri'],
                            'picture' => $element['images'][0]['url'],
                            'artist_name'=> $element['artists'][0]['name'],
                            'artist_uri' => $element['artists'][0]['uri'],
                            'album_tracks'=> self::getAlbumTracks($element['id']));
        }
      }
    return ['id' => '10-1', 'result' => ['album_sought' => $elementsought, 'albums' => $result]];
  }

  public function getSearchArtist($elementsought)
  {
    $token = $this->getGeneralToken();
    $curl = curl_init();
    $url = MUSIC_URL_API."v1/search?q=".$elementsought."&type=artist&limit=1";
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl,CURLOPT_URL, $url);
    $header = array('Accept: application/json', 'Content-Type: application/json','Authorization: Bearer '.$token);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $data_sought = json_decode(curl_exec($curl),true);
    curl_close($curl);
    $result = array();
    foreach($data_sought as $key => $artists)
    {
      foreach($artists['items'] as $k2 => $element)
      {
        $result[]=array('name' => $element['name'],
                            'url' => $element['external_urls']['spotify'],
                            'uri' => $element['uri'],
                            'big_picture' => $element['images'][0]['url'],
                            'genres'=> $element['genres'],
                            'albums'=> self::getArtistAlbum($element['id']));
      }
    }
    return ['id' => '10-2', 'result' => ['artist_sought' => $elementsought, 'artist' => $result]];
  }

  public function getSearchTrack($elementsought)
  {
    $token = $this->getGeneralToken();
    $curl = curl_init();
    $url = MUSIC_URL_API."v1/search?q=".$elementsought."&type=track&limit=10";
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl,CURLOPT_URL, $url);
    $header = array('Accept: application/json', 'Content-Type: application/json','Authorization: Bearer '.$token);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $data_sought = json_decode(curl_exec($curl),true);
    curl_close($curl);
    $result = array();
    foreach($data_sought as $key => $tracks)
    {
          foreach($tracks['items'] as $k2 => $element)
          {
            $result[]=array('name'=> $element['name'],
                            'url'=> $element['external_urls']['spotify'],
                            'track'=> $element['preview_url'],
                            'uri'=> $element['uri'],
                            'duration'=>$element['duration_ms'],
                            'album_name'=> $element['album']['name'],
                            'album_uri'=> $element['album']['uri'],
                            'album_picture' => $element['album']['images'][0]['url'],
                            'album_artist'=> $element['album']['artists'][0]['name'],
                            'album_artist_uri' => $element['album']['artists'][0]['uri'],
                            'track_artist_name'=> $element['artists'][0]['name'],
                            'track_artist_uri' => $element['artists'][0]['uri']);
        }
      }
  return ['id' => '10-3', 'result' => ['track_sought' => $elementsought, 'tracks' => $result]];
  }

  public function getSearchPlaylist($elementsought)
  {
    $token = $this->getGeneralToken();
    $curl = curl_init();
    $url = MUSIC_URL_API."v1/search?q=".$elementsought."&type=playlist&limit=1";
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl,CURLOPT_URL, $url);
    $header = array('Accept: application/json', 'Content-Type: application/json','Authorization: Bearer '.$token);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $data_sought = json_decode(curl_exec($curl),true);
    curl_close($curl);
    $result = array();
    foreach($data_sought as $key => $playlist)
    {
      foreach($playlist['items'] as $k2 => $element)
      {
        $owner_id = substr($element['owner']['uri'], 13);
        $id_playlist = $element['id'];
        $result[]=array('name'=> $element['name'],
                        'uri'=> $element['uri'],
                        'picture'=> $element['images'][0]['url'],
                        'owner_name'=> $element['owner']['display_name'],
                        'owner_url'=> $element['owner']['external_urls']['spotify'],
                        'owner_uri'=> $element['owner']['uri'],
                        'tracks'=> self::getPLaylistTrack($owner_id, $id_playlist)
                      );
      }
    }
    return['id' => '10-4', 'result' => ['playlists_sought' => $elementsought, 'playlists' => $result]];
  }

}
