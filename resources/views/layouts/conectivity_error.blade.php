@extends('base_layout')
@section('title')
	Error
@stop
@section('content')
<script>
	swal({
		type: "error",
		title: "Tenemos problemas con la conexion a los servidores. <br>Por favor intente de nuevo más tarde. <br>(Si es recurrente le agradecemos reportarlo a soporte@ludcis.com)",
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