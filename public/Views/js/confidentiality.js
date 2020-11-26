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
       '<div class="form-group col-12">' +
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
$("#revelador").on("change",function(){
  $("#datos-revelador").empty();
  var revelador = $(this).val();
  if (revelador != 'Si mismo') {
    $("#datos-revelador").append(
       '<div class="row">'+
              '<div class="form-group col-12">'+
                '<div class="input-group mb-3">'+
                  '<div class="input-group-prepend d-md-inline-flex">'+
                    '<span class="input-group-text"><i class="fa fa-user"></i></span>'+
                 '</div>'+
                  '<input title="nombre de la compañía o persona a quien representa" type="text" class="form-control" name="newFirstCompany" placeholder="Nombre de la compañía o persona" required>'+
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
             '</div>'
            );
    }
});
$("#receptor").on("change",function(){
  $("#datos-receptor").empty();
  var receptor = $(this).val();
  if (receptor != 'Si mismo') {
    $("#datos-receptor").append(
       '<div class="row">'+
              '<div class="form-group col-12">'+
                '<div class="input-group mb-3">'+
                  '<div class="input-group-prepend d-md-inline-flex">'+
                    '<span class="input-group-text"><i class="fa fa-user"></i></span>'+
                 '</div>'+
                  '<input title="nombre de la compañía o persona a quien representa" type="text" class="form-control" name="newSecondCompany" placeholder="Nombre de la compañía o persona" required>'+
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
             '</div>'
            );
    }
});
});
