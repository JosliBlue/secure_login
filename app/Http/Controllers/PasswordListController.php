<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PasswordListController extends Controller
{
    public function index()
    {
        return view('passwords');
    }
}
