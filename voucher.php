<?php
session_start();
require_once 'db/config.php';

$socio_nombre = "Cliente";
$socio_correo = "";

if (!empty($_SESSION['dni']) && isset($_SESSION['rol']) && $_SESSION['rol'] === 'socio') {
    $dni_socio = $_SESSION['dni'];
    $stmt_socio = $conn->prepare("SELECT nombre, apellido, email FROM socios WHERE dni = ?");
    $stmt_socio->bind_param("s", $dni_socio);
    $stmt_socio->execute();
    $result = $stmt_socio->get_result();
    if ($row = $result->fetch_assoc()) {
        $socio_nombre = $row['nombre'] . ' ' . $row['apellido'];
        $socio_correo = $row['email'];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['metodo'])) {
    $dni = $_SESSION['dni'] ?? '00000000';
    $dni_insertar = $dni;

    $titulo = $_SESSION['titulo'] ?? 'Película no encontrada';
    $pelicula_id = $_SESSION['pelicula_id'] ?? 1;
    $fecha = $_SESSION['fecha'] ?? $_SESSION['fecha_funcion'] ?? date('Y-m-d');
    $hora = $_SESSION['hora'] ?? $_SESSION['hora_funcion'] ?? '20:00';
    $cantidad = $_SESSION['cantidad'] ?? $_SESSION['cantidad_entradas'] ?? 2;
    $metodo_pago = $_POST['metodo'];
    $total = ($_SESSION['monto_entradas'] ?? 0) + ($_SESSION['monto_dulceria'] ?? 0);

    $sala_numero = (($pelicula_id - 1) % 13) + 1;
    $sala_nombre = "Sala " . $sala_numero;

    function generarAsientosConsecutivos($cantidad) {
        $filas = range('A', 'L');
        $fila = $filas[array_rand($filas)];
        $col_inicio = rand(1, max(1, 13 - $cantidad + 1));
        $asientos = [];
        for ($i = 0; $i < $cantidad; $i++) {
            $asientos[] = $fila . ($col_inicio + $i);
        }
        return $asientos;
    }

    if (!empty($_SESSION['butacas']) && count($_SESSION['butacas']) == $cantidad) {
        $asientos = $_SESSION['butacas'];
    } else {
        $asientos = generarAsientosConsecutivos($cantidad);
    }

    $_SESSION['asientos'] = $asientos;
    $asientos_str = implode(", ", $asientos);

    $stmt = $conn->prepare("INSERT INTO compras (pelicula_id, titulo, dni, socio_nombre, socio_correo, fecha, hora, sala, cantidad, metodo_pago, total, created_at, asientos)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?)");
    $stmt->bind_param("isssssssdsds",
        $pelicula_id,
        $titulo,
        $dni_insertar,
        $socio_nombre,
        $socio_correo,
        $fecha,
        $hora,
        $sala_nombre,
        $cantidad,
        $metodo_pago,
        $total,
        $asientos_str
    );

    if ($stmt->execute()) {
        $compra_id = $stmt->insert_id;
        $_SESSION['compra_id'] = $compra_id;

        $compra_data = [
            'id' => $compra_id,
            'titulo' => $titulo,
            'fecha' => $fecha,
            'hora' => $hora,
            'sala' => $sala_nombre,
            'cantidad' => $cantidad,
            'metodo_pago' => $metodo_pago,
            'total' => $total,
            'asientos' => $asientos_str,
            'socio_nombre' => $socio_nombre,
            'socio_correo' => $socio_correo,
            'monto_entradas' => $_SESSION['monto_entradas'] ?? 0,
            'monto_dulceria' => $_SESSION['monto_dulceria'] ?? 0
        ];
    } else {
        die("❌ Error al procesar la compra: " . $stmt->error);
    }
}

if (isset($_SESSION['compra_id']) && !isset($compra_data)) {
    $compra_id = $_SESSION['compra_id'];
    $stmt = $conn->prepare("SELECT * FROM compras WHERE id = ?");
    $stmt->bind_param("i", $compra_id);
    $stmt->execute();
    $compra_data = $stmt->get_result()->fetch_assoc();

    $stmt2 = $conn->prepare("SELECT asiento FROM asientos_reservados WHERE compra_id = ?");
    $stmt2->bind_param("i", $compra_id);
    $stmt2->execute();
    $res = $stmt2->get_result();
    $arr = [];
    while ($row = $res->fetch_assoc()) {
        $arr[] = $row['asiento'];
    }
    $compra_data['asientos'] = implode(", ", $arr);

    $compra_data['monto_entradas'] = $_SESSION['monto_entradas'] ?? 0;
    $compra_data['monto_dulceria'] = $_SESSION['monto_dulceria'] ?? 0;
}

if (!isset($compra_data)) {
    die('❌ No se encontraron datos de compra.');
}
?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Voucher de Compra - Cineplanet</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            body {
                background-color: #f8f9fa;
                position: relative;
            }
            
            .dulceria-btn {
                position: fixed;
                top: 20px;
                left: 20px;
                z-index: 1000;
                background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
                border: none;
                color: white;
                padding: 12px 20px;
                border-radius: 25px;
                font-weight: bold;
                box-shadow: 0 4px 15px rgba(255,107,107,0.3);
                transition: transform 0.3s ease;
            }
            
            .dulceria-btn:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 20px rgba(255,107,107,0.4);
                color: white;
            }
            
            .voucher-container {
                max-width: 600px;
                margin: 80px auto 50px;
                background: white;
                border-radius: 15px;
                box-shadow: 0 10px 30px rgba(0,0,0,0.1);
                overflow: hidden;
            }
            
            .voucher-header {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                padding: 30px;
                text-align: center;
            }
            
            .voucher-body {
                padding: 30px;
            }
            
            .info-row {
                display: flex;
                justify-content: space-between;
                padding: 10px 0;
                border-bottom: 1px solid #eee;
            }
            
            .info-row:last-child {
                border-bottom: none;
                font-weight: bold;
                font-size: 1.1em;
                color: #667eea;
            }
            
            .qr-code {
                text-align: center;
                margin: 20px 0;
            }
            
            .action-btn {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                border: none;
                color: white;
                padding: 12px 30px;
                border-radius: 25px;
                font-weight: bold;
                margin: 5px;
                transition: transform 0.3s ease;
            }
            
            .action-btn:hover {
                transform: translateY(-2px);
                color: white;
            }
            
            .email-btn {
                background: linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%);
            }
            
            .email-btn:hover {
                background: linear-gradient(135deg, #4a9f23 0%, #95d9b8 100%);
            }
        </style>
    </head>
    <body>

    <a href="cartelera.php" class="btn cartelera-btn">
                    🎬 Ver Cartelera
                </a>

    <?php if (isset($_GET['enviado']) && isset($_SESSION['mensaje_exito'])): ?>
        <div class="alert alert-success alert-dismissible fade show" style="position: fixed; top: 80px; right: 20px; z-index: 1001; max-width: 400px;">
            <strong>✅ ¡Éxito!</strong> <?php echo $_SESSION['mensaje_exito']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['mensaje_exito']); ?>
    <?php endif; ?>

    <?php if (isset($_GET['error']) && isset($_SESSION['mensaje_error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" style="position: fixed; top: 80px; right: 20px; z-index: 1001; max-width: 400px;">
            <strong>❌ Error:</strong> <?php echo $_SESSION['mensaje_error']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['mensaje_error']); ?>
    <?php endif; ?>

    <div class="voucher-container">
        <div class="voucher-header">
            <h2>🎬 CINEPLANET</h2>
            <p>Voucher de Compra</p>
            <h4><?php echo htmlspecialchars($compra_data['titulo']); ?></h4>
        </div>
        
        <div class="voucher-body">
            
           <div class="info-row">
    <?php if (!empty($compra_data['socio_nombre']) && $compra_data['socio_nombre'] !== 'Cliente'): ?>
        <strong>👤 Socio:</strong>
<span>
<?php 
if (!empty($compra_data['socio_nombre']) && $compra_data['socio_nombre'] !== 'Cliente') {
    echo htmlspecialchars($compra_data['socio_nombre']);
} else {
    echo 'No es socio';
}
?>
</span>

    <?php else: ?>
        <strong>👤 Cliente</strong>
        <span></span>
    <?php endif; ?>
</div>

            <div class="info-row">
                <strong>📅 Fecha:</strong>
                <span><?php echo date('d/m/Y', strtotime($compra_data['fecha'])); ?></span>
            </div>
            
            <div class="info-row">
                <strong>🕒 Hora:</strong>
                <span><?php echo $compra_data['hora']; ?></span>
            </div>
            
            <div class="info-row">
                <strong>🎭 Sala:</strong>
                <span><?php echo $compra_data['sala'] ?? 'Sala 1'; ?></span>
            </div>
            
            <div class="info-row">
                <strong>💺 Asientos:</strong>
                <span><?php echo htmlspecialchars($compra_data['asientos'] ?? ''); ?>
    </span>
            </div>
            
            <div class="info-row">
                <strong>💳 Método de Pago:</strong>
                <span><?php echo htmlspecialchars($compra_data['metodo_pago'] ?? 'No especificado'); ?></span>
            </div>
            
            <div class="info-row">
                <strong>💰 TOTAL:</strong>
                <span>S/ <?php echo number_format($compra_data['total'], 2); ?></span>
            </div>
            
            
            
            <div class="text-center">
                <button class="btn action-btn" onclick="window.print()">
                    🖨️ Imprimir Voucher
                </button>
                
            <?php if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'socio'): ?>
        
        <form action="enviar_correo_cliente.php" method="get" style="display: inline;">
            <input type="hidden" name="compra_id" value="<?php echo $compra_data['id']; ?>">
            <button type="submit" class="btn action-btn email-btn">
                📧 Enviar por Correo
            </button>
        </form>
    <?php else: ?>
        
        <form action="enviar_correo.php" method="post" style="display: inline;">
            <input type="hidden" name="compra_id" value="<?php echo $compra_data['id']; ?>">
            <button type="submit" class="btn action-btn email-btn">
                📧 Enviar por Correo
            </button>
        </form>
    <?php endif; ?>

                
                
            </div>
        </div>
    </div>

    </body>
    </html>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>