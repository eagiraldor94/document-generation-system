@extends('base_layout')
@section('title')
	Contrato de prestación de servicios profesionales
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
              <h1 class="m-0 text-dark"> Contrato de prestación <small>de servicios profesionales</small></h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">Generación de documentos</li>
                <li class="breadcrumb-item active">Contrato de prestación de servicios</li>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </section>
      <section class="content">
        <div class="container">
<!-- Default box -->
      <div class="card mb-5 text-center">
        <form role="form" method="post" action="cservicios" enctype="multipart/form-data">
          @csrf
        <div class="card-header bg-primary d-flex justify-content-center">
          <h1 class="card-title my-auto">Datos del contrato</h1>
        </div>
        <div class="card-body">
            <div class="row">
                  <div class="alert alert-secondary w-100">
                  <h2>INFORMACIÓN DEL CONTRATANTE</h2>
                  <input type="hidden" name="newCode" value="{{$code}}">
                  </div>
            </div>
            <!-- Tipo Contratante -->
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
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-user"></i></span>
                  </div>
                  <input class="form-control" type="text" name="newFirstPart" placeholder="Nombre del Representante o Contratante" required>
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
                    <input type="text" class="form-control" name="newFirstExpedition" placeholder="Ciudad de expedición (Opcional)">
                  </div>
               </div>
             </div>
             <div id="info-contratante">
             </div>
            <div class="row">
              <div class="form-group col-12">
                <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fa fa-phone-square"></i></span>
                </div>
                <input class="form-control" type="text" name="newFirstPhone" placeholder="Telefono del contratante" data-inputmask="'mask':'(999) 999-9999'" data-mask>
              </div>
               </div>
             </div>
            <div class="row">
                  <div class="alert alert-secondary w-100">
                  <h2>INFORMACIÓN DEL PRESTADOR</h2>
                  </div>
            </div>
            <!-- Tipo Prestador -->
            <div class="row">
              <div class="form-group col-12">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text">Prestador</span>
                    </div>
                    <select id="tipo-prestador" name="newSecondType" class="form-control" required>
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
                      <option value="NIT">NIT</option>
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
            <!-- Ciudad -->
               <div class="form-group col-12">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                    </div>
                    <input type="text" class="form-control" name="newSecondExpedition" placeholder="Ciudad de expedición (Opcional)">
                  </div>
               </div>
             </div>
             <div id="info-prestador">
             </div>
            <!-- Telefono 1 -->
            <div class="row">
              <div class="form-group col-12 col-sm-6">
                <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fa fa-phone-square"></i></span>
                </div>
                <input class="form-control" type="text" name="newSecondPhone" placeholder="Telefono del prestador" data-inputmask="'mask':'(999) 999-9999'" data-mask>
              </div>
               </div>
            <!-- Email -->
              <div class="form-group col-12 col-sm-6">
                <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                </div>
                <input class="form-control" type="email" name="newSecondEmail" placeholder="Email del prestador">
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
                    <input type="text" class="form-control" name="newSecondAddress" placeholder="Direccion del prestador" required>
                  </div>
               </div>
            <!-- Ciudad -->
               <div class="form-group col-12 col-sm-6">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                    </div>
                    <input title="Ciudad donde se encuentra la empresa o donde reside el prestador" type="text" class="form-control" name="newSecondCity" placeholder="Ciudad del prestador" required>
                  </div>
               </div>
             </div>
            <div class="row">  
              <div class="form-group col-12 col-sm-6">
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-user-cog"></i></span>
                  </div>
                  <input class="form-control" type="text" name="newSecondProfesion" placeholder="Servicio a prestar (Profesión)" required>
                </div>
              </div>
              <div class="form-group col-12 col-sm-6">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text">¿Tiene tarjeta profesional?</span>
                    </div>
                    <select id="tarjeta-profesional" name="newSecondCard" class="form-control" required>
                      <option value="">(Si es empresa, ponga No)</option>
                      <option value="Si">Si</option>
                      <option value="No">No</option>
                    </select>
                  </div>
               </div>
            </div>
            <div id="info-tp">
            </div>
            <div class="row">
                  <div class="alert alert-secondary w-100">
                  <h2>INFORMACIÓN ADICIONAL</h2>
                  </div>
            </div>
            <div class="row">
              <div class="form-group col-12">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text">¿Se presta el servicio en una ubicación especifica?</span>
                    </div>
                    <select id="ubicacion-servicio" name="newServiceLocation" class="form-control" required>
                      <option value="">Seleccione una opción</option>
                      <option value="Si">Si</option>
                      <option value="No">No</option>
                    </select>
                  </div>
               </div>
              </div>
              <div id="locacion-servicio">
              </div>
             <div class="row">
            <!-- Cargo -->
               <div class="form-group col-12 col-sm-6">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-user-cog"></i></span>
                    </div>
                    <input type="text" class="form-control" name="newCharge" placeholder="Cargo u oficio" required>
                  </div>
               </div>
            <!-- Ciudad -->
               <div class="form-group col-12 col-sm-6">
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
               <div class="form-group col-12 col-sm-6">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                    </div>
                    <input title="Fecha en que inicia las labores" type="text" class="form-control datepicker" name="newStartDate" placeholder="Fecha de inicio" required>
                  </div>
               </div>
            <!-- Fecha de terminacion -->
               <div class="form-group col-12 col-sm-6">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                    </div>
                    <input title="Fecha final de las labores" type="text" class="form-control datepicker" name="newEndDate" placeholder="Fecha de terminación" required>
                  </div>
               </div>
             </div>
            <div class="row">
            <!-- Salario -->
               <div class="form-group col-12">
                <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fa fa-dollar-sign"></i></span>
                </div>
                <input class="form-control" type="number" name="newSalary" placeholder="Honorarios totales" required>
              </div>
               </div>
            </div>
            <div class="row">
            <div class="form-group col-12">
              <div class="input-group mb-3">
                  <div class="input-group-prepend d-md-inline-flex">
                  <span class="input-group-text"><i class="fas fa-handshake"></i></span>
                  </div>
                  <select id="periodo-pago" title="tipo de pago de los honorarios" name="newSalaryPayment" class="form-control" required>
                    <option value="">Forma de pago</option>
                    <option value="100a">100% anticipado</option>
                    <option value="50-50">50%-50%</option>
                    <option value="100b">100% al terminar</option>
                    <option value="periodico">Cada determinado tiempo</option>
                  </select>
                </div>
             </div>
            </div>
            <div id="cuotas">
            </div>
             <div id="info-cuotas">
             </div>
             <div id="fechas-pago" class="row mx-auto">
             </div>
            <div class="row">
              <div class="form-group col-12">
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
            <div class="row">
            <!-- Facultades del contratista -->
              <div class="form-group col-12">
                <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-shield-alt"></i></span>
                </div>
                <textarea class="form-control" name="newFaculties" placeholder="Facultades otorgadas al contratista para ejercer su labor" rows="3" required></textarea>
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
                
                <input class="form-control" type="email" name="newFirstEmail" placeholder="Email del Contratante">
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
  <script src="/Views/js/servicios2.js"></script>
@stop