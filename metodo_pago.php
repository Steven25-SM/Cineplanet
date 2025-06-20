<?php
session_start();
require_once 'db/config.php';

$monto_entradas = $_SESSION['monto_entradas'] ?? 0;
$monto_dulceria = $_SESSION['monto_dulceria'] ?? 0;
$total_a_pagar = $monto_entradas + $monto_dulceria;

if ($total_a_pagar <= 0) {
    $_SESSION['mensaje_error'] = "Debe seleccionar entradas o productos antes de continuar.";
    header("Location: cartelera.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Método de Pago</title>
  <link rel="icon" type="image/png" href="media/logo1.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background-color: #111; color: white; }
    .btn-option { padding: 1rem 2rem; font-size: 1.25rem; }

    body {
      background-color: white;
    }

    .metodo-box {
      border: 3px solid black;
      padding: 60px;
      
    }

    .text-success{
      text-align: center;
    }
    
    .fs-3 {
      text-align: center;
      color: black;
    }

    .text-info{
      color: rgb(30, 73, 138) !important;
    }

    .text-success{
      color: red !important;
    }

    .btn-option {
  padding: 1rem 1.2rem;
  font-size: 1.25rem;
  margin: 0 0.75rem; 
}

.btn-yape {
  background-color: #7c00ff;
  border-color: #7c00ff;
  color: white;
}

.btn-yape:hover {
  background-color: #6500cc;
  border-color: #6500cc;
}


  </style>
</head>


<body class="d-flex flex-column justify-content-center align-items-center vh-100">
  <div class="metodo-box"><h2 class="text-info mb-4">Escoge un Método de Pago</h2>
  <p class="fs-3">Total a pagar:<br> <span class="text-success">S/ <?= number_format($total_a_pagar, 2) ?></span></p>

  <div class="btn-group-custom">
  <button onclick="irVoucher('Tarjeta')" class="btn btn-primary btn-option">💳 Pagar con tarjeta</button>
  <button onclick="irVoucher('Yape')" class="btn btn-yape btn-option">📱 Yape</button>
</div>

</div> 

<form id="formPago" method="post" action="voucher.php">
  <input type="hidden" name="metodo" id="metodo">
</form>


  <script>
  function irVoucher(metodo) {
    document.getElementById('metodo').value = metodo;
    document.getElementById('formPago').submit();
  }
</script>

</body>
</html>
