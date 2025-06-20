<?php
session_start();
require_once 'db/config.php';

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
  header('Location: index.php');
  exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nombre = trim($_POST['nombre']);
  $capacidad = intval($_POST['capacidad']);
  $descripcion = trim($_POST['descripcion']);
  $estado = trim($_POST['estado']);

  if ($nombre && $capacidad && $descripcion && $estado) {
    $sql = "INSERT INTO salas (nombre, tipo, capacidad, descripcion, estado) VALUES (?, 'especial', ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("siss", $nombre, $capacidad, $descripcion, $estado);

    if ($stmt->execute()) {
      header('Location: admin_salase.php');
      exit();
    } else {
      $error = "❌ Error al agregar la sala: " . $stmt->error;
    }
  } else {
    $error = "❌ Todos los campos son obligatorios.";
  }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Agregar Sala Especial</title>
  <link rel="icon" type="image/png" href="media/logo2.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
  <div class="container py-4">
    <h2 class="mb-4">➕ Agregar Sala Especial</h2>
    <a href="admin_salase.php" class="btn btn-secondary mb-3">⬅ Volver</a>

    <?php if (isset($error)): ?>
      <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="post">
      <div class="mb-3">
        <label class="form-label">Nombre de la sala</label>
        <input type="text" name="nombre" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Capacidad</label>
        <input type="number" name="capacidad" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Descripción</label>
        <textarea name="descripcion" class="form-control" required></textarea>
      </div>

      <div class="mb-3">
        <label class="form-label">Estado</label>
        <select name="estado" class="form-select" required>
          <option value="activa">Activa</option>
          <option value="ocupada">Ocupada</option>
          <option value="inactiva">Inactiva</option>
        </select>
      </div>

      <button type="submit" class="btn btn-primary">💾 Agregar Sala</button>
    </form>
  </div>
</body>

</html>