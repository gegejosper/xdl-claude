<?php

namespace App\Helpers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;

class GlobalHelpers 
{
    //
    public static function get_user_name(){
        if (Auth::check())
        {
            $name = Auth::user()->name;
        }
        return $name;
    }
    public static function get_user_id(){
        if (Auth::check())
        {
            $id = Auth::user()->id;
        }
        return $id;
    }
    
    public static function random_characters($length_letters){
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomCharacters = '';
        for ($i = 0; $i < $length_letters; $i++) {
            $randomCharacters .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomCharacters;
    }
}
