<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $asientos = $_POST['asientos'];
    
    $conexion = new mysqli("localhost", "usuario", "clave", "cineplanet");

    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }

    $asientos_array = explode(",", $asientos);
    foreach ($asientos_array as $asiento) {
        $stmt = $conexion->prepare("INSERT INTO asientos_reservados (asiento) VALUES (?)");
        $stmt->bind_param("s", $asiento);
        $stmt->execute();
    }

    echo "Asientos reservados exitosamente: $asientos";
    $conexion->close();
}
?>
