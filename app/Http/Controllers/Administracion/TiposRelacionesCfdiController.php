<?php
namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\TiposRelacionesCfdi;

class TiposRelacionesCfdiController extends ControllerBase
{
	public function __construct()
	{
	    $this->entity = new TiposRelacionesCfdi();
	}
}