<?php

namespace App\Actions\Fortify;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Fortify\Http\Requests\LoginRequest;

class AutenticateUser 
{
    public function authenticate(Request $request)
    {
       $credentials = $request->only('email', 'password');//recupero dati necessari per l'autenticazione fa query "via mail più sicuro" o se non esiste salt + pepper in db
       $user = User::where('email', $credentials['email'])->first();
       if(!$user){
        $pepper = config('app.pepper');
        dd($passwordWithSaltPepper);
        $passwwordwithpepper = $credentials['password'] . $user->salt . $pepper;
        if(Hash::check($passwwordwithpepper, $user->password)){
            Auth::login($user);
            return $user;
        }
        return null;
       }
       return null;
    }
    }