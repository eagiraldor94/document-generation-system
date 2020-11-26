$(function(){
  function toDate(dateStr) {
  var parts = dateStr.split("/")
  return new Date(parts[2], parts[1] - 1, parts[0])
}
/*==============================================
=            AGREGAR DATOS DE EMPRESA        =
==============================================*/
$("#tipo-contratante").on("change",function(){
  var contratante = $(this).val();
  $("#info-empresa").empty();
  switch (contratante) {
  case 'PJ':
    $("#info-empresa").append(
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
             '</div>');
    break;
  default:
  }
});
/*==============================================
=            AGREGAR DATOS DE TIPO DE CONTRATO        =
==============================================*/
$("#tipo-contrato").on("change",function(){
  var contrato = $(this).val();
  $("#fin").empty();
  switch (contrato) {
  case 'A término fijo':
    $("#fin").append(
        '<div class="form-group" style="width:100%">' +
          '<div class="input-group mb-3">' +
            '<div class="input-group-prepend d-md-inline-flex">' +
              '<span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>' +
            '</div>' +
            '<input id="fecha-terminacion" title="Fecha final de las labores" type="text" class="form-control datepicker2" name="newEndDate" placeholder="Fecha de terminación" required>' +
          '</div>' +
       '</div>');
    $( ".datepicker2" ).datepicker({ format: 'dd/mm/yyyy',autoclose: true,language:'es' });
    $("#fecha-terminacion").prop('required', true);
    break;
  case 'A término indefinido':
    break;
  case 'Por obra o labor':
    $("#fin").append(
        '<div class="form-group" style="width:100%">' +
          '<div class="input-group mb-3">' +
            '<div class="input-group-prepend d-md-inline-flex">' +
              '<span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>' +
            '</div>' +
            '<input id="fecha-terminacion" title="Fecha final de las labores" type="text" class="form-control datepicker2" name="newEndDate" placeholder="Fecha de terminación" required>' +
          '</div>' +
       '</div>');
    $( ".datepicker2" ).datepicker({ format: 'dd/mm/yyyy',autoclose: true,language:'es' });
    $("#fecha-terminacion").prop('required', true);
    break;
  case 'A término fijo inferior a un año':
    $("#fin").append(
        '<div class="form-group" style="width:100%">' +
          '<div class="input-group mb-3">' +
            '<div class="input-group-prepend d-md-inline-flex">' +
              '<span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>' +
            '</div>' +
            '<input id="fecha-terminacion" title="Fecha final de las labores" type="text" class="form-control datepicker2" name="newEndDate" placeholder="Fecha de terminación" required>' +
          '</div>' +
       '</div>');
    $( ".datepicker2" ).datepicker({ format: 'dd/mm/yyyy',autoclose: true,language:'es' });
    $("#fecha-terminacion").prop('required', true);
    break;
  default:
  }
});
$("#cambio-cargo").on("change",function(){
  var cambio = $(this).val();
  $("#nuevo-cargo").empty();
  if (cambio == 1) {
    $("#nuevo-cargo").append(
        '<div class="form-group w-100">' +
          '<div class="input-group mb-3">' +
            '<div class="input-group-prepend d-md-inline-flex">' +
              '<span class="input-group-text"><i class="fas fa-user-cog"></i></span>' +
            '</div>' +
            '<input title="Definir si cambia el cargo al modificar el contrato" type="text" class="form-control" name="newCharge" placeholder="Nuevo Cargo Empleado" required>' +
          '</div>' +
       '</div>');
  }
});
});