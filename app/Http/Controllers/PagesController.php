<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Users;

class PagesController extends Controller
{
    public function getHome(){
          // get amount of players
          $players = Users::get();
          $playerCount = $players->count();
          return view('home', ['playerCount' => $playerCount]);
    }
}
