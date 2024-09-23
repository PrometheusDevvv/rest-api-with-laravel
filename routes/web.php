<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/setup', function(){
    $credentials = [
        'email' => 'admin@gmail.com',
        'password' => 'password'
    ];
    if(!Auth::attempt($credentials)){
        $user = new User();
        $user->name = "Admin";
        $user->email = $credentials['email'];
        $user->password = Hash::make($credentials['password']);
        $user->save();

        if(Auth::attempt($credentials)){
            $user = Auth::user();
            $adminToken = $user->createToken('admin-token', ['create', 'update', 'delete']);
            $userToken = $user->createToken('update-token', ['create', 'update']);
            $basicToken = $user->createToken('basic-token');


            return [
                'admin' => $adminToken->plainTextToken,
                'user' => $userToken->plainTextToken,
                'basic' => $basicToken->plainTextToken
            ];

        }   
    }
});