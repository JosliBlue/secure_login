<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LogListController extends Controller
{
    public function index()
    {
        return view('logs');
    }
}
