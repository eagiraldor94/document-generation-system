@extends('base_layout')
@section('title')
	Error
@stop
@section('content')
<script>
	swal({
		type: "error",
		title: "¡Ha ocurrido un error en la validación de la autenticidad de los datos! (Codigo de referencia {{$referencia}})",
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