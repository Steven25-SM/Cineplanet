<?php
session_start();
include 'db/config.php';

if (!isset($_SESSION['dni'])) {
    header("Location: login.php");
    exit();
}

$dni = $_SESSION['dni'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['nueva_imagen'])) {
    $img = $_FILES['nueva_imagen'];
    $nombreImg = uniqid() . "_" . basename($img['name']);
    $rutaDestino = 'uploads/' . $nombreImg;

    if (move_uploaded_file($img['tmp_name'], $rutaDestino)) {
    $conn->query("UPDATE socios SET imagen = '$nombreImg' WHERE DNI = '$dni'");

    $_SESSION['imagen'] = $nombreImg;
}
}

$result = $conn->query("SELECT * FROM socios WHERE DNI = '$dni'");
if ($result && $result->num_rows === 1) {
    $socio = $result->fetch_assoc();
} else {
    echo "Socio no encontrado.";
    exit(); 
}

$query = "SELECT c.*, p.titulo FROM compras c JOIN peliculas p ON c.pelicula_id = p.id WHERE c.dni = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $dni);
$stmt->execute();
$compras = $stmt->get_result();

$diasParaCumple = null;

if (isset($socio)) {
    $cumple = new DateTime(date('Y') . '-' . date('m-d', strtotime($socio['fecha_nacimiento'])));
    $hoy = new DateTime();

    if ($cumple < $hoy) {
        $cumple->modify('+1 year');
    }

    $intervalo = $hoy->diff($cumple);
    $diasParaCumple = $intervalo->days;

    if ($diasParaCumple == 0) {
        $diasParaCumple = null;
    }
}
?>

<?php if ($diasParaCumple !== null): ?>
<div style="position: absolute; top: 10px; left: 10px; background-color: red; color: white; padding: 6px 12px; border-radius: 10px; font-weight: bold; z-index: 999;">
    🎉 Faltan <?= $diasParaCumple ?> día<?= $diasParaCumple == 1 ? '' : 's' ?> para tu cumple
</div>
<?php endif; ?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Perfil</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
  body {
    background-color: #f8f9fa;
  }

  .profile-card {
    max-width: 400px; 
    margin: 50px auto;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 0 20px rgba(0,0,0,0.1);
  }

  .image-container {
  width: 200px;
  height: 200px;
  margin: 0 auto;
  overflow: hidden;
  border-radius: 50%;
  border: 3px solid #dee2e6;
  position: relative;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
}

.image-container img {
  width: 100%;
  height: 100%;
  object-fit: cover; 
  border-radius: 50%; 
  display: block;
}

  .image-container img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: 0.3s;
  }

  .image-container:hover::after {
    content: "Cambiar";
    position: absolute;
    bottom: 0;
    width: 100%;
    background: rgba(0, 0, 0, 0.6);
    color: white;
    text-align: center;
    font-size: 14px;
    padding: 5px;
    border-bottom-left-radius: 50%;
    border-bottom-right-radius: 50%;
  }

  input[type="file"] {
    display: none;
  }

</style>

</head>
<body class="container mt-4">

  <div class="card mb-5 p-4 shadow profile-card">
    <div class="text-center">
      <form method="POST" enctype="multipart/form-data">
        <div class="image-container">
          <label for="nueva_imagen">
            <img src="<?= $socio['imagen'] ? 'uploads/' . htmlspecialchars($socio['imagen']) : 'https://www.w3schools.com/howto/img_avatar.png' ?>" class="profile-pic" alt="Foto de perfil" title="Haz clic para cambiar tu foto">
          </label>
        </div>
        <input type="file" name="nueva_imagen" id="nueva_imagen" onchange="this.form.submit()">
      </form>

      <h3 class="mt-3">Bienvenido, <?= htmlspecialchars($socio['nombre']) ?></h3>
      <p>Tu DNI: <?= htmlspecialchars($socio['DNI']) ?></p>
      <p>Fecha de Nacimiento: <?= htmlspecialchars($socio['fecha_nacimiento']) ?></p>
    </div>
  </div>

  <h3 class="mt-4">🎟 Historial de Compras</h3>
  <table class="table table-striped">
    <thead>
      <tr>
        <th>Película</th>
        <th>Fecha</th>
        <th>Hora</th>
        <th>Cantidad</th>
        <th>Método de Pago</th>
        <th>Fecha de Compra</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($compra = $compras->fetch_assoc()): ?>
      <tr>
        <td><?= htmlspecialchars($compra['titulo']) ?></td>
        <td><?= htmlspecialchars($compra['fecha']) ?></td>
        <td><?= htmlspecialchars($compra['hora']) ?></td>
        <td><?= htmlspecialchars($compra['cantidad']) ?></td>
        <td><?= htmlspecialchars($compra['metodo_pago']) ?></td>
        <td><?= htmlspecialchars($compra['created_at']) ?></td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>

  <a href="logout.php" class="btn btn-danger mt-3">Cerrar sesión</a>
</body>
</html>
