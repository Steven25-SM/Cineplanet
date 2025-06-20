<?php
session_start();

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
  header('Location: index.php');
  exit();
}

require_once 'db/config.php';

$host = 'localhost';
$db = 'cineplanet';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

try {
  $pdo = new PDO($dsn, $user, $pass, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  ]);

  $stmt = $pdo->query("SELECT * FROM tickets ORDER BY fecha_creacion DESC");
  $tickets = $stmt->fetchAll();
} catch (PDOException $e) {
  die("❌ Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <title>Gestionar Tickets</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>

<body>
  <div class="container mt-4">
    <h2>🎟️ Tickets de soporte recibidos</h2>
    <a href="admin.php" class="btn btn-secondary mb-3">⬅ Volver al panel</a>

    <?php if (empty($tickets)): ?>
      <div class="alert alert-info">No hay tickets por el momento.</div>
    <?php else: ?>
      <div class="table-responsive">
        <table class="table table-bordered table-hover">
          <thead class="table-dark">
            <tr>
              <th>ID</th>
              <th>Tipo</th>
              <th>DNI</th>
              <th>Nombre</th>
              <th>Correo</th>
              <th>Mensaje</th>
              <th>Estado</th>
              <th>Acción</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($tickets as $t): ?>
              <tr>
                <td><?= $t['id'] ?></td>
                <td><?= ucfirst($t['tipo']) ?></td>
                <td><?= $t['dni'] ?></td>
                <td><?= htmlspecialchars($t['nombre']) ?></td>
                <td><?= htmlspecialchars($t['correo']) ?></td>
                <td><?= nl2br(htmlspecialchars($t['mensaje'])) ?></td>
                <td>
                  <?php if ($t['estado'] === 'respondido'): ?>
                    <span class="badge bg-success">Respondido</span>
                  <?php elseif ($t['estado'] === 'pendiente'): ?>
                    <span class="badge bg-warning text-dark">Pendiente</span>
                  <?php else: ?>
                    <span class="badge bg-secondary"><?= ucfirst($t['estado']) ?></span>
                  <?php endif; ?>
                </td>
                <td>
                  <a href="responder_ticket.php?id=<?= $t['id'] ?>" class="btn btn-sm btn-danger">Responder</a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>
</body>

</html>