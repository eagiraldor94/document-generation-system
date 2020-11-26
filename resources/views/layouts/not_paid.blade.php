@extends('base_layout')
@section('title')
	Error
@stop
@section('content')
<script>
	swal({
		type: "error",
		title: "¡El pago de su documento se encuentra en validación o su documento ya se ha generado!. Para correcciones en los documentos o si hay algún error por favor comuníquese a soporte@ludcis.com para darle solución en la mayor brevedad con la referencia del documento que es: {{$code}} (Codigo de error 470.)",
		showConfirmButton: true,
		confirmButtonText: "Cerrar",
		closeOnConfirm: false
		}).then((result)=>{
				if(result.value){
					window.location = "https://ludcis.com";
			}
		});
</script>
@stop