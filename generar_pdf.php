<?php
// Protección: Verificar sesión
if(!$_COOKIE[fecagu] || !$_COOKIE[fecaguotp]){
    echo "<script>alert('Debe iniciar sesión'); window.location='index.php';</script>";
    exit;
}

// Obtener datos del usuario logueado
$sesiones = json_decode(file_get_contents('sesiones.json'), true);
$sesion = $sesiones[$_COOKIE[fecagu]];
$identificacion = $sesion['identificacion'];

// Aquí va tu lógica para generar el PDF
// Por ahora, solo mostramos un mensaje

echo "<h1>Generando PDF para: {$sesion['nombre']}</h1>";
echo "<p>Cédula: $identificacion</p>";
echo "<p><a href='index.php?a=home'>← Volver</a></p>";

// TODO: Conectar con ExcelReader y PDFGenerator cuando estén listos
?>