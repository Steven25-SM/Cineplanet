<?php
session_start();

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['monto_dulceria'])) {
  $_SESSION['monto_dulceria'] = floatval($data['monto_dulceria']);
  echo json_encode(['status' => 'ok']);
} else {
  echo json_encode(['status' => 'error', 'message' => 'Monto no recibido']);
}
?>
