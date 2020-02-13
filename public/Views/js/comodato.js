$(function(){

/*==============================================
=            AGREGAR NÚMERO BIENES        =
==============================================*/
$("#numero-bienes").on("change",function(){
  $("#lista-bienes").empty();
  var bienes = $(this).val();
  for (var i = 1; i <= bienes; i++) {
    $("#lista-bienes").append(
       '<div class="form-group ml-3" style="width:96.5%">' +
        '<div class="input-group mb-3">' +
            '<div class="input-group-prepend d-md-inline-flex">' +
            '<span class="input-group-text"><b>*</b></span>' +
            '</div>' +
            '<input type="text" class="form-control" name="newGood'+i+'" placeholder="Bien número '+i+'" required>' +
       '</div></div>'
            );
  }
});
/*==============================================
=            AGREGAR NÚMERO TESTIGOS        =
==============================================*/
$("#numero-testigos").on("change",function(){
  $("#info-testigos").empty();
  var cuotas = $(this).val();
  for (var i = 1; i <= cuotas; i++) {
    $("#info-testigos").append(
       '<div class="row">'+
              '<div class="form-group ml-3" style="width: 96.5%">'+
                '<div class="input-group mb-3">'+
                  '<div class="input-group-prepend">'+
                    '<span class="input-group-text"><i class="fa fa-user"></i></span>'+
                 '</div>'+
                  '<input class="form-control" type="text" name="newWitness'+i+'" placeholder="Nombre del testigo número '+i+'" required>'+
               '</div>'+
             '</div>'+
            '</div>'+
            '<div class="row">'+
             '<div class="form-group ml-3" style="width:32%">'+
                '<div class="input-group mb-3">'+
                   '<div class="input-group-prepend d-md-inline-flex">'+
                   '<span class="input-group-text"><i class="fas fa-id-card"></i></span>'+
                   '</div>'+
                    '<select name="newWitness'+i+'IdType" class="form-control" required>'+
                      '<option value="">Tipo de documento</option>'+
                      '<option value="CC">Cedula de ciudadanía</option>'+
                      '<option value="CE">Cedula de extranjería</option>'+
                    '</select>'+
                  '</div>'+
               '</div>'+
               '<div class="form-group ml-3" style="width:63%">'+
                '<div class="input-group mb-3">'+
                    '<div class="input-group-prepend d-md-inline-flex">'+
                    '<span class="input-group-text"><i class="fas fa-hashtag"></i></span>'+
                    '</div>'+
                    '<input type="text" class="form-control" name="newWitness'+i+'Id" placeholder="Número del documento" required>'+
                  '</div>'+
               '</div>'+
             '</div>'
            );
  }
});
});
