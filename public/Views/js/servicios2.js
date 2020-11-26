$(function(){

/*==============================================
=            AGREGAR DATOS DE EMPRESA        =
==============================================*/
$("#tipo-contratante").on("change",function(){
  var contratante = $(this).val();
  $("#info-contratante").empty();
  switch (contratante) {
  case 'PJ':
    $("#info-contratante").append(
              '<div class="row">'+
                '<div class="form-group col-12">'+
                  '<div class="input-group mb-3">'+
                    '<div class="input-group-prepend">'+
                      '<span class="input-group-text"><i class="fa fa-user"></i></span>'+
                    '</div>'+
                    '<input class="form-control" type="text" name="newFirstCompany" placeholder="Nombre de la Empresa" required>'+
                  '</div>'+
                '</div>'+
              '</div>'+
              '<div class="row">'+
                '<div class="form-group col-12 col-sm-4">'+
                  '<div class="input-group mb-3">'+
                      '<div class="input-group-prepend d-md-inline-flex">'+
                      '<span class="input-group-text"><i class="fas fa-id-card"></i></span>'+
                      '</div>'+
                      '<select name="newFirstCompanyIdType" class="form-control" required>'+
                        '<option value="">Tipo de documento</option>'+
                        '<option value="CC">Cedula de ciudadanía</option>'+
                        '<option value="CE">Cedula de extranjería</option>'+
                        '<option value="NIT">NIT</option>'+
                      '</select>'+
                    '</div>'+
                 '</div>'+
                 '<div class="form-group col-12 col-sm-8">'+
                  '<div class="input-group mb-3">'+
                      '<div class="input-group-prepend d-md-inline-flex">'+
                      '<span class="input-group-text"><i class="fas fa-hashtag"></i></span>'+
                      '</div>'+
                      '<input type="text" class="form-control" name="newFirstCompanyId" placeholder="Número del documento" required>'+
                    '</div>'+
                 '</div>'+
               '</div>'+
               '<div class="row">'+
                 '<div class="form-group col-12">'+
                  '<div class="input-group mb-3">'+
                      '<div class="input-group-prepend d-md-inline-flex">'+
                      '<span class="input-group-text"><i class="fas fa-home"></i></span>'+
                      '</div>'+
                      '<input type="text" class="form-control" name="newFirstAddress" placeholder="Dirección de la empresa" required>'+
                    '</div>'+
                 '</div>'+
               '</div>'+
             '<div class="row">'+
               '<div class="form-group col-12">'+
                '<div class="input-group mb-3">'+
                    '<div class="input-group-prepend d-md-inline-flex">'+
                    '<span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>'+
                    '</div>'+
                    '<input type="text" class="form-control" name="newFirstCity" placeholder="Ciudad de la empresa" required>'+
                  '</div>'+
               '</div>'+
             '</div>'+
            '<div class="row">'+
              '<div class="form-group col-12">'+
                '<div class="input-group mb-3">'+
                  '<div class="input-group-prepend">'+
                    '<span class="input-group-text"><i class="fa fa-university"></i></span>'+
                  '</div>'+
                  '<input class="form-control" type="text" name="newFirstCompanyCamera" placeholder="Camara de comercio donde está inscrita" title="Camara de comercio donde se encuentra matriculada la empresa. Por ejemplo para la camara de comercio de Bogota poner (de Bogota), para la camara de comercio Aburra Sur poner (Aburra Sur)" required>'+
                '</div>'+
              '</div>'+
            '</div>'+
            '<div class="row">'+
              '<div class="form-group col-12">'+
                '<div class="input-group mb-3">'+
                  '<div class="input-group-prepend">'+
                    '<span class="input-group-text"><i class="fa fa-hashtag"></i></span>'+
                  '</div>'+
                  '<input class="form-control" type="number" name="newFirstCompanyCameraNumber" placeholder="No. Certificado de camara de comercio" required>'+
                '</div>'+
              '</div>'+
            '</div>');
    break;
  default:
  }
});
/*==============================================
=            AGREGAR DATOS DE PRESTADOR       =
==============================================*/
$("#tipo-prestador").on("change",function(){
  var prestador = $(this).val();
  $("#info-prestador").empty();
  switch (prestador) {
  case 'PJ':
    $("#info-prestador").append(
              '<div class="row">'+
                '<div class="form-group col-12">'+
                  '<div class="input-group mb-3">'+
                    '<div class="input-group-prepend">'+
                      '<span class="input-group-text"><i class="fa fa-user"></i></span>'+
                    '</div>'+
                    '<input class="form-control" type="text" name="newSecondCompany" placeholder="Nombre de la Empresa" required>'+
                  '</div>'+
                '</div>'+
              '</div>'+
              '<div class="row">'+
                '<div class="form-group col-12 col-sm-4">'+
                  '<div class="input-group mb-3">'+
                      '<div class="input-group-prepend d-md-inline-flex">'+
                      '<span class="input-group-text"><i class="fas fa-id-card"></i></span>'+
                      '</div>'+
                      '<select name="newSecondCompanyIdType" class="form-control" required>'+
                        '<option value="">Tipo de documento</option>'+
                        '<option value="CC">Cedula de ciudadanía</option>'+
                        '<option value="CE">Cedula de extranjería</option>'+
                        '<option value="NIT">NIT</option>'+
                      '</select>'+
                    '</div>'+
                 '</div>'+
                 '<div class="form-group col-12 col-sm-8">'+
                  '<div class="input-group mb-3">'+
                      '<div class="input-group-prepend d-md-inline-flex">'+
                      '<span class="input-group-text"><i class="fas fa-hashtag"></i></span>'+
                      '</div>'+
                      '<input type="text" class="form-control" name="newSecondCompanyId" placeholder="Número del documento" required>'+
                    '</div>'+
                 '</div>'+
               '</div>'+
            '<div class="row">'+
              '<div class="form-group col-12">'+
                '<div class="input-group mb-3">'+
                  '<div class="input-group-prepend">'+
                    '<span class="input-group-text"><i class="fa fa-university"></i></span>'+
                  '</div>'+
                  '<input class="form-control" type="text" name="newSecondCompanyCamera" placeholder="Camara de comercio donde está inscrita" title="Camara de comercio donde se encuentra matriculada la empresa. Por ejemplo para la camara de comercio de Bogota poner (de Bogota), para la camara de comercio Aburra Sur poner (Aburra Sur)" required>'+
                '</div>'+
              '</div>'+
            '</div>'+
            '<div class="row">'+
              '<div class="form-group col-12">'+
                '<div class="input-group mb-3">'+
                  '<div class="input-group-prepend">'+
                    '<span class="input-group-text"><i class="fa fa-hashtag"></i></span>'+
                  '</div>'+
                  '<input class="form-control" type="number" name="newSecondCompanyCameraNumber" placeholder="No. Certificado de camara de comercio" required>'+
                '</div>'+
              '</div>'+
            '</div>');
      $('#tarjeta-profesional').val('No');
    break;
  case 'PN':
    $("#info-prestador").append(
             '<div class="row">'+
               '<div class="form-group col-12 col-sm-6">'+
                '<div class="input-group mb-3">'+
                    '<div class="input-group-prepend d-md-inline-flex">'+
                    '<span class="input-group-text"><i class="fas fa-compass"></i></span>'+
                    '</div>'+
                    '<input title="Ciudad donde nació el prestador" type="text" class="form-control" name="newSecondBornSite" placeholder="Lugar de nacimiento" required>'+
                  '</div>'+
               '</div>'+
               '<div class="form-group col-12 col-sm-6">'+
                '<div class="input-group mb-3">'+
                    '<div class="input-group-prepend d-md-inline-flex">'+
                    '<span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>'+
                    '</div>'+
                    '<input title="fecha en que se expidió el documento del deudor" type="text" class="form-control datepicker" name="newSecondBornDate" placeholder="Fecha de nacimiento" required>'+
                  '</div>'+
               '</div>'+
             '</div>'+
             '<div class="row">'+
               '<div class="form-group col-12 col-sm-6">'+
                '<div class="input-group mb-3">'+
                    '<div class="input-group-prepend d-md-inline-flex">'+
                    '<span class="input-group-text"><i class="fas fa-globe-americas"></i></span>'+
                    '</div>'+
                    '<input type="text" class="form-control" name="newSecondNationality" placeholder="Nacionalidad del prestador" required>'+
                  '</div>'+
               '</div>'+
               '<div class="form-group col-12 col-sm-6">'+
                '<div class="input-group mb-3">'+
                    '<div class="input-group-prepend d-md-inline-flex">'+
                    '<span class="input-group-text"><i class="fas fa-file-medical-alt"></i></span>'+
                    '</div>'+
                    '<input type="text" class="form-control" name="newSecondEPS" placeholder="EPS del prestador" required>'+
                  '</div>'+
               '</div>'+
             '</div>'+
             '<div class="row">'+
               '<div class="form-group col-12 col-sm-6">'+
                '<div class="input-group mb-3">'+
                    '<div class="input-group-prepend d-md-inline-flex">'+
                    '<span class="input-group-text"><i class="fas fa-wallet"></i></span>'+
                    '</div>'+
                    '<input title="Fondo de pensiones del prestador" type="text" class="form-control" name="newSecondAFP" placeholder="Fondo de pensiones" required>'+
                  '</div>'+
               '</div>'+
               '<div class="form-group col-12 col-sm-6">'+
                '<div class="input-group mb-3">'+
                    '<div class="input-group-prepend d-md-inline-flex">'+
                    '<span class="input-group-text"><i class="fas fa-medkit"></i></span>'+
                    '</div>'+
                    '<input type="text" class="form-control" name="newSecondARP" placeholder="Atención de riesgos profesionales" required>'+
                  '</div>'+
               '</div>'+
             '</div>');
    break;
  default:
  }
});
/*==============================================
=            AGREGAR DATOS DE EMPRESA        =
==============================================*/
$("#tarjeta-profesional").on("change",function(){
  var tarjeta = $(this).val();
  $("#info-tp").empty();
  switch (tarjeta) {
  case 'Si':
    $("#info-tp").append(
              '<div class="row">'+
                '<div class="form-group col-12">'+
                  '<div class="input-group mb-3">'+
                    '<div class="input-group-prepend">'+
                      '<span class="input-group-text"><i class="fas fa-hashtag"></i></span>'+
                    '</div>'+
                    '<input class="form-control" type="number" name="newSecondProfesionalCard" placeholder="Número de tarjeta profesional" required>'+
                  '</div>'+
                '</div>'+
              '</div>');
    break;
  default:
  }
});
/*==============================================
=            AGREGAR DATOS DE EMPRESA        =
==============================================*/
$("#ubicacion-servicio").on("change",function(){
  var tarjeta = $(this).val();
  $("#locacion-servicio").empty();
  switch (tarjeta) {
  case 'Si':
    $("#locacion-servicio").append(
              '<div class="row">'+
                '<div class="form-group col-12">'+
                  '<div class="input-group mb-3">'+
                    '<div class="input-group-prepend">'+
                      '<span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>'+
                    '</div>'+
                    '<input class="form-control" type="text" name="newServiceAddress" placeholder="Dirección donde se presta el servicio" required>'+
                  '</div>'+
                '</div>'+
              '</div>');
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
=            AGREGAR DATOS DE LA CUENTA PAGO        =
==============================================*/
$("#periodo-pago").on("change",function(){
  $("#cuotas").empty();
  $("#info-cuotas").empty();
  $("#fechas-pago").empty();
  var pago = $(this).val();
  if (pago == 'periodico') {
    $("#cuotas").append(
      '<div class="row">' +
        '<div class="form-group col-12">' +
          '<div class="input-group mb-3">' +
            '<div class="input-group-prepend d-md-inline-flex">' +
              '<span class="input-group-text"><i class="fas fa-credit-card"></i></span>' +
            '</div>' +
            '<select id="tipo-cuotas" name="newFeesType" class="form-control" required>' +
                      '<option value="">Tipo de cuotas</option>'+
                      '<option value="Semanal">Semanal</option>'+
                      '<option value="Mensual">Mensual</option>'+
                      '<option value="Cada 2 semanas">Cada 2 semanas</option>'+
                      '<option value="Cada 15 días">Cada 15 días</option>'+
                      '<option value="Cada 30 días">Cada 30 días</option>'+
                      '<option value="Personalizado">Personalizado</option>'+
                   '</select>' +
          '</div>' +
       '</div>' +
     '</div>'
            );
  }
});
/*==============================================
=            AGREGAR DATOS DEL TIPO DE CUOTAS        =
==============================================*/
$("#cuotas").on("change","#tipo-cuotas",function(){
  $("#info-cuotas").empty();
  $("#fechas-pago").empty();
  var tipo = $(this).val();
  switch (tipo) {
  case 'Personalizado':
    $("#info-cuotas").append(
      '<div class="row">' +
        '<div class="form-group col-12">' +
          '<div class="input-group mb-3">' +
            '<div class="input-group-prepend d-md-inline-flex">' +
              '<span class="input-group-text"><i class="fas fa-hashtag"></i></span>' +
            '</div>' +
            '<input id="cuotas-pago" type="number" class="form-control" step="1" min="1" max="60"  name="newFeesNumber" placeholder="Número de cuotas" required>' +
          '</div>' +
       '</div>' +
     '</div>'
            );
    break;
  case '':
    break;
  default:
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
});
