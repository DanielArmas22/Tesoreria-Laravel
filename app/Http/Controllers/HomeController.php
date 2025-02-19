<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller 
{
    public function index()
    {
        // if(auth()->user()->hasRole('padre')){
        //     dd(auth()->user()->estudiantes);
        // }
        return view('pages.home');
    }
    public function redirect()
    {
        // return view('pages.redirect');
    }
}
