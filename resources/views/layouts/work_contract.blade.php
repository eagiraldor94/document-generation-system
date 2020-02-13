@extends('base_layout')
@section('title')
	Contrato de trabajo
@stop
@section('css')
  <link rel="stylesheet" href="/Views/plugins/datepicker/datepicker3.css">
@stop
@section('content')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <div class="container">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0 text-dark"> Contrato de trabajo</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">Generación de documentos</li>
                <li class="breadcrumb-item active">Contrato de trabajo</li>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </section>
      <section class="content">
        <div class="container">
<!-- Default box -->
      <div class="card mb-5 text-center">
        <form role="form" method="post" action="ctrabajo" enctype="multipart/form-data">
          @csrf
        <div class="card-header bg-primary d-flex justify-content-center">
          <h1 class="card-title my-auto">Datos del contrato</h1>
        </div>
        <div class="card-body">
            <div class="row">
                  <div class="alert alert-secondary w-100">
                  <h2>INFORMACIÓN DEL EMPLEADOR</h2>
                  <input type="hidden" name="newCode" value="{{$code}}">
                  </div>
            </div>
            <div class="row">  
              <div class="form-group ml-3" style="width: 96.5%">
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-user"></i></span>
                  </div>
                  <input class="form-control" type="text" name="newFirstPart" placeholder="Nombre Representante o encargado" required>
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
                    <select name="newFirstIdType" class="form-control" required>
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
                    <input type="text" class="form-control" name="newFirstId" placeholder="Número del documento" required>
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
                    <input type="text" class="form-control" name="newFirstExpedition" placeholder="Ciudad de expedición del documento" required>
                  </div>
               </div>
             </div>
            <div class="row">  
              <div class="form-group ml-3" style="width: 96.5%">
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-user"></i></span>
                  </div>
                  <input class="form-control" type="text" name="newFirstCompany" placeholder="Nombre de la Empresa" required>
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
                    <select name="newFirstCompanyIdType" class="form-control" required>
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
                    <input type="text" class="form-control" name="newFirstCompanyId" placeholder="Número del documento" required>
                  </div>
               </div>
             </div>
             <div class="row">
            <!-- Direccion -->
               <div class="form-group ml-3" style="width:96.5%">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-home"></i></span>
                    </div>
                    <input type="text" class="form-control" name="newFirstAddress" placeholder="Dirección de la empresa" required>
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
                    <input type="text" class="form-control" name="newFirstCity" placeholder="Ciudad donde laborará" required>
                  </div>
               </div>
             </div>
            <div class="row">
                  <div class="alert alert-secondary w-100">
                  <h2>INFORMACIÓN DEL EMPLEADO</h2>
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
            <!-- Documento -->
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
                <input class="form-control" type="text" name="newSecondPhone" placeholder="Telefono del empleado" data-inputmask="'mask':'(999) 999-9999'" data-mask>
              </div>
               </div>
            <!-- Email -->
              <div class="form-group ml-3" style="width:47.5%">
                <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                </div>
                <input class="form-control" type="email" name="newSecondEmail" placeholder="Email del empleado">
              </div>
               </div>
             </div>
             <div class="row">
            <!-- Direccion -->
               <div class="form-group ml-3" style="width:96.5%">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-home"></i></span>
                    </div>
                    <input type="text" class="form-control" name="newSecondAddress" placeholder="Direccion del empleado" required>
                  </div>
               </div>
             </div>
             <div class="row">
            <!-- Ciudad -->
               <div class="form-group ml-3" style="width:47.5%">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-map-marked-alt"></i></span>
                    </div>
                    <input type="text" class="form-control" name="newSecondNeighborhood" placeholder="Barrio de residencia" required>
                  </div>
               </div>
            <!-- Ciudad -->
               <div class="form-group ml-3" style="width:47.5%">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                    </div>
                    <input title="Ciudad donde reside el empleado" type="text" class="form-control" name="newSecondCity" placeholder="Ciudad de residencia" required>
                  </div>
               </div>
             </div>
             <div class="row">
            <!-- Lugar de nacimiento -->
               <div class="form-group ml-3" style="width:47.5%">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-compass"></i></span>
                    </div>
                    <input title="Ciudad donde nació el empleado" type="text" class="form-control" name="newSecondBornSite" placeholder="Lugar de nacimiento" required>
                  </div>
               </div>
            <!-- Fecha de nacimiento -->
               <div class="form-group ml-3" style="width:47.5%">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                    </div>
                    <input title="fecha en que se expidió el documento del deudor" type="text" class="form-control datepicker" name="newSecondBornDate" placeholder="Fecha de nacimiento" required>
                  </div>
               </div>
             </div>
             <div class="row">
            <!-- Nacionalidad -->
               <div class="form-group ml-3" style="width:47.5%">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-globe-americas"></i></span>
                    </div>
                    <input type="text" class="form-control" name="newSecondNationality" placeholder="Nacionalidad del empleado" required>
                  </div>
               </div>
            <!-- EPS -->
               <div class="form-group ml-3" style="width:47.5%">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-file-medical-alt"></i></span>
                    </div>
                    <input type="text" class="form-control" name="newSecondEPS" placeholder="EPS del empleado" required>
                  </div>
               </div>
             </div>
             <div class="row">
            <!-- AFP -->
               <div class="form-group ml-3" style="width:47.5%">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-wallet"></i></span>
                    </div>
                    <input title="Fondo de pensiones del empleado" type="text" class="form-control" name="newSecondAFP" placeholder="Fondo de pensiones" required>
                  </div>
               </div>
            <!-- ARP -->
               <div class="form-group ml-3" style="width:47.5%">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-medkit"></i></span>
                    </div>
                    <input type="text" class="form-control" name="newSecondARP" placeholder="Atención de riesgos profesionales" required>
                  </div>
               </div>
             </div>
            <div class="row">
                  <div class="alert alert-secondary w-100">
                  <h2>INFORMACIÓN ADICIONAL</h2>
                  </div>
            </div>
            <!-- Tipo de contrato -->
            <div class="row">
              <div class="form-group ml-3" style="width:96.5%">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-cog"></i></span>
                    </div>
                    <select id="tipo-contrato" name="newContractType" class="form-control" required>
                      <option value="">Tipo de Contrato de trabajo</option>
                      <option value="A término fijo">A término fijo</option>
                      <option value="A término fijo inferior a un año">A término fijo inferior a un año</option>
                      <option value="A término indefinido">A término indefinido</option>
                      <option value="Por obra o labor">Por obra o labor</option>
                    </select>
                  </div>
               </div>
             </div>
             <div class="row">
            <!-- Cargo -->
               <div class="form-group ml-3" style="width:47.5%">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-user-cog"></i></span>
                    </div>
                    <input type="text" class="form-control" name="newCharge" placeholder="Cargo" required>
                  </div>
               </div>
            <!-- Ciudad -->
               <div class="form-group ml-3" style="width:47.5%">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                    </div>
                    <input type="text" class="form-control" name="newContractCity" placeholder="Ciudad de contratación" required>
                  </div>
               </div>
             </div>
             <div class="row">
            <!-- Fecha de inicio -->
               <div class="form-group ml-3" style="width:47.5%">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                    </div>
                    <input id="fecha-inicio"  title="Fecha en que inicia las labores" type="text" class="form-control datepicker" name="newStartDate" placeholder="Fecha de inicio" required>
                  </div>
               </div>
               <div id="fin" class="ml-3" style="width: 47.5%">
               </div>
             </div>
            <div class="row">
            <!-- Salario -->
               <div class="form-group ml-3" style="width:47.5%">
                <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fa fa-dollar-sign"></i></span>
                </div>
                <input class="form-control" type="number" name="newSalary" placeholder="Salario" required>
              </div>
               </div>
            <!-- Dias de prueba -->
               <div class="form-group ml-3" style="width:47.5%">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-hourglass-end"></i></span>
                    </div>
                    <input id="dias-prueba" type="number" class="form-control" name="newTestDays" min="0" max="60" placeholder="Dias de prueba" required>
                  </div>
                  <small class="form-text text-left">El valor máximo es 1/5 del total de días para el contrato a término fijo inferior a un año y 60 días para las demás modalidades.</small>
               </div>
            </div>
            <div class="row">
            <!-- Email -->
              <div class="form-group ml-3" style="width:96.5%">
                <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                </div>
                
                <input class="form-control" type="email" name="newFirstEmail" placeholder="Email del empleador">
              </div>
               </div>
            </div>        
        </div>
        <div class="card-footer">
          <div class="row w-100">
              <div class="input-group justify-content-center">
                <button type="submit" class="btn btn-success" name="newDocument">Descargar contrato</button>
              </div>
          </div>
        </div>
        <!-- /.card-body -->

        </form>
      </div>
      <!-- /.card -->
    </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
@stop
@section('js')
  <script src="/Views/plugins/datepicker/bootstrap-datepicker.js"></script>
  <script src="/Views/js/datepicker.js"></script>
  <script src="/Views/plugins/datepicker/locales/bootstrap-datepicker.es.js"></script>
  <script src="/Views/js/trabajo.js"></script>
@stop