$(function(){

/*==============================================
=            AGREGAR DATOS DE LA CUENTA PAGO        =
==============================================*/
$("#notarializado").on("change",function(){
  $("#datos-notaria").empty();
  var notarializado = $(this).val();
  switch (notarializado) {
  case 'Si':
    $("#datos-notaria").append(
       '<div class="form-group ml-3" style="width:47.5%">' +
        '<div class="input-group mb-3">' +
            '<div class="input-group-prepend d-md-inline-flex">' +
            '<span class="input-group-text"><i class="fas fa-university"></i></span>' +
            '</div>' +
            '<input title="Notaría en la cual se inscribío (En letras)" type="text" class="form-control" placeholder="Notaría" name="newNotarie" required>' +
       '</div></div>' +
       '<div class="form-group ml-3" style="width:47.5%">' +
        '<div class="input-group mb-3">' +
            '<div class="input-group-prepend d-md-inline-flex">' +
            '<span class="input-group-text"><i class="far fa-circle"></i></span>' +
            '</div>' +
            '<input title="Círculo al que pertenece la notaría (Este depende de la ciudad y se puede averiguar por internet o llamando a la notaría)" type="text" class="form-control" placeholder="Círculo de la notaría" name="newCircle" required>' +
       '</div></div>' 
            );
    break;
  default:
  }
});
});
