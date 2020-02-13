@php
session_start();
@endphp
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title> Ludcis | @yield('title') </title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <link rel="icon" href="Views/img/plantilla/AF_FAVICON-01.png">
<!--=====================================
=            CSS Plugins            =
======================================-->

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="/Views/dist/css/adminlte.css">
  <!-- Form fix -->
  <link rel="stylesheet" href="/Views/dist/css/form.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Rubik&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Montserrat&display=swap" rel="stylesheet">
    <!-- iCheck for checkboxes and radio inputs -->
  <link rel="stylesheet" href="/Views/plugins/iCheck/all.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="/Views/plugins/daterangepicker/daterangepicker-bs3.css">
  @yield('css')
    <!-- Bootstrap 4 -->
  <link rel="stylesheet" href="/Views/plugins/bootstrap/css/bootstrap.css">
    <!-- Morris chart -->
  <link rel="stylesheet" href="/Views/plugins/morris/morris.css">
  <!--=====================================
=            Javascript Plugins            =
======================================-->
  <!-- jQuery -->
  <script src="/Views/plugins/jquery/jquery.min.js"></script>
  <!-- jQuery Number -->
  <script src="/Views/plugins/jQueryNumber/jquery.number.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="/Views/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- FastClick -->
  <script src="/Views/plugins/fastclick/fastclick.js"></script>
  <!-- AdminLTE App -->
  <script src="/Views/dist/js/adminlte.min.js"></script>
  <!-- AdminLTE for demo purposes -->
  <!-- DataTables -->
  <script src="/Views/plugins/datatables/jquery.dataTables.js"></script>
  <script src="/Views/plugins/datatables/dataTables.bootstrap4.js"></script>
  <script src="https://cdn.datatables.net/responsive/2.2.2/js/responsive.bootstrap4.min.js"></script>
  <script src="https://cdn.datatables.net/responsive/2.2.2/js/dataTables.responsive.min.js"></script>
  <!-- sweet alert 2 -->
  <script src="/Views/plugins/sweet-alert-2/sweetalert2.all.js"></script>
  <!-- iCheck 1.0.1 -->
  <script src="/Views/plugins/iCheck/icheck.min.js"></script>
  <!-- InputMask -->
<script src="/Views/plugins/input-mask/jquery.inputmask.js"></script>
<script src="/Views/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="/Views/plugins/input-mask/jquery.inputmask.extensions.js"></script>
<!-- daterangepicker -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js"></script>
<script src="/Views/plugins/daterangepicker/daterangepicker.js"></script>
<!-- Morris.js charts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="/Views/plugins/morris/morris.min.js"></script><!-- ChartJS 1.0.1 -->
<script src="/Views/plugins/chartjs-old/Chart.min.js"></script>
  <script src="/Views/js/plantilla.js"></script>
</head>
<body class="hold-transition layout-top-nav">
<!-- Site wrapper -->
  <div class="wrapper" style="background: #F4F6F9">
    @include('layouts.menu')
    @yield('content')
    @include('layouts.footer')
</div>

  @yield('js')
</body>
</html>
