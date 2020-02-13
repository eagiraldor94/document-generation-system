@extends('base_layout')
@section('title')
	Error
@stop
@section('content')
<script>
	swal({
		type: "error",
		title: "¡El documento no se ha encontrado en las bases de datos, por favor contacte nuestra división de soporte, a través de chat o a soporte@ludcis.com para poder ayudarle en la mayor brevedad!. (Codigo de error 450)",
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