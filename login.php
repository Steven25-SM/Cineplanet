<?php
session_start();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $dni = $_POST['dni'];
  $password = $_POST['password'];

  $host = 'localhost';
  $db   = 'cineplanet';
  $user = 'root';
  $pass = '';
  $charset = 'utf8mb4';

  $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

  try {
    $pdo = new PDO($dsn, $user, $pass, [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    // ✅ Incluye el campo estado
    $stmt = $pdo->prepare("SELECT dni, password, imagen, rol, nombre, apellido, email, estado FROM socios WHERE dni = ?");
    $stmt->execute([$dni]);
    $socio = $stmt->fetch();

    if ($socio && password_verify($password, $socio['password'])) {
      // Verifica el estado del socio
      if ($socio['estado'] === 'suspendido') {
        $error = '⚠️ Has sido suspendido por incumplir las normas de socio Cineplanet.';
      } elseif ($socio['estado'] === 'baneado') {
        $error = '❌ Tu cuenta ha sido baneada permanentemente. Contacta al soporte si crees que es un error.';
      } else {
        // ✅ Estado OK, iniciar sesión
        $_SESSION['dni'] = $socio['dni'];
        $_SESSION['imagen'] = $socio['imagen'];
        $_SESSION['rol'] = $socio['rol'];
        $_SESSION['nombre'] = $socio['nombre'];
        $_SESSION['apellido'] = $socio['apellido'];
        $_SESSION['correo'] = $socio['email'];

        header('Location: index.php');
        exit();
      }
    } else {
      $error = 'Datos incorrectos';
    }

  } catch (PDOException $e) {
    $error = "Error en la base de datos: " . $e->getMessage();
  }
}
?>


<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Inicio de sesión | CinePlanet</title>
  <link rel="icon" type="icon/png" href="/media/images-removebg-preview.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  </body>

  <style>
    body.bg-light {
      background: url("https://estudiotarazona.com/wp-content/uploads/2023/08/palomitas-maiz-mesa-frente-cine-scaled-1620x810.jpg");
      background-position: center;
      background-repeat: no-repeat;
      background-size: cover;
    }

    .container {
      height: 100vh;
    }

    .col-md-4 {
      box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
      background-color: white !important;
    }

    a {
      color: rgb(255, 0, 0);
    }

    a:hover {
      text-decoration: underline;
    }

    .bg-white {
      border: 6px solid rgb(255, 0, 0);
    }

    h2 {
      color: black;
    }

    label {
      color: black;
    }

    input {
      border: 2px solid rgb(0, 0, 0) !important;
    }

    .btn {
      margin-top: 10px !important;
    }

    header {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      background: white;
      padding: 10px 20px;
      display: flex;
      justify-content: flex-end;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
      z-index: 1000;
    }

    .user-menu {
      position: relative;
    }

    #userBtn {
      background: none;
      border: none;
      font-size: 1rem;
      cursor: pointer;
    }

    #userDropdown {
      position: absolute;
      top: 30px;
      right: 0;
      background: white;
      border: 1px solid #ccc;
      padding: 8px 12px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
      display: none;
      min-width: 120px;
      border-radius: 4px;
      z-index: 1001;
    }

    #userDropdown a {
      display: block;
      padding: 6px 0;
      color: #007bff;
      text-decoration: none;
    }

    #userDropdown a:hover {
      text-decoration: underline;
    }

  </style>
</head>

<body class="bg-light">

  <a href="index.php" class="btn btn-primary position-absolute top-0 start-0 m-3">Regresar al inicio</a>

  <div class="container d-flex align-items-center justify-content-center min-vh-100">
    <div class="col-md-4 p-4 bg-white rounded shadow-sm">
      <h2 class="text-center mb-4">Iniciar sesión</h2>

      <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
      <?php endif; ?>

      <form method="POST" action="login.php">
        <div class="mb-3">
          <label for="dni" class="form-label">DNI</label>
          <input type="text" name="dni" class="form-control" id="dni" placeholder="Ingresa tu DNI" required />
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Contraseña</label>
          <input type="password" name="password" class="form-control" id="password" placeholder="Ingresa tu contraseña" required />
        </div>

        <button type="submit" class="btn btn-primary w-100 mb-3">Iniciar sesión</button>
        <div class="text-center">
          <a href="#" class="text-decoration-none">¿Olvidaste tu contraseña?</a>
        </div>
      </form>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const btn = document.getElementById('userBtn');
    const dropdown = document.getElementById('userDropdown');
    btn?.addEventListener('click', () => {
      dropdown.style.display = dropdown.style.display === 'none' || dropdown.style.display === '' ? 'block' : 'none';
    });
    window.onclick = e => {
      if (!btn.contains(e.target) && !dropdown.contains(e.target)) {
        dropdown.style.display = 'none';
      }
    };
  </script>

</body>

</html>