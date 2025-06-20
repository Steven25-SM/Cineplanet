<?php
session_start();
include 'db/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $nombre = $_POST['nombre'] ?? '';
  $apellido = $_POST['apellido'] ?? '';
  $dni = $_POST['dni'] ?? '';
  $celular = $_POST['celular'] ?? '';
  $email = $_POST['email'] ?? '';
  $password = $_POST['password'] ?? '';
  $fecha_nacimiento = $_POST['fecha_nacimiento'] ?? '';
  $sexo = $_POST['sexo'] ?? '';
  $rol = 'usuario';

  if (
    !$nombre || !$apellido || !$dni || !$celular || !$email ||
    !$password || !$fecha_nacimiento || !$sexo
  ) {
    $error = "Completa todos los campos.";
  } else {
    
    $stmt = $conn->prepare("SELECT dni FROM socios WHERE dni = ?");
    $stmt->bind_param('s', $dni);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
      $error = "El DNI ya está registrado.";
    } else {
      $password_hash = password_hash($password, PASSWORD_DEFAULT);

      $stmt = $conn->prepare("INSERT INTO socios (nombre, apellido, dni, celular, email, password, fecha_nacimiento, sexo, rol) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param('sssssssss', $nombre, $apellido, $dni, $celular, $email, $password_hash, $fecha_nacimiento, $sexo, $rol);


      if ($stmt->execute()) {
        $_SESSION['dni'] = $dni;
        $success = true;
      } else {
        $error = "Error al registrar.";
      }
    }
    $stmt->close();
  }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Registro - Cineplanet</title>
  <link rel="stylesheet" href="registro.css">
  <link rel="icon" type="image/png" href="media/logo1.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      background: url("https://peruretail.sfo3.cdn.digitaloceanspaces.com/wp-content/uploads/Plantillas-Facebook-2-28.png");
      background-position: center;
    }

    .mb-3 a:link {
      color: blue;
    }

    .mb-3 a:visited {
      color: rgb(0, 255, 76);
    }

    input:not([type="checkbox"]) {
      border: 2px solid black;

    }

    .form-control {
      border: 2px solid black !important;
    }

    .form-container {
      border: 6px solid rgb(0, 89, 255)
    }

    .form-check-input {
      box-shadow: black 0 0 2px;
    }

    .btn {
      background-color: #0D6EFD !important;
    }

    .container {
      max-width: 600px;
      margin-top: 50px;
    }

    .form-container {
      background-color: #f8f9fa;
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .form-control,
    .form-check-input {
      margin-bottom: 15px;
    }
  </style>
</head>

<body>
  <a href="index.php" class="btn btn-secondary position-absolute m-3" style="top: 0; left: 0; z-index: 1000;">← Volver</a>

  <div class="container">
    <div class="form-container">
      <h2 class="text-center mb-4">¡Hazte socio de Cineplanet!</h2>
      <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
      <?php endif; ?>
      <form action="register.php" method="POST">
        <div class="mb-3">
          <label for="nombre" class="form-label">Nombres</label>
          <input type="text" class="form-control" id="nombre" name="nombre" required>
        </div>
        <div class="mb-3">
          <label for="apellido" class="form-label">Apellidos</label>
          <input type="text" class="form-control" id="apellido" name="apellido" required>
        </div>
        <div class="mb-3">
          <label for="dni" class="form-label">DNI</label>
          <input type="text" class="form-control" id="dni" name="dni" required>
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Contraseña</label>
          <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <div class="mb-3">
          <label for="email" class="form-label">Correo electrónico</label>
          <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
          <label for="celular" class="form-label">Celular</label>
          <input type="text" class="form-control" id="celular" name="celular" required>
        </div>
        <div class="mb-3">
          <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
          <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" required>
        </div>

        <div class="mb-3">
          <label for="sexo" class="form-label">Sexo</label>
          <select class="form-control" id="sexo" name="sexo" required>
            <option value="masculino">Masculino</option>
            <option value="femenino">Femenino</option>
            <option value="otro">Otro</option>
          </select>
        </div>
        <div class="mb-3 form-check">
          <input type="checkbox" class="form-check-input" id="terminos" name="terminos" required>
          <label class="form-check-label" for="terminos">Acepto los <a href="https://cdnpe.cineplanet.com.pe/assets/1862f49f-807a-44cc-bf34-629d915042e7" target="_blank" rel="noreferrer">Términos y Condiciones</a> y la <a href="https://cdnpe.cineplanet.com.pe/assets/0b1cdd88-83a2-48b7-820b-8bac386ced30" target="_blank" rel="noreferrer">Política de Privacidad</a></label>
        </div>
        <div class="mb-3 form-check">
          <input type="checkbox" class="form-check-input" id="tratamiento" name="tratamiento" required>
          <label class="form-check-label" for="tratamiento">He leído y acepto las finalidades de tratamiento adicionales</label>
        </div>
        <div class="d-flex justify-content-between">
          <button type="submit" class="btn btn-primary">Registrarme</button>
        </div>

      </form>
    </div>
  </div>

  <div class="modal fade" id="registroModal" tabindex="-1" aria-labelledby="registroModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="registroModalLabel">Registro Exitoso</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          ¡Felicidades! Te has registrado correctamente como socio de Cineplanet.
        </div>
        <div class="modal-footer">
          <a href="login.php" class="btn btn-primary">Iniciar sesión</a>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      <?php if (isset($success) && $success): ?>
        var registroModal = new bootstrap.Modal(document.getElementById('registroModal'));
        registroModal.show();
      <?php endif; ?>
    });
  </script>

</body>

</html>