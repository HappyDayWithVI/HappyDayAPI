<?php

namespace App\Http\Controllers;

use App\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller{
    /**
     * Retrieve the user for the given ID.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id){
        echo $id;
        $quote = User::query()->findOrFail($id);

        echo "<pre>";
        var_dump($quote);
        echo "</pre>";
    }

    public function store(Request $request){
        // $this->validateRequest($request);

        echo "<pre>";
        var_dump($request);
        echo "</pre>";

        /*
        $user = User::create([
            'name' => $request->get('name'),
            'firstname' => $request->get('firstname'),
            'birthdate' => $request->get('birthdate'),
            'mail' => $request->get('mail'),
            'token' => '',
            'subtoken' => '',
            'city' => $request->get('city'),
            'zipcode' => $request->get('zipcode'),
            'lastupdate' => '',
            'username' => $request->get('username'),
            'password' => $request->get('password'),
        ]);
        */

        // return success("L'utilisateur n°  {$user->id} est crée,201)";
    }
}