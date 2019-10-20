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
      <div class="card mb-5 pb-5 text-center">
        <form role="form" method="post" action="pago" enctype="multipart/form-data">
          @csrf
        <div class="card-header bg-warning d-flex justify-content-center">
          <h1 class="card-title my-auto"><b>ESTE ES UN DOCUMENTO CON COSTO</b></h1>
        </div>
        <div class="card-body">
            <div class="row">
                  <div class="alert alert-light w-100">
                  <h2>El costo del documento {{$product->name}} es de $ {{number_format($product->value,2)}} COP (pesos colombianos). Este valor ya trae el iva incluido y su pago será recibido a través PayU para brindarle un entorno seguro.
                  Sin trampas ni costes ocultos!<br>
                  <b>Recuerde:</b> Si su pago no es por medios eléctronicos, de le enviará un correo con un enlace para la generacion de su documento tan pronto sea aprobado.</h2>
                  <input type="hidden" name="newCode" value="{{$document->hash}}">
              </div>
            </div>
            <div class="row">
                  <div class="alert alert-secondary w-100">
                  <h2>DATOS DE FACTURACIÓN</h2>
              </div>
            </div>
            <div class="row">  
              <div class="form-group ml-3" style="width: 96.5%">
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
              <div class="form-group ml-3" style="width:32%">
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
               <div class="form-group ml-3" style="width:63%">
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
              <div class="form-group ml-3" style="width:96.5%">
                <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                </div>
                <input title="Email al que se enviará la factura" class="form-control" type="email" name="newEmail" placeholder="Email" required>
              </div>
               </div>
            </div>
        </div>
        <div class="card-footer">
          <div class="row w-100">
            <div class="form-group text-center" style="width: 100%">
              <div class="input-group justify-content-center">
                <button type="submit" class="btn btn-success" name="newPayment">Proceder al pago</button>
              </div>
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