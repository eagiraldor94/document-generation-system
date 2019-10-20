$(function(){

/*==============================================
=            AGREGAR DATOS DE LA CUENTA         =
==============================================*/
$("#tipo-contrato").on("change",function(){
  $("#cargo").empty();
  var cargo = $(this).val();
  switch (cargo) {
  case 'Contrato':
    $("#cargo").append(
      '<div class="row">' +
       '<div class="form-group ml-3" style="width:93%">' +
        '<div class="input-group mb-3">' +
            '<div class="input-group-prepend d-md-inline-flex">' +
            '<span class="input-group-text"><i class="fas fa-user-cog"></i></span>' +
            '</div>' +
            '<input type="text" class="form-control" name="newCharge" placeholder="Cargo en la empresa" required>' +
          '</div>' +
       '</div>' +
     '</div>'
            );
    break;
  default:
  }
});
});
