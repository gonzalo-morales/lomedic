<?php

namespace App\Http\Controllers\Wsdl;


class Areas
{
    public function __construct()
    {
        return (Object) ['hola'=>'mundo'];
    }
    
    public function index()
    {
        return '12345';
    }
    
	public function getDate()
	{
	    return 'sdf';
	}

	public function show()
    {
        return 'AAAA';
    }
}