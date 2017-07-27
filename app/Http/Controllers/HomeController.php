<?php

namespace App\Http\Controllers;

use Session;
use Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

use Illuminate\Support\Facades\Route;


class HomeController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth');
	}

	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
	// dump ( Route::currentRouteAction() );
	// dump (  request()->company );
	// dump (  Auth::user()->perfiles->pluck('nombre_perfil')->toArray() );
	// dump (  Auth::user()->permisos() );
	// dump (  Auth::user()->modulos() );
		return view('home');
	}
}
