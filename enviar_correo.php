<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
require_once 'db/config.php';

if (!isset($_POST['compra_id'])) {
    die('❌ Error: No se recibió el ID de compra.');
}

$compra_id = intval($_POST['compra_id']);

$query = "SELECT * FROM compras WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $compra_id);
$stmt->execute();
$compra = $stmt->get_result()->fetch_assoc();

if (!$compra) {
    die('❌ Error: Compra no encontrada.');
}

$correo_destino = $_POST['correo_cliente'] ?? null;

if (!$correo_destino) {
    $correo_destino = $compra['socio_correo'] ?? '';

    if (empty($correo_destino)) {
        $stmt_socio = $conn->prepare("SELECT email FROM socios WHERE DNI = ?");
        $stmt_socio->bind_param("s", $compra['dni']);
        $stmt_socio->execute();
        $socio_result = $stmt_socio->get_result();
        if ($socio_data = $socio_result->fetch_assoc()) {
            $correo_destino = $socio_data['email'];
        }
    }
}

if (empty($correo_destino) || !filter_var($correo_destino, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['mensaje_error'] = "❌ Correo electrónico no válido.";
    header("Location: voucher.php?error=1");
    exit();
}

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'steven060604@gmail.com';
    $mail->Password = 'aqns fxbq sqfg khyy';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;
    $mail->CharSet = 'UTF-8';
    $mail->setFrom('steven060604@gmail.com', 'Cineplanet');
    $mail->addAddress($correo_destino, $compra['socio_nombre'] ?? 'Estimado Cliente');
    $mail->isHTML(true);
    $mail->Subject = '🎫 Tu Voucher de Cineplanet - ' . $compra['titulo'];

    $html_body = "
    <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; background-color: #f8f9fa;'>
        <div style='background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0;'>
            <h1 style='margin: 0; font-size: 28px;'>🎬 CINEPLANET</h1>
            <p style='margin: 10px 0 0 0; font-size: 16px;'>Tu Voucher de Compra</p>
        </div>
        <div style='background: white; padding: 30px; border-radius: 0 0 10px 10px;'>
            <div style='text-align: center; margin-bottom: 25px;'>
                <h2 style='color: #333; margin: 0; font-size: 22px;'>" . htmlspecialchars($compra['titulo']) . "</h2>
            </div>
            <div style='border: 1px solid #eee; border-radius: 8px; padding: 20px; margin-bottom: 20px;'>
                <div style='display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #eee;'>
                    <strong>👤 Nombre:</strong>
                    <span>" . htmlspecialchars($compra['socio_nombre'] ?? 'Cliente') . "</span>
                </div>
                <div style='display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #eee;'>
                    <strong>📅 Fecha:</strong>
                    <span>" . date('d/m/Y', strtotime($compra['fecha'])) . "</span>
                </div>
                <div style='display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #eee;'>
                    <strong>🕒 Hora:</strong>
                    <span>" . $compra['hora'] . "</span>
                </div>
                <div style='display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #eee;'>
                    <strong>🎭 Sala:</strong>
                    <span>" . ($compra['sala'] ?? 'Sala 1') . "</span>
                </div>
                <div style='display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #eee;'>
                    <strong>💺 Asientos:</strong>
                    <span>" . $compra['asientos'] . "</span>
                </div>
                <div style='display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #eee;'>
                    <strong>💳 Método de Pago:</strong>
                    <span>" . ($compra['metodo_pago'] ?? 'No especificado') . "</span>
                </div>
                <div style='display: flex; justify-content: space-between; padding: 8px 0; font-weight: bold; color: #667eea; font-size: 18px;'>
                    <strong>💰 TOTAL:</strong>
                    <span>S/ " . number_format($compra['total'], 2) . "</span>
                </div>
            </div>
            <div style='text-align: center; border: 2px dashed #ccc; padding: 20px; margin: 20px 0; background-color: #f9f9f9;'>
                <div style='font-size: 40px; margin-bottom: 10px;'>📱</div>
                <p style='margin: 0; font-weight: bold;'>Código de Voucher: #" . str_pad($compra['id'], 6, '0', STR_PAD_LEFT) . "</p>
                <p style='margin: 5px 0 0 0; font-size: 14px; color: #666;'>Presenta este voucher en el cine</p>
            </div>
            <div style='text-align: center; background-color: #f0f8ff; padding: 15px; border-radius: 8px; margin-top: 20px;'>
                <p style='margin: 0; color: #333; font-size: 14px;'>¡Gracias por elegir Cineplanet! 🍿<br>Te esperamos para disfrutar de tu película.</p>
            </div>
        </div>
        <div style='text-align: center; padding: 20px; color: #666; font-size: 12px;'>
            <p>© 2025 Cineplanet Perú - La mejor experiencia cinematográfica</p>
        </div>
    </div>
    ";

    $mail->Body = $html_body;

    if ($mail->send()) {
        $_SESSION['mensaje_exito'] = "¡Voucher enviado exitosamente a " . htmlspecialchars($correo_destino) . "!";
        header("Location: voucher.php?enviado=1");
        exit();
    }

} catch (Exception $e) {
    $_SESSION['mensaje_error'] = "Error al enviar el correo: " . $mail->ErrorInfo;
    header("Location: voucher.php?error=1");
    exit();
}
?>
