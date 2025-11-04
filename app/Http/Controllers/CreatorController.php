<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CreatorController extends Controller
{
    public function index()
    {
        return view('creator.dashboard');
    }
}
