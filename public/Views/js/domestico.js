$( function() {
$("#dia-pago").on("change",function(){
  var max = $(this).attr('max');
  var dias = $(this).val();
  if (dias>max) {
    $(this).val(max);
  }
});
 });