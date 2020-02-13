$(function(){
function toDate(dateStr) {
  var parts = dateStr.split("/")
  return new Date(parts[2], parts[1] - 1, parts[0])
}
function testDays(inicio,termino) {
  var days;
  var date1 = toDate(inicio);
  var date2 = toDate(termino);
  var time = date2.getTime() - date1.getTime();
  var days = time / (1000 * 3600 * 24); 
  days = Math.floor(days);
  
  return days
}
/*==============================================
=            AGREGAR DATOS DE LA CUENTA PAGO        =
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
    $("#dias-prueba").attr('max','60');
    break;
  case 'A término indefinido':
    $("#dias-prueba").attr('max','60');
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
    $("#dias-prueba").attr('max','60');
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
    var termino = $('#fecha-terminacion').val();
    if (termino != "" && termino != null) {
      var inicio = $('#fecha-inicio').val();
      var dias = testDays(inicio,termino);
      dias = Math.floor(dias/5);
      $("#dias-prueba").attr('max',dias);
    }
    break;
  default:
  }
});
$('#fin').on("change","#fecha-terminacion",function(){
  var termino = $(this).val();
  var contrato = $("#tipo-contrato").val();
  var inicio = $("#fecha-inicio").val();
  if (contrato == 'A término fijo inferior a un año' && inicio != "" && inicio != null) {
    var dias = testDays(inicio,termino);
    dias = Math.floor(dias/5);
    $("#dias-prueba").attr('max',dias);
  }
});
$("#fecha-inicio").on("change",function(){
  var inicio = $(this).val();
  var contrato = $("#tipo-contrato").val();
  var termino = $("#fecha-terminacion").val();
  if (contrato == 'A término fijo inferior a un año' && termino != "" && termino != null) {
    var dias = testDays(inicio,termino);
    dias = Math.floor(dias/5);
    $("#dias-prueba").attr('max',dias);
  }
});
$("#dias-prueba").on("change",function(){
  var max = $(this).attr('max');
  var dias = $(this).val();
  if (dias>max) {
    $(this).val(max);
  }
});
});
