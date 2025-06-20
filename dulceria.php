    <?php
    session_start();
    include 'db/config.php';

    $pelicula = $_SESSION['pelicula'] ?? 'Sin selección';
    $monto_entradas = $_SESSION['monto_entradas'] ?? 0;

    $categoria = $_GET['categoria'] ?? '';
    $sql = "SELECT * FROM dulceria";

    if (!empty($categoria)) {
        $categoria = $conn->real_escape_string($categoria);
        $sql .= " WHERE categoria = '$categoria'";
    }

    $resultado = $conn->query($sql);
    ?>

    <!DOCTYPE html>
    <html lang="es">

    <head>
        <meta charset="UTF-8">
        <title>Dulcería</title>
        <link rel="icon" type="icon/png" href="/media/images-removebg-preview.png">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            .card-custom {
                max-width: 240px;
                margin: 0 auto;
            }

            .btn-agregar {
                font-size: 0.85rem;
                padding: 5px 10px;
                width: 100%;
                margin-top: 5px;
            }

            .btn-confirmar {
                max-width: 300px;
                margin: 30px auto 0;
                display: block;
                font-size: 1rem;
                padding: 10px 0;
                padding: 10px 10px;
            }

            .cantidad-btn {
                width: 38px;
                border-radius: 50%;
            }

            .row-productos {
                justify-content: center;
            }

            .cantidad-btn {
                width: 38px;
                height: 38px;
                border-radius: 50% !important;
                padding: 0;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .cantidad-btn {
                width: 38px;
                height: 38px;
                border-radius: 50%;
                padding: 0;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .input-group input[type="text"] {
                max-width: 40px;
                padding: 0 4px;
                text-align: center;
                font-weight: bold;
                margin: 0 4px;
                height: 38px;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .card .card-body p {
                color: #004A8C;
                font-weight: bold;
                margin-bottom: 8px;
            }

            .input-group {
                display: flex;
                justify-content: center;
                margin-top: 20px;
            }

            #cantidad-1 {
                border: none;
            }

            .card .card-body p {
                color: #004A8C;
                font-weight: bold;
                margin-bottom: 8px;
            }

            .border {
                margin-top: 40px;
            }

            .border h6 {
                font-size: 30px;
                text-align: center;
            }

            #total {
                color: red;
            }

            .input-group .btn-outline-secondary:first-child:hover {
                background-color: red;
                color: white;
            }

            .input-group .btn-outline-secondary:last-child:hover {
                background-color: rgb(11, 228, 102);
                color: white;
            }
        
        </style>
    </head>
    <body>

    <div class="container-fluid my-5">
            <div class="row">

        
                <div class="col-md-3">
                    <div class="border p-3 mb-4 bg-light">
                        <h6> Total a pagar: <br> <span id="total">S/<?= number_format($monto_entradas, 2) ?></span></h6>
                    </div>
                    <button onclick="confirmarPedido()" class="btn btn-danger btn-confirmar">Confirmar y continuar →</button>
                </div>
                <div class="col-md-9">
                    <h2 class="mb-4 text-center">🍿 Dulcería</h2>

                    <form method="GET" class="mb-4">
                        <div class="row g-2 justify-content-center">
                            <div class="col-auto">
                                <select name="categoria" class="form-select">
                                    <option value="">Todas las categorías</option>
                                    <option value="combo" <?= $categoria == 'combo' ? 'selected' : '' ?>>Combos</option>
                                    <option value="canchita" <?= $categoria == 'canchita' ? 'selected' : '' ?>>Canchita</option>
                                    <option value="adicional" <?= $categoria == 'adicional' ? 'selected' : '' ?>>Snacks</option>
                                    <option value="bebida" <?= $categoria == 'bebida' ? 'selected' : '' ?>>Bebidas</option>
                                </select>

                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-primary">Filtrar</button>
                            </div>
                        </div>
                    </form>


                    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4 row-productos">
                        <?php while ($producto = $resultado->fetch_assoc()): ?>
                            <div class="col">
                                <div class="card card-custom h-100">
                                    <img src="<?= $producto['imagen'] ?>" class="card-img-top" style="height: 160px; object-fit: cover;">
                                    <div class="card-body text-center">
                                        <h6 class="card-title"><?= $producto['nombre'] ?></h6>
                                        <p>S/ <?= number_format($producto['precio'], 2) ?></p>
                                        <div class="input-group input-group-sm mb-2">

                                            <button class="btn btn-outline-secondary cantidad-btn" onclick="cambiarCantidad(<?= $producto['id'] ?>, -1, <?= $producto['precio'] ?>)">−</button>
                                            <input type="text" class="form-control text-center" id="cantidad-<?= $producto['id'] ?>" value="0" readonly>
                                            <button class="btn btn-outline-secondary cantidad-btn" onclick="cambiarCantidad(<?= $producto['id'] ?>, 1, <?= $producto['precio'] ?>)">+</button>

                                        </div>

                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>
        </div>

        <script>
            let montoEntradas = <?= $monto_entradas ?>;
            let cantidades = {};
            
            if (sessionStorage.getItem('cantidades')) {
                cantidades = JSON.parse(sessionStorage.getItem('cantidades'));
                
                for (const id in cantidades) {
                    const elem = document.getElementById('cantidad-' + id);
                    if (elem) {
                        elem.value = cantidades[id].cantidad;
                    }
                }

                let montoDulceria = recalcularDulceria();
                document.getElementById('total').innerText = "S/ " + (montoEntradas + montoDulceria).toFixed(2);
            }

            function recalcularDulceria() {
                let totalDulceria = 0;
                for (const id in cantidades) {
                    totalDulceria += cantidades[id].cantidad * cantidades[id].precio;
                }
                return totalDulceria;
            }

            function cambiarCantidad(id, cambio, precio) {
                if (!cantidades[id]) {
                    cantidades[id] = {
                        cantidad: 0,
                        precio: precio
                    };
                }
                cantidades[id].cantidad = Math.max(0, cantidades[id].cantidad + cambio);
                document.getElementById('cantidad-' + id).value = cantidades[id].cantidad;

                let montoDulceria = recalcularDulceria();
                document.getElementById('total').innerText = " S/ " + (montoEntradas + montoDulceria).toFixed(2);
                sessionStorage.setItem('cantidades', JSON.stringify(cantidades));

            }

            function confirmarPedido() {

                let montoDulceria = recalcularDulceria();

                fetch('guardar_total_dulceria.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            monto_dulceria: parseFloat(montoDulceria.toFixed(2))
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === 'ok') {
                            sessionStorage.removeItem('cantidades'); 
                            window.location.href = 'metodo_pago.php';
                        } else {
                            alert('Error al guardar el total. Intenta de nuevo.');
                        }
                    });
                sessionStorage.removeItem('cantidades');

            }
        </script>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>

    </html>