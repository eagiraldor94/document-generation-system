@extends('base_layout')
@section('title')
	Contrato de compraventa
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
              <h1 class="m-0 text-dark"> Contrato de compraventa <small>vehículo automotor </small></h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">Generación de documentos</li>
                <li class="breadcrumb-item active">Contrato de compraventa</li>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
          </div>
      </div>
      <div class="content">
        <div class="container">
<!-- Default box -->
      <div class="card mb-5 text-center">
        <form role="form" method="post" action="/compraventa" enctype="multipart/form-data">
          @csrf
        <div class="card-header bg-primary d-flex justify-content-center">
          <h1 class="card-title my-auto"><b>Datos de la compraventa</b></h1>
        </div>
        <div class="card-body">
            <div class="row">
                  <div class="alert alert-secondary w-100">
                  <h2>INFORMACIÓN DEL VENDEDOR</h2>
                  <input type="hidden" name="newCode" value="{{$code}}">
              </div>
            </div>
            <div class="row">  
              <div class="form-group ml-3" style="width: 96.5%">
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-user"></i></span>
                  </div>
                  <input class="form-control" type="text" name="newSeller" placeholder="Nombre del vendedor" required>
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
                    <select name="newSellerIdType" class="form-control" required>
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
                    <input type="text" class="form-control" name="newSellerId" placeholder="Número del documento" required>
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
                    <input title="Ciudad donde reside el vendedor" type="text" class="form-control" name="newSellerCity" placeholder="Ciudad de residencia" required>
                  </div>
               </div>
            <!-- Expedicion -->
               <div class="form-group ml-3" style="width:47.5%">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                    </div>
                    <input title="Ciudad donde se expidió el documento del vendedor" type="text" class="form-control" name="newSellerExpedition" placeholder="Ciudad de expedición" required>
                  </div>
               </div>
             </div>
            <div class="row">
                  <div class="alert alert-secondary w-100">
                  <h2>INFORMACIÓN DEL COMPRADOR</h2>
                  </div>
            </div>
            <div class="row">  
              <div class="form-group ml-3" style="width: 96.5%">
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-user"></i></span>
                  </div>
                  <input class="form-control" type="text" name="newBuyer" placeholder="Nombre del comprador" required>
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
                    <select name="newBuyerIdType" class="form-control" required>
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
                    <input type="text" class="form-control" name="newBuyerId" placeholder="Número del documento" required>
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
                    <input title="Ciudad donde reside el vendedor" type="text" class="form-control" name="newBuyerCity" placeholder="Ciudad de residencia" required>
                  </div>
               </div>
            <!-- Expedicion -->
               <div class="form-group ml-3" style="width:47.5%">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                    </div>
                    <input title="Ciudad donde se expidió el documento del vendedor" type="text" class="form-control" name="newBuyerExpedition" placeholder="Ciudad de expedición" required>
                  </div>
               </div>
             </div>
            <div class="row">
                  <div class="alert alert-secondary w-100">
                  <h2>INFORMACIÓN DEL VEHICULO</h2>
                  </div>
            </div>
            <div class="row">  
              <div class="form-group ml-3" style="width: 47.5%">
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-motorcycle"></i></span>
                  </div>
                  <input class="form-control" type="text" name="newClass" placeholder="Clase de vehículo (Automovil, motocicleta, etc)" title="Ver en la matrícula del vehículo" required>
                </div>
              </div>
              <div class="form-group ml-3" style="width: 47.5%">
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-bus"></i></span>
                  </div>
                  <input class="form-control" type="text" name="newType" placeholder="Tipo de carrocería" title="Ver en la matrícula del vehículo" required>
                </div>
              </div>
            </div>
            <div class="row">  
              <div class="form-group ml-3" style="width: 47.5%">
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-weight"></i></span>
                  </div>
                  <input class="form-control" type="text" name="newCapacity" placeholder="Capacidad (pasajeros o kg)" title="Ver en la matrícula del vehículo" required>
                </div>
              </div>
              <div class="form-group ml-3" style="width: 47.5%">
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><b>CC</b></span>
                  </div>
                  <input class="form-control" type="text" name="newMotorSize" placeholder="Cilindraje del motor" title="Ver en la matrícula del vehículo" required>
                </div>
              </div>
            </div>
            <div class="row">  
              <div class="form-group ml-3" style="width: 47.5%">
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-shield-alt"></i></span>
                  </div>
                  <input class="form-control" type="text" name="newBrand" placeholder="Marca del vehículo" title="Ej: Renault, Honda, Nissan" required>
                </div>
              </div>
              <div class="form-group ml-3" style="width: 47.5%">
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-palette"></i></span>
                  </div>
                  <input class="form-control" type="text" name="newColor" placeholder="Color del vehículo" title="Ver en la matrícula del vehículo" required>
                </div>
              </div>
            </div>
            <div class="row">  
              <div class="form-group ml-3" style="width: 47.5%">
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                  </div>
                  <input class="form-control" type="text" name="newModel" placeholder="Modelo del vehículo" title="Ver en la matrícula del vehículo" required>
                </div>
              </div>
              <div class="form-group ml-3" style="width: 47.5%">
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-car"></i></span>
                  </div>
                  <input class="form-control" type="text" name="newLine" placeholder="Línea del vehículo" title="Logan, Sandero, Aveo, BWS, Boxer" required>
                </div>
              </div>
            </div>
            <div class="row">  
              <div class="form-group ml-3" style="width:47.5%">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-taxi"></i></span>
                    </div>
                    <select name="newService" class="form-control" required>
                      <option value="">Servicio del Vehículo</option>
                      <option value="Particular">Particular</option>
                      <option value="Público">Público</option>
                      <option value="Diplomatico">Diplomatico</option>
                      <option value="Oficial">Oficial</option>
                      <option value="Especial">Especial</option>
                      <option value="Otros">Otros</option>
                    </select>
                  </div>
               </div>
              <div class="form-group ml-3" style="width: 47.5%">
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                  </div>
                  <input class="form-control" type="text" name="newPlate" placeholder="Placa del vehículo" title="Ver en la matrícula del vehículo" required>
                </div>
              </div>
            </div>
             <div class="row">
            <!-- Chasis -->
               <div class="form-group ml-3" style="width:96.5%">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                    </div>
                    <input title="Ver matrícula o improntas" type="text" class="form-control" name="newChassisNumber" placeholder="Número de chasis del vehículo" required>
                  </div> 
               </div>
             </div>
             <div class="row">
            <!-- Motor -->
               <div class="form-group ml-3" style="width:96.5%">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                    </div>
                    <input title="Ver matrícula o improntas" type="text" class="form-control" name="newEngineNumber" placeholder="Número de motor del vehículo" required>
                  </div> 
               </div>
             </div>
             <div class="row">
            <!-- Serie -->
               <div class="form-group ml-3" style="width:96.5%">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                    </div>
                    <input title="Ver matrícula, llenar con asteriscos si no se tiene" type="text" class="form-control" name="newSerialNumber" placeholder="Número de serie del vehículo (*** si no se tiene)" required>
                  </div> 
               </div>
             </div>
            <div class="row">
              <div class="form-group ml-3" style="width:96.5%">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-file"></i></span>
                    </div>
                    <select id="tipo-aduana" title="Elija si el vehículo tiene acta o manifiesto de aduana" name="newAduanaType" class="form-control" required>
                      <option value="">Tipo de documento de Aduana</option>
                      <option value="Acta">Acta</option>
                      <option value="Manifiesto">Manifiesto</option>
                      <option value="Sin info">No tengo información</option>
                    </select>
                  </div>
               </div>
            </div>
            <div id="datos-aduana">
            </div>
            <div class="row">
              <div class="form-group ml-3" style="width:96.5%">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-cogs"></i></span>
                    </div>
                    <select title="Estado en que se encuentra el vehículo" name="newVehicleState" class="form-control" required>
                      <option value="">Estado de funcionamiento del vehiculo</option>
                      <option value="Perfecto estado">Perfecto estado</option>
                      <option value="Buen estado">Buen estado</option>
                      <option value="Mal estado">Mal estado</option>
                    </select>
                  </div>
               </div>
            </div>
            <div class="row">
                  <div class="alert alert-secondary w-100">
                  <h2>INFORMACIÓN DEL DOCUMENTO</h2>
                  </div>
            </div>
            <div class="row">
            <!-- Ciudad -->
               <div class="form-group ml-3" style="width:47.5%">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                    </div>
                    <input title="Ciudad donde se lleva a cabo el contrato" type="text" class="form-control" name="newContractCity" placeholder="Ciudad del contrato" required>
                  </div> 
               </div>
            <!-- Dirección -->
               <div class="form-group ml-3" style="width:47.5%">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-home"></i></span>
                    </div>
                    <input title="Dirección donde se debe o se efectuó la compraventa" type="text" class="form-control" name="newContractAddress" placeholder="Dirección de la compraventa" required>
                  </div> 
               </div>
             </div>
            <div class="row">  
              <div class="form-group ml-3" style="width: 96.5%">
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-dollar-sign"></i></span>
                  </div>
                  <input class="form-control" type="number" name="newAmount" placeholder="Dinero pagado por el vehículo en pesos colombianos (COP)" required>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="form-group ml-3" style="width:96.5%">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-credit-card"></i></span>
                    </div>
                    <select id="tipo-pago" title="Metodo de pago del vehículo" name="newPaymentType" class="form-control" required>
                      <option value="">Forma de pago</option>
                      <option value="Efectivo">Efectivo</option>
                      <option value="Deposito">Deposito</option>
                    </select>
                  </div>
               </div>
            </div>
            <div id="cuenta-pago">
              
            </div>
            <div class="row">
              <div class="form-group ml-3" style="width:96.5%">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-file-invoice-dollar"></i></span>
                    </div>
                    <select title="Persona que se hara cargo de los gastos de traspaso" name="newVehicleExpenses" class="form-control" required>
                      <option value="">Gastos de traspaso</option>
                      <option value="Comprador">Comprador</option>
                      <option value="Vendedor">Vendedor</option>
                      <option value="Partes iguales">Partes iguales</option>
                    </select>
                  </div>
               </div>
            </div>
            <div class="row">
              <div class="form-group ml-3" style="width:96.5%">
                <div class="input-group mb-3">
                    <div class="input-group-prepend d-md-inline-flex">
                    <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                    </div>
                    <select id="numero-testigos" title="Número de testigos de la compraventa" name="newWitnessNumber" class="form-control" required>
                      <option value="">Número de testigos</option>
                      <option value="0">0</option>
                      <option value="1">1</option>
                      <option value="2">2</option>
                    </select>
                  </div>
               </div>
            </div>
            <div id="info-testigos">
              
            </div>
             <div class="row">
            <!-- Email -->
              <div class="form-group ml-3" style="width:96.5%">
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
                <button type="submit" class="btn btn-success" name="newDocument">Descargar compraventa</button>
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
  <script src="/Views/js/compraventa.js"></script>
@stop