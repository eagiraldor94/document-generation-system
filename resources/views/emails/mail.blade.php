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
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
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
    <div class="row">
      <h3 class="align-self-center" style="text-align: center;padding-top: 10%; font-size: 12px"><b>AVISO DE CONFIDENCIALIDAD</b></h3>
      <p style="text-align: justify; font-size: 10px">La información contenida en este email, está destinada para el uso del individuo, compañía o entidad a la cual está direccionado y contiene información que es de carácter Confidencial o Privada. Si usted no es el destinatario autorizado, cualquier retención, distribución, utilización, divulgación o copia del presente mensaje, está terminantemente prohibida y puede ser sancionada por la ley. Si por error recibe este mensaje, favor notificar a la dirección soporte@ludcis.com y elimine el mensaje y cualquier copia de este de forma inmediata. Este mensaje ha sido revisado con software antivirus, para evitar que contenga código malicioso que pueda afectar sistemas de cómputo, sin embargo, es responsabilidad del destinatario confirmar este hecho en el momento de su recepción. El presente mensaje, no es una declaración oficial de LUDCIS S.A.S ni de ninguno de sus miembros de los cuerpos directivos de la compañía. Gracias.</p>
    </div>
</body>
</html>
