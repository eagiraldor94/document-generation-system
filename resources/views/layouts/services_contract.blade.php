@extends('base_layout')
@section('title')
	Contrato de prestación de servicios profesionales
@stop
@section('css')
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
@stop
@section('content')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <div class="container-fluid">
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
<!-- Default box -->
      <div class="card mb-5 pb-5 text-center">
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
            <div class="row">  
              <div class="form-group ml-3" style="width: 96.5%">
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-user"></i></span>
                  </div>
                  <input class="form-control" type="text" name="newFirstPart" placeholder="Nombre del Contratante" required>
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
               <div class="form-group ml-3" style="width:47.5%">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-home"></i></span>
                    </div>
                    <input type="text" class="form-control" name="newFirstAddress" placeholder="Dirección de la empresa" required>
                  </div>
               </div>
            <!-- Ciudad -->
               <div class="form-group ml-3" style="width:47.5%">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                    </div>
                    <input type="text" class="form-control" name="newFirstCity" placeholder="Ciudad donde laborará" required>
                  </div>
               </div>
             </div>
            <div class="row">  
              <div class="form-group ml-3" style="width: 47.5%">
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-university"></i></span>
                  </div>
                  <input class="form-control" type="text" name="newFirstCompanyCamera" placeholder="Camara de comercio donde está inscrita" title='Camara de comercio donde se encuentra matriculada la empresa. Por ejemplo para la camara de comercio de Bogota poner "de Bogota", para la camara de comercio Aburra Sur poner "Aburra Sur"' required>
                </div>
              </div>
              <div class="form-group ml-3" style="width: 47.5%">
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-hashtag"></i></span>
                  </div>
                  <input class="form-control" type="number" name="newFirstCompanyCameraNumber" placeholder="No. Certificado de camara de comercio" required>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="form-group ml-3" style="width:96.5%">
                <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fa fa-phone-square"></i></span>
                </div>
                <input class="form-control" type="text" name="newFirstPhone" placeholder="Telefono de la empresa" data-inputmask="'mask':'(999) 999-9999'" data-mask>
              </div>
               </div>
             </div>
            <div class="row">
                  <div class="alert alert-secondary w-100">
                  <h2>INFORMACIÓN DEL PRESTADOR</h2>
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
             <div class="row">
            <!-- Ciudad -->
               <div class="form-group ml-3" style="width:96.5%">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                    </div>
                    <input type="text" class="form-control" name="newSecondExpedition" placeholder="Ciudad de expedición del documento" required>
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
                <input class="form-control" type="text" name="newSecondPhone" placeholder="Telefono del prestador" data-inputmask="'mask':'(999) 999-9999'" data-mask>
              </div>
               </div>
            <!-- Email -->
              <div class="form-group ml-3" style="width:47.5%">
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
               <div class="form-group ml-3" style="width:47.5%">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-home"></i></span>
                    </div>
                    <input type="text" class="form-control" name="newSecondAddress" placeholder="Direccion del prestador" required>
                  </div>
               </div>
            <!-- Ciudad -->
               <div class="form-group ml-3" style="width:47.5%">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                    </div>
                    <input title="Ciudad donde reside el prestador" type="text" class="form-control" name="newSecondCity" placeholder="Ciudad de residencia" required>
                  </div>
               </div>
             </div>
            <div class="row">  
              <div class="form-group ml-3" style="width: 96.5%">
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-user"></i></span>
                  </div>
                  <input class="form-control" type="number" name="newSecondProfesionalCard" placeholder="Tarjeta profesional No." required>
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
                    <input title="Ciudad donde nació el prestador" type="text" class="form-control" name="newSecondBornSite" placeholder="Lugar de nacimiento" required>
                  </div>
               </div>
            <!-- Fecha de nacimiento -->
               <div class="form-group ml-3" style="width:47.5%">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
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
                    <input type="text" class="form-control" name="newSecondNationality" placeholder="Nacionalidad del prestador" required>
                  </div>
               </div>
            <!-- EPS -->
               <div class="form-group ml-3" style="width:47.5%">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-file-medical-alt"></i></span>
                    </div>
                    <input type="text" class="form-control" name="newSecondEPS" placeholder="EPS del prestador" required>
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
                    <input title="Fondo de pensiones del prestador" type="text" class="form-control" name="newSecondAFP" placeholder="Fondo de pensiones" required>
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
             <div class="row">
            <!-- Cargo -->
               <div class="form-group ml-3" style="width:47.5%">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-user-cog"></i></span>
                    </div>
                    <input type="text" class="form-control" name="newCharge" placeholder="Cargo u oficio" required>
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
                    <input title="Fecha en que inicia las labores" type="text" class="form-control datepicker" name="newStartDate" placeholder="Fecha de inicio" required>
                  </div>
               </div>
            <!-- Fecha de terminacion -->
               <div class="form-group ml-3" style="width:47.5%">
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
               <div class="form-group ml-3" style="width:96.5%">
                <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fa fa-dollar-sign"></i></span>
                </div>
                <input class="form-control" type="number" name="newSalary" placeholder="Honorarios" required>
              </div>
               </div>
            </div>
            <div class="row">
            <div class="form-group ml-3" style="width:96.5%">
              <div class="input-group mb-3">
                  <div class="input-group-prepend d-md-inline-flex">
                  <span class="input-group-text"><i class="fas fa-handshake"></i></span>
                  </div>
                  <select title="tipo de pago de los honorarios" name="newSalaryPayment" class="form-control" required>
                    <option value="">Forma de pago</option>
                    <option value="100a">100% anticipado</option>
                    <option value="50-50">50%-50%</option>
                    <option value="100b">100% al terminar</option>
                  </select>
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
            <div class="row">
            <!-- Facultades del contratista -->
              <div class="form-group ml-3" style="width:96.5%">
                <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-shield-alt"></i></span>
                </div>
                <textarea class="form-control" name="newFaculties" placeholder="Facultades otorgadas al contratista para ejercer su labor" rows="3"></textarea>
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
                
                <input class="form-control" type="email" name="newFirstEmail" placeholder="Email del Contratante">
              </div>
               </div>
            </div>        
        </div>
        <div class="card-footer">
          <div class="row w-100">
            <div class="form-group text-center" style="width: 100%">
              <div class="input-group justify-content-center">
                <button type="submit" class="btn btn-success" name="newDocument">Descargar contrato</button>
              </div>
            </div>
          </div>
        </div>
        <!-- /.card-body -->

        </form>
      </div>
      <!-- /.card -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
@stop
@section('js')
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script src="/Views/js/datepicker.js"></script>
  <script src="/Views/js/servicios.js"></script>
@stop