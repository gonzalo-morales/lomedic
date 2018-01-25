<?php
namespace App\Http\Controllers\Estadisticas;

use Illuminate\Http\Request;
use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\Sucursales;
use DB;

class GeneralesController extends ControllerBase
{
    public function index($company, $attributes = ['where'=>[]])
	{
	    return view('estadisticas.generales.index',[
	        'localidades' => Sucursales::where('activo',1)->pluck('sucursal','id_sucursal')->prepend('TODAS LAS SUCURSALES','-999'),
	        'padecimientos'=>[],
	        'pacientes' => [],
	        'medicos' => [],
	    ]);
	}
	
	public function store(Request $request, $company, $compact = false)
	{
	    $fecha_inicio = isset($request->fecha_ini) ? $request->fecha_ini : '1900-01-01';
	    $fecha_fin = isset($request->fecha_fin) ? $request->fecha_fin : '1900-01-01';
	    $localidad = isset($request->localidades) ? $request->localidades : -999;
	    
	    $padecimientos = DB::connection($company)->table('rec_opr_recetas as r')->leftJoin('maestro.gen_cat_diagnosticos as d','d.id_diagnostico','r.fk_id_diagnostico')
    	    ->selectRaw('d.clave_diagnostico as clave, d.diagnostico as nombre, count(r.fk_id_diagnostico) as total')
    	    ->whereBetween(DB::RAW("to_char(r.fecha, 'YYYY-MM-DD')"), [$fecha_inicio, $fecha_fin])->whereraw("(r.fk_id_sucursal = $localidad or $localidad = -999)")
    	    ->groupBy(['r.fk_id_diagnostico', 'd.diagnostico', 'd.clave_diagnostico'])->orderByRaw('total desc')->limit(10)->get();
    	    
	    $pacientes = DB::connection($company)->table('rec_opr_recetas as r')->leftJoin('maestro.gen_cat_afiliados as p','p.id_afiliacion','r.fk_id_afiliacion')
            ->selectRaw("coalesce(p.id_afiliacion,'#N/A') as clave, coalesce(r.nombre_paciente_no_afiliado, concat(coalesce(p.nombre,''),' ',coalesce(p.paterno,''),' ',coalesce(p.materno,''))) as nombre, count(r.fk_id_diagnostico) as total")
            ->whereBetween(DB::RAW("to_char(r.fecha, 'YYYY-MM-DD')"), [$fecha_inicio, $fecha_fin])->whereraw("(r.fk_id_sucursal = $localidad or $localidad = -999)")
            ->groupBy(['p.nombre','p.paterno','p.materno', 'p.id_afiliacion','r.nombre_paciente_no_afiliado'])->orderByRaw('total desc')->limit(10)->get();
	            
        $medicos = DB::connection($company)->table('rec_opr_recetas as r')->leftJoin('maestro.gen_cat_medicos as m','m.id_medico','r.fk_id_medico')
            ->selectRaw("m.cedula, concat(m.nombre,' ',m.paterno,' ',m.materno) as nombre, count(r.fk_id_diagnostico) as total")
            ->whereBetween(DB::RAW("to_char(r.fecha, 'YYYY-MM-DD')"), [$fecha_inicio, $fecha_fin])->whereraw("(r.fk_id_sucursal = $localidad or $localidad = -999)")
            ->groupBy(['m.nombre','m.paterno','m.materno', 'm.cedula'])->orderByRaw('total desc')->limit(10)->get();
        
	    return view('estadisticas.generales.index',[
	        'localidades' => Sucursales::where('activo',1)->pluck('sucursal','id_sucursal')->prepend('TODAS LAS SUCURSALES','-999'),
	        'padecimientos' => $padecimientos,
	        'pacientes' => $pacientes,
	        'medicos' => $medicos,
	    ]);
	}
}