<?php
session_start();
ini_set('display_errors', 1);

// Contraseña maestra (cambiar por una segura)
$CONTRASENA_MAESTRA = 'Fec@aguinaldo2025!';

// Obtener parámetro de acción
$a = isset($_GET['a']) ? $_GET['a'] : '';

// ========== ACCIÓN: LOGIN SIMPLE ==========
if($a=='login')
{
    $password_ingresada = $_POST['password'] ?? '';

    if($password_ingresada === $CONTRASENA_MAESTRA) {
        $_SESSION['autenticado'] = true;
        $_SESSION['fecha_login'] = time();
        header("Location: index_admin_simple.php?a=home");
        exit;
    } else {
        header("Location: index_admin_simple.php?m=" . urlencode('Contraseña incorrecta'));
        exit;
    }
}

// ========== VERIFICAR AUTENTICACIÓN ==========
if($a != 'login' && $a != '' && !isset($_SESSION['autenticado'])) {
    header("Location: index_admin_simple.php?m=" . urlencode('Debe iniciar sesión primero'));
    exit;
}

// ========== ACCIÓN: HOME ==========
if($a=="home")
{
    if(!isset($_SESSION['autenticado'])){
        header("Location: index_admin_simple.php");
        exit;
    }

    // Variables para la plantilla
    $VERMENSAJE = 'none';
    $MENSAJE = '';
    $TABLA = 'none';
    $IDENTIFICACION = '';
    $NOMBRE = '';
    $PRODUCTO = '';
    $ESTADO = '';

    if(isset($_GET['m'])) {
        $MENSAJE = $_GET['m'];
        $VERMENSAJE = 'block';
    }
    ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="favicon.png">

    <title>Fecolsubsidio - Aguinaldo FEC 2025</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/custom.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/d29ca2114a.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

  </head>

  <body>

<div style="position: fixed; top: 10px; left: 10px; z-index: 1000;">
      <a href="index_admin_simple.php?a=home"><img src="images/logob.png" style="height:40px;"></a>
    </div>
    <div style="position: fixed; top: 15px; right: 20px; z-index: 1000;">
      <a href="index_admin_simple.php?a=salir" style="text-decoration: none; color: #333; font-size: 14px;"><i class="fas fa-sign-out-alt"></i> Salir</a>
    </div>


    <div class="container" role="main">
        <div class="alert alert-success" style="display:<?php echo $VERMENSAJE; ?>;">
        <?php echo $MENSAJE; ?>
        </div>





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
</div>
</form>
<br/><br/>
<table class="table table-striped" style="display:<?php echo $TABLA; ?>;">
    <tr>
        <td>Cedula</td>
        <td>Nombre</td>
        <td>Producto</td>
        <td>Estado</td>
        <td></td>

    </tr>

    <tr>
        <td><?php echo $IDENTIFICACION; ?></td>
        <td><?php echo $NOMBRE; ?></td>
        <td><?php echo $PRODUCTO; ?></td>
        <td><?php echo $ESTADO; ?></td>
        <td>
            <a href="?a=generar_pdf&identificacion=<?php echo $IDENTIFICACION; ?>">
            <div class="btn btn-success form-control" style="border-radius: 10px;">Generar PDF</div>
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

    <script>







  </body>
</html>
    <?php
    exit;
}

// ========== ACCIÓN: BUSCAR ==========
if($a=="buscar")
{
    if(!isset($_SESSION['autenticado'])){
        header("Location: index_admin_simple.php");
        exit;
    }

    $identificacion_buscar = $_POST['identificacion'];

    // Cargar librerías
    require 'vendor/autoload.php';
    require('fpdf/fpdf.php');

    try {
        // Leer Excel
        $archivo_excel = 'base_datos_aguinaldo_2025.xlsx';
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($archivo_excel);
        $hoja = $spreadsheet->getSheetByName('base de datos');

        $encontrado = false;
        $datos = [];

        // Buscar la cédula en el Excel
        foreach ($hoja->getRowIterator() as $row) {
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);

            $rowData = [];
            foreach ($cellIterator as $cell) {
                $rowData[] = $cell->getCalculatedValue();
            }

            if(isset($rowData[0]) && $rowData[0] == $identificacion_buscar) {
                $encontrado = true;
                $datos = [
                    'identificacion' => $rowData[0],
                    'nombre' => $rowData[1] ?? '',
                    'valor_aguinaldo' => floatval($rowData[2] ?? 0),
                    'valor_retencion' => floatval($rowData[3] ?? 0),
                    'valor_abonado' => floatval($rowData[4] ?? 0)
                ];
                break;
            }
        }

        if(!$encontrado) {
            header("Location: index_admin_simple.php?a=home&m=" . urlencode("No se encontró registro para la cédula: $identificacion_buscar"));
            exit;
        }

        // Generar PDF directamente
        $pdf = new \setasign\Fpdi\Fpdi();

        $pdf->setSourceFile('plantilla_aguinaldo.pdf');
        $tplId = $pdf->importPage(1);

        $size = $pdf->getImportedPageSize($tplId);
        $orientation = ($size['width'] > $size['height']) ? 'L' : 'P';

        $pdf->AddPage($orientation, [$size['width'], $size['height']]);
        $pdf->useTemplate($tplId);

        // Configurar fuente y escribir datos
        $pdf->SetTextColor(0, 0, 0);

        // Campo 1: Nombre
        $pdf->SetFont('Helvetica', '', 10);
        $pdf->SetXY(8, 98.5);
        $pdf->Write(0, $datos['nombre']);

        // Campo 2: Valor aguinaldo
        $pdf->SetFont('Helvetica', '', 10);
        $pdf->SetXY(127, 98.5);
        $pdf->Write(0, '$' . number_format($datos['valor_aguinaldo'], 0, ',', '.'));

        // Campo 3: Retención
        $pdf->SetFont('Helvetica', '', 10);
        $pdf->SetXY(41, 118);
        $pdf->Write(0, '$' . number_format($datos['valor_retencion'], 0, ',', '.'));

        // Campo 4: Valor abonado
        $pdf->SetFont('Helvetica', '', 10);
        $pdf->SetXY(127, 118);
        $pdf->Write(0, '$' . number_format($datos['valor_abonado'], 0, ',', '.'));

        // Descargar PDF
        $pdf->Output('D', 'Aguinaldo_' . $identificacion_buscar . '.pdf');
        exit;

    } catch(Exception $e) {
        header("Location: index_admin_simple.php?a=home&m=" . urlencode("Error: " . $e->getMessage()));
        exit;
    }
}

// ========== ACCIÓN: MOSTRAR RESULTADO ==========
if($a=="mostrar_resultado")
{
    if(!isset($_SESSION['autenticado'])){
        header("Location: index_admin_simple.php");
        exit;
    }

    // Variables para la plantilla
    $VERMENSAJE = 'none';
    $MENSAJE = '';
    $TABLA = 'table';
    $IDENTIFICACION = $_GET['identificacion'] ?? '';
    $NOMBRE = $_GET['nombre'] ?? '';
    $PRODUCTO = 'Aguinaldo 2025';
    $ESTADO = 'Pendiente';

    ?>\n<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="favicon.png">

    <title>Fecolsubsidio - Aguinaldo FEC 2025</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/custom.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/d29ca2114a.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

  </head>

  <body>

<div style="position: fixed; top: 10px; left: 10px; z-index: 1000;">
      <a href="index_admin_simple.php?a=home"><img src="images/logob.png" style="height:40px;"></a>
    </div>
    <div style="position: fixed; top: 15px; right: 20px; z-index: 1000;">
      <a href="index_admin_simple.php?a=salir" style="text-decoration: none; color: #333; font-size: 14px;"><i class="fas fa-sign-out-alt"></i> Salir</a>
    </div>




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
</div>
</form>
<br/><br/>
<table class="table table-striped" style="display:<?php echo $TABLA; ?>;">
    <tr>
        <td>Cedula</td>
        <td>Nombre</td>
        <td>Producto</td>
        <td>Estado</td>
        <td></td>

    </tr>

    <tr>
        <td><?php echo $IDENTIFICACION; ?></td>
        <td><?php echo $NOMBRE; ?></td>
        <td><?php echo $PRODUCTO; ?></td>
        <td><?php echo $ESTADO; ?></td>
        <td>
            <a href="?a=generar_pdf&identificacion=<?php echo $IDENTIFICACION; ?>">
            <div class="btn btn-success form-control" style="border-radius: 10px;">Generar PDF</div>
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

    <script>







  </body>
</html>
    <?php
    exit;
}

// ========== ACCIÓN: GENERAR PDF ==========
if($a=="generar_pdf")
{
    if(!isset($_SESSION['autenticado'])){
        header("Location: index_admin_simple.php");
        exit;
    }

    $identificacion_buscar = $_GET['identificacion'];

    // Cargar librería PhpSpreadsheet
    require 'vendor/autoload.php';

    try {
        $archivo_excel = 'base_datos_aguinaldo_2025.xlsx';
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($archivo_excel);
        $hoja = $spreadsheet->getSheetByName('base de datos');

        $encontrado = false;
        $datos = [];

        // Recorrer filas buscando la identificación
        foreach ($hoja->getRowIterator() as $row) {
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);

            $rowData = [];
            foreach ($cellIterator as $cell) {
                $rowData[] = $cell->getCalculatedValue();
            }

            // Verificar si la columna A coincide con la identificación
            if(isset($rowData[0]) && $rowData[0] == $identificacion_buscar) {
                $encontrado = true;
                $datos = [
                    'identificacion' => $rowData[0],
                    'nombre' => $rowData[1] ?? '',
                    'valor_aguinaldo' => floatval($rowData[2] ?? 0),
                    'valor_retencion' => floatval($rowData[3] ?? 0),
                    'valor_abonado' => floatval($rowData[4] ?? 0)
                ];
                break;
            }
        }

        if(!$encontrado) {
            header("Location: index_admin_simple.php?a=home&m=" . urlencode("No se encontró registro para generar el PDF"));
            exit;
        }

        // Generar PDF usando FPDF/FPDI
        require('fpdf/fpdf.php');
        require('vendor/autoload.php');

        $pdf = new \setasign\Fpdi\Fpdi();

        // Importar la plantilla PDF existente
        $pdf->setSourceFile('plantilla_aguinaldo.pdf');
        $tplId = $pdf->importPage(1);

        // Obtener dimensiones de la página original
        $size = $pdf->getImportedPageSize($tplId);
        $orientation = ($size['width'] > $size['height']) ? 'L' : 'P'; // L=Landscape, P=Portrait

        // Crear página con la orientación y tamaño correcto
        $pdf->AddPage($orientation, [$size['width'], $size['height']]);

        // Usar la plantilla
        $pdf->useTemplate($tplId);

        // Configurar fuente
        //$pdf->SetFont('Helvetica', '', 12);
        $pdf->SetTextColor(0, 0, 0);

        // Escribir los 4 campos en coordenadas fijas (ajustar según tu plantilla)
        // Campo 1: Estimado(a) - Nombre completo
        $pdf->SetFont('Helvetica', '', 10);
        $pdf->SetXY(8, 98.5);
        $pdf->Write(0, $datos['nombre']);

        // Campo 2: Valor aguinaldo 2025
        $pdf->SetFont('Helvetica', '', 10);
        $pdf->SetXY(127, 98.5);
        $pdf->Write(0, '$' . number_format($datos['valor_aguinaldo'], 0, ',', '.'));

        // Campo 3: Valor retención en la fuente
        $pdf->SetFont('Helvetica', '', 10);
        $pdf->SetXY(41, 118);
        $pdf->Write(0, '$' . number_format($datos['valor_retencion'], 0, ',', '.'));

        // Campo 4: Valor abonado a los depósitos
        $pdf->SetFont('Helvetica', '', 10);
        $pdf->SetXY(127, 118);
        $pdf->Write(0, '$' . number_format($datos['valor_abonado'], 0, ',', '.'));

        // Salida del PDF
        $pdf->Output('I', 'Aguinaldo_' . $identificacion_buscar . '.pdf');
        exit;

    } catch(Exception $e) {
        header("Location: index_admin_simple.php?a=home&m=" . urlencode("Error al generar el PDF: " . $e->getMessage()));
        exit;
    }
}

// ========== ACCIÓN: SALIR ==========
if($a=="salir")
{
    session_destroy();
    header("Location: index_admin_simple.php");
    exit;
}

// ========== PÁGINA PRINCIPAL (Login Simple) ==========
$mensaje = '';
$vermensaje = 'none';
if(isset($_GET['m'])) {
    $mensaje = $_GET['m'];
    $vermensaje = 'block';
}

// Si ya está autenticado, redirigir a home
if(isset($_SESSION['autenticado'])) {
    header("Location: index_admin_simple.php?a=home");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Fecolsubsidio - Aguinaldo FEC 2025</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/custom.min.css">
    <link rel="stylesheet" href="css/custom.css">
</head>
<body style="padding-top: 0px !important;">
    <div class="container">
        <div class="row">
            <div class="col-lg-3"><center><img src="img/logo-fondo-empleados-colsubsidio.png" style="width: 80%;padding: 15px;"></center></div>
            <div class="col-lg-7"></div>
            <div class="col-lg-2"><br><br><a href="https://fecolsubsidio.com/" class="btn verde" style="padding: 5px; background-color: #88a80d; font-size: 15px;color: aliceblue;">Ir a la pagina principal</a></div>
            <div class="col-lg-3"></div>
            <div class="col-lg-6" style="padding: 15px;">
                <div class="verde" style="font-family: sans-serif; text-align: center; font-weight: 700; font-size: 30px; background-color: #333333; padding: 5px; color: white;">
                    Aguinaldo FEC 2025 - Panel de Administración
                </div>
            </div>
            <div class="col-lg-3"></div>
        </div>
        <form name="loginform" id="loginform" action="index_admin_simple.php?a=login" method="POST">
            <div class="row">
            <div class="col-lg-4"></div>
            <div class="col-lg-4">
                <div class="alert alert-warning" style="display:<?php echo $vermensaje; ?>;">
                    <?php echo $mensaje; ?>
                </div>
                <br><br><br>
                <label style="font-weight: bold;">Contraseña de acceso:</label>
                <input name="password" id="password" type="password" class="form-control" required placeholder="Ingrese la contraseña" autofocus>
                <br>
                <button type="submit" id="btnEnviar" class="btn btn-success form-control">Acceder</button>
                <br><br>
            </div>
            <div class="col-lg-4"></div>
            <div class="col-lg-12" style="padding-bottom: 60px;"></div>
            </div>
        </form>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-lg-3"></div>
            <div class="col-lg-6" style="text-align: center; font-family: sans-serif;">
                <hr>
                <h4 style="font-weight: 800; font-size: 25px; color: #80B918">MÁS INFORMACIÓN</h4>
                <p style="font-weight: 500; font-size: 15px;">PBX:(601) 232 84 55 - atencionalasociado@fecolsubsidio.com</p>
            </div>
            <div class="col-lg-3"></div>
        </div>
    </div>

    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>

    <script>
        const formulario = document.getElementById('loginform');
        const btnEnviar = document.getElementById('btnEnviar');

        formulario.addEventListener('submit', function() {
          btnEnviar.disabled = true;
          btnEnviar.textContent = 'Validando acceso...';
        });
    </script>
</body>
</html>
