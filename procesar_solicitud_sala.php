<?php
session_start();

$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'cineplanet';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("❌ Error de conexión: " . $conn->connect_error);
}

$dni = isset($_SESSION['dni']) ? $_SESSION['dni'] : null;
$nombre    = $_POST['nombre'];
$correo    = $_POST['correo'];
$telefono  = $_POST['telefono'];
$sucursal  = $_POST['sucursal'];
$tipo      = $_POST['tipo_sala'];
$tipo_evento = $_POST['tipo_evento'];  
$personas  = $_POST['personas'];
$fecha     = $_POST['fecha'];
$hora      = $_POST['hora'];
$mensaje   = $_POST['mensaje'];

$stmt = $conn->prepare("INSERT INTO solicitudes_sala 
(dni, nombre, correo, telefono, sucursal, tipo_sala, personas, fecha, hora, mensaje, tipo_evento)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssssssss", $dni, $nombre, $correo, $telefono, $sucursal, $tipo_sala, $personas, $fecha, $hora, $mensaje, $tipo_evento);


$stmt->bind_param("ssssssisss", $dni, $nombre, $correo, $telefono, $sucursal, $tipo, $personas, $fecha, $hora, $mensaje);

if ($stmt->execute()) {
    echo "<script>
        alert('✅ Solicitud enviada con éxito. Te contactaremos pronto.');
        window.location.href = 'index.php';
    </script>";
} else {
    echo "<script>
        alert('❌ Error al enviar la solicitud: " . $conn->error . "');
        window.history.back();
    </script>";
}

$stmt->close();
$conn->close();
?>
