<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
  die('Acceso no autorizado.');
}

require 'db/config.php';

$dni = $_POST['dni'];
$nuevo_estado = $_POST['estado'];

if ($_SESSION['dni'] === $dni) {
  die('❌ No puedes cambiar tu propio estado.');
}

$query = "UPDATE socios SET estado = ? WHERE dni = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $nuevo_estado, $dni);
$stmt->execute();

header("Location: admin_socios.php?mensaje=estado_actualizado");
exit();
