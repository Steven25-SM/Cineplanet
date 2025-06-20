<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
  header('Location: index.php');
  exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Nueva Promoción</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
  <div class="container py-4">
    <h2>➕ Agregar Promoción</h2>
    <form action="procesar_promocion.php" method="post" enctype="multipart/form-data">
      <div class="mb-3">
        <label>Título</label>
        <input type="text" name="titulo" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Descripción</label>
        <textarea name="descripcion" class="form-control" required></textarea>
      </div>
      <div class="mb-3">
        <label>Imagen</label>
        <input type="file" name="imagen" class="form-control" accept="image/*" required>
      </div>
      <div class="mb-3">
        <label>Fecha de inicio</label>
        <input type="date" name="fecha_inicio" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Fecha de fin</label>
        <input type="date" name="fecha_fin" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-success">Crear</button>
    </form>
  </div>
</body>

</html>