<?php
session_start();
$dni = isset($_SESSION['dni']) ? $_SESSION['dni'] : null;
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Solicitar Sala Especial</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f9f9f9;
    }
    h2 {
      text-align: center;
      margin-top: 20px;
    }
    form {
      max-width: 600px;
      background: white;
      margin: 20px auto;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 0 10px #ccc;
      display: flex;
      flex-direction: column;
      gap: 12px;
    }
    label {
      font-weight: bold;
    }
    input, select, textarea {
      padding: 8px;
      font-size: 16px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }
    button {
      padding: 10px;
      background-color: #007bff;
      color: white;
      font-weight: bold;
      border: none;
      cursor: pointer;
      border-radius: 5px;
    }
    .cost-info {
      background: #e0ffe0;
      padding: 10px;
      border-left: 4px solid green;
      font-size: 14px;
    }
  </style>
</head>
<body>

<h2>🎉 Solicitar Sala Especial</h2>

<form method="POST" action="procesar_solicitud_sala.php">
  <input type="hidden" name="dni" value="<?= $dni ?>">

  <label>Nombre:</label>
  <input type="text" name="nombre" required>

  <label>Correo:</label>
  <input type="email" name="correo" required>

  <label>Teléfono de contacto:</label>
  <input type="text" name="telefono" required>

  <label>Sucursal Cineplanet:</label>
  <select name="sucursal" required>
    <option>Puruchuco</option>
    <option>San Miguel</option>
    <option>La Rambla</option>
    <option>Salaverry</option>
    <option>Primavera</option>
    <option>San Juan</option>
    <option>Piura</option>
    <option>Arequipa</option>
  </select>

  <label>Tipo de sala:</label>
  <select name="tipo_sala" required>
    <option>2D</option>
    <option>3D</option>
    <option>Privada</option>
  </select>

<label for="tipo_evento">Tipo de evento:</label>
<select name="tipo_evento" required>
  <option value="">-- Selecciona un tipo --</option>
  <option value="Cumpleaños">Cumpleaños</option>
  <option value="Reunión privada">Reunión privada</option>
  <option value="Conferencia">Conferencia</option>
  <option value="Proyección especial">Proyección especial</option>
</select>

  <label>Número de personas:</label>
  <input type="number" name="personas" min="1" required>

  <label>Fecha deseada:</label>
  <input type="date" name="fecha" required>

  <label>Hora deseada:</label>
  <input type="time" name="hora" required>

  <label>Mensaje adicional:</label>
  <textarea name="mensaje" rows="4"></textarea>

  <div class="cost-info">
    💰 <strong>Costos estimados:</strong><br>
    - Sala 2D: S/ 25 por persona<br>
    - Sala 3D: S/ 30 por persona<br>
    - Sala Privada: Desde S/ 800 (hasta 20 personas)<br>
    * Precios referenciales, sujetos a disponibilidad.
  </div>

  <button type="submit">📩 Enviar solicitud</button>
</form>

</body>
</html>
