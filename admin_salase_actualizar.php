<?php
session_start();
require_once 'db/config.php';

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
  header('Location: index.php');
  exit();
}

if (!isset($_POST['id'], $_POST['nombre'], $_POST['capacidad'], $_POST['descripcion'], $_POST['estado'])) {
  die("❌ Faltan datos del formulario.");
}

$id = intval($_POST['id']);
$nombre = trim($_POST['nombre']);
$capacidad = intval($_POST['capacidad']);
$descripcion = trim($_POST['descripcion']);
$estado = trim($_POST['estado']);

$sql = "UPDATE salas SET nombre = ?, capacidad = ?, descripcion = ?, estado = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sissi", $nombre, $capacidad, $descripcion, $estado, $id);

if ($stmt->execute()) {
  header("Location: admin_salase.php");
  exit();
} else {
  die("❌ Error al actualizar sala: " . $stmt->error);
}
