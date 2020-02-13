<?php

namespace ludcis\Http\Controllers;

use Illuminate\Http\Request;
use ludcis;

class ControladorBusquedas extends Controller
{
    public static function buscarDocumentos($word){

    	$productos = ludcis\ProductForSearch::where('name','like','%'.$word.'%')->orWhere('code','like','%'.$word.'%')->get();
    	echo json_encode($productos);
    }
}
