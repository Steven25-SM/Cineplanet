<?php
session_start();
require_once 'db/config.php';

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
  header('Location: index.php');
  exit();
}

if (!isset($_GET['id'], $_GET['accion'])) {
  die("❌ Parámetros faltantes.");
}

$id = intval($_GET['id']);
$accion = $_GET['accion'];

$hoy = date('Y-m-d');
$consulta = mysqli_query($conn, "SELECT * FROM promociones WHERE id = $id LIMIT 1");
$promo = mysqli_fetch_assoc($consulta);

if (!$promo) {
  die("❌ Promoción no encontrada.");
}

if ($hoy < $promo['fecha_inicio'] || $hoy > $promo['fecha_fin']) {
  die("❌ Promoción fuera de rango de fechas.");
}

if ($promo['estado'] === 'bloqueada') {
  die("❌ Esta promoción está bloqueada por el sistema.");
}

$nuevoEstado = $accion === 'activar' ? 'activa' : 'inactiva';

$stmt = $conn->prepare("UPDATE promociones SET estado = ? WHERE id = ?");
$stmt->bind_param("si", $nuevoEstado, $id);

if ($stmt->execute()) {
  header("Location: admin_promociones.php?msg=estado_actualizado");
  exit();
} else {
  die("❌ Error al cambiar el estado.");
}
