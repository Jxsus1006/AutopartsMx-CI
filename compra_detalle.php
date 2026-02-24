<?php
require 'config/config.php';
require 'clases/clienteFunciones.php';

if(!isset($_SESSION['token'])){
    header("Location: compras.php");
    exit;
}

$token_session = $_SESSION['token'];
$orden = $_GET['orden'] ?? null;
$token = $_GET['token'] ?? null;

if ($orden == null || $token == null || $token != $token_session) {
    header("Location: compras.php");
    exit;
}
//conexion a la bd
$db = new Database();
$con = $db->conectar();


$sqlCompra = $con->prepare("SELECT id_compra, id_transaccion, fecha, total FROM compra WHERE id_transaccion = ? LIMIT 1");
$sqlCompra->execute([$orden]);

//traer el resultado de la consulta
$rowCompra = $sqlCompra->fetch(PDO::FETCH_ASSOC);

//extraer id de compra
$idCompra = $rowCompra['id_compra'];

$fecha = new DateTime($rowCompra['fecha']);
$fecha = $fecha->format('d/m/Y H:i');

//extraer el detalle de la compra
$sqlDetalle = $con->prepare("SELECT id_detalle, id_compra, nombre_pieza, precio, cantidad FROM detalle_compra WHERE id_compra = ?");
$sqlDetalle->execute([$idCompra]);

?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Autoparts mx</title>
    <script src="inicioA.js" defer></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css"
        rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3"
        crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Enlace a Font Awesome -->
</head>

<body>

    <?php include 'menu.php'; ?>

    <main>
        <div class="container">
            <hr>
            <div class="row">
                <div class="col-12 col-md-4">
                    <div class="card mb-3">
                        <div class="card-header">
                            <strong>Detalle de su compra</strong>
                        </div>
                        <div class="card-body">
                            <p><strong>Fecha: </strong><?php echo $fecha; ?></p>
                            <p><strong>Pedido: </strong><?php echo $rowCompra['id_transaccion']; ?></p>
                            <p><strong>Total: </strong>
                            <?php echo MONEDA . ' ' . number_format($rowCompra['total'],2,'.',','); ?>
                            </p>

                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-8">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Producto:</th>
                                    <th>Precio:</th>
                                    <th>Cantidad:</th>
                                    <th>Subtotal:</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php 
                                while($row = $sqlDetalle->fetch(PDO::FETCH_ASSOC)) {
                                    $precio = $row['precio'];
                                    $cantidad = $row['cantidad'];
                                    $subtotal = $precio * $cantidad;
                                    ?>
                                
                                <tr>
                                    <td><?php echo $row['nombre_pieza']; ?></td>
                                    <td><?php echo MONEDA . ' ' . number_format($precio,2,'.',','); ?></td>
                                    <td><?php echo $cantidad; ?></td>
                                    <td><?php echo MONEDA . ' ' . number_format($subtotal,2,'.',','); ?></td>

                                </tr>

                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>



        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>

    </script>
</body>

</html>