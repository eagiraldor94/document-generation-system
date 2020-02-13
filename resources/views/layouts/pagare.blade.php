@extends('base_layout')
@section('title')
	Pagaré
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
              <h1 class="m-0 text-dark"> Formulario <small>para pagaré</small></h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">Generación de documentos</li>
                <li class="breadcrumb-item active">Pagaré</li>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
          </div>
      </div>
      <div class="content">
        <div class="container">
<!-- Default box -->
      <div class="card mb-5 text-center">
        <form role="form" method="post" action="pagare" enctype="multipart/form-data">
          @csrf
        <div class="card-header bg-primary d-flex justify-content-center">
          <h1 class="card-title my-auto"><b>Datos del pagaré</b></h1>
        </div>
        <div class="card-body">
            <div class="row">
                  <div class="alert alert-secondary w-100">
                  <h2>INFORMACIÓN DEL DEUDOR</h2>
                  <input type="hidden" name="newCode" value="{{$code}}">
              </div>
            </div>
            <div class="row">  
              <div class="form-group ml-3" style="width: 96.5%">
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-user"></i></span>
                  </div>
                  <input class="form-control" type="text" name="newDebtor" placeholder="Nombre del deudor" required>
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
                    <select name="newDebtorIdType" class="form-control" required>
                      <option value="">Tipo de documento</option>
                      <option value="CC">Cedula de ciudadanía</option>
                      <option value="CE">Cedula de extranjería</option>
                    </select>
                  </div>
               </div>
               <div class="form-group ml-3" style="width:63%">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                    </div>
                    <input type="text" class="form-control" name="newDebtorId" placeholder="Número del documento" required>
                  </div>
               </div>
             </div>
             <div class="row">
            <!-- Residencia -->
               <div class="form-group ml-3" style="width:47.5%">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-home"></i></span>
                    </div>
                    <input title="Ciudad donde reside el deudor" type="text" class="form-control" name="newDebtorCity" placeholder="Ciudad de residencia" required>
                  </div>
               </div>
            <!-- Expedicion -->
               <div class="form-group ml-3" style="width:47.5%">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                    </div>
                    <input title="Ciudad donde se expidió el documento del deudor" type="text" class="form-control" name="newDebtorExpedition" placeholder="Ciudad de expedición" required>
                  </div>
               </div>
             </div>
            <div class="row">
                  <div class="alert alert-secondary w-100">
                  <h2>INFORMACIÓN DEL ACREEDOR</h2>
                  </div>
            </div>
            <div class="row">  
              <div class="form-group ml-3" style="width: 96.5%">
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-user"></i></span>
                  </div>
                  <input class="form-control" type="text" name="newCreditor" placeholder="Nombre del acreedor" required>
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
                    <select name="newCreditorIdType" class="form-control" required>
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
                    <input type="text" class="form-control" name="newCreditorId" placeholder="Número del documento" required>
                  </div>
               </div>
             </div>
             <div class="row">
            <!-- Expedicion -->
               <div class="form-group ml-3" style="width:96.5%">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                    </div>
                    <input title="Ciudad donde se expidió el documento del acreedor" type="text" class="form-control" name="newCreditorExpedition" placeholder="Ciudad de expedición" required>
                  </div>
               </div>
             </div>
            <div class="row">
                  <div class="alert alert-secondary w-100">
                  <h2>INFORMACIÓN DE LA DEUDA</h2>
                  </div>
            </div>
            <div class="row">  
              <div class="form-group ml-3" style="width: 96.5%">
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-dollar-sign"></i></span>
                  </div>
                  <input class="form-control" type="number" name="newAmount" placeholder="Monto de la deuda en pesos colombianos (COP)" required>
                </div>
              </div>
            </div>
             <div class="row">
            <!-- Ciudad -->
               <div class="form-group ml-3" style="width:96.5%">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                    </div>
                    <input title="Ciudad donde se realizó el prestamo" type="text" class="form-control" name="newCreditCity" placeholder="Ciudad del prestamo" required>
                  </div> 
               </div>
             </div>
            <div class="row">
              <div class="form-group ml-3" style="width:96.5%">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-file-invoice-dollar"></i></span>
                    </div>
                    <select id="tipo-pago" title="Metodo de pago de la deuda" name="newPaymentType" class="form-control" required>
                      <option value="Efectivo">Forma de pago</option>
                      <option value="Efectivo">Efectivo</option>
                      <option value="Deposito">Deposito</option>
                    </select>
                  </div>
               </div>
            </div>
            <div id="cuenta-pago">
            </div>
            <!-- Documento -->
            <div class="row">
              <div class="form-group ml-3" style="width:47.5%">
                <div class="input-group mb-3">
                  <input title="Interés efectivo mensual" min="0" step=".01" class="form-control" type="number" name="newInterest" placeholder="Interés" required>
                  <div class="input-group-append">
                    <span class="input-group-text"><i class="fas fa-percentage"></i></span>
                  </div>
                </div>
               </div>
              <div class="form-group ml-3" style="width:47.5%">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                      <span class="input-group-text"><i class="fas fa-credit-card"></i></span>
                    </div>
                    <select id="tipo-cuotas" name="newFeesType" class="form-control" required>
                      <option value="">Tipo de cuotas</option>
                      <option value="Un pago">Un pago</option>
                      <option value="Semanal">Semanal</option>
                      <option value="Mensual">Mensual</option>
                      <option value="Cada 2 semanas">Cada 2 semanas</option>
                      <option value="Cada 15 días">Cada 15 días</option>
                      <option value="Cada 30 días">Cada 30 días</option>
                      <option value="Personalizado">Personalizado</option>
                    </select>
                  </div>
               </div>
             </div>
             <div id="info-cuotas">
             </div>
             <div id="fechas-pago" class="row mx-auto">
             </div>
             <div class="row">
            <!-- fecha de pago final -->
               <div class="form-group ml-3" style="width:47.5%">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                    </div>
                    <input type="text" class="form-control datepicker" name="newPaymentDate" placeholder="Fecha de pago total" required>
                  </div>
               </div>
            <!-- Email -->
              <div class="form-group ml-3" style="width:47.5%">
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
                <button type="submit" class="btn btn-success" name="newDocument">Descargar pagaré</button>
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
  <script src="/Views/js/pagare.js"></script>
@stop