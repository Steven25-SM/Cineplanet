<?php
session_start();
require_once 'db/config.php';

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
  header('Location: index.php');
  exit();
}

if (!isset($_GET['id'])) {
  die("❌ ID no especificado.");
}

$id = intval($_GET['id']);

// Obtener los datos actuales de la sala
$stmt = $conn->prepare("SELECT * FROM salas WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$sala = $result->fetch_assoc();

if (!$sala) {
  die("❌ Sala no encontrada.");
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Editar Sala Especial</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
  <div class="container py-4">
    <h2 class="mb-4">✏️ Editar Sala Especial</h2>
    <a href="admin_salase.php" class="btn btn-secondary mb-3">⬅ Volver</a>

    <form action="admin_salase_actualizar.php" method="post">
      <input type="hidden" name="id" value="<?= $sala['id'] ?>">

      <div class="mb-3">
        <label class="form-label">Nombre</label>
        <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($sala['nombre']) ?>" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Capacidad</label>
        <input type="number" name="capacidad" class="form-control" value="<?= $sala['capacidad'] ?>" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Descripción</label>
        <textarea name="descripcion" class="form-control" required><?= htmlspecialchars($sala['descripcion']) ?></textarea>
      </div>

      <div class="mb-3">
        <label class="form-label">Estado</label>
        <select name="estado" class="form-select" required>
          <option value="activa" <?= $sala['estado'] === 'activa' ? 'selected' : '' ?>>Activa</option>
          <option value="ocupada" <?= $sala['estado'] === 'ocupada' ? 'selected' : '' ?>>Ocupada</option>
          <option value="inactiva" <?= $sala['estado'] === 'inactiva' ? 'selected' : '' ?>>Inactiva</option>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">Uso actual (opcional)</label>
        <input type="text" name="uso_actual" class="form-control" value="<?= htmlspecialchars($sala['uso_actual'] ?? '') ?>">
      </div>

      <button type="submit" class="btn btn-primary">💾 Guardar cambios</button>
    </form>
  </div>
</body>

</html>