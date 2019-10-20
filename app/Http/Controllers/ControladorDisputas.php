<?php

namespace ludcis\Http\Controllers;

use Illuminate\Http\Request;

use Mail;

class ControladorDisputas extends Controller
{
    //
    public function store(){
    	$payu = ControladorGeneral::correoPayU($_POST);

    }
}
