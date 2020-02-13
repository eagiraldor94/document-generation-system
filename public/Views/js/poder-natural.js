$(function(){
/*==============================================
=            AGREGAR DATOS DEL DOCUMENTO DE ADUANA        =
==============================================*/
$("#graduado").on("change",function(){
  $("#datos-tarjeta").empty();
  var tprof = $(this).val();
  switch (tprof) {
  case 'Si':
    $("#datos-tarjeta").append(
      '<div class="row">' +
       '<div class="form-group ml-3" style="width:96.5%">' +
        '<div class="input-group mb-3">' +
            '<div class="input-group-prepend d-md-inline-flex">' +
            '<span class="input-group-text"><i class="fas fa-hashtag"></i></span>' +
            '</div>' +
            '<input type="text" class="form-control" name="newAgentGraduateNumber" placeholder="Número de la tarjeta profesional del apoderado" required>' +
          '</div>' +
       '</div>' +
     '</div>'
            );
    break;
  default:
  }
});
/*==============================================
=            AGREGAR NÚMERO REPRESENTADOS        =
==============================================*/
$("#numero-representados").on("change",function(){
  $("#lista-representados").empty();
  var cuotas = $(this).val();
  for (var i = 1; i <= cuotas; i++) {
    $("#lista-representados").append(
            '<div class="row text-center">'+
             '<div class="form-group ml-3" style="width:80%">'+
                '<div class="input-group mb-3">'+
                   '<div class="input-group-prepend d-md-inline-flex">'+
                   '<span class="input-group-text"><i class="fas fa-user-clock"></i></span>'+
                   '</div>'+
                    '<select name="newRepresented'+i+'Type" class="form-control" required>'+
                      '<option value="">Tipo de representado</option>'+
                      '<option value="el Señor">el Señor</option>'+
                      '<option value="la Señora">la Señora</option>'+
                      '<option value="el Menor">el Menor</option>'+
                      '<option value="la Menor">la Menor</option>'+
                    '</select>'+
                  '</div>'+
               '</div>'+
               '<div class="form-group ml-3" style="width:80%">'+
                '<div class="input-group mb-3">'+
                    '<div class="input-group-prepend d-md-inline-flex">'+
                    '<span class="input-group-text"><i class="fas fa-user"></i></span>'+
                    '</div>'+
                    '<input type="text" class="form-control" name="newRepresented'+i+'" placeholder="Nombre completo del representado" required>'+
                  '</div>'+
               '</div>'+
             '</div>'
            );
  }
});
});
