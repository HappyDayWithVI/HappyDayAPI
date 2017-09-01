<?php

namespace App\Http\Controllers;

class SpeechController extends Controller{

    public function interpretSpeech($message){
        // function will return general display or not
        //$message = str_replace("%20", " ", $message);

        $message = urldecode($message);

        if ($message == "que puis-je faire") {
            $res['message'] = 'Va dormir, sérieux tu dois être tellement fatigué !';
        }else if($message == "présente toi"){

        }else{
            $message_item = explode(" ", $message);

            if (in_array("meteo", $message_item) || in_array("météo", $message_item) ) {
                if (in_array("a", $message_item) || in_array("à", $message_item)) {
                    if (in_array("a", $message_item)) {
                        $pos_city = array_search("a", $message_item)+1;
                    }else{
                        $pos_city = array_search("à", $message_item)+1;
                    }
                    
                    $city = $message_item[$pos_city];
                }else{
                    if ((count($message_item) > 1 && !in_array("semaine", $message_item) || count($message_item) > 2 && in_array("semaine", $message_item))) {

                        $city = "";

                        foreach ($message_item as $el) {
                            if ($el != "semaine" && $el != "meteo" && $el != "météo" && $el != "cette" ) {
                                $city .= $el." ";
                            }
                        }                      
                    }else{
                        // get default user city
                        $city = "Lyon";
                    }
                }

                if (in_array("semaine", $message_item)) {
                    $res = app('App\Http\Controllers\WeatherController')->getWeeklyWeather($city);
                    $res['message'] = "Voici la météo à ". $res['result']['city'] ." pour les sept jours à venir";

                    $alt = $res;
                    unset( $alt['result']['week'][0] );
                    $message = '';
                    foreach ($alt['result']['week'] as $key => $value) {
                        $message .= '. '. $value['day'] .'. il fera '. $value['temp'] .' degré. avec '. $value['desc'];
                    }

                    $res['message'] .= $message;
                }else{
                    $res = app('App\Http\Controllers\WeatherController')->getWeather($city);
                    
                    $res['message'] = "Aujourd'hui à ". $res['result']['city'] .". " . $res['result']['day']. ". il fait ". $res['result']['temp'] ." degré. et ". $res['result']['desc']; 
                }


            }else if(in_array("serie", $message_item)){
                if (in_array("genre", $message_item)) {

                    foreach ($message_item as $word) {
                        if ($word != "genre" && $word != "serie" ) {
                            $genre = $word;
                            break;
                        }
                    }

                    $res = app('App\Http\Controllers\TvshowController')->getTvshowByGenre($genre);

                    if($genre == "horreur"){
                        $desc_genre = " si vous avez envie de vous faire peur";
                    }else if($genre == "fantastique"){
                        $desc_genre = " pour vivre des aventures à côté de dragons...";
                    }else if($genre == "comédie"){
                        $desc_genre = " histoire de rigoler un bon coup.";
                    }else{
                        $desc_genre = "";
                    }

                    $res['message'] = "Voici une sélection de série ".$genre.". Vous pouvez regarder ".$res['result']['shows'][0]['name'].", ".$res['result']['shows'][1]['name']." ou, ".$res['result']['shows'][2]['name'].", ".$desc_genre; 
                }else if (in_array("personnage", $message_item) || in_array("acteur", $message_item) ) {

                    $name = "";
                    $d_name = "";
                    foreach ($message_item as $word) {
                        if ($word != "personnage" && $word != "serie" && $word != "acteur" ) {
                            $name .= $word."+";
                            $d_name .= $word." ";
                        }
                    }



                    $res = app('App\Http\Controllers\TvshowController')->getCharacterOfTvshowByName($name);

                    $nb_res = count($res["result"]['character_data']);
                    $serieList = "";
                    for ($i=0; $i < 5; $i++) { 
                        $serieList .= $res["result"]['character_data'][$i]["name_character"].". Joué par ".$res["result"]['character_data'][$i]["name_actor"].". ";
                        if ($i == $nb_res-1) {
                            break;
                        }
                    }

                    $res['message'] = "Voici une liste de ".count($res['result']['character_data'])." personnages que vous pourrez retrouver dans la série ".$d_name. " ainsi que les acteurs qui y jouent comme. ".$serieList;
                }else if (in_array("avec", $message_item)) {

                    $acteur = "";

                    $d_name = "";
                    foreach ($message_item as $word) {                        
                        if ($word != "avec" && $word != "serie" ) {
                            $acteur .= $word."+";
                            $d_name .= $word." ";
                        }
                    }

                    $res = app('App\Http\Controllers\TvshowController')->getTvshowByActor($acteur);

                    $nb_res = count($res["result"]['role_data']);

                    $serieList = "";
                    for ($i=0; $i < 5; $i++) { 
                        if ($i != 0) {
                            $serieList .= "Celui de ";
                        }

                        $serieList .= $res["result"]['role_data'][$i]["character"]." dans la série ".$res["result"]['role_data'][$i]["tvshow"].". ";
                        if ($i == $nb_res-1) {
                            break;
                        }
                    }

                    $res['message'] = $d_name." a eu le rôle de ".$serieList; 
                }else{

                    $name = "";
                    $d_name = "";
                    foreach ($message_item as $word) {
                        if ($word != "serie" ) {
                            $name .= $word."+";
                            $d_name .= $word." ";
                        }
                    }

                    $name = rtrim($name,"+");

                    $res = app('App\Http\Controllers\TvshowController')->getTvshowByName($name);
                    
                    if (count($res['result']["shows"]) == 1) {
                        if ($res['result']["shows"][0]['status'] == "En cours") {
                            $status = ". La série est toujours en cours de diffusion.";
                        }else{
                            $status = ". La série est terminée, il n'y aura plus de nouveaux épisode.";
                        }
                        $res['message'] = "Voici quelques informations sur la série ".$d_name.". La série comporte ".$res['result']["shows"][0]['episode']." épisodes pour ".$res['result']["shows"][0]['season']." saisons ".$status." Voici le synopsis de la série : ".$res['result']["shows"][0]['resume']; 
                    }else{
                        if (count($res['result']["shows"]) > 5) {
                            $res['message'] = "J'ai trouvé ".count($res['result']["shows"])." dont le titre contient ".$d_name; 
                        }else{
                            $nb_res = count($res["result"]['shows']);

                            $serieList = "";
                            for ($i=0; $i < 5; $i++) {

                                $serieList .= $res["result"]['shows'][$i]["name"].". ";
                                if ($i == $nb_res-1) {
                                    break;
                                }
                            }


                            $res['message'] = "J'ai trouvé les séries suivantes : ".$serieList;

                        }
                    }
                }
            }else if(in_array("film", $message_item)){
                if (in_array("genre", $message_item)) {

                    foreach ($message_item as $word) {
                        if ($word != "genre" && $word != "film" ) {
                            $genre = $word;
                            break;
                        }
                    }

                    if($genre == "horreur"){
                        $desc_genre = "Si vous avez envie de vous faire peur, ";
                    }else if($genre == "fantastique"){
                        $desc_genre = "Pour vivre des aventures à côté de dragons, ";
                    }else if($genre == "comédie"){
                        $desc_genre = "Histoire de rigoler un bon coup, ";


                    }else{
                        $desc_genre = "";
                    }

                    $res = app('App\Http\Controllers\MoviesController')->getMoviesByGenre($genre);

                    $nb_res = count($res["result"]);

                    $movieList = "";
                    for ($i=0; $i < 5; $i++) {

                        $movieList .= $res["result"][$i]["name"].". ";
                        if ($i == $nb_res-1) {
                            break;
                        }
                    }


                    $res['message'] = $desc_genre.", vous pouvez regarder l'un des films suivants. ".$movieList; 
                }elseif (in_array("nouveau", $message_item)) {

                    $res = app('App\Http\Controllers\MoviesController')->getMovies();

                    $res['message'] = "Voilà les résultats que j'ai trouvé pour les films du moment."; 
                }else if (in_array("personnage", $message_item) || in_array("acteur", $message_item) ) {

                    $name = "";
                    $d_name = "";
                    foreach ($message_item as $word) {
                        if ($word != "personnage" && $word != "film" && $word != "acteur") {
                            $name .= $word."+";
                            $d_name .= $word." ";
                        }
                    }

                    $res = app('App\Http\Controllers\MoviesController')->getActorByMovieName($name);

                    $nb_res = count($res["result"]['character_data']);
                    $serieList = "";
                    for ($i=0; $i < 5; $i++) { 
                        $serieList .= $res["result"]['character_data'][$i]["name_actor"]." joue. ".$res["result"]['character_data'][$i]["name_character"].". ";
                        if ($i == $nb_res-1) {
                            break;
                        }
                    }
                    $res['message'] = "Les acteurs suivants jouent dans le film ".$d_name.". ".$serieList; 
                }else if (in_array("avec", $message_item)) {

                    $acteur = "";
                    $d_acteur = "";

                    foreach ($message_item as $word) {                        
                        if ($word != "avec" && $word != "film" ) {
                            $acteur .= $word."+";
                            $d_acteur .= $word." ";
                        }
                    }

                    $res = app('App\Http\Controllers\MoviesController')->getMovieByActor($acteur);

                    $nb_res = count($res["result"]['role_data']);
                    $movieList = "";
                    for ($i=0; $i < 7; $i++) { 
                        $movieList .= $res["result"]['role_data'][$i]["movie"].". ";
                        if ($i == $nb_res-1) {
                            break;
                        }
                    }
                    $res['message'] = $d_acteur." joue notamment dans les films suivants. ".$movieList;
                }else{
                    $name = "";
                    foreach ($message_item as $word) {
                        if ($word != "film" ) {
                            $name .= $word."+";
                        }
                    }

                    $name = rtrim($name,"+");

                    $res = app('App\Http\Controllers\MoviesController')->getMovieByTitle($name);
                    $res['message'] = "Voici les informations que j'ai trouvé sur le film ".str_replace("+", " ", $name);
                }
            }else if(in_array("livre", $message_item)){
                if (in_array("de", $message_item)) {

                    $author = "";
                    $d_author = "";

                    foreach ($message_item as $word) {                       
                        if ($word != "de" && $word != "livre" ) {
                            $author .= $word."+";
                            $d_author .= $word."+";
                        }
                    }

                    $res = app('App\Http\Controllers\BookController')->getBookByAuthor($author);
                    $res['message'] = $d_author." a écrit les livres suivants";
                }else{
                    $name = "";
                    foreach ($message_item as $word) {
                        if ($word != "livre" ) {
                            $name .= $word."+";
                        }
                    }

                    $name = rtrim($name,"+");

                    $res = app('App\Http\Controllers\BookController')->getBookByName($name);
                    $res['message'] = "Voilà les resultats que j'ai trouvé pour le livre ".$name;
                }
            }else if(in_array("programme", $message_item)){
                if (in_array("soir", $message_item)) {
                    $res = app('App\Http\Controllers\TvguideController')->getTvGuideTonigt();

                    $programme = '';

                    foreach ($res['result'] as $val) {
                        $programme .= "Sur ".$val["channel"]. ", ".$val['title'].". ";
                    }
                    $res['message'] = $programme;
                }else{
                   $res = app('App\Http\Controllers\TvguideController')->getTvGuideByTime();
                    $programme = '';
                   foreach ($res['result'] as $val) {
                        $programme .= $val['title'].", sur ".$val["channel"]. ". ";
                    }
                    $res['message'] = "En ce moment à la télé il y a ".$programme;

                }
            }else if(in_array("restaurant", $message_item) || in_array("restaurants", $message_item)){
                if (in_array("meilleur", $message_item) || in_array("meilleurs", $message_item)) {
                    $city = "";
                    $d_city = "";
                    foreach ($message_item as $word) {
                        if ($word != "meilleur" || $word != "restaurant" || $word != "meilleurs" || $word != "restaurants" || $word != "a" || $word != "à" || $word != "de") {
                            $city .= $word."+";
                            $d_city .= $word." ";
                        }
                    }

                    $res = app('App\Http\Controllers\RestaurantController')->getBestRestaurantByCity($city);

                    $restaurantList = "";
                    foreach ($res['result'] as $food) {
                        $restaurantList .= $food['name'].". ";
                    }
                    $res['message'] = "Les ".str_replace("+", " ", $city)." sont : ".$restaurantList;
                }else{
                    if ($key = array_search('à', $message_item)) {
                        $assumed_city = $message_item[$key+1];
                    }else if($key = array_search('a', $message_item)){
                        $assumed_city = $message_item[$key+1];
                    }
                    unset($message_item[$key+1]);
                    $type= "";
                    foreach ($message_item as $word) {
                        if ($word != "restaurant" || $word != "restaurants" || $word != "a" || $word != "à" ) {
                            $type .= $word."+";
                        }
                    }
                    $res = app('App\Http\Controllers\RestaurantController')->getRestaurantByName($type, $assumed_city);

                    if (count($res['result']) != 1) {
                        $res['message'] = "J'ai trouvé ".count($res['result'])." restaurants qui pourraient correspondre à votre recherche";
                    }else{
                        $res['message'] = "Vous trouverez le restaurant ".$res['result'][0]['name']." au ".$res['result'][0]['adress'].". Il obtient une note de ".$res['result'][0]['rating']." sur 10. Pourquoi ne pas aller y manger quelque chose ? ";
                    }
                }
            }else if(in_array("musique", $message_item)){
                if (in_array("nouveauté", $message_item)){
                    $country = "FR";
                    $res = app('App\Http\Controllers\MusicController')->getNewRealease($country);

                    $musicList = "";
                    foreach ($res['result']['new_releases'] as $value) {
                        $musicList .= $value['name'].' de '.$value['artist_name'].". ";
                    }

                    $res['message'] = "Voici les dernières nouveautés musique française. ".$musicList;
                }else if (in_array("album",$message_item)){
                    $elementsought = "";
                    foreach ($message_item as $word){
                        if ($word != 'musique' && $word != 'album'){
                            $elementsought .= $word ."+";
                        }
                    }
                    
                    $res = app('App\Http\Controllers\MusicController')->getSearchAlbum(trim($elementsought,"+"));

                    $musicList = "";
                    for ($i=0; $i < 5; $i++) { 
                        $musicList .= "un album de .".$res['result']['albums'][$i]['artist_name'].".";
                    }

                    $res['message'] = "J'ai trouvé ".$musicList;
                }else if (in_array("artiste",$message_item) || in_array("chanteur", $message_item)){
                    $elementsought = "";
                    foreach ($message_item as $word){
                        if ($word != 'musique' && $word != 'artiste' && $word != 'chanteur'){
                            $elementsought .= $word ."+";
                        }
                    }
                    $res = app('App\Http\Controllers\MusicController')->getSearchArtist(trim($elementsought,"+"));
                    $res['message'] = "Voici les albums et chansons de ".str_replace("+", " ", $elementsought);
                }else if (in_array("chanson",$message_item)){
                    $elementsought = "";
                    foreach ($message_item as $word){
                        if ($word != 'musique' && $word != 'chanson'){
                            $elementsought .= $word ."+";
                        }
                    }
                    
                    $res = app('App\Http\Controllers\MusicController')->getSearchTrack(trim($elementsought,"+"));
                }else if (in_array("playlist",$message_item)){
                    $elementsought = "";
                    foreach ($message_item as $word){
                        if ($word != 'musique' && $word != 'playlist'){
                            $elementsought .= $word ."+";
                        }
                    }

                    $res = app('App\Http\Controllers\MusicController')->getSearchPlaylist(trim($elementsought,"+"));

                }
            } else{
                $res['message'] = "Je n'ai pas compris ce que tu me demande";
            }
        }

        return response()->json($res);

        // if general get weather
    }

    //
}