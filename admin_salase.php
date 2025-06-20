<?php
session_start();
require_once 'db/config.php';

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
  header('Location: index.php');
  exit();
}

$salasQuery = "SELECT * FROM salas WHERE tipo = 'especial'";
$salas = mysqli_query($conn, $salasQuery);
$solicitudesQuery = "SELECT * FROM solicitudes_sala WHERE estado = 'aprobado' AND sala_asignada IS NULL";
$solicitudes = mysqli_query($conn, $solicitudesQuery);
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Gestionar Salas Especiales</title>
  <link rel="icon" type="image/png" href="media/logo2.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

  <div class="container py-4">
    <h1 class="mb-4">🏰 Gestión de Salas Especiales</h1>
    <a href="admin.php" class="btn btn-secondary mb-3">🔙 Volver al Panel</a>
    <a href="admin_salase_agregar.php" class="btn btn-primary mb-3 ms-2">➕ Agregar nueva sala especial</a>

    <h4 class="mt-4">📋 Lista de Salas Especiales</h4>
    <div class="table-responsive">
      <table class="table table-bordered table-hover">
        <thead class="table-dark">
          <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Capacidad</th>
            <th>Descripción</th>
            <th>Estado</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($sala = mysqli_fetch_assoc($salas)) { ?>
            <tr>
              <td><?= $sala['id'] ?></td>
              <td><?= htmlspecialchars($sala['nombre']) ?></td>
              <td><?= $sala['capacidad'] ?></td>
              <td><?= htmlspecialchars($sala['descripcion']) ?></td>
              <td><?= htmlspecialchars($sala['estado'] ?? 'activa') ?></td>

              <td>
                <a href="admin_salase_editar.php?id=<?= $sala['id'] ?>" class="btn btn-sm btn-warning">✏️ Editar</a>
                <a href="admin_salase_eliminar.php?id=<?= $sala['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar esta sala?')">🗑️ Eliminar</a>
              </td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>

    <h4 class="mt-5">📬 Solicitudes Aprobadas (Pendientes de Sala)</h4>
    <?php if (mysqli_num_rows($solicitudes) > 0): ?>
      <div class="table-responsive">
        <table class="table table-bordered table-striped">
          <thead class="table-primary">
            <tr>
              <th>ID</th>
              <th>Nombre</th>
              <th>Correo</th>
              <th>Evento</th>
              <th>Fecha</th>
              <th>Hora</th>
              <th>Asignar Sala</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($sol = mysqli_fetch_assoc($solicitudes)) { ?>
              <tr>
                <td><?= $sol['id'] ?></td>
                <td><?= htmlspecialchars($sol['nombre']) ?></td>
                <td><?= htmlspecialchars($sol['correo']) ?></td>
                <td><?= htmlspecialchars($sol['tipo_evento'] ?? 'No especificado') ?>
                <td><?= $sol['fecha'] ?></td>
                <td><?= $sol['hora'] ?></td>
                <td>
                  <form method="post" action="asignar_sala.php" class="d-flex">
                    <input type="hidden" name="solicitud_id" value="<?= $sol['id'] ?>">
                    <select name="sala_id" class="form-select form-select-sm me-2" required>
                      <option value="">-- Sala --</option>
                      <?php

                      $salasDisponibles = mysqli_query($conn, "SELECT * FROM salas WHERE tipo = 'especial'");
                      while ($s = mysqli_fetch_assoc($salasDisponibles)) {
                        echo "<option value='{$s['id']}'>Sala {$s['id']}</option>";
                      }
                      ?>

                    </select>
                    <button type="submit" class="btn btn-success btn-sm">Asignar</button>
                  </form>
                </td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <div class="alert alert-info">No hay solicitudes aprobadas sin sala asignada.</div>
    <?php endif; ?>
  </div>

</body>

</html>