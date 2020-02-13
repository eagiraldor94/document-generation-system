@extends('emails.mail')
@section('body')
<div class="row">
	<p>Buen día señor@ {{$name}}</p>

	<div class="row" style="width: 100%; text-align: center;">
	<h3 class="align-self-center" style="color:#000; text-decoration: none; font-size: 18px"><b>La plataforma <span style="color:#000; text-decoration: none;">ludcis</span> le hace envio del documento solicitado.</b></h3>
	</div>

	<h5><b>Fecha: </b>{{$fecha}}</h5>
	
	<div class="row" style="width: 100%; text-align: justify;">
	<span style="font-size: 15px"><p>Ante cualquier duda puede comunicarse a la direccion de correo soporte@ludcis.com o a través de nuestro chat de atención al cliente.</p>
	Para consultar haga click en el siguiente enlace.</span></div><br><br><br>
	<div class="row" style="width: 100%; text-align: center;">
	<b><a style="color:#fff; text-decoration: none; font-size: 20px; background-color: #FFD900; border-radius: 5px; padding: 8px 20px" href="{{$page}}" class="btn btn-primary">Ir a ludcis</a></b></div><br><br>
	<div class="row" style="width: 100%; text-align: center;">O escanea el siguiente codigo QR:</div>
	<div class="row" style="width: 100%; text-align: center;"><img src="{!!$message->embedData($qr, 'QrCode.png', 'image/png')!!}"></div>
</div>
@stop