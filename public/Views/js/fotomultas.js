$(function(){

/*==============================================
=            AGREGAR NÚMERO BIENES        =
==============================================*/
$("#numero-comparendos").on("change",function(){
  $("#lista-comparendos").empty();
  var comparendos = $(this).val();
  for (var i = 1; i <= comparendos; i++) {
    $("#lista-comparendos").append(
       '<div class="form-group col-12">' +
        '<div class="input-group mb-3">' +
            '<div class="input-group-prepend d-md-inline-flex">' +
            '<span class="input-group-text"><i class="fas fa-receipt"></i></span>' +
            '</div>' +
            '<input type="text" class="form-control" name="newPenalty'+i+'" placeholder="Comparendo número '+i+'" required>' +
       '</div></div>'
            );
  }
});
});
