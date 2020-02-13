$(function(){

/*==============================================
=            AGREGAR DATOS DE LA CUENTA PAGO        =
==============================================*/
$("#tipo-acreedor").on("change",function(){
  $("#representante-acreedor").empty();
  var tipo = $(this).val();
  if (tipo == 'la copropiedad' || tipo == 'el conjunto residencial'|| tipo == 'la empresa') {
    $("#representante-acreedor").append(
       '<div class="form-group ml-3" style="width:47.5%">' +
        '<div class="input-group mb-3">' +
            '<div class="input-group-prepend d-md-inline-flex">' +
            '<span class="input-group-text"><i class="fas fa-user"></i></span>' +
            '</div>' +
            '<input type="text" class="form-control" placeholder="Nombre de quíen envía el documento" name="newAgent" required>' +
       '</div></div>' +
       '<div class="form-group ml-3" style="width:47.5%">' +
        '<div class="input-group mb-3">' +
            '<div class="input-group-prepend d-md-inline-flex">' +
            '<span class="input-group-text"><i class="fas fa-cogs"></i></span>' +
            '</div>' +
            '<input type="text" class="form-control" placeholder="Cargo que ocupa" name="newCharge" required>' +
       '</div></div>' 
            );
  }
});
});
