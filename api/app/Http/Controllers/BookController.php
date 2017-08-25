<?php

namespace App\Http\Controllers;

class BookController extends Controller{
    public function __construct(){

    }

    public function getBookByName($title){
        $data_book_url = simplexml_load_file(BOOK_BASEURL.'book/title.xml?key='.BOOK_KEY.'&title=Harry%20Potter');
        $data_book_url =  json_encode($data_book_url);
        echo $data_book_url;


        // file_get_contents(BOOK_BASEURL.'book/title.xml?key='.BOOK_KEY.'&title=Harry%20Potter');

        // var_dump(BOOK_BASEURL.'book/title.xml?key='.BOOK_KEY.'&title=Harry%20Potter');

        
    }
}
