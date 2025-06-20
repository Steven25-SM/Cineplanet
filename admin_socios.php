<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
  header('Location: index.php');
  exit();
}

require 'db/config.php';

$sql = "SELECT dni, nombre, apellido, email, estado FROM socios";
$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Administrar Socios</title>
  <link rel="icon" type="image/png" href="media/logo2.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
  <h2 class="mb-4">👥 Administración de Socios</h2>

  <a href="admin.php" class="btn btn-primary mb-3">Volver al Panel</a>

  <table class="table table-bordered table-hover bg-white">
    <thead class="table-dark">
      <tr>
        <th>DNI</th>
        <th>Nombre</th>
        <th>Correo</th>
        <th>Estado</th>
        <th>Acción</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($socio = $resultado->fetch_assoc()): ?>
        <tr>
          <td><?= htmlspecialchars($socio['dni']) ?></td>
          <td><?= htmlspecialchars($socio['nombre'] . ' ' . $socio['apellido']) ?></td>
          <td><?= htmlspecialchars($socio['email']) ?></td>
          <td><?= ucfirst($socio['estado']) ?></td>
          <td>
            <form method="POST" action="acciones_socio.php" class="d-flex gap-2">
              <input type="hidden" name="dni" value="<?= $socio['dni'] ?>">
              <select name="estado" class="form-select form-select-sm">
                <option value="activo" <?= $socio['estado'] === 'activo' ? 'selected' : '' ?>>✅ Activo</option>
                <option value="suspendido" <?= $socio['estado'] === 'suspendido' ? 'selected' : '' ?>>⏸️ Suspendido</option>
                <option value="baneado" <?= $socio['estado'] === 'baneado' ? 'selected' : '' ?>>🚫 Baneado</option>
              </select>
              <button type="submit" class="btn btn-sm btn-primary">Aplicar</button>
            </form>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

</body>
</html>
