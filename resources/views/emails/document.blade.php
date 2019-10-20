@extends('emails.mail')
@section('body')
<div class="row">
	<p>Buen día señor@ {{$name}}</p>

	<h3 class="align-self-center">La plataforma ludcis.com le hace envio del documento solicitado</h3>

	<h5><b>Fecha: </b>{{$fecha}}</h5>
	
	<p>Ante cualquier duda puede comunicarse a la direccion de correo soporte@ludcis.com o a través de nuestro chat de atención al cliente.</p>
	
	Para consultar haga click en el siguiente enlace.<br><br><br>
	<div class="row" style="width: 100%; text-align: center;">
	<a style="font-size: 20px;" href="https://ludcis.com" class="btn btn-primary">Ir a ludcis</a></div>

</div>
@stop