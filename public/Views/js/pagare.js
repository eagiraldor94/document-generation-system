$(function(){

/*==============================================
=            AGREGAR DATOS DE LA CUENTA PAGO        =
==============================================*/
$("#tipo-pago").on("change",function(){
  $("#cuenta-pago").empty();
  var deposito = $(this).val();
  switch (deposito) {
  case 'Deposito':
    $("#cuenta-pago").append(
      '<div class="row">' +
        '<div class="form-group ml-3" style="width:47.5%">' +
          '<div class="input-group mb-3">' +
            '<div class="input-group-prepend d-md-inline-flex">' +
              '<span class="input-group-text"><i class="fas fa-wallet"></i></span>' +
            '</div>' +
            '<select title="Tipo de cuenta pago" name="newPaymentAccount" class="form-control" required>' +
              '<option value="Cuenta de ahorros">Tipo de cuenta</option>' +
              '<option value="Cuenta de ahorros">Ahorros</option>' +
              '<option value="Cuenta corriente">Corriente</option>' +
              '<option value="Fiducuenta">Fiducuenta</option>' +
            '</select>' +
          '</div>' +
       '</div>' +
       '<div class="form-group ml-3" style="width:47.5%">' +
        '<div class="input-group mb-3">' +
            '<div class="input-group-prepend d-md-inline-flex">' +
            '<span class="input-group-text"><i class="fas fa-university"></i></span>' +
            '</div>' +
            '<input type="text" class="form-control" name="newPaymentBank" placeholder="nombre del Banco" required>' +
          '</div>' +
       '</div>' +
     '</div>' +
      '<div class="row">' +
       '<div class="form-group ml-3" style="width:96.5%">' +
        '<div class="input-group mb-3">' +
            '<div class="input-group-prepend d-md-inline-flex">' +
            '<span class="input-group-text"><i class="fas fa-hashtag"></i></span>' +
            '</div>' +
            '<input type="number" class="form-control" name="newPaymentNumber" placeholder="Número de cuenta" required>' +
          '</div>' +
       '</div>' +
     '</div>'
            );
    break;
  default:
  }
});
/*==============================================
=            AGREGAR NÚMERO CUOTAS        =
==============================================*/
$("#cuotas-pago").on("change",function(){
  $("#fechas-pago").empty();
  var cuotas = $(this).val();
  for (var i = 1; i <= cuotas; i++) {
    $("#fechas-pago").append(
       '<div class="form-group ml-3" style="width:47.5%">' +
        '<div class="input-group mb-3">' +
            '<div class="input-group-prepend d-md-inline-flex">' +
            '<span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>' +
            '</div>' +
            '<input type="text" class="form-control datepicker2" name="newPaymentDate'+i+'" placeholder="Fecha de pago número '+i+'" required>' +
       '</div></div>'
            );
  }
    $( ".datepicker2" ).datepicker();
});
});
