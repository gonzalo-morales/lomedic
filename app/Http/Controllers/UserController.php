<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function store()
    {
        return ("usuarios");
    }

    public function view()
    {
        return ("Todos los usuarios");
    }

    public function create(Request $request)
    {
        return view('user.create');
    }
}
