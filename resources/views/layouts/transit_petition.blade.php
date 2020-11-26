@extends('base_layout')
@section('title')
	Derecho de petición
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
              <h1 class="m-0 text-dark"> Derecho de petición <small>fotodetecciones</small></h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">Generación de documentos</li>
                <li class="breadcrumb-item active">Derecho de petición</li>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
          </div>
      </div>
      <div class="content">
        <div class="container">
<!-- Default box --> 
      <div class="card mb-5 text-center">
        <form role="form" method="post" action="/ptransito" enctype="multipart/form-data">
          @csrf
        <div class="card-header bg-primary d-flex justify-content-center">
          <h1 class="card-title my-auto"><b>Datos del derecho</b></h1>
        </div>
        <div class="card-body">
            <div class="row">
                  <div class="alert alert-secondary w-100">
                  <h2>INFORMACIÓN DEL PETICIONARIO (PERSONA QUE PRESENTA EL DERECHO DE PETICIÓN)</h2>
                  <input type="hidden" name="newCode" value="{{$code}}">
              </div>
            </div>
            <div class="row">  
              <div class="form-group col-12">
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-user"></i></span>
                  </div>
                  <input class="form-control" type="text" name="newPetitioner" placeholder="Nombre del Peticionario" required>
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
                    <select name="newPetitionerIdType" class="form-control" required>
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
                    <input type="text" class="form-control" name="newPetitionerId" placeholder="Número del documento" required>
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
                    <input title="Dirección del Peticionario" type="text" class="form-control" name="newPetitionerAddress" placeholder="Direccion de residencia" required>
                  </div>
               </div>
            <!-- Barrio -->
               <div class="form-group col-12 col-sm-6">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-map-signs"></i></span>
                    </div>
                    <input title="Barrio del Peticionario" type="text" class="form-control" name="newPetitionerNeighborhood" placeholder="Barrio de residencia" required>
                  </div>
               </div>
             </div>
             <div class="row">
            <!-- Residencia -->
               <div class="form-group col-12 col-sm-6">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                    </div>
                    <input title="Ciudad o municipio donde reside el Peticionario" type="text" class="form-control" name="newPetitionerCity" placeholder="Ciudad de residencia" required>
                  </div>
               </div>
            <!-- Expedicion -->
               <div class="form-group col-12 col-sm-6">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                    </div>
                    <input title="Ciudad donde se expidió el documento del Peticionario" type="text" class="form-control" name="newPetitionerExpedition" placeholder="Ciudad de expedición (Opcional)">
                  </div>
               </div>
             </div>
            <div class="row">
              <div class="form-group col-12 col-sm-6">
                <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fa fa-phone-square"></i></span>
                </div>
                <input class="form-control" type="text" name="newPetitionerPhone" placeholder="Telefono del peticionario" data-inputmask="'mask':'(999) 999-9999'" data-mask>
              </div>
               </div>
              <!-- Email -->
                <div class="form-group col-12 col-sm-6">
                  <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                  </div>
                  <input title="Email del peticionario" class="form-control" type="email" name="newPetitionerEmail" placeholder="Email del peticionario">
                </div>
                 </div>
             </div>
            <div class="row">
                  <div class="alert alert-secondary w-100">
                  <h2>INFORMACIÓN DE LOS COMPARENDOS</h2>
                  </div>
            </div>
            <div class="row">
               <div class="form-group col-12 col-sm-6">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-university"></i></span>
                    </div>
                    <input title="Secretaría en la cual se encuentran los comparendos. Por ejemplo: Medellín, Bogota, Envigado, etc." type="text" class="form-control" name="newTransitSecretary" placeholder="Secretaría de movilidad" required>
                  </div>
               </div>
               <div class="form-group col-12 col-sm-6">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                    </div>
                    <input id="numero-comparendos" type="number" class="form-control" step="1" min="1" name="newPenaltyNumber" placeholder="Cantidad de comparendos" required>
                  </div>
               </div>
            </div>
             <div id="lista-comparendos" class="row mx-auto">
               
             </div>
            <div class="row">
                  <div class="alert alert-secondary w-100">
                  <h2>INFORMACIÓN DEL DOCUMENTO</h2>
                  </div>
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
  <script src="/Views/js/fotomultas.js"></script>
@stop