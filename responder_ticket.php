<?php
session_start();

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header('Location: index.php');
    exit();
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

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

    if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        die('❌ ID de ticket no válido.');
    }

    $id = $_GET['id'];

    $stmt = $pdo->prepare("SELECT * FROM tickets WHERE id = ?");
    $stmt->execute([$id]);
    $ticket = $stmt->fetch();

    if (!$ticket) {
        die('❌ Ticket no encontrado.');
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $respuesta = trim($_POST['respuesta']);
        if ($respuesta === '') {
            $error = "La respuesta no puede estar vacía.";
        } else {
            
            $update = $pdo->prepare("UPDATE tickets SET estado = 'respondido', respuesta = ?, fecha_respuesta = NOW() WHERE id = ?");
            $update->execute([$respuesta, $id]);

            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'steven060604@gmail.com';
                $mail->Password   = 'aqns fxbq sqfg khyy';
                $mail->SMTPSecure = 'tls';
                $mail->Port       = 587;
                $mail->CharSet    = 'UTF-8';

                $mail->setFrom('steven060604@gmail.com', 'Soporte Cineplanet');
                $mail->addAddress($ticket['correo'], $ticket['nombre']);

                $mail->isHTML(true);
                $mail->Subject = '🛠 Respuesta a tu Ticket - Cineplanet';

                $html_body = "
                <div style='font-family: Arial, sans-serif; max-width: 600px; margin: auto; background-color: #f8f9fa;'>
                    <div style='background-color: #4B79A1; color: white; padding: 20px; border-radius: 10px 10px 0 0; text-align: center;'>
                        <h2 style='margin: 0;'>Respuesta de Soporte 🎟️</h2>
                    </div>
                    <div style='padding: 20px; background-color: white; border-radius: 0 0 10px 10px;'>
                        <p>Hola <strong>" . htmlspecialchars($ticket['nombre']) . "</strong>,</p>
                        <p>Hemos revisado tu ticket y aquí tienes nuestra respuesta:</p>
                        <hr>
                        <p><strong>📝 Tu mensaje:</strong><br>" . nl2br(htmlspecialchars($ticket['mensaje'])) . "</p>
                        <p><strong>✅ Nuestra respuesta:</strong><br>" . nl2br(htmlspecialchars($respuesta)) . "</p>
                        <hr>
                        <p>Gracias por escribirnos. Si necesitas más ayuda, puedes responder este correo o abrir un nuevo ticket.</p>
                        <p style='font-size: 12px; color: #555;'>Cineplanet Perú - Soporte técnico</p>
                    </div>
                </div>
                ";

                $mail->Body = $html_body;
                $mail->send();

                header("Location: admin.php?resuelto=$id");
                exit();
            } catch (Exception $e) {
                $error = "❌ Error al enviar el correo: " . $mail->ErrorInfo;
            }
        }
    }

} catch (PDOException $e) {
    die("❌ Error en la base de datos: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Responder Ticket</title>
  <link rel="icon" type="image/png" href="media/logo1.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="bg-light">

<div class="container mt-5">
  <h2 class="mb-4">Responder ticket #<?= htmlspecialchars($ticket['id']) ?></h2>

  <div class="card mb-4">
    <div class="card-body">
      <h5 class="card-title">De: <?= htmlspecialchars($ticket['nombre']) ?> (<?= htmlspecialchars($ticket['correo']) ?>)</h5>
      <p><strong>Tipo:</strong> <?= htmlspecialchars($ticket['tipo']) ?></p>
      <p><strong>Mensaje:</strong><br><?= nl2br(htmlspecialchars($ticket['mensaje'])) ?></p>
      <p><strong>Estado actual:</strong> <?= $ticket['estado'] === 'respondido' ? '✅ Respondido' : '🕐 Pendiente' ?></p>

      <?php if ($ticket['estado'] === 'respondido'): ?>
        <hr>
        <p><strong>Respuesta enviada:</strong><br><?= nl2br(htmlspecialchars($ticket['respuesta'])) ?></p>
        <p><strong>Fecha de respuesta:</strong> <?= htmlspecialchars($ticket['fecha_respuesta']) ?></p>
      <?php else: ?>
        <hr>
        <?php if (isset($error)): ?>
          <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        <form method="post">
          <div class="mb-3">
            <label for="respuesta" class="form-label">Escribe tu respuesta:</label>
            <textarea name="respuesta" id="respuesta" class="form-control" rows="5" required></textarea>
          </div>
          <button type="submit" class="btn btn-success">Enviar respuesta</button>
          <a href="admin.php" class="btn btn-secondary">Cancelar</a>
        </form>
      <?php endif; ?>
    </div>
  </div>
</div>

</body>
</html>
