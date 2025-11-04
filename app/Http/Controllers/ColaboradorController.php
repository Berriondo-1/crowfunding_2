<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ColaboradorController extends Controller
{
    public function index()
    {
        return view('colaborador.dashboard');
    }
}
