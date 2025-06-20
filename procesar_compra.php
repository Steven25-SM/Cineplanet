<?php
session_start();
require_once 'db/config.php';

if (!$conn) {
    die("❌ Error de conexión a la base de datos: " . mysqli_connect_error());
}

if (isset($_SESSION['compra_id'])) {
    header("Location: voucher.php?id=" . $_SESSION['compra_id']);
    exit();
}

if (empty($_SESSION['compra'])) {
    die('❌ Sesión incompleta. No se puede procesar la compra.');
}

$compra = $_SESSION['compra'];
$dni_cliente = $_SESSION['dni'] ?? '00000000';
$correo_cliente = $_SESSION['correo'] ?? null;
$pelicula_id = $compra['pelicula_id'] ?? 1;
$titulo = $compra['titulo'] ?? 'Desconocido';
$fecha = $compra['fecha'] ?? date('Y-m-d');
$hora = $compra['hora'] ?? '20:00';
$metodo_pago = $compra['metodo_pago'] ?? 'Sin especificar';
$cantidad = isset($_SESSION['cantidad']) ? (int)$_SESSION['cantidad'] : ((isset($compra['cantidad']) ? (int)$compra['cantidad'] : 1));
$total = $compra['total'] ?? 0;

function generarAsientosConsecutivos($cantidad) {
    $filas = range('A', 'L');
    $asientos_generados = [];
    $fila = $filas[array_rand($filas)];
    $col_inicio = rand(1, max(1, 13 - $cantidad + 1));

    for ($i = 0; $i < $cantidad; $i++) {
        $asientos_generados[] = $fila . ($col_inicio + $i);
    }

    return $asientos_generados;
}

if (!empty($_SESSION['butacas']) && count($_SESSION['butacas']) === $cantidad) {
    $asientos = $_SESSION['butacas'];
} else {
    $asientos = generarAsientosConsecutivos($cantidad);
}
$_SESSION['asientos'] = $asientos;
$asientos_str = implode(", ", $asientos);

$stmt = $conn->prepare("INSERT INTO compras (pelicula_id, titulo, dni, fecha, hora, cantidad, metodo_pago, total, created_at, asientos)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?)");
if (!$stmt) {
    die("❌ Error en prepare: " . $conn->error);
}

$stmt->bind_param("isssssdss",
    $pelicula_id,
    $titulo,
    $dni_cliente,
    $fecha,
    $hora,
    $cantidad,
    $metodo_pago,
    $total,
    $asientos_str
);

if (!$stmt->execute()) {
    die("❌ Error al insertar compra: " . $stmt->error);
}

$compra_id = $stmt->insert_id;

if (!empty($compra['dulceria'])) {
    foreach ($compra['dulceria'] as $producto) {
        $pstmt = $conn->prepare("INSERT INTO compras_dulceria (compra_id, producto_id, cantidad, subtotal)
                                 VALUES (?, ?, ?, ?)");
        if ($pstmt) {
            $pstmt->bind_param("iiid", $compra_id, $producto['id'], $producto['cantidad'], $producto['subtotal']);
            $pstmt->execute();
        }
    }
}


$stmt_asiento = $conn->prepare("INSERT INTO asientos_reservados (compra_id, asiento) VALUES (?, ?)");
foreach ($asientos as $asiento) {
    $stmt_asiento->bind_param("is", $compra_id, $asiento);
    $stmt_asiento->execute();
}

$_SESSION['compra'] = [
    'id' => $compra_id,
    'titulo' => $titulo,
    'dni' => $dni_cliente,
    'correo' => $correo_cliente,
    'pelicula_id' => $pelicula_id,
    'fecha' => $fecha,
    'hora' => $hora,
    'cantidad' => $cantidad,
    'metodo_pago' => $metodo_pago,
    'total' => $total,
    'asientos' => $asientos_str
];

$_SESSION['compra_id'] = $compra_id;

header("Location: voucher.php?id=$compra_id");
exit();
