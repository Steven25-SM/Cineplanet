<?php
session_start();

$mensajeExito = '';
$mensajeError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  
    $dni = htmlspecialchars(trim($_POST['dni']));
    $nombre = htmlspecialchars(trim($_POST['nombre']));
    $correo = htmlspecialchars(trim($_POST['correo']));
    $tipo = htmlspecialchars(trim($_POST['tipo']));
    $mensaje = htmlspecialchars(trim($_POST['mensaje']));

    if ($dni && $nombre && $correo && $tipo && $mensaje) {
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

            $stmt = $pdo->prepare("INSERT INTO tickets (dni, nombre, correo, tipo, mensaje, estado, fecha_creacion) 
                                   VALUES (?, ?, ?, ?, ?, 'pendiente', NOW())");

            $stmt->execute([$dni, $nombre, $correo, $tipo, $mensaje]);

            $mensajeExito = " Tu ticket ha sido enviado. Responderemos pronto.";
        } catch (PDOException $e) {
            $mensajeError = "❌ Error en la base de datos: " . $e->getMessage();
        }
    } else {
        $mensajeError = "⚠️ Por favor, completa todos los campos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Soporte - Cineplanet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'includes/header.php'; ?>

<div class="container mt-5 pt-5" style="margin-top: 100px;">

    <h2 class="mb-4 text-center">📩 Enviar Ticket de Soporte</h2>

    <?php if ($mensajeExito): ?>
        <div class="alert alert-success"><?= $mensajeExito ?></div>
    <?php elseif ($mensajeError): ?>
        <div class="alert alert-danger"><?= $mensajeError ?></div>
    <?php endif; ?>

    <form method="post" class="p-4 border rounded bg-light">
        <div class="mb-3">
            <label for="dni" class="form-label">DNI</label>
            <input type="text" class="form-control" name="dni" id="dni" required value="<?= $_SESSION['dni'] ?? '' ?>">
        </div>
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre completo</label>
            <input type="text" class="form-control" name="nombre" id="nombre" required>
        </div>
        <div class="mb-3">
            <label for="correo" class="form-label">Correo electrónico</label>
            <input type="email" class="form-control" name="correo" id="correo" required>
        </div>
        <div class="mb-3">
            <label for="tipo" class="form-label">Tipo de ticket</label>
            <select name="tipo" id="tipo" class="form-select" required>
                <option value="">Selecciona una opción</option>
                <option value="Problema técnico">Problema técnico</option>
                <option value="Consulta general">Consulta general</option>
                <option value="Solicitar reembolso">Solicitar reembolso</option>
                <option value="Otro">Otro</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="mensaje" class="form-label">Mensaje</label>
            <textarea name="mensaje" id="mensaje" class="form-control" rows="5" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Enviar ticket</button>
    </form>
</div>

</body>
</html>
