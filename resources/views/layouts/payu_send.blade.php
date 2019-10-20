@extends('base_layout')
@section('title')
	Redirigiendo a PayU
@stop
@section('content')
<form method="post" id="formularioPayU" action="https://sandbox.checkout.payulatam.com/ppp-web-gateway-payu/">
  <input name="merchantId"    type="hidden"  value="{{$merchant}}"   >
  <input name="accountId"    type="hidden"  value="{{$account}}"   >
  <input name="referenceCode" type="hidden"  value="{{$refCode}}" >
  <input name="description"   type="hidden"  value="Pago de documento ({{$docName}})"  >
  <input name="amount"        type="hidden"  value="{{$amount}}"   >
  <input name="tax"           type="hidden"  value="{{$tax}}"  >
  <input name="taxReturnBase" type="hidden"  value="{{$base}}" >
  <input name="signature"     type="hidden"  value="{{$sign}}"  >
  <input name="algorithmSignature"     type="hidden"  value="SHA256"  >
  <input name="currency"      type="hidden"  value="COP" >
  <input name="test"          type="hidden"  value="1" >
  <input name="buyerFullName"    type="hidden"  value="{{$post['newBuyer']}}" >
  <input name="buyerEmail"    type="hidden"  value="{{$post['newEmail']}}" >
  <input name="extra1"    type="hidden"  value="{{$hash}}" >
  <input name="extra2"    type="hidden"  value="{{$post['newBuyer']}}" >
  <input name="responseUrl"    type="hidden"  value="https://documentos2.ludcis.com/payu/retorno" >
  <input name="confirmationUrl"    type="hidden"  value="https://documentos2.ludcis.com/api/confirmations/payu" >
  <input style="display: none" name="Submit"        type="submit"  value="Enviar" >
</form>
@stop
@section('js')
<script type="text/javascript">
    document.getElementById('formularioPayU').submit(); // SUBMIT FORM
</script>
@stop