<?php
session_start();
require_once 'db/config.php';

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo']);
    $descripcion = trim($_POST['descripcion']);
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];
    $estado = 'inactiva';

    if (!empty($_FILES['imagen']['name'])) {
        $imagen_nombre = basename($_FILES['imagen']['name']);
        $ruta_destino = 'img/promociones/' . $imagen_nombre;

        if (!file_exists('img/promociones')) {
            mkdir('img/promociones', 0777, true);
        }

        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_destino)) {
            
            $stmt = $conn->prepare("INSERT INTO promociones (titulo, descripcion, imagen, fecha_inicio, fecha_fin, estado) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $titulo, $descripcion, $ruta_destino, $fecha_inicio, $fecha_fin, $estado);
            $stmt->execute();
        } else {
            die("❌ Error al mover la imagen.");
        }
    } else {
        die("❌ No se seleccionó ninguna imagen.");
    }

    header("Location: admin_promociones.php?estado=creado");
    exit();
}
?>
