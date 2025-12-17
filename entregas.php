<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="favicon.png">

    <title>Fecolsubsidio - Aguinaldo FEC 2025</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/custom.min.css" rel="stylesheet">
    <link href="css/jquery.dataTables.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/d29ca2114a.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Bootstrap theme -->

  </head>

  <body>

<div class="navbar navbar-expand-lg fixed-top navbar-dark bg-primary">
      <div class="container">
        <a href="index.php?a=home" class="navbar-brand"><img src="images/logob.png" style="height:35px;"></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav mr-auto">
              <li class="nav-item">
              <a class="nav-link" href="index.php?a=home"> <i class="fas fa-home"></i> Inicio</a>
            </li>


            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" id="download"> <i class="fas fa-tools"></i> Herramientas <span class="caret"></span></a>
              <div class="dropdown-menu" aria-labelledby="download">
                  {MODULOS}
              </div>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="index.php?a=cc"> <i class="fas fa-key"></i> Cambiar clave</a>
            </li>
            <li class="nav-item">
            <a class="nav-link" href="index.php?a=salir"> <i class="fas fa-sign-out-alt"></i> Cerrar sesi贸n</a>
            </li>
          </ul>


        </div>
      </div>
    </div>


    <div class="container" role="main">
        <div class="alert alert-success" style="display:{VERMENSAJE};">
        {MENSAJE}
        </div>


        <!-- InstanceBeginEditable name="EditRegion1" -->




<div style="width:auto;padding:10px;color:#ffffff;background: #000000;font-size: 26px; text-align: center;">Aguinaldo FEC 2025</div>
<br/><br/>

<form action="?a=buscar" method="post">
<div class="row">
    <div class="col-lg-2"></div>

    <div class="col-lg-4">
        <label for="identificacion" style="font-weight: bold;">Ingrese el numero de cedula del asociado</label>
        <input type="number" required class="form-control" id="identificacion" name="identificacion">
    </div>
    <div class="col-lg-4">
        <label>&nbsp;</label>
        <input type="submit" value="Buscar" class="btn btn-success form-control" style="border-radius: 10px;">

    </div>

    <div class="col-lg-2"></div>
    <div class="col-lg-4"></div>
    <div class="col-lg-4">
        <br/><br/><br/>
        <a href="?a=reporte"target="_new"><div class="btn btn-warning form-control" style="border-radius: 10px;">Descargar reporte</div></a>

    </div>
    <div class="col-lg-4"></div>
</div>
</form>
<br/><br/>
<table class="table table-striped" style="display:{TABLA};">
    <tr>
        <td>Cedula</td>
        <td>Nombre</td>
        <td>Producto</td>
        <td>Estado</td>
        <td></td>

    </tr>

    <tr>
        <td>{IDENTIFICACION}</td>
        <td>{NOMBRE}</td>
        <td>{PRODUCTO}</td>
        <td>{ESTADO}</td>
        <td>
            <a href="?a=entregar&id_solicitud={ID_SOLICITUD}">
            <div class="btn btn-success form-control" style="border-radius: 10px;">Ver proceso</div>
            </a>
            </td>

    </tr>

</table>








    </div>






    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/custom.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/jquery.dataTables.min.js"></script>

    <script>







  </body>
</html>