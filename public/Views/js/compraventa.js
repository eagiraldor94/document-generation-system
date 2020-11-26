$(function(){
/*==============================================
=            AGREGAR DATOS DEL DOCUMENTO DE ADUANA        =
==============================================*/
$("#tipo-aduana").on("change",function(){
  $("#datos-aduana").empty();
  var aduana = $(this).val();
  switch (aduana) {
  case 'Acta':
    $("#datos-aduana").append(
      '<div class="row">' +
        '<div class="form-group col-12 col-sm-6">' +
          '<div class="input-group mb-3">' +
            '<div class="input-group-prepend d-md-inline-flex">' +
            '<span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>' +
            '</div>' +
            '<input type="text" class="form-control datepicker2" name="newAduanaDate" placeholder="Fecha del documento aduanero" required>' +
          '</div>' +
       '</div>' +
       '<div class="form-group col-12 col-sm-6">' +
        '<div class="input-group mb-3">' +
            '<div class="input-group-prepend d-md-inline-flex">' +
            '<span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>' +
            '</div>' +
            '<input type="text" class="form-control" name="newAduanaCity" placeholder="Ciudad de suscripción" title="Ciudad donde se suscribio el documento aduanero" required>' +
          '</div>' +
       '</div>' +
     '</div>' +
      '<div class="row">' +
       '<div class="form-group col-12">' +
        '<div class="input-group mb-3">' +
            '<div class="input-group-prepend d-md-inline-flex">' +
            '<span class="input-group-text"><i class="fas fa-hashtag"></i></span>' +
            '</div>' +
            '<input type="text" class="form-control" name="newAduanaNumber" placeholder="Número del documento aduanero" required>' +
          '</div>' +
       '</div>' +
     '</div>'
            );
    $( ".datepicker2" ).datepicker({ format: 'dd/mm/yyyy',autoclose: true,language:'es' });
    break;
  case 'Manifiesto':
    $("#datos-aduana").append(
      '<div class="row">' +
        '<div class="form-group col-12 col-sm-6">' +
          '<div class="input-group mb-3">' +
            '<div class="input-group-prepend d-md-inline-flex">' +
            '<span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>' +
            '</div>' +
            '<input type="text" class="form-control datepicker2" name="newAduanaDate" placeholder="Fecha del documento aduanero" required>' +
          '</div>' +
       '</div>' +
       '<div class="form-group col-12 col-sm-6">' +
        '<div class="input-group mb-3">' +
            '<div class="input-group-prepend d-md-inline-flex">' +
            '<span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>' +
            '</div>' +
            '<input type="text" class="form-control" name="newAduanaCity" placeholder="Ciudad de suscripción" title="Ciudad donde se suscribio el documento aduanero" required>' +
          '</div>' +
       '</div>' +
     '</div>' +
      '<div class="row">' +
       '<div class="form-group col-12">' +
        '<div class="input-group mb-3">' +
            '<div class="input-group-prepend d-md-inline-flex">' +
            '<span class="input-group-text"><i class="fas fa-hashtag"></i></span>' +
            '</div>' +
            '<input type="text" class="form-control" name="newAduanaNumber" placeholder="Número del documento aduanero" required>' +
          '</div>' +
       '</div>' +
     '</div>'
            );
    $( ".datepicker2" ).datepicker({ format: 'dd/mm/yyyy',autoclose: true,language:'es' });
    break;
  default:
  }
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
            '<input type="text" class="form-control" name="newPaymentBank" placeholder="nombre del Banco" required>' +
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
/*==============================================
=            AGREGAR NÚMERO TESTIGOS        =
==============================================*/
$("#numero-testigos").on("change",function(){
  $("#info-testigos").empty();
  var cuotas = $(this).val();
  for (var i = 1; i <= cuotas; i++) {
    $("#info-testigos").append(
       '<div class="row">'+
              '<div class="form-group col-12">'+
                '<div class="input-group mb-3">'+
                  '<div class="input-group-prepend">'+
                    '<span class="input-group-text"><i class="fa fa-user"></i></span>'+
                 '</div>'+
                  '<input class="form-control" type="text" name="newWitness'+i+'" placeholder="Nombre del testigo número '+i+'" required>'+
               '</div>'+
             '</div>'+
            '</div>'+
            '<div class="row">'+
             '<div class="form-group col-12 col-sm-4">'+
                '<div class="input-group mb-3">'+
                   '<div class="input-group-prepend d-md-inline-flex">'+
                   '<span class="input-group-text"><i class="fas fa-id-card"></i></span>'+
                   '</div>'+
                    '<select name="newWitness'+i+'IdType" class="form-control" required>'+
                      '<option value="">Tipo de documento</option>'+
                      '<option value="CC">Cedula de ciudadanía</option>'+
                      '<option value="CE">Cedula de extranjería</option>'+
                    '</select>'+
                  '</div>'+
               '</div>'+
               '<div class="form-group col-12 col-sm-8">'+
                '<div class="input-group mb-3">'+
                    '<div class="input-group-prepend d-md-inline-flex">'+
                    '<span class="input-group-text"><i class="fas fa-hashtag"></i></span>'+
                    '</div>'+
                    '<input type="text" class="form-control" name="newWitness'+i+'Id" placeholder="Número del documento" required>'+
                  '</div>'+
               '</div>'+
             '</div>'
            );
  }
});
});
