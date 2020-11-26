@extends('base_layout')
@section('title')
	{{$product->name}}
@stop
@section('content')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
      <div class="content">
        <div class="container">
<!-- Default box -->
      <div class="card my-5 pb-0 text-center">
        <form style="margin-bottom: 0px" role="form" method="post" action="/pago" enctype="multipart/form-data">
          @csrf
        <div class="card-header bg-warning d-flex justify-content-center">
          <h2 class="my-auto" style="font-family: 'Montserrat';font-size: '28px';"><span style='font-weight: 900 !important'>ESTE ES UN DOCUMENTO CON COSTO</span></h2>
        </div>
        <div class="card-body">
            <div class="row">
                  <div class="alert alert-light w-100">
                  <h4 style="font-family: 'Rubik',sans-serif">El costo del documento {{$product->name}} es de $ {{number_format($product->value,2)}} COP (pesos colombianos). Este valor ya trae el iva incluido y su pago será recibido a través PayU para brindarle un entorno seguro.
                  Sin trampas ni costes ocultos!<br><br>
                  <span style="color:#000"><b>Recuerde:</b> Si su pago no es por medios eléctronicos, se le enviará un correo con un enlace para la generacion de su documento tan pronto sea aprobado.</span><br><br>
                  Para más información sobre el documento<br><b><a style="color:#000" target="_blank" href="{{$product->page}}">Clic aquí</a></b>.</h4>
                  <input type="hidden" name="newCode" value="{{$document->hash}}">
              </div>
            </div>
            <div class="row">
                  <div class="alert alert-secondary w-100">
                  <h3><b>DATOS DE FACTURACIÓN</b></h3>
              </div>
            </div>
            <div class="row">  
              <div class="form-group col-12">
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-user"></i></span>
                  </div>
                  <input class="form-control" type="text" name="newBuyer" placeholder="Nombre del comprador" required>
                </div>
              </div>
            </div>
            <!-- Documento -->
            <div class="row">
              <div class="form-group col-12 col-sm-4">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                    </div>
                    <select name="newIdType" class="form-control" required>
                      <option value="">Tipo de documento</option>
                      <option value="CC">Cedula de ciudadanía</option>
                      <option value="CE">Cedula de extranjería</option>
                      <option value="NIT">NIT</option>
                    </select>
                  </div>
               </div>
               <div class="form-group col-12 col-sm-8">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                    </div>
                    <input type="text" class="form-control" name="newId" placeholder="Número del documento" required>
                  </div>
               </div>
             </div>
             <div class="row">
            <!-- Email -->
              <div class="form-group col-12">
                <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                </div>
                <input title="Email al que se enviará la factura" class="form-control" type="email" name="newEmail" placeholder="Email" required>
              </div>
               </div>
            </div>
            <div class="row">  
              <div class="form-group col-12">
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-cut"></i></span>
                  </div>
                  <input class="form-control" type="text" name="newDiscount" placeholder="Codigo de descuento (opcional)">
                </div>
              </div>
            </div>
        </div>
        <div class="card-footer">
          <div class="row w-100">
              <div class="input-group justify-content-center">
                <button type="submit" class="btn btn-success" name="newPayment">Proceder al pago</button>
              </div>
          </div>
        </div>
        <!-- /.card-body -->

        </form>
      </div>
      <!-- /.card -->
    </div>
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->  
  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
    <div class="p-3">
      <h5>Ludcis</h5>
      <p>Pagare</p>
    </div>
  </aside>
  <!-- /.control-sidebar -->
@stop