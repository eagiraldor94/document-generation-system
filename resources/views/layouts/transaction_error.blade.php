@extends('base_layout')
@section('title')
	Error
@stop
@section('content')
<script>
	swal({
		type: "error",
		title: "{{$error}}. ¡Hemos tenido inconvenientes con la transacción!. (Codigo de referencia {{$referencia}})",
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