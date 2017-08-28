<?php

namespace App\Http\Controllers;

class BookController extends Controller{
    public function __construct(){

    }

    public function getBookByName($title){
        $data_book_url = file_get_contents('https://www.googleapis.com/books/v1/volumes?q=intitle:'.$title);
        $data_book = json_decode($data_book_url);

        $books = array();


        foreach ($data_book->items as $book) {
            if ($book->volumeInfo->language == "fr" && isset($book->volumeInfo->description)) {

                $author = "";
                foreach ($book->volumeInfo->authors as $auth) {
                    $author .= $auth." / ";
                }

                if (isset($book->volumeInfo->imageLinks)) {
                    if (isset($book->volumeInfo->imageLinks->smallThumbnail)) {
                        $img = $book->volumeInfo->imageLinks->smallThumbnail;
                    }elseif (isset($book->volumeInfo->imageLinks->thumbnail)) {
                        $img = $book->volumeInfo->imageLinks->thumbnail;
                    }else{
                        $img = "";
                    }
                }else{
                    $img = "";
                }

                $author = substr($author, 0, -3);

                $book_item = ["name" => $book->volumeInfo->title, 'author' => $author, 'image' => $img, 'resume' => $book->volumeInfo->description, "published_year" => substr($book->volumeInfo->publishedDate, 0, 4)];

                array_push($books, $book_item);
            }
        }
            
        return ['id' => '4-1', 'result' => $books];


        // return response()->json( json_decode($data_book_url) );
    }

    public function getBookByAuthor($author){
        $data_book_url = file_get_contents('https://www.googleapis.com/books/v1/volumes?q=inauthor:'.$author);
        $data_book = json_decode($data_book_url);

        $books = array();


        foreach ($data_book->items as $book) {
            if ($book->volumeInfo->language == "fr" && isset($book->volumeInfo->description)) {

                $author = "";
                foreach ($book->volumeInfo->authors as $auth) {
                    $author .= $auth." / ";
                }

                $author = substr($author, 0, -3);

                if (isset($book->volumeInfo->imageLinks)) {
                    if (isset($book->volumeInfo->imageLinks->smallThumbnail)) {
                        $img = $book->volumeInfo->imageLinks->smallThumbnail;
                    }elseif (isset($book->volumeInfo->imageLinks->thumbnail)) {
                        $img = $book->volumeInfo->imageLinks->thumbnail;
                    }else{
                        $img = "";
                    }
                }else{
                    $img = "";
                }

                $book_item = ["name" => $book->volumeInfo->title, 'author' => $author, 'image' => $img, 'resume' => $book->volumeInfo->description, "published_year" => substr($book->volumeInfo->publishedDate, 0, 4)];

                array_push($books, $book_item);
            }
        }
            
        return ['id' => '4-2', 'result' => $books];
    }

    public function getBookByCategory($category){
        $data_book_url = file_get_contents('https://www.googleapis.com/books/v1/volumes?q=subject:'.$category);
        return response()->json( json_decode($data_book_url) );
    }

    public function getBookByISBN($isbn){
        $data_book_url = file_get_contents('https://www.googleapis.com/books/v1/volumes?q=isbn:'.$isbn);
        return response()->json( json_decode($data_book_url) );
    }

    public function getBookByPublisher($publisher){
        $data_book_url = file_get_contents('https://www.googleapis.com/books/v1/volumes?q=inpublisher:'.$publisher);
        return response()->json( json_decode($data_book_url) );
    }
}
