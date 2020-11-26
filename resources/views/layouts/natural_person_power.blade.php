@extends('base_layout')
@section('title')
	Poder especial amplio y suficiente
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
              <h1 class="m-0 text-dark"> Poder amplio y suficiente <small> otorgado por persona natural</small></h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">Generación de documentos</li>
                <li class="breadcrumb-item active">Poder especial</li>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
          </div>
      </div>
      <div class="content">
        <div class="container">
<!-- Default box --> 
      <div class="card mb-5 text-center">
        <form role="form" method="post" action="/pnatural" enctype="multipart/form-data">
          @csrf
        <div class="card-header bg-primary d-flex justify-content-center">
          <h1 class="card-title my-auto"><b>Datos del poder</b></h1>
        </div>
        <div class="card-body">
            <div class="row">
                  <div class="alert alert-secondary w-100">
                  <h2>INFORMACIÓN DEL OTORGANTE (PERSONA QUE OTORGA EL PODER)</h2>
                  <input type="hidden" name="newCode" value="{{$code}}">
              </div>
            </div>
            <div class="row">  
              <div class="form-group col-12">
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-user"></i></span>
                  </div>
                  <input class="form-control" type="text" name="newGrantor" placeholder="Nombre del Otorgante" required>
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
            <!-- Residencia -->
               <div class="form-group col-12 col-sm-6">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                    </div>
                    <input title="Ciudad o municipio donde reside el Otorgante" type="text" class="form-control" name="newGrantorCity" placeholder="Ciudad de residencia" required>
                  </div>
               </div>
            <!-- Expedicion -->
               <div class="form-group col-12 col-sm-6">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                    </div>
                    <input title="Ciudad donde se expidió el documento del Otorgante" type="text" class="form-control" name="newGrantorExpedition" placeholder="Ciudad de expedición" required>
                  </div>
               </div>
             </div>
            <div class="row">
                  <div class="alert alert-secondary w-100">
                  <h2>INFORMACIÓN DEL APODERADO (PERSONA QUE RECIBE EL PODER)</h2>
              </div>
            </div>
            <div class="row">  
              <div class="form-group col-12">
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-user"></i></span>
                  </div>
                  <input class="form-control" type="text" name="newAgent" placeholder="Nombre del Apoderado" required>
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
                    <select name="newAgentIdType" class="form-control" required>
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
                    <input type="text" class="form-control" name="newAgentId" placeholder="Número del documento" required>
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
                    <input title="Ciudad o municipio donde reside el Apoderado" type="text" class="form-control" name="newAgentCity" placeholder="Ciudad de residencia" required>
                  </div>
               </div>
            <!-- Expedicion -->
               <div class="form-group col-12 col-sm-6">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                    </div>
                    <input title="Ciudad donde se expidió el documento del Apoderado" type="text" class="form-control" name="newAgentExpedition" placeholder="Ciudad de expedición" required>
                  </div>
               </div>
             </div>
            <div class="row">
              <div class="form-group col-12 col-sm-6">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-user-cog"></i></span>
                    </div>
                    <select title="Titulo que recibe el apoderado" name="newAgentTitle" class="form-control" required>
                      <option value="">Título del apoderado</option>
                      <option value="El Señor">El Señor</option>
                      <option value="La Señora">La Señora</option>
                      <option value="El Doctor">El Doctor</option>
                      <option value="La Doctora">La Doctora</option>
                    </select>
                  </div>
               </div>
              <div class="form-group col-12 col-sm-6">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-id-badge"></i></span>
                    </div>
                    <select id="graduado" title="Elija si el apoderado es abogado, se puede poner 'No' si no se tienen los datos o no es relevante" name="newAgentGraduate" class="form-control" required>
                      <option value="">¿Es abogado el apoderado?</option>
                      <option value="Si">Si</option>
                      <option value="No">No</option>
                      <option value="No se">No tengo información o no es relevante</option>
                    </select>
                  </div>
               </div>
            </div>
            <div id="datos-tarjeta">
            </div>
            <div class="row">
                  <div class="alert alert-secondary w-100">
                  <h2>INFORMACIÓN DE TERCEROS (A QUIENES VA A REPRESENTAR)</h2>
                  </div>
                  <small class="form-text text-center alert alert-warning w-100"><b>SI</b> usted actúa en representación de otra persona diligencie lo siguiente (de lo contrario ponga 0):</small>
            </div>
            <div class="row">
               <div class="form-group col-12">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                    </div>
                    <input id="numero-representados" type="number" class="form-control" step="1" min="0" name="newRepresentedNumber" placeholder="Número de personas a representar" title="'0' si no es en representación de terceros" required>
                  </div>
               </div>
            </div>
             <div id="lista-representados">
             </div>
            <div class="row">
                  <div class="alert alert-secondary w-100">
                  <h2>INFORMACIÓN DEL PODER</h2>
                  </div>
            </div>
            <div class="row">  
              <div class="form-group col-12">
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-user-tie"></i></span>
                  </div>
                  <input class="form-control" type="text" name="newReceiver" placeholder="¿A quién va dirigido?" required>
                </div>
                  <small class="form-text text-left">Ej: Fiscal, Notario, Juez, Juan Pérez, A quíen pueda interesar. Si es un juez se debe especificar tipo de juez y reparto, por ejemplo: Juez civil del circuito 13 </small>
              </div>
            </div>
            <div class="row">
            <!-- Tipo de formulación -->
              <div class="form-group col-12">
                <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-shield-alt"></i></span>
                </div>
                <textarea class="form-control" name="newFormulation" placeholder="Describa la diligencia o formulación para la que se otorga el poder" rows="3"></textarea>
              </div>
               </div>
            </div>
            <div class="row">
            <!-- Ciudad -->
               <div class="form-group col-12">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-map-marked-alt"></i></span>
                    </div>
                    <input type="text" class="form-control" name="newDocumentCity" placeholder="Ciudad de elaboración" required>
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
  <script src="/Views/js/poder-natural.js"></script>
@stop