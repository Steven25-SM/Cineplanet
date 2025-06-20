<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Ventajas de Ser Socio | Cineplanet</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" type="image/png" href="/media/images-removebg-preview.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .ventaja-icono {
      font-size: 2rem;
      color: #0d6efd;
    }
    .ventaja-card {
      border-radius: 1rem;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .ventaja-card:hover {
      transform: scale(1.03);
      box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
    }
  </style>
</head>
<body>
  <?php include 'includes/header.php'; ?> <!-- ✅ Solo aquí, no al inicio -->

  <div class="container py-5">
        <div class="text-center mb-5">
     
        <p class="text-muted mt-2">Descubre todos los beneficios que obtienes al registrarte como Socio.</p>
        </div>

    <div class="row g-4">
      <div class="col-md-6 col-lg-4">
        <div class="card p-4 ventaja-card h-100">
          <div class="ventaja-icono text-center mb-3">👤</div>
          <h5 class="text-center fw-bold">Perfil Personalizable</h5>
          <p class="text-center text-muted">Accede a tu cuenta, edita tu nombre, correo y preferencias desde tu panel personal.</p>
        </div>
      </div>

      <div class="col-md-6 col-lg-4">
        <div class="card p-4 ventaja-card h-100">
          <div class="ventaja-icono text-center mb-3">📜</div>
          <h5 class="text-center fw-bold">Historial de Compras</h5>
          <p class="text-center text-muted">Revisa fácilmente todas tus compras anteriores, entradas y combos adquiridos.</p>
        </div>
      </div>

      <div class="col-md-6 col-lg-4">
        <div class="card p-4 ventaja-card h-100">
          <div class="ventaja-icono text-center mb-3">💸</div>
          <h5 class="text-center fw-bold">Descuentos Exclusivos</h5>
          <p class="text-center text-muted">¡Mitad de precio en entradas seleccionadas solo para socios registrados!</p>
        </div>
      </div>

      <div class="col-md-6 col-lg-4">
        <div class="card p-4 ventaja-card h-100">
          <div class="ventaja-icono text-center mb-3">🎂</div>
          <h5 class="text-center fw-bold">Entrada Gratis en tu Cumpleaños</h5>
          <p class="text-center text-muted">Recibe una entrada 2D totalmente gratis para cualquier película el día de tu cumpleaños.</p>
        </div>
      </div>

      <div class="col-md-6 col-lg-4">
        <div class="card p-4 ventaja-card h-100">
          <div class="ventaja-icono text-center mb-3">🎁</div>
          <h5 class="text-center fw-bold">Canje de Puntos</h5>
          <p class="text-center text-muted">Acumula puntos con cada compra y canjéalos por descuentos en combos y productos.</p>
        </div>
      </div>

      <div class="col-md-6 col-lg-4">
        <div class="card p-4 ventaja-card h-100">
          <div class="ventaja-icono text-center mb-3">🚀</div>
          <h5 class="text-center fw-bold">Acceso Prioritario</h5>
          <p class="text-center text-muted">Accede antes que nadie a preventas, estrenos y promociones especiales.</p>
        </div>
      </div>
    </div>

    <?php if (!isset($_SESSION['rol']) || $_SESSION['rol'] === 'cliente'): ?>
      <div class="text-center mt-5">
        <a href="register.php" class="btn btn-primary btn-lg">¡Hazte Socio Ahora!</a>
      </div>
    <?php endif; ?>
  </div>

  <footer class="bg-dark text-light mt-5 pt-4 pb-3">
    <div class="container">
      <div class="row align-items-center mb-3">
        <div class="col-md-6">
          <h5 class="fw-bold mb-1">CinePlanet</h5>
          <p class="mb-0">Vive la experiencia del cine como nunca antes.</p>
        </div>
        <div class="col-md-6 text-md-end mt-3 mt-md-0">
          <a href="#" class="text-light me-3 text-decoration-none">Términos</a>
          <a href="#" class="text-light me-3 text-decoration-none">Política</a>
          <a href="/soporte.php" class="text-light text-decoration-none">Soporte</a>
        </div>
      </div>
      <div class="row border-top pt-3">
        <div class="col-md-6">
          <small>&copy; 2025 CinePlanet. Todos los derechos reservados.</small>
        </div>
        <div class="col-md-6 text-md-end">
          <a href="https://www.facebook.com/cineplanet/?locale=es_LA" target="_blank" class="me-2">
            <img src="https://cdn-icons-png.flaticon.com/24/733/733547.png" alt="Facebook" width="24" height="24">
          </a>
          <a href="https://x.com/cineplanet" target="_blank" class="me-2">
            <img src="https://cdn-icons-png.flaticon.com/24/733/733579.png" alt="Twitter" width="24" height="24">
          </a>
          <a href="https://www.instagram.com/cineplanetoficial/?hl=es" target="_blank">
            <img src="https://cdn-icons-png.flaticon.com/24/2111/2111463.png" alt="Instagram" width="24" height="24">
          </a>
        </div>
      </div>
    </div>
  </footer>

</body>
</html>
