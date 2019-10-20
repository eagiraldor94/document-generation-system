<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
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
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

    <!-- Bootstrap 4 -->
  <link rel="stylesheet" href="/Views/plugins/bootstrap/css/bootstrap.css">
  <!--=====================================
=            Javascript Plugins            =
======================================-->


  <!-- jQuery -->
  <script src="/Views/plugins/jquery/jquery.min.js"></script>
  <!-- jQuery Number -->
  <script src="/Views/plugins/jQueryNumber/jquery.number.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="/Views/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<!-- Site wrapper -->

    <div class="row" style="text-align: center;">
      <img class="align-self-center" style="width: 25%" src="https://documentos.ludcis.com/Views/img/plantilla/logo.jpg" alt="Logotipo ludcis">
    </div>
    <div class="row">
      <h1 class="align-self-center"><b>Envío de {{$document}} <small class="text-muted">/ Ludcis</small></b></h1>
    </div>
      @yield('body')
</body>
</html>
