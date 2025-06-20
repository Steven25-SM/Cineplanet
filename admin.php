<?php
session_start();

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
  header('Location: index.php');
  exit();
}

require_once 'db/config.php';

$pendientes_tickets = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM tickets WHERE estado = 'pendiente'"))[0];
$pendientes_solicitudes = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM solicitudes_sala WHERE estado = 'pendiente'"))[0];
$total_socios = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM socios"))[0];
$total_salasespeciales = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM salas WHERE tipo = 'especial'"))[0];
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Panel de Administración</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      background-color: #fff0f0;
    }

    header {
      background-color: #c30000;
      color: white;
      padding: 15px;
      text-align: center;
      font-size: 1.5rem;
      font-weight: bold;
      letter-spacing: 1px;
    }

    .admin-panel {
      padding: 20px;
    }

    .sidebar {
      background-color: #f2f2f2;
      padding: 20px;
      border-right: 2px solid #ccc;
      height: 100%;
    }

    .sidebar a {
      display: block;
      margin-bottom: 10px;
      font-weight: 500;
      color: #c30000;
      text-decoration: none;
    }

    .sidebar a:hover {
      text-decoration: underline;
    }

    .resumen-card {
      background-color: #fff;
      border: 1px solid #ccc;
      padding: 20px;
      border-radius: 10px;
      margin-bottom: 20px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
  </style>
</head>

<body>
  <header>
    <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px;">
      <div>
        <a href="index.php" class="btn btn-primary" style="font-weight: bold; font-size: 16px;">
  Volver al inicio
</a>

      </div>
      <div>
        <h1 style="margin: 0;">Panel de administrador</h1>
      </div>
      <div></div>
    </div>
  </header>

  <div class="container-fluid">
    <div class="row">

      <div class="col-md-3 sidebar">
        <h5>👤 Bienvenido, Admin</h5>
        <a href="admin_promociones.php">📢 Gestionar promociones</a>
        <a href="admin_tickets.php">🎟️ Gestionar tickets de soporte</a>
        <a href="admin_solicitudes_sala.php">📩 Ver solicitudes de salas especiales</a>
        <a href="admin_salase.php">🏰 Gestionar salas especiales</a>
        <a href="admin_socios.php" class="btn btn-dark mt-3">👥 Administrar Socios</a>

      </div>

      <div class="col-md-9 admin-panel">
        <h3 class="mb-4">📊 Resumen General</h3>

        <div class="row">
          <div class="col-md-6">
            <div class="resumen-card">
              <h5>🎟️ Tickets Pendientes</h5>
              <p><?= $pendientes_tickets ?> sin responder</p>
              <a href="admin_tickets.php" class="btn btn-outline-danger btn-sm">Ver tickets</a>
            </div>
          </div>
          <div class="col-md-6">
            <div class="resumen-card">
              <h5>📩 Solicitudes de Sala Pendientes</h5>
              <p><?= $pendientes_solicitudes ?> solicitudes esperando aprobación</p>
              <a href="admin_solicitudes_sala.php" class="btn btn-outline-warning btn-sm">Ver solicitudes</a>
            </div>
          </div>
          <div class="col-md-6">
            <div class="resumen-card">
              <h5>👥 Socios Registrados</h5>
              <p><?= $total_socios ?> socios activos</p>
            </div>
          </div>
          <div class="col-md-6">
            <div class="resumen-card">
              <h5>🏰 Salas Especiales Disponibles</h5>
              <p><?= $total_salasespeciales ?> salas creadas</p>
              <a href="admin_salase.php" class="btn btn-outline-primary btn-sm">Gestionar salas</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</body>

</html>