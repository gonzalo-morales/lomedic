<?php
namespace App;
use Session;
use Auth;
use DB;
use Request;
use URL;

use Illuminate\Database\Eloquent\Model;

class Menu
{
    public function getMenu($Parent = Null)
    {
        $id_usuario = empty(Auth::id()) ? Auth::id() : 1;
        $htmlMenu = '';

        $Rows = DB::select('SELECT *
            FROM ges_cat_modulos m
            LEFT JOIN ges_det_parents p ON p.fk_id_modulo = m.id_modulo AND p.activo = TRUE
            WHERE m.eliminar = FALSE
            AND m.activo = TRUE
            AND m.accion_menu = TRUE
            AND '.(empty($Parent) ? 'p.fk_id_parent is null' : 'p.fk_id_parent = '.$Parent).'
            AND m.id_modulo IN(
            	SELECT DISTINCT mp.fk_id_modulo
            	FROM ges_cat_usuarios u
            	LEFT JOIN ges_det_usuario_perfil up ON up.fk_id_usuario = u.id_usuario AND up.activo = TRUE
            	LEFT JOIN ges_det_modulo_perfil mp ON mp.fk_id_perfil = up.fk_id_perfil AND mp.activo = TRUE
            	WHERE u.eliminar = FALSE
            	AND u.activo = TRUE
            	AND u.id_usuario = :id_usuario
            	ORDER BY fk_id_modulo)
            ORDER BY id_modulo', ['id_usuario' => $id_usuario]);

        $Children = DB::table('ges_det_parents')->select('fk_id_parent')->distinct()->whereRaw('fk_id_parent is not null')->get()->toarray();
        $Parents =[];
        foreach($Children as $item)
            array_push($Parents,$item->fk_id_parent);

            $Company = request()->company;
            foreach($Rows as $item)
            {
                $IsParent  = in_array($item->id_modulo,$Parents);
                $Url = !empty($item->url) ? URL('/').'/'.$Company.'/'.$item->url : '#';
                $Icono = !empty($item->icono) ? "<i class='material-icons'>$item->icono</i>" : '';

                $htmlMenu .= "<li class='no-padding'>
                    <ul class='no-padding".($IsParent ? " collapsible collapsible-accordion" : '')."'>
                        <li><a class='collapsible-header waves-effect' href='$Url'>".$Icono.' <span class="menu-text">'.$item->nombre."</span></a>";

                if($IsParent)
                { $htmlMenu .= "<div class='collapsible-body'><ul>\n".$this->getMenu($item->id_modulo)."\n</ul></div>"; }

                $htmlMenu .= "</li>
                    </ul>
                </li>";
            }
            return $htmlMenu;
    }

    public function getBarra($Parent = Null)
    {
        $id_usuario = empty(Auth::id()) ? Auth::id() : 1;
        $htmlAccion = '';

        $Rows = DB::select('SELECT *
            FROM ges_cat_modulos m
            LEFT JOIN ges_det_parents p ON p.fk_id_modulo = m.id_modulo AND p.activo = TRUE
            WHERE m.eliminar = FALSE
            AND m.activo = TRUE
            AND m.accion_barra = TRUE
            AND '.(empty($Parent) ? 'p.fk_id_parent is null' : 'p.fk_id_parent = '.$Parent).'
            AND m.id_modulo IN(
            	SELECT DISTINCT mp.fk_id_modulo
            	FROM ges_cat_usuarios u
            	LEFT JOIN ges_det_usuario_perfil up ON up.fk_id_usuario = u.id_usuario AND up.activo = TRUE
            	LEFT JOIN ges_det_modulo_perfil mp ON mp.fk_id_perfil = up.fk_id_perfil AND mp.activo = TRUE
            	WHERE u.eliminar = FALSE
            	AND u.activo = TRUE
            	AND u.id_usuario = :id_usuario
            	ORDER BY fk_id_modulo)
            ORDER BY id_modulo', ['id_usuario' => $id_usuario]);

        $Company = request()->company;
        foreach($Rows as $item)
        {
            $Url = !empty($item->url) ? URL('/').'/'.$item->url : '#';
            $htmlAccion .= '<a class="btn waves-effect waves-light" href="'.$Url.'">'.$item->nombre.'</a> ';
        }

        return $htmlAccion;
    }
}