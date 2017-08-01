<?php

namespace App\Http\Controllers\Administracion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
