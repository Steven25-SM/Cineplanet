<?php
session_start();
require_once 'db/config.php';

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
  header("Location: index.php");
  exit();
}

$promos = mysqli_query($conn, "SELECT * FROM promociones ORDER BY fecha_inicio DESC");
$hoy = date('Y-m-d');
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title> Gestionar Promociones</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
  <div class="container py-4">
    <h2>🎁 Promociones</h2>
    <a href="admin.php" class="btn btn-secondary mb-3">⬅ Volver al panel</a>
    <a href="admin_promociones_agregar.php" class="btn btn-primary mb-3 ms-2">➕ Nueva Promoción</a>

    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'estado_actualizado'): ?>
      <div class="alert alert-success">✅ Estado de promoción actualizado.</div>
    <?php endif; ?>

    <table class="table table-bordered">
      <thead class="table-dark">
        <tr>
          <th>ID</th>
          <th>Imagen</th>
          <th>Título</th>
          <th>Descripción</th>
          <th>Estado</th>
          <th>Rango</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($p = mysqli_fetch_assoc($promos)):
          $fueraRango = ($hoy < $p['fecha_inicio'] || $hoy > $p['fecha_fin']);
        ?>
          <tr>
            <td><?= $p['id'] ?></td>
            <td><img src="<?= htmlspecialchars($p['imagen']) ?>" alt="promo" width="80"></td>
            <td><?= htmlspecialchars($p['titulo']) ?></td>
            <td><?= htmlspecialchars($p['descripcion']) ?></td>
            <td>
              <?php if ($p['estado'] === 'activa'): ?>
                <span class="badge bg-success">Activa</span>
              <?php elseif ($p['estado'] === 'bloqueada'): ?>
                <span class="badge bg-danger">Bloqueada</span>
              <?php else: ?>
                <span class="badge bg-secondary">Inactiva</span>
              <?php endif; ?>
            </td>
            <td><?= $p['fecha_inicio'] ?> → <?= $p['fecha_fin'] ?></td>
            <td>
              <a href="admin_promociones_editar.php?id=<?= $p['id'] ?>" class="btn btn-warning btn-sm">✏️ Editar</a>

              <?php if ($fueraRango || $p['estado'] === 'bloqueada'): ?>
                <span class="btn btn-sm btn-outline-secondary disabled">⛔ No disponible</span>
              <?php else: ?>
                <a href="toggle_promocion.php?id=<?= $p['id'] ?>&accion=<?= $p['estado'] === 'activa' ? 'desactivar' : 'activar' ?>" class="btn btn-sm btn-info">
                  <?= $p['estado'] === 'activa' ? 'Desactivar' : 'Activar' ?>
                </a>
              <?php endif; ?>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</body>

</html>