  <?php
  session_start();

  if (isset($_GET['id'])) {
      $_SESSION['pelicula_id'] = (int)$_GET['id'];
  }
  if (isset($_GET['titulo'])) {
      $_SESSION['titulo'] = $_GET['titulo'];
  }
  if (isset($_GET['poster'])) {
      $_SESSION['poster'] = $_GET['poster'];
  }

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $general = isset($_POST['general']) ? (int)$_POST['general'] : 0;
    $menor = isset($_POST['menor']) ? (int)$_POST['menor'] : 0;

    $_SESSION['general'] = $general;
    $_SESSION['menor'] = $menor;

    $total = ($general * 20.50) + ($menor * 13.50);

    $_SESSION['butacas'] = $_POST['butacas'] ?? [];
    $_SESSION['monto_entradas'] = $total;
    $_SESSION['cantidad'] = $general + $menor;

  }

  ?>

  <!DOCTYPE html>
  <html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Seleccionar Entradas</title>
    <link rel="icon" type="image/png" href="media/logo1.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
  body {
    background: #ffffff;
    color: #222;
    font-family: 'Segoe UI', sans-serif;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .cine-box {
    background-color: #f9f9f9;
    border: 3px solid black;
    border-radius: 18px;
    padding: 3rem 2rem;
    box-shadow: 0 0 20px rgb(0, 0, 0);
    width: 100%;
    max-width: 640px;
    animation: fadeIn 0.8s ease;
    font-size: 1.15rem;
  }

  .cine-box strong {
    color: red;
  }

  .cine-box input {
    border: 1px solid black;
  }

  .cine-box h2 {
    color:rgb(30, 73, 138);
    font-size: 2.7rem;
    margin-bottom: 1.5rem;
    font-weight: 600;
  }

  .monto {
    font-size: 1.5rem;
    color: #d9534f;
    font-weight: bold;
    margin-bottom: 1.5rem;
  }

  .form-label {
    color: #333;
    font-size: 1.15rem;
  }

  .form-control {
    background-color: #ffffff;
    border: 1px solid #ccc;
    color: #000;
    font-size: 1.15rem;
    padding: 10px;
  }

  .form-control:focus {
    border-color: #00b3b3;
    box-shadow: 0 0 10px rgba(0, 179, 179, 0.4);
  }

  .btn-confirm, .btn-snacks {
    border: none;
    padding: 14px 36px;
    border-radius: 30px;
    font-weight: bold;
    font-size: 1.2rem;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }

  .btn-confirm {
    background-color: rgb(31, 78, 148);
    color: #fff;
  }

  .btn-confirm:hover {
    background-color: rgb(11, 228, 102);
    color: #fff;
  }

  .btn-confirm:hover {
    transform: scale(1.05);
    box-shadow: 0 0 20px rgba(0, 179, 179, 0.4);
  }

  .btn-snacks {
    background: linear-gradient(to right,rgb(167, 40, 40),rgb(230, 35, 35));
    color: #fff;
    margin-top: 20px;
  }

  .btn-snacks:hover {
    transform: scale(1.05);
    box-shadow: 0 0 20px rgba(72, 255, 142, 0.3);
    color: white;
  }
    </style>
  </head>
  <body>
    <div class="cine-box text-center">
      <h2>Seleccionar Entradas</h2>
      
      <?php if (isset($_SESSION['titulo'])): ?>
        <p class="fs-6 mb-3">🎬 <strong><?= htmlspecialchars($_SESSION['titulo']) ?></strong></p>
      <?php endif; ?>
      
      <p class="fs-5 mb-4">💰 Total acumulado: <strong>S/ <?= number_format($_SESSION['monto_entradas'] ?? 0, 2) ?></strong></p>

      <form action="seleccionar_butacas.php" method="post">
        <div class="row mb-4">
          <div class="col-md-6">
            <label class="form-label">🎟 General (S/ 20.50)</label>
            <input type="number" name="general" class="form-control" min="0" value="<?= $_SESSION['general'] ?? 0 ?>" />
          </div>
          <div class="col-md-6">
            <label class="form-label">👦 Menor (S/ 13.50)</label>
            <input type="number" name="menor" class="form-control" min="0" value="<?= $_SESSION['menor'] ?? 0 ?>" />
          </div>
        </div>

        <?php if (isset($_SESSION['butacas'])): ?>
          <?php foreach ($_SESSION['butacas'] as $butaca): ?>
            <input type="hidden" name="butacas[]" value="<?= htmlspecialchars($butaca) ?>" />
          <?php endforeach; ?>
        <?php endif; ?>

        <button type="submit" class="btn btn-confirm">Confirmar Entradas</button>
      </form>

      <form action="dulceria.php" method="post">
        <button type="submit" class="btn btn-snacks">Ir a Dulcería</button>
      </form>
    </div>
  </body>
  </html>
