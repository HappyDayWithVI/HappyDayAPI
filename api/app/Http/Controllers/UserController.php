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




    public function authenticate(Request $request){
     
        $this->validate($request, [
     
           'username' => 'required',
     
           'password' => 'required'
     
        ]);
     
        $user = User::where('username', $request->input('username'))->first();
     
        if($request->input('password') == $user->password){
     
            $apikey = base64_encode(str_random(40));
     
            User::where('username', $request->input('username'))->update(['subtoken' => "$apikey"]);;
     
            return response()->json(['status' => 'success','subtoken' => $apikey, 'name' => $user->firstname]);
     
        }else{
            return response()->json(['status' => 'fail'],401);
     
        }
    }

    public function authenticateGoogle($id){
        $user = User::where('token', $id)->first();
          
        $apikey = base64_encode(str_random(40));
        
        User::where('token', $id)->update(['subtoken' => "$apikey"]);;
        
        return response()->json(['status' => 'success','subtoken' => $apikey]);
    }


    public function store(Request $request){
        // $this->validateRequest($request);

        return "okokok";
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