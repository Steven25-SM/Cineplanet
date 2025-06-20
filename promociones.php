<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'db/config.php';

$hoy = date('Y-m-d');
$promos = mysqli_query($conn, "SELECT * FROM promociones WHERE estado = 'activa' AND fecha_inicio <= '$hoy' AND fecha_fin >= '$hoy' ORDER BY fecha_inicio DESC");
$cantidad = mysqli_num_rows($promos);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Promociones – CinePlanet</title>
  <link rel="icon" type="image/png" href="media/logo1.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #ffffff;
      padding-top: 96px;
    }

    .card-img-top {
      width: 100%;
      max-height: 320px;
      object-fit: contain;
      background-color: white;
      padding: 15px;
      border-bottom: 1px solid #ddd;
    }

    .card-body {
      padding: 1rem;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }

    .card-title {
      font-size: 1.2rem;
      font-weight: bold;
    }

    .card-text {
      font-size: 1rem;
      color: #333;
    }

    .card {
      height: 100%;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }

    .card:hover {
      transform: scale(1.02);
    }

    .promo-section {
      background: linear-gradient(135deg, #003366, #00509e);
      color: white;
    }

    .card-footer {
      background-color: transparent;
    }
  </style>
</head>
<body>

<?php include 'includes/header.php'; ?>

<section class="container mt-5 pt-5 promo-section rounded p-4 shadow">
  <h2 class="mb-4 text-center">🎁 Promociones Especiales</h2>

  <?php if ($cantidad === 0): ?>
    <div class="alert alert-warning bg-light text-dark text-center">
      No hay promociones disponibles por el momento. ¡Vuelve pronto!
    </div>

  <?php elseif ($cantidad === 1): 
    $promo = mysqli_fetch_assoc($promos); ?>
    <div class="row justify-content-center">
      <div class="col-md-6 col-sm-10">
        <div class="card h-100 shadow-sm">
          <img src="<?= htmlspecialchars($promo['imagen']) ?>" class="card-img-top" alt="Promo">
          <div class="card-body">
            <h5 class="card-title text-primary"><?= htmlspecialchars($promo['titulo']) ?></h5>
            <p class="card-text"><?= htmlspecialchars($promo['descripcion']) ?></p>
          </div>
          <div class="card-footer text-muted small">
            Vigente del <?= $promo['fecha_inicio'] ?> al <?= $promo['fecha_fin'] ?>
          </div>
        </div>
      </div>
    </div>

  <?php elseif ($cantidad === 2): ?>
    <div class="row justify-content-center row-cols-1 row-cols-md-2 g-4">
      <?php while ($p = mysqli_fetch_assoc($promos)) { ?>
        <div class="col">
          <div class="card h-100 shadow-sm">
            <img src="<?= htmlspecialchars($p['imagen']) ?>" class="card-img-top" alt="Promo">
            <div class="card-body">
              <h5 class="card-title text-primary"><?= htmlspecialchars($p['titulo']) ?></h5>
              <p class="card-text"><?= htmlspecialchars($p['descripcion']) ?></p>
            </div>
            <div class="card-footer text-muted small">
              Vigente del <?= $p['fecha_inicio'] ?> al <?= $p['fecha_fin'] ?>
            </div>
          </div>
        </div>
      <?php } ?>
    </div>

  <?php else: ?>
    <div class="row row-cols-1 row-cols-md-3 g-4">
      <?php while ($p = mysqli_fetch_assoc($promos)) { ?>
        <div class="col">
          <div class="card h-100 shadow-sm">
            <img src="<?= htmlspecialchars($p['imagen']) ?>" class="card-img-top" alt="Promo">
            <div class="card-body">
              <h5 class="card-title text-primary"><?= htmlspecialchars($p['titulo']) ?></h5>
              <p class="card-text"><?= htmlspecialchars($p['descripcion']) ?></p>
            </div>
            <div class="card-footer text-muted small">
              Vigente del <?= $p['fecha_inicio'] ?> al <?= $p['fecha_fin'] ?>
            </div>
          </div>
        </div>
      <?php } ?>
    </div>
  <?php endif; ?>
</section>

</body>
</html>
