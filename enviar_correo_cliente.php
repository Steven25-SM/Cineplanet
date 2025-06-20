<?php
session_start();
require_once 'db/config.php';

$compra_id = $_GET['compra_id'] ?? null;

if (!$compra_id) {
    die('❌ Compra no encontrada.');
}

$stmt = $conn->prepare("SELECT * FROM compras WHERE id = ?");
$stmt->bind_param("i", $compra_id);
$stmt->execute();
$result = $stmt->get_result();
$compra = $result->fetch_assoc();

if (!$compra) {
    die('❌ Datos de compra no válidos.');
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Enviar Voucher</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .form-container {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            max-width: 400px;
            width: 100%;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h4>📧 Enviar Voucher</h4>
    <p>Ingresa tu correo para recibir el voucher:</p>
    <form action="enviar_correo.php" method="post">
        <input type="hidden" name="compra_id" value="<?php echo $compra_id; ?>">
        <div class="mb-3">
            <label for="correo" class="form-label">Correo electrónico</label>
            <input type="email" class="form-control" name="correo_cliente" id="correo" required>
        </div>
        <button type="submit" class="btn btn-success">Enviar ahora</button>
        <a href="voucher.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

</body>
</html>
