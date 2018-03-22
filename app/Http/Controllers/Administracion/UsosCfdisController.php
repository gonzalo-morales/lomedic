<?php
namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\UsosCfdis;

class UsosCfdisController extends ControllerBase
{
	public function __construct()
	{
	    $this->entity = new UsosCfdis;
	}
}