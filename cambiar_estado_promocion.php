<?php
session_start();
require_once 'db/config.php';

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
  header('Location: index.php');
  exit();
}

if (!isset($_GET['id'], $_GET['accion'])) {
  die("❌ Datos incompletos.");
}

$id = intval($_GET['id']);
$accion = $_GET['accion'];

if ($accion === 'activar') {

  $estado = 'activa';
} elseif ($accion === 'desactivar') {
  $estado = 'inactiva';
} else {
  die("❌ Acción no válida.");
}

$stmt = $conn->prepare("UPDATE promociones SET estado = ? WHERE id = ?");
$stmt->bind_param("si", $estado, $id);

if ($stmt->execute()) {
  header("Location: admin_promociones.php?msg=estado_actualizado");
  exit();
} else {
  die("❌ Error al actualizar estado.");
}
