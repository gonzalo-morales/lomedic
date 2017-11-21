<?php

namespace App\Http\Controllers;

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
	    if(empty(request()->company)) {
	        return redirect()->route('login');
	    }

		#return view('handheld.home');
		// Handheld
		if (str_contains(request()->header('User-Agent'), ['MSIE 6.8', 'Windows CE'])) {
			return view('handheld.home');
		}

		return view('home');
	}
}