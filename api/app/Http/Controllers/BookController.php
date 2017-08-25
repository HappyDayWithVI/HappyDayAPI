<?php

namespace App\Http\Controllers;

class BookController extends Controller{
    public function __construct(){

    }

    public function getBookByName($title){
        $data_book_url = file_get_contents('https://www.googleapis.com/books/v1/volumes?q=intitle:'.$title);
        return response()->json( json_decode($data_book_url) );
    }

    public function getBookByAuthor($author){
        $data_book_url = file_get_contents('https://www.googleapis.com/books/v1/volumes?q=inauthor:'.$author);
        return response()->json( json_decode($data_book_url) );
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
