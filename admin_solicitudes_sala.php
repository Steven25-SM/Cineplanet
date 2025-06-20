<?php
session_start();
require_once 'db/config.php';

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
  header('Location: index.php');
  exit();
}

$result = mysqli_query($conn, "SELECT * FROM solicitudes_sala ORDER BY fecha DESC");
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Solicitudes de Salas</title>
  <link rel="icon" type="image/png" href="media/logo2.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
  <div class="container py-4">
    <h2>📩 Solicitudes de Salas Especiales</h2>
    <a href="admin.php" class="btn btn-secondary mb-3">⬅ Volver al panel</a>

    <?php if (mysqli_num_rows($result) === 0): ?>
      <div class="alert alert-info">No hay solicitudes registradas.</div>
    <?php else: ?>
      <table class="table table-bordered">
        <thead class="table-dark">
          <tr>
            <th>Nombre</th>
            <th>Correo</th>
            <th>Teléfono</th>
            <th>Tipo Evento</th>
            <th>Sucursal</th>
            <th>Tipo Sala</th>
            <th>Fecha</th>
            <th>Hora</th>
            <th>Estado</th>
            <th>Sala Asignada</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($s = mysqli_fetch_assoc($result)): ?>
            <tr>
              <td><?= htmlspecialchars($s['nombre']) ?></td>
              <td><?= htmlspecialchars($s['correo']) ?></td>
              <td><?= htmlspecialchars($s['telefono']) ?></td>
              <td><?= htmlspecialchars($s['tipo_evento'] ?? 'No especificado') ?></td>
              <td><?= htmlspecialchars($s['sucursal']) ?></td>
              <td><?= htmlspecialchars($s['tipo_sala']) ?></td>
              <td><?= $s['fecha'] ?></td>
              <td><?= $s['hora'] ?></td>
              <td><?= ucfirst($s['estado']) ?></td>
              <td><?= $s['sala_asignada'] ?? '—' ?></td>
              <td>
                <?php if ($s['estado'] === 'pendiente'): ?>
                  <a href="acciones_solicitud_sala.php?id=<?= $s['id'] ?>&accion=aprobar" class="btn btn-success btn-sm">Aprobar</a>
                  <a href="acciones_solicitud_sala.php?id=<?= $s['id'] ?>&accion=rechazar" class="btn btn-danger btn-sm">Rechazar</a>
                <?php else: ?>
                  —
                <?php endif; ?>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>
</body>

</html>