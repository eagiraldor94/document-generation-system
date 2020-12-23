@extends('base_layout')
@section('title')
	Redirigiendo a ePayco
@stop
@section('js')
<script type="text/javascript" src="https://checkout.epayco.co/checkout.js">   </script>
<script type="text/javascript">
  var handler = ePayco.checkout.configure({
          key: '',
          test: true
        })
var data={
          //Parametros compra (obligatorio)
          name: "Compra de documento en Ludcis",
          description: "{{$docName}}",
          currency: "COP",
          amount: "{{$amount}}",
          country: "CO",
          lang: "es",
          tax_base: "{{$base}}",
          tax: "{{$tax}}",
 

          //Onpage="false" - Standard="true"
          external: "true",


          //Atributos opcionales
          extra1: "{{$hash}}",
          extra2: "{{$post['newBuyer']}}",
          response: "",
          confirmation: "",

          //Atributos cliente
          name_billing: "{{$post['newBuyer']}}",
          type_doc_billing: "{{mb_strtolower($post['newIdType'])}}",
          number_doc_billing: "{{$post['newId']}}"

         //atributo deshabilitación metodo de pago
          //methodsDisable: ["TDC", "PSE","SP","CASH","DP"]

          }
    if ({{$amount}}>2500) {
         handler.open(data)
    }else{
        window.location.replace("https://ludcis.com");
    }
</script>
@stop