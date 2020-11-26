$(function(){

/*==============================================
=            AGREGAR DATOS DE LA CUENTA PAGO        =
==============================================*/
$("#tipo-acreedor").on("change",function(){
  $("#representante-acreedor").empty();
  var tipo = $(this).val();
  if (tipo == 'la copropiedad' || tipo == 'el conjunto residencial'|| tipo == 'la empresa') {
    $("#representante-acreedor").append(
       '<div class="form-group col-12 col-sm-6">' +
        '<div class="input-group mb-3">' +
            '<div class="input-group-prepend d-md-inline-flex">' +
            '<span class="input-group-text"><i class="fas fa-user"></i></span>' +
            '</div>' +
            '<input type="text" class="form-control" placeholder="Nombre de quíen envía el documento" name="newAgent" required>' +
       '</div></div>' +
       '<div class="form-group col-12 col-sm-6">' +
        '<div class="input-group mb-3">' +
            '<div class="input-group-prepend d-md-inline-flex">' +
            '<span class="input-group-text"><i class="fas fa-cogs"></i></span>' +
            '</div>' +
            '<input type="text" class="form-control" placeholder="Cargo que ocupa" name="newCharge" required>' +
       '</div></div>' 
            );
  }
});
$("#tipo-contrato").on("change",function(){
  $("#complemento-contrato").empty();
  var tipo = $(this).val();
  switch (tipo) {
    case 'El contrato':
      $("#complemento-contrato").append(
        '<div class="row">' +
          '<div class="form-group col-12 col-sm-6">' +
            '<div class="input-group mb-3">' +
              '<div class="input-group-prepend d-md-inline-flex">' +
                '<span class="input-group-text"><i class="fas fa-file"></i></span>' +
              '</div>' +
              '<select name="newComplement1" class="form-control" required>'+
                        '<option value="">Tipo de contrato</option>'+
                        '<option value="Compraventa">Compraventa</option>'+
                        '<option value="Prenda">Prenda</option>'+
                        '<option value="Suministro">Suministro</option>'+
                        '<option value="Prestación de servicios">Prestación de servicios</option>'+
                        '<option value="Arrendamiento">Arrendamiento</option>'+
                        '<option value="Leasing">Leasing</option>'+
                        '<option value="Agencia">Agencia</option>'+
                        '<option value="Factoring">Factoring</option>'+
                        '<option value="Franquicia">Franquicia</option>'+
                        '<option value="Permuta">Permuta</option>'+
                        '<option value="Comisión">Comisión</option>'+
                        '<option value="Mandato">Mandato</option>'+
                        '<option value="Transporte">Transporte</option>'+
                        '<option value="Deposito">Deposito</option>'+
                        '<option value="Seguro">Seguro</option>'+
                        '<option value="Préstamo">Préstamo</option>'+
                        '<option value="Forwards">Forwards</option>'+
                        '<option value="Underwriting">Underwriting</option>'+
                        '<option value="Anticresis">Anticresis</option>'+
                        '<option value="Maquila">Maquila</option>'+
                        '<option value="Outsourcing">Outsourcing</option>'+
                        '<option value="Corretaje">Corretaje</option>'+
                        '<option value="Fiduciario">Fiduciario</option>'+
                        '<option value="Concesión">Concesión</option>'+
                        '<option value="Fiducia mercantil">Fiducia mercantil</option>'+
                        '<option value="Publicidad">Publicidad</option>'+
              '</select>' +
            '</div>' +
         '</div>' +
          '<div class="form-group col-12 col-sm-6">' +
          '<div class="input-group mb-3">' +
              '<div class="input-group-prepend d-md-inline-flex">' +
              '<span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>' +
              '</div>' +
              '<input type="text" class="form-control datepicker2" name="newComplement2" placeholder="Fecha de firma" required>' +
         '</div>>' +
         '</div>' +
       '</div>'
              );
      $( ".datepicker2" ).datepicker({ format: 'dd/mm/yyyy',autoclose: true,language:'es' });
      break;
    case 'El pagaré':
      $("#complemento-contrato").append(
      '<div class="row">' +
       '<div class="form-group col-12">' +
        '<div class="input-group mb-3">' +
            '<div class="input-group-prepend d-md-inline-flex">' +
            '<span class="input-group-text"><i class="fas fa-hashtag"></i></span>' +
            '</div>' +
            '<input type="number" class="form-control" name="newComplement1" placeholder="Número del documento" required>' +
          '</div>' +
       '</div>' +
     '</div>'
              );
      break;
    case 'La letra de cambio':
      $("#complemento-contrato").append(
      '<div class="row">' +
       '<div class="form-group col-12">' +
        '<div class="input-group mb-3">' +
            '<div class="input-group-prepend d-md-inline-flex">' +
            '<span class="input-group-text"><i class="fas fa-hashtag"></i></span>' +
            '</div>' +
            '<input type="number" class="form-control" name="newComplement1" placeholder="Número del documento" required>' +
          '</div>' +
       '</div>' +
     '</div>'
              );
      break;
    case 'La sentencia':
      $("#complemento-contrato").append(
      '<div class="row">' +
       '<div class="form-group col-12">' +
        '<div class="input-group mb-3">' +
            '<div class="input-group-prepend d-md-inline-flex">' +
            '<span class="input-group-text"><i class="fas fa-hashtag"></i></span>' +
            '</div>' +
            '<input type="number" class="form-control" name="newComplement1" placeholder="Número del documento" required>' +
          '</div>' +
       '</div>' +
     '</div>'
              );
      break;
    case 'La factura':
      $("#complemento-contrato").append(
      '<div class="row">' +
       '<div class="form-group col-12">' +
        '<div class="input-group mb-3">' +
            '<div class="input-group-prepend d-md-inline-flex">' +
            '<span class="input-group-text"><i class="fas fa-hashtag"></i></span>' +
            '</div>' +
            '<input type="number" class="form-control" name="newComplement1" placeholder="Número del documento" required>' +
          '</div>' +
       '</div>' +
     '</div>'
              );
      break;
    case 'El canon de arrendamiento':
      $("#complemento-contrato").append(
      '<div class="row">' +
       '<div class="form-group col-12">' +
        '<div class="input-group mb-3">' +
            '<div class="input-group-prepend d-md-inline-flex">' +
            '<span class="input-group-text"><i class="fas fa-eye"></i></span>' +
            '</div>' +
            '<textarea class="form-control" name="newComplement1" placeholder="Descripción de la propiedad" required></textarea>' +
          '</div>' +
       '</div>' +
     '</div>'
              );
      break;
    case 'El canon de administración':
      $("#complemento-contrato").append(
      '<div class="row">' +
       '<div class="form-group col-12">' +
        '<div class="input-group mb-3">' +
            '<div class="input-group-prepend d-md-inline-flex">' +
            '<span class="input-group-text"><i class="fas fa-eye"></i></span>' +
            '</div>' +
            '<textarea class="form-control" name="newComplement1" placeholder="Descripción de la propiedad" required></textarea>' +
          '</div>' +
       '</div>' +
     '</div>'
              );
      break;
    case 'El prestamo personal':
      $("#complemento-contrato").append(
        '<div class="row">' +
          '<div class="form-group col-12">' +
          '<div class="input-group">' +
              '<div class="input-group-prepend d-md-inline-flex">' +
              '<span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>' +
              '</div>' +
              '<input type="text" class="form-control datepicker2" name="newComplement2" placeholder="Fecha de prestamo" required>' +
         '</div>>' +
         '</div>' +
       '</div>'
              );
      $( ".datepicker2" ).datepicker({ format: 'dd/mm/yyyy',autoclose: true,language:'es' });
      break;
    default:
      // statements_def
      break;
  }
});
});
