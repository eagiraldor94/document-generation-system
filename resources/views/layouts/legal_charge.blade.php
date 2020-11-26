@extends('base_layout')
@section('title')
	Cobro Prejurídico
@stop
@section('css')
  <link rel="stylesheet" href="/Views/plugins/datepicker/datepicker3.css">
@stop
@section('content')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0 text-dark"> Cobro <small>prejurídico</small></h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">Generación de documentos</li>
                <li class="breadcrumb-item active">Cobro prejurídico</li>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
          </div>
      </div>
      <div class="content">
        <div class="container">
<!-- Default box --> 
      <div class="card mb-5 text-center">
        <form role="form" method="post" action="/prejuridico" enctype="multipart/form-data">
          @csrf
        <div class="card-header bg-primary d-flex justify-content-center">
          <h1 class="card-title my-auto"><b>Datos del cobro</b></h1>
        </div>
        <div class="card-body">
            <div class="row">
                  <div class="alert alert-secondary w-100">
                  <h2>INFORMACIÓN DEL DEUDOR</h2>
                  <input type="hidden" name="newCode" value="{{$code}}">
              </div>
            </div>
            <div class="row">  
              <div class="form-group col-12">
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-user"></i></span>
                  </div>
                  <input class="form-control" type="text" name="newDebtor" placeholder="Nombre del deudor" required>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="form-group col-12">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-user-cog"></i></span>
                    </div>
                    <select name="newDebtorClass" class="form-control" required>
                      <option value="">Tipo de deudor</option>
                      <option value="la copropiedad">Copropiedad</option>
                      <option value="el conjunto residencial">Conjunto residencial</option>
                      <option value="la empresa">Empresa</option>
                      <option value="el señor">Señor</option>
                      <option value="la señora">Señora</option>
                    </select>
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
                    <select name="newDebtorIdType" class="form-control">
                      <option value="">Tipo de documento (Opcional)</option>
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
                    <input type="text" class="form-control" name="newDebtorId" placeholder="Número del documento (Opcional)">
                  </div>
               </div>
             </div>
            <div class="row">
                  <div class="alert alert-secondary w-100">
                  <h2>INFORMACIÓN DEL COBRADOR</h2>
              </div>
            </div>
            <div class="row">  
              <div class="form-group col-12">
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-user"></i></span>
                  </div>
                  <input class="form-control" type="text" name="newCreditor" placeholder="Nombre del acreedor" required>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="form-group col-12">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-user-cog"></i></span>
                    </div>
                    <select name="newCreditorClass" id="tipo-acreedor" class="form-control" required>
                      <option value="">Tipo de acreedor</option>
                      <option value="la copropiedad">Copropiedad</option>
                      <option value="el conjunto residencial">Conjunto residencial</option>
                      <option value="la empresa">Empresa</option>
                      <option value="el señor">Señor</option>
                      <option value="la señora">Señora</option>
                    </select>
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
                    <select name="newCreditorIdType" class="form-control">
                      <option value="">Tipo de documento (Opcional)</option>
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
                    <input type="text" class="form-control" name="newCreditorId" placeholder="Número del documento (Opcional)">
                  </div>
               </div>
             </div>
             <div class="row">
            <!-- Direccion -->
               <div class="form-group col-12 col-sm-6">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-home"></i></span>
                    </div>
                    <input title="Dirección del acreedor" type="text" class="form-control" name="newCreditorAddress" placeholder="Dirección de contacto" required>
                  </div>
               </div>
               <div class="form-group col-12 col-sm-6">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                    </div>
                    <input title="Ciudad del acreedor" placeholder="Ciudad de domicilio" type="text" class="form-control" name="newCreditorCity" required>
                  </div>
               </div>
             </div>
             <div id="representante-acreedor" class="row mx-auto">
               
             </div>
            <div class="row">
              <div class="form-group col-12 col-sm-6">
                <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fa fa-phone-square"></i></span>
                </div>
                <input class="form-control" type="text" name="newCreditorPhone" placeholder="Telefono del acreedor" data-inputmask="'mask':'(999) 999-9999'" data-mask>
              </div>
               </div>
              <!-- Email -->
                <div class="form-group col-12 col-sm-6">
                  <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                  </div>
                  <input title="Email del acreedor" class="form-control" type="email" name="newCreditorEmail" placeholder="Email del acreedor">
                </div>
                 </div>
             </div>
            <div class="row">
                  <div class="alert alert-secondary w-100">
                  <h2>INFORMACIÓN DEL DOCUMENTO</h2>
                  </div>
            </div>
            <div class="row">
              <div class="form-group col-12">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-cogs"></i></span>
                    </div>
                    <select name="newContract" id="tipo-contrato" class="form-control" required>
                      <option value="">Naturaleza de la obligación</option>
                      <option value="El contrato">El contrato</option>
                      <option value="El pagaré">El pagaré</option>
                      <option value="La letra de cambio">La letra de cambio</option>
                      <option value="La sentencia">La sentencia</option>
                      <option value="La factura">La factura</option>
                      <option value="El canon de arrendamiento">El canon de arrendamiento</option>
                      <option value="El canon de administración">El canon de administración</option>
                      <option value="El prestamo personal">El prestamo personal</option>
                    </select>
                  </div>
               </div>

            </div>
            <div id="complemento-contrato">
              
            </div>
             <div class="row">
            <!-- Ciudad -->
              <div class="form-group col-12">
                <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                </div>
                <input class="form-control" type="text" name="newDocumentCity" placeholder="Ciudad de elaboración del documento">
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
                <input title="Email al que se enviará el documento" class="form-control" type="email" name="newEmail" placeholder="Email de envio">
              </div>
               </div>
            </div>
        </div>
        <div class="card-footer">
          <div class="row w-100">
              <div class="input-group justify-content-center">
                <button type="submit" class="btn btn-success" name="newDocument">Generar Documento</button>
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
@section('js')
  <script src="/Views/plugins/datepicker/bootstrap-datepicker.js"></script>
  <script src="/Views/js/datepicker.js"></script>
  <script src="/Views/plugins/datepicker/locales/bootstrap-datepicker.es.js"></script>
  <script src="/Views/js/prejuridico.js"></script>
@stop