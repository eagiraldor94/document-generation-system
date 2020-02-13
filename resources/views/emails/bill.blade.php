@extends('emails.mail')
@section('body')
<div class="row">
	<p>Buen día señor@ {{$name}}</p>

	<h3 class="align-self-center">La plataforma ludcis.com le hace envio de su factura de venta</h3>

	<h5><b>Fecha: </b>{{$fecha}}</h5>
	
	<p>Ante cualquier duda puede comunicarse a la direccion de correo soporte@ludcis.com o a través de nuestro chat de atención al cliente.</p>
	
	Para acceder a su documento haga click en el siguiente enlace.<br><br><br>
	<div class="row" style="width: 100%; text-align: center;"><a style="color:#fff; text-decoration: none; font-size: 20px; background-color: #FFD900; border-radius: 5px; padding: 8px 20px" href="https://documentos2.ludcis.com/documentos/{{$hash}}" class="btn btn-primary">Generar mi documento</a></div><br><br>
	<div class="row" style="width: 100%; text-align: center;">O escanea el siguiente codigo QR:</div>
	<div class="row" style="width: 100%; text-align: center;"><img src="{!!$message->embedData($qr, 'QrCode.png', 'image/png')!!}"></div>

</div>
@stop