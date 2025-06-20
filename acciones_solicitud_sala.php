<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
  header('Location: index.php');
  exit();
}

require_once 'db/config.php';

$id = $_GET['id'];
$accion = $_GET['accion'];

if ($accion === 'aprobar') {
  $nuevoEstado = 'aprobado';
} elseif ($accion === 'rechazar') {
  $nuevoEstado = 'rechazado';
} else {
  header('Location: admin_solicitudes_sala.php');
  exit();
}

$sql = "UPDATE solicitudes_sala SET estado = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $nuevoEstado, $id);
$stmt->execute();

header('Location: admin_solicitudes_sala.php');
exit();
