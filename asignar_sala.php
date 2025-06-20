<?php
session_start();
require_once 'db/config.php';

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
  header('Location: index.php');
  exit();
}

$solicitud_id = $_POST['solicitud_id'];
$sala_id = $_POST['sala_id'];

$query = "SELECT nombre, tipo_evento FROM solicitudes_sala WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $solicitud_id);
$stmt->execute();
$result = $stmt->get_result();
$sol = $result->fetch_assoc();
$uso = $sol ? $sol['tipo_evento'] . ' - ' . $sol['nombre'] : 'Asignado';

$conn->begin_transaction();

try {

  $stmt1 = $conn->prepare("UPDATE solicitudes_sala SET sala_asignada = ? WHERE id = ?");
  $stmt1->bind_param("ii", $sala_id, $solicitud_id);
  $stmt1->execute();
  $stmt2 = $conn->prepare("UPDATE salas SET estado = 'ocupada', uso_actual = ? WHERE id = ?");
  $stmt2->bind_param("si", $uso, $sala_id);
  $stmt2->execute();

  $conn->commit();
  header("Location: admin_salase.php");
  exit();
} catch (Exception $e) {
  $conn->rollback();
  die("❌ Error al asignar sala: " . $e->getMessage());
}
