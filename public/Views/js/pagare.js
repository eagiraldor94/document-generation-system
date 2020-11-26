$(function(){
/*==============================================
=            AGREGAR DATOS DEL TIPO DE CUOTAS        =
==============================================*/
$("#tipo-cuotas").on("change",function(){
  $("#info-cuotas").empty();
  $("#fechas-pago").empty();
  var tipo = $(this).val();
  switch (tipo) {
  case 'Personalizado':
    $("#info-cuotas").append(
      '<div class="row">' +
        '<div class="form-group col-12 col-sm-6">' +
          '<div class="input-group mb-3">' +
            '<div class="input-group-prepend d-md-inline-flex">' +
              '<span class="input-group-text"><i class="fas fa-hashtag"></i></span>' +
            '</div>' +
            '<input id="cuotas-pago" type="number" class="form-control" step="1" min="1" max="60"  name="newFeesNumber" placeholder="Número de cuotas" required>' +
          '</div>' +
       '</div>' +
        '<div class="form-group col-12 col-sm-6">' +
          '<div class="input-group mb-3">' +
            '<div class="input-group-prepend d-md-inline-flex">' +
              '<span class="input-group-text"><i class="fas fa-calculator"></i></span>' +
            '</div>' +
            '<select name="newFeesMix" class="form-control" required>'+
                      '<option value="">¿Qué paga en las cuotas?</option>'+
                      '<option value="intereses">Interés</option>'+
                      '<option value="capital">Capital</option>'+
                      '<option value="capital e intereses">Capital e interés</option>'+
            '</select>' +
          '</div>' +
       '</div>' +
     '</div>'
            );
    break;
  case 'Un pago':
    break;
  case '':
    break;
  default:
    $("#info-cuotas").append(
      '<div class="row">' +
        '<div class="form-group col-12">' +
          '<div class="input-group mb-3">' +
            '<div class="input-group-prepend d-md-inline-flex">' +
              '<span class="input-group-text"><i class="fas fa-calculator"></i></span>' +
            '</div>' +
            '<select name="newFeesMix" class="form-control" required>'+
                      '<option value="">¿Qué paga en las cuotas?</option>'+
                      '<option value="intereses">Interés</option>'+
                      '<option value="capital">Capital</option>'+
                      '<option value="capital e intereses">Capital e interés</option>'+
            '</select>' +
          '</div>' +
       '</div>' +
     '</div>'
            );
    $("#fechas-pago").append(
       '<div class="form-group col-12">' +
        '<div class="input-group mb-3">' +
            '<div class="input-group-prepend d-md-inline-flex">' +
            '<span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>' +
            '</div>' +
            '<input type="text" class="form-control datepicker2" name="newPaymentDate1" placeholder="Fecha de inicio de pagos" required>' +
       '</div></div>'
            );
    $( ".datepicker2" ).datepicker({ format: 'dd/mm/yyyy',autoclose: true,language:'es' });
  }
});
/*==============================================
=            AGREGAR NÚMERO CUOTAS        =
==============================================*/
$("#info-cuotas").on("change","#cuotas-pago",function(){
  $("#fechas-pago").empty();
  var cuotas = $(this).val();
  for (var i = 1; i <= cuotas; i++) {
    $("#fechas-pago").append(
       '<div class="form-group col-12 col-sm-6">' +
        '<div class="input-group mb-3">' +
            '<div class="input-group-prepend d-md-inline-flex">' +
            '<span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>' +
            '</div>' +
            '<input type="text" class="form-control datepicker2" name="newPaymentDate'+i+'" placeholder="Fecha de pago número '+i+'" required>' +
       '</div></div>'
            );
  }
    $( ".datepicker2" ).datepicker({ format: 'dd/mm/yyyy',autoclose: true,language:'es' });
});
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
        '<div class="form-group col-12 col-sm-6">' +
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
       '<div class="form-group col-12 col-sm-6">' +
        '<div class="input-group mb-3">' +
            '<div class="input-group-prepend d-md-inline-flex">' +
            '<span class="input-group-text"><i class="fas fa-university"></i></span>' +
            '</div>' +
            '<input type="text" class="form-control" name="newPaymentBank" placeholder="Nombre del Banco" required>' +
          '</div>' +
       '</div>' +
     '</div>' +
      '<div class="row">' +
       '<div class="form-group col-12">' +
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
});
