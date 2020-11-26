@extends('base_layout')
@section('title')
	Cesión de contrato
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
              <h1 class="m-0 text-dark"> Cesión <small>de contrato</small></h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">Generación de documentos</li>
                <li class="breadcrumb-item active">Cesión de contrato</li>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
          </div>
      </div>
      <div class="content">
        <div class="container">
<!-- Default box --> 
      <div class="card mb-5 text-center">
        <form role="form" method="post" action="ccesion" enctype="multipart/form-data">
          @csrf
        <div class="card-header bg-primary d-flex justify-content-center">
          <h1 class="card-title my-auto"><b>Datos del contrato</b></h1>
        </div>
        <div class="card-body">
            <div class="row">
                  <div class="alert alert-secondary w-100">
                  <h2>INFORMACIÓN DEL CEDENTE (QUÍEN CEDE EL CONTRATO)</h2>
                  <input type="hidden" name="newCode" value="{{$code}}">
              </div>
            </div>
            <div class="row">  
              <div class="form-group col-12">
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-user"></i></span>
                  </div>
                  <input class="form-control" type="text" name="newGrantor" placeholder="Nombre del cedente" required>
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
                    <select name="newGrantorIdType" class="form-control" required>
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
                    <input type="text" class="form-control" name="newGrantorId" placeholder="Número del documento" required>
                  </div>
               </div>
             </div>
             <div class="row">
            <!-- Direccion -->
               <div class="form-group col-12">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-home"></i></span>
                    </div>
                    <input title="Dirección del cedente" type="text" class="form-control" name="newGrantorAddress" placeholder="Dirección de residencia o notificacion" required>
                  </div>
               </div>
             </div>
             <div class="row">
            <!-- Residencia -->
               <div class="form-group col-12 col-sm-6">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-map-marked-alt"></i></span>
                    </div>
                    <input title="Ciudad donde reside el cedente" type="text" class="form-control" name="newGrantorCity" placeholder="Ciudad de residencia" required>
                  </div>
               </div>
            <!-- Expedicion -->
               <div class="form-group col-12 col-sm-6">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                    </div>
                    <input title="Ciudad donde se expidió el documento del cedente" type="text" class="form-control" name="newGrantorExpedition" placeholder="Ciudad de expedición (Opcional)">
                  </div>
               </div>
             </div>
            <div class="row">
                  <div class="alert alert-secondary w-100">
                  <h2>INFORMACIÓN DEL CESIONARIO (QUÍEN RECIBE EL CONTRATO)</h2>
              </div>
            </div>
            <div class="row">  
              <div class="form-group col-12">
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-user"></i></span>
                  </div>
                  <input class="form-control" type="text" name="newAssign" placeholder="Nombre del cesionario" required>
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
                    <select name="newAssignIdType" class="form-control" required>
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
                    <input type="text" class="form-control" name="newAssignId" placeholder="Número del documento" required>
                  </div>
               </div>
             </div>
             <div class="row">
            <!-- Direccion -->
               <div class="form-group col-12">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-home"></i></span>
                    </div>
                    <input title="Dirección del cesionario" type="text" class="form-control" name="newAssignAddress" placeholder="Dirección de residencia o notificacion" required>
                  </div>
               </div>
             </div>
             <div class="row">
            <!-- Residencia -->
               <div class="form-group col-12 col-sm-6">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-map-marked-alt"></i></span>
                    </div>
                    <input title="Ciudad donde reside el cesionario" type="text" class="form-control" name="newAssignCity" placeholder="Ciudad de residencia" required>
                  </div>
               </div>
            <!-- Expedicion -->
               <div class="form-group col-12 col-sm-6">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                    </div>
                    <input title="Ciudad donde se expidió el documento del cesionario" type="text" class="form-control" name="newAssignExpedition" placeholder="Ciudad de expedición (Opcional)">
                  </div>
               </div>
             </div>
            <div class="row">
                  <div class="alert alert-secondary w-100">
                  <h2>INFORMACIÓN DEL CEDIDO (OTRA PARTE DEL CONTRATO)</h2>
              </div>
            </div>
            <div class="row">  
              <div class="form-group col-12">
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-user"></i></span>
                  </div>
                  <input class="form-control" type="text" name="newCeded" placeholder="Nombre del cedido" required>
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
                    <select name="newCededIdType" class="form-control" required>
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
                    <input type="text" class="form-control" name="newCededId" placeholder="Número del documento" required>
                  </div>
               </div>
             </div>
             <div class="row">
            <!-- Direccion -->
               <div class="form-group col-12">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-home"></i></span>
                    </div>
                    <input title="Dirección del cedido" type="text" class="form-control" name="newCededAddress" placeholder="Dirección de residencia o notificacion" required>
                  </div>
               </div>
             </div>
             <div class="row">
            <!-- Residencia -->
               <div class="form-group col-12 col-sm-6">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-map-marked-alt"></i></span>
                    </div>
                    <input title="Ciudad donde reside el cedido" type="text" class="form-control" name="newCededCity" placeholder="Ciudad de residencia" required>
                  </div>
               </div>
            <!-- Expedicion -->
               <div class="form-group col-12 col-sm-6">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                    </div>
                    <input title="Ciudad donde se expidió el documento del cedido" type="text" class="form-control" name="newCededExpedition" placeholder="Ciudad de expedición (Opcional)">
                  </div>
               </div>
             </div>
            <div class="row">
                  <div class="alert alert-secondary w-100">
                  <h2>INFORMACIÓN DEL CONTRATO A CEDER</h2>
              </div>
            </div>
            <div class="row">
               <div class="form-group col-12">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-cogs"></i></span>
                    </div>
                    <input title="Tipo de contrato que se va a ceder. Ej: Arrendamiento, De suministro, De prestación de servicios" type="text" class="form-control" name="newContractType" placeholder="Tipo de contrato a ceder" required>
                  </div>
               </div>
            </div>
             <div class="row">
            <!-- Residencia -->
               <div class="form-group col-12">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-map-marked-alt"></i></span>
                    </div>
                    <input title="Municipio donde se pacta el contrato" type="text" class="form-control" name="newContractCity" placeholder="Ciudad de pacto" required>
                  </div>
               </div>
             </div>
             <div class="row">
            <!-- Cedente -->
               <div class="form-group col-12 col-sm-6">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-user-cog"></i></span>
                    </div>
                    <input title="Calidad que esta tiene en el contrato. Ej: Arrendatario, Contratante, etc." type="text" class="form-control" name="newGrantorRol" placeholder="Calidad del cedente" required>
                  </div>
               </div>
            <!-- Cedido -->
               <div class="form-group col-12 col-sm-6">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-user-cog"></i></span>
                    </div>
                    <input title="Calidad que esta tiene en el contrato. Ej: Arrendador, Prestador, etc." type="text" class="form-control" name="newCededRol" placeholder="Calidad del cedido" required>
                  </div>
               </div>
             </div>
             <div class="row">
            <!-- Fecha de inicio -->
               <div class="form-group col-12 col-sm-6">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                    </div>
                    <input title="Fecha de elaboración del contrato" type="text" class="form-control datepicker" name="newStartDate" placeholder="Fecha de elaboración del contrato" required>
                  </div>
               </div>
            <!-- Fecha de terminacion -->
               <div class="form-group col-12 col-sm-6">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                    </div>
                    <input title="Fecha final de vigencia del contrato" type="text" class="form-control datepicker" name="newEndDate" placeholder="Vigencia del contrato" required>
                  </div>
               </div>
             </div>
            <div class="row">
              <div class="form-group col-12">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                      <span class="input-group-text">¿Fue notarializado el contrato?</span>
                    </div>
                    <select id="notarializado" title="La pregunta se refiere a si el contrato se inscribió en una notaria" name="newContractNotarialized" class="form-control" required>
                      <option value="">Elija una opción</option>
                      <option value="Si">Si</option>
                      <option value="No">No</option>
                    </select>
                  </div>
               </div>
            </div>
             <div id="datos-notaria" class="row mx-auto">
               
             </div>
            <div class="row">
                  <div class="alert alert-secondary w-100">
                  <h2>INFORMACIÓN DEL DOCUMENTO</h2>
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
  <script src="/Views/js/cesion-contrato.js"></script>
@stop