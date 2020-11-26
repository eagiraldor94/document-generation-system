@extends('base_layout')
@section('title')
	Otrosí de teletrabajo
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
              <h1 class="m-0 text-dark"> Otro sí de teletrabajo</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">Generación de documentos</li>
                <li class="breadcrumb-item active">Otro sí de teletrabajo</li>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </section>
      <section class="content">
        <div class="container">
<!-- Default box -->
      <div class="card mb-5 text-center">
        <form role="form" method="post" action="/oteletrabajo" enctype="multipart/form-data">
          @csrf
        <div class="card-header bg-primary d-flex justify-content-center">
          <h1 class="card-title my-auto">Datos del otro sí</h1>
        </div>
        <div class="card-body">
            <div class="row">
                  <div class="alert alert-secondary w-100">
                  <h2>INFORMACIÓN DEL EMPLEADOR</h2>
                  <input type="hidden" name="newCode" value="{{$code}}">
                  </div>
            </div>
            <!-- Contratante -->
            <div class="row">
              <div class="form-group col-12">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text">Contratante</span>
                    </div>
                    <select id="tipo-contratante" name="newFirstType" class="form-control" required>
                      <option value="">Tipo de entidad</option>
                      <option value="PN">Persona (Persona Natural)</option>
                      <option value="PJ">Empresa (Persona Jurídica)</option>
                    </select>
                  </div>
               </div>
             </div>
            <div class="row">  
              <div class="form-group col-12">
                <div class="input-group mb-3">
                  <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fa fa-user"></i></span>
                  </div>
                  <input class="form-control" type="text" name="newFirstPart" placeholder="Representante o Contratante (Persona)" required>
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
                    <select name="newFirstIdType" class="form-control" required>
                      <option value="">Tipo de documento</option>
                      <option value="CC">Cedula de ciudadanía</option>
                      <option value="CE">Cedula de extranjería</option>
                    </select>
                  </div>
               </div>
               <div class="form-group col-12 col-sm-8">
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
               <div class="form-group col-12">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                    </div>
                    <input type="text" class="form-control" name="newFirstExpedition" placeholder="Ciudad de expedición del documento (Opcional)">
                  </div>
               </div>
             </div>
             <div id="info-empresa">
             </div>
            <div class="row">
                  <div class="alert alert-secondary w-100">
                  <h2>INFORMACIÓN DEL EMPLEADO</h2>
                  </div>
            </div>
            <div class="row">  
              <div class="form-group col-12">
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
              <div class="form-group col-12 col-sm-4">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                    </div>
                    <select name="newSecondIdType" class="form-control" required>
                      <option value="">Tipo de documento</option>
                      <option value="CC">Cedula de ciudadanía</option>
                      <option value="CE">Cedula de extranjería</option>
                    </select>
                  </div>
               </div>
               <div class="form-group col-12 col-sm-8">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                    </div>
                    <input type="text" class="form-control" name="newSecondId" placeholder="Número del documento" required>
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
                    <input type="text" class="form-control" name="newSecondAddress" placeholder="Dirección donde desempeñará su trabajo remoto" required>
                  </div>
               </div>
             </div>
             <div class="row">
            <!-- Ciudad -->
               <div class="form-group col-12">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                    </div>
                    <input title="Ciudad donde reside el empleado" type="text" class="form-control" name="newSecondCity" placeholder="Ciudad de residencia" required>
                  </div>
               </div>
             </div>
            <div class="row">
                  <div class="alert alert-secondary w-100">
                  <h2>INFORMACIÓN ADICIONAL</h2>
                  </div>
            </div>
             <div class="row">
            <!-- Direccion -->
               <div class="form-group col-12">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-building"></i></span>
                    </div>
                    <input type="text" class="form-control" name="newWorkAddress" placeholder="Direccion donde laborará" required>
                  </div>
               </div>
             </div>
            <!-- Tipo de contrato -->
            <div class="row">
              <div class="form-group col-12 col-sm-6">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-cog"></i></span>
                    </div>
                    <select id="tipo-contrato" name="newContractType" class="form-control" required>
                      <option value="">Tipo de Contrato de trabajo anterior</option>
                      <option value="A término fijo">A término fijo</option>
                      <option value="A término fijo inferior a un año">A término fijo inferior a un año</option>
                      <option value="A término indefinido">A término indefinido</option>
                      <option value="Por obra o labor">Por obra o labor</option>
                    </select>
                  </div>
               </div>
            <!-- Dias semana -->
               <div class="form-group col-12 col-sm-6">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                    </div>
                    <input type="number" class="form-control" name="newWeekDays" placeholder="Dias laborados por semana" required>
                  </div>
               </div>
             </div>
             <div class="row">
            <!-- Numero contrato -->
               <div class="form-group col-12 col-sm-6">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-file-alt"></i></span>
                    </div>
                    <input type="text" class="form-control" name="newLastContract" placeholder="Número de contrato a modificar (Opcional)">
                  </div>
               </div>
            <!-- Fecha de contrato anterior -->
               <div class="form-group col-12 col-sm-6">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                    </div>
                    <input title="Fecha que tiene el contrato a modificar" type="text" class="form-control datepicker" name="newLastContractDate" placeholder="Fecha del contrato a modificar" required>
                  </div>
                  <small class="form-text text-left">Fecha en la qué se firmó el contrato que se está modificando</small>
               </div>
             </div>
             <div class="row">
            <!-- Numero Clausula -->
               <div class="form-group col-12">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                    </div>
                    <input type="text" class="form-control" name="newLastSalary" placeholder="Número de clausula de salario contrato a modificar (en Letras)" required>
                  </div>
               </div>
             </div>
            <!-- Modificacion -->
            <div class="row">
              <div class="form-group col-12 col-sm-6">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-user-cog"></i></span>
                    </div>
                    <select id="cambio-cargo" name="newChargeChange" class="form-control" required>
                      <option value="">¿Se modifica el cargo actual?</option>
                      <option value="1">Si</option>
                      <option value="0">No</option>                    
                    </select>
                  </div>
               </div>
              <div id="nuevo-cargo" class="col-12 col-sm-6"></div>
              </div>
            <!-- Periodicidad del pago -->
            <div class="row">
              <div class="form-group col-12">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-cog"></i></span>
                    </div>
                    <select name="newPaymentCicle" class="form-control" required>
                      <option value="">Periodicidad del pago</option>
                      <option value="Semanal">Semanal</option>
                      <option value="Quincenal">Quincenal</option>
                      <option value="Mensual">Mensual</option>
                    </select>
                  </div>
                  <small class="form-text text-center alert alert-warning"><b>¡CUIDADO!</b> Tenga presente que el monto indicado en el campo salario y en el campo de compensación deben ser cancelados con esta periodicidad.</small>
               </div>
             </div>
            <div class="row">
            <!-- Salario -->
               <div class="form-group col-12">
                <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fa fa-dollar-sign"></i></span>
                </div>
                <input class="form-control" type="number" name="newSalary" placeholder="Salario" required>
              </div>
               </div>
            </div>
            <div class="row">
            <!-- Compensacion -->
               <div class="form-group col-12">
                <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fa fa-dollar-sign"></i></span>
                </div>
                <input class="form-control" type="number" name="newPlusSalary" placeholder="Compensación gastos (internet, etc). Estos tienen la misma periodicidad que el salario." required>
              </div>
                  <small class="form-text text-left">Si no se dará compensación adicional poner 0</small>
               </div>
             </div>
            <div class="row">  
              <div class="form-group col-12">
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-toolbox"></i></span>
                  </div>
                  <textarea class="form-control" rows="3" name="newTools" placeholder="Tecnologías que se utilizará para mantener el contacto con el Teletrabajador separados por coma: (Celular, internet, sky, Messenger, telefonía fija, etc.)" required></textarea>
                </div>
              </div>
            </div>
            <div class="row">  
              <div class="form-group col-12">
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-bullseye"></i></span>
                  </div>
                  <textarea class="form-control" rows="5" name="newGoals" placeholder="Objetivos/ Metas a cumplir por parte del Teletrabajador: (Cantidad de llamadas, de envío o contestación de correos electrónicos, de soporte técnico virtual, etc.). Especificar periodicidad (Diaria, Semanal, Mensual)" required></textarea>
                </div>
              </div>
            </div>
            <div class="row">  
              <div class="form-group col-12">
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-user-clock"></i></span>
                  </div>
                  <textarea class="form-control" rows="3" name="newHours" placeholder="Disponibilidad que debe tener el teletrabajador y horas de trabajo" required></textarea>
                </div>
              </div>
            </div>
            <div class="row">  
              <div class="form-group col-12">
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-user"></i></span>
                  </div>
                  <input class="form-control" type="text" name="newChecker" placeholder="Nombre del Supervisor (Opcional)">
                </div>
              </div>
            </div>
             <div class="row">
            <!-- Ciudad -->
               <div class="form-group col-12">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                    </div>
                    <input type="text" class="form-control" name="newContractCity" placeholder="Ciudad de firma" required>
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
                    <input id="fecha-inicio"  title="Fecha en que inicia la vigencia del otro sí" type="text" class="form-control datepicker" name="newStartDate" placeholder="Fecha de inicio otro sí" required>
                  </div>
               </div>
               <div id="fin" class="col-12 col-sm-6">
               </div>
             </div>
            <div class="row">
            <!-- Email -->
              <div class="form-group col-12">
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
                <button type="submit" class="btn btn-success" name="newDocument">Descargar otro sí</button>
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
  <script src="/Views/js/otrosi.js"></script>
@stop