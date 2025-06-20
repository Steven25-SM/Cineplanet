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
$stmt = $conn->prepare("SELECT * FROM promociones WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$promo = $result->fetch_assoc();

if (!$promo) {
  die("❌ Promoción no encontrada.");
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Editar Promoción</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
  <div class="container py-4">
    <h2>✏️ Editar Promoción</h2>
    <form action="procesar_promocion.php" method="post" enctype="multipart/form-data">
      <input type="hidden" name="id" value="<?= $promo['id'] ?>">
      <div class="mb-3">
        <label class="form-label">Título</label>
        <input type="text" name="titulo" class="form-control" value="<?= htmlspecialchars($promo['titulo']) ?>" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Descripción</label>
        <textarea name="descripcion" class="form-control" required><?= htmlspecialchars($promo['descripcion']) ?></textarea>
      </div>
      <div class="mb-3">
        <label class="form-label">Imagen (opcional)</label><br>
        <img src="<?= $promo['imagen'] ?>" width="150"><br><br>
        <input type="file" name="imagen">
      </div>
      <div class="mb-3">
        <label class="form-label">Fecha Inicio</label>
        <input type="date" name="fecha_inicio" class="form-control" value="<?= $promo['fecha_inicio'] ?>" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Fecha Fin</label>
        <input type="date" name="fecha_fin" class="form-control" value="<?= $promo['fecha_fin'] ?>" required>
      </div>
      <button type="submit" class="btn btn-primary">💾 Guardar cambios</button>
    </form>
  </div>
</body>

</html>