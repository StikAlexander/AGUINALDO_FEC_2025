<?php
include('funciones.php');
ini_set('display_errors', 1);

// Obtener parámetro de acción
$a = isset($_GET['a']) ? $_GET['a'] : '';

// ========== ACCIÓN: LOGIN ==========
if($a=='login')
{
    $data = array("TransportKey" => "{766862FB-B809-4E30-8A5D-318C941DEB00}", "NumeroDocumento" => $_POST['cedula']."","Password" => $_POST['contr']);
    $post = json_encode($data);

    $ch = curl_init('https://190.26.205.170/FEC/WebApi/Api/FECUser');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($post))
    );
    $response = curl_exec($ch);
    curl_close($ch);

    $respuesta=json_decode($response);
    $res = explode("|", $respuesta->MensajeRetorno);

    $nombre="$res[3] $res[2]";
    $identificacion=$res[1];
    $telefono=$res[19];

    if($res[0]=='NO' || $res[0]=='XX' || !$res[0])
    {
        header("Location: index.php?m=" . urlencode('Su número de cédula o contraseña no son correctos. Inténtelo nuevamente'));
        exit;
    }
    else
    {
        $cookie=md5(time()+rand(1,1000000));
        setcookie('fecagu', $cookie);

        $codigo= random_int(10000, 99999);

        // Guardar sesión en archivo JSON
        $sesiones = [];
        if(file_exists('sesiones.json')) {
            $sesiones = json_decode(file_get_contents('sesiones.json'), true);
        }
        $sesiones[$cookie] = [
            'nombre' => $nombre,
            'identificacion' => $identificacion,
            'telefono' => $telefono,
            'codigo_otp' => $codigo,
            'fecha' => time()
        ];
        file_put_contents('sesiones.json', json_encode($sesiones));

        enviarsms($telefono, "FECOLSUBSIDIO - Su codigo de validacion para aguinaldo es: $codigo");

        header("Location: index.php?a=validar&identificacion=$identificacion");
        exit;
    }
}

// ========== ACCIÓN: VALIDAR OTP ==========
if($a=="validar")
{
    if(!$_COOKIE['fecagu']){
        header("Location: index.php?m=" . urlencode('Debe iniciar sesión primero'));
        exit;
    }

    $sesiones = json_decode(file_get_contents('sesiones.json'), true);
    $sesion = $sesiones[$_COOKIE['fecagu']];
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Fecolsubsidio - Solicitud de aguinaldos</title>
    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/prism-okaidia.css">
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
                <div class="verde" style="font-family: sans-serif; text-align: center; font-weight: 700; font-size: 30px; background-color: #333333; padding: 5px; color: white;">Validar acceso a solicitud de aguinaldos</div>
            </div>
            <div class="col-lg-3"></div>
        </div>
        <form name="loginform" id="loginform" action="index.php?a=validar2" method="POST">
            <div class="row">
            <div class="col-lg-4"></div>
            <div class="col-lg-4">
                <div class="alert alert-warning" style="display:none;"></div>
                <br><br><br>
                <center><label>Ingresa el código recibido en su celular o a su correo electrónico.</label></center>
                <input name="identificacion" id="identificacion" type="hidden" class="form-control" required value="<?php echo $sesion['identificacion']; ?>">
                <input name="codigo" id="codigo" type="number" class="form-control" required placeholder="Código de validación" style="text-align: center; font-size:24px;">
                <input type="submit" class="btn btn-success form-control" value="Continuar">
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

    <!-- Bootstrap core JavaScript -->
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/prism.js" data-manual></script>
    <script src="js/custom.js"></script>
</body>
</html>
    <?php
    exit;
}

// ========== ACCIÓN: VALIDAR2 (Procesar OTP) ==========
if($a=="validar2")
{
    if(!$_COOKIE['fecagu']){
        header("Location: index.php");
        exit;
    }

    $sesiones = json_decode(file_get_contents('sesiones.json'), true);
    $sesion = $sesiones[$_COOKIE['fecagu']];

    // Obtener identificación y código desde POST
    $identificacion_post = $_POST['identificacion'];
    $codigo_post = $_POST['codigo'];

    if($sesion['identificacion'] == $identificacion_post && $sesion['codigo_otp'] == $codigo_post)
    {
        setcookie('fecaguotp', time());
        header("Location: index.php?a=home");
        exit;
    } else {
        header("Location: index.php?a=validar&identificacion=" . $identificacion_post . "&m=" . urlencode('Código incorrecto'));
        exit;
    }
}

// ========== ACCIÓN: HOME ==========
if($a=="home")
{
    if(!$_COOKIE['fecagu'] || !$_COOKIE['fecaguotp']){
        header("Location: index.php");
        exit;
    }

    $sesiones = json_decode(file_get_contents('sesiones.json'), true);
    $sesion = $sesiones[$_COOKIE['fecagu']];
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Aguinaldo 2025 - Bienvenido</title>
        <link href="css/bootstrap.css" rel="stylesheet">
        <link href="css/custom.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    </head>
    <body>
        <div class="navbar navbar-expand-lg fixed-top navbar-dark bg-success">
            <div class="container">
                <a href="index.php?a=home" class="navbar-brand">Aguinaldo 2025</a>
                <div class="navbar-nav ml-auto">
                    <span class="navbar-text text-white mr-3">
                        <i class="fas fa-user"></i> <?php echo $sesion['nombre']; ?>
                    </span>
                    <a href="index.php?a=salir" class="btn btn-outline-light btn-sm">
                        <i class="fas fa-sign-out-alt"></i> Salir
                    </a>
                </div>
            </div>
        </div>

        <div class="container" style="margin-top: 100px;">
            <div class="alert alert-success">
                <h4><i class="fas fa-check-circle"></i> ¡Acceso exitoso!</h4>
                <p>Bienvenido <?php echo $sesion['nombre']; ?></p>
            </div>

            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5><i class="fas fa-gift"></i> Generar PDF de Aguinaldo</h5>
                </div>
                <div class="card-body">
                    <p>Aquí podrá generar su certificado de aguinaldo personalizado.</p>
                    <a href="generar_pdf.php" class="btn btn-primary">
                        <i class="fas fa-file-pdf"></i> Generar PDF
                    </a>
                </div>
            </div>
        </div>

        <script src="js/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
    </body>
    </html>
    <?php
    exit;
}

// ========== ACCIÓN: SALIR ==========
if($a=="salir")
{
    setcookie('fecagu', '', time()-3600);
    setcookie('fecaguotp', '', time()-3600);
    header("Location: index.php");
    exit;
}

// ========== PÁGINA PRINCIPAL (Login) ==========
// Preparar mensaje si viene en URL
$mensaje = '';
$vermensaje = 'none';
if(isset($_GET['m'])) {
    $mensaje = $_GET['m'];
    $vermensaje = 'block';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Fecolsubsidio - Solicitud de aguinaldos</title>
    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/prism-okaidia.css">
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
                    Aguinaldo FEC 2025
                </div>
            </div>
            <div class="col-lg-3"></div>
        </div>
        <form name="loginform" id="loginform" action="index.php?a=login" method="POST">
            <div class="row">
            <div class="col-lg-4"></div>
            <div class="col-lg-4">
                <div class="alert alert-warning" style="display:<?php echo $vermensaje; ?>;">
                    <?php echo $mensaje; ?>
                </div>
                <br><br><br>
                <label>Ingrese su cédula y contraseña FEC.</label>
                <input name="cedula" id="cedula" type="number" class="form-control" required placeholder="Cédula">
                <input name="contr" id="contr" type="password" class="form-control" placeholder="Contraseña" required>
                <br>
                <button type="submit" id="btnEnviar" class="btn btn-success form-control">Ingresar</button>
                <br><br>
                - Si tienes contraseña para consulta web, digítala.<br><br>
                - Si ingresas a la consulta web FEC por primera vez u olvidaste tu contraseña haz <a href="https://servicios3.selsacloud.com/linix/v6/860534049/loginAsociado.php?nit=860534049" target="_new">clic aquí</a>  y la contraseña será enviada a tu correo registrado en el FEC.<br><br><br>
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

    <!-- Bootstrap core JavaScript -->
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/prism.js" data-manual></script>
    <script src="js/custom.js"></script>

    <script>
        // Obtener el formulario y el botón
        const formulario = document.getElementById('loginform');
        const btnEnviar = document.getElementById('btnEnviar');

        // Añadir un evento de escucha para el envío del formulario
        formulario.addEventListener('submit', function() {
          // Deshabilitar el botón de envío
          btnEnviar.disabled = true;
          // Opcionalmente, cambiar el texto para dar feedback al usuario
          btnEnviar.textContent = 'Validando ingreso...';
        });
    </script>
</body>
</html>
