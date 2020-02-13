@extends('base_layout')
@section('title')
	Acuerdo de confidencialidad
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
              <h1 class="m-0 text-dark"> Acuerdo <small>de confidencialidad</small></h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">Generación de documentos</li>
                <li class="breadcrumb-item active">Acuerdo de confidencialidad</li>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <div class="content">
        <div class="container">
<!-- Default box -->
      <div class="card mb-5 text-center">
        <form role="form" method="post" action="confidencialidad" enctype="multipart/form-data">
          @csrf
        <div class="card-header bg-primary d-flex justify-content-center">
          <h1 class="card-title my-auto">Datos del acuerdo</h1>
        </div>
        <div class="card-body">
            <div class="row">
                  <div class="alert alert-secondary w-100">
                  <h2>PARTE REVELADORA</h2>
                  <input type="hidden" name="newCode" value="{{$code}}">
              </div>
            </div>
            <div class="row">  
              <div class="form-group ml-3" style="width: 96.5%">
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-user"></i></span>
                  </div>
                  <input class="form-control" type="text" name="newFirstPart" placeholder="Nombre completo" required>
                </div>
              </div>
            </div>
            <div class="row">
             <div class="form-group ml-3" style="width:32%">
                <div class="input-group mb-3">
                   <div class="input-group-prepend d-md-inline-flex">
                   <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                   </div>
                    <select name="newFirstIdType" class="form-control" required>
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
                    <input type="text" class="form-control" name="newFirstId" placeholder="Número del documento" required>
                  </div>
               </div>
             </div>
            <!-- Telefono 1 -->
            <div class="row">
              <div class="form-group ml-3" style="width:47.5%">
                <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fa fa-phone-square"></i></span>
                </div>
                <input class="form-control" type="text" name="newFirstPhone" placeholder="Telefono parte reveladora" data-inputmask="'mask':'(999) 999-9999'" data-mask>
              </div>
               </div>
            <!-- Email -->
              <div class="form-group ml-3" style="width:47.5%">
                <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                </div>
                <input class="form-control" type="email" name="newFirstEmail" placeholder="Email parte reveladora" required>
              </div>
               </div>
             </div>
             <div class="row">
            <!-- Direccion -->
               <div class="form-group ml-3" style="width:96.5%">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                    </div>
                    <input type="text" class="form-control" name="newFirstAddress" placeholder="Dirección" required>
                  </div>
               </div>
             </div>
            <!-- Documento -->
            <div class="row">
              <div class="form-group ml-3" style="width:96.5%">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-user-tie"></i></span>
                    </div>
                    <select name="newFirstType" class="form-control" id="revelador" required>
                      <option value="Si mismo">A nombre de quien actua</option>
                      <option value="Si mismo">Si mismo</option>
                      <option value="Empresa">Empresa</option>
                      <option value="Persona natural">Persona natural</option>
                    </select>
                  </div>
               </div>
             </div>
            <div id="datos-revelador">
            </div>
            <div class="row">
              <div class="alert alert-secondary w-100">
                  <h2>INFORMACIÓN PARTE RECEPTORA</h2>
              </div>
            </div>
            <div class="row">  
              <div class="form-group ml-3" style="width: 96.5%">
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-user"></i></span>
                  </div>
                  <input class="form-control" type="text" name="newSecondPart" placeholder="Nombre completo" required>
                </div>
              </div>
            </div>
            <div class="row">
             <div class="form-group ml-3" style="width:32%">
                <div class="input-group mb-3">
                   <div class="input-group-prepend d-md-inline-flex">
                   <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                   </div>
                    <select name="newSecondIdType" class="form-control" required>
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
                    <input type="text" class="form-control" name="newSecondId" placeholder="Número del documento" required>
                  </div>
               </div>
             </div>
            <!-- Telefono 1 -->
            <div class="row">
              <div class="form-group ml-3" style="width:47.5%">
                <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fa fa-phone-square"></i></span>
                </div>
                
                <input class="form-control" type="text" name="newSecondPhone" placeholder="Telefono parte receptora" data-inputmask="'mask':'(999) 999-9999'" data-mask>
              </div>
               </div>
            <!-- Email -->
              <div class="form-group ml-3" style="width:47.5%">
                <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                </div>
                
                <input class="form-control" type="email" name="newSecondEmail" placeholder="Email parte receptora">
              </div>
               </div>
             </div>
             <div class="row">
            <!-- Direccion -->
               <div class="form-group ml-3" style="width:96.5%">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                    </div>
                    <input type="text" class="form-control" name="newSecondAddress" placeholder="Dirección" required>
                  </div>
               </div>
             </div>
            <!-- Documento -->
            <div class="row">
              <div class="form-group ml-3" style="width:96.5%">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                    </div>
                    <select name="newSecondType" class="form-control" id="receptor" required>
                      <option value="Si mismo">A nombre de quien actua</option>
                      <option value="Si mismo">Si mismo</option>
                      <option value="Empresa">Empresa</option>
                      <option value="Persona natural">Persona natural</option>
                    </select>
                  </div>
               </div>
             </div>
            <div id="datos-receptor">
            </div>
            <div class="row">
                  <div class="alert alert-secondary w-100">
                  <h2>INFORMACIÓN ADICIONAL</h2>
                  </div>
            </div>
            <div class="row">
              <div class="form-group ml-3" style="width:96.5%">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-file-alt"></i></span>
                    </div>
                    <select id="tipo-contrato" title="Tipo de contrato de confidencialidad" name="newContractType" class="form-control" required>
                      <option value="">Tipo de confidencialidad</option>
                      <option value="Contrato">Por contrato de un cargo (Empresa-persona)</option>
                      <option value="Sociedad">Por proposicion de sociedad (Persona-persona)</option>
                      <option value="Convenio">Por convenio (Empresa-empresa)</option>
                    </select>
                  </div>
               </div>
            </div>
            <div id="cargo">
            </div>
            <div class="row">
            <!-- Ciudad -->
              <div class="form-group ml-3" style="width:96.5%">
                <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fa fa-map-marker-alt"></i></span>
                </div>
                <input class="form-control" type="text" name="newCity" placeholder="Ciudad donde se firma">
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
                <input class="form-control" type="email" name="newSendEmail" id="nuevoSendEmail" placeholder="Email para envío del documento">
              </div>
               </div>
            </div>        
        </div>
        <div class="card-footer">
          <div class="row w-100">
              <div class="input-group justify-content-center">
                <button type="submit" class="btn btn-success" name="newDocument">Descargar acuerdo</button>
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
      <p>Contrato de confidencialidad</p>
    </div>
  </aside>
  <!-- /.control-sidebar -->
@stop
@section('js')
  <script src="/Views/plugins/datepicker/bootstrap-datepicker.js"></script>
  <script src="/Views/js/datepicker.js"></script>
  <script src="/Views/plugins/datepicker/locales/bootstrap-datepicker.es.js"></script>
  <script src="/Views/js/confidentiality.js"></script>
@stop