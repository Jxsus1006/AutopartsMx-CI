<?php
require 'config/config.php';
$db = new Database();
$con = $db->conectar();

$productos = isset($_SESSION['carrito']['productos']) ? $_SESSION['carrito']['productos'] : null;
//print_r($_SESSION);

$lista_carrito = array();

if ($productos != null) {
    foreach ($productos as $clave => $cantidad) {

        $sql = $con->prepare("SELECT id_pieza, nombre_pieza, marca, codigo, precio, descripcion, $cantidad AS cantidad FROM productos WHERE id_pieza=? AND activo>=1");
        $sql->execute([$clave]);

        //Fetch para traer producto por producto
        $lista_carrito[] = $sql->fetch(PDO::FETCH_ASSOC);
    }
} else {
    header("Location: catalogo.php");
    exit;
}


//Eliminar carrito
//session_destroy();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css"
        rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3"
        crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/2f26ebf69b.js" crossorigin="anonymous"></script>

    <link href="CSS/estilocat.css" rel="stylesheet">
</head>

<body>

    <?php include 'menu.php'; ?>

    <main>
        <div class="container">
            <div class="row">
                <div class="col-6">
                    <h4>Detalles de su pago</h4>
                    <div id="paypal-button-container"></div>

                </div>

                <div class="col-6">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Subtotal</th>
                                    <th></th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($lista_carrito == null) {
                                    echo '<tr><td colspan="5" class="text-center"><b>Lista vacía</b></td></tr>';
                                } else {

                                    $total = 0;
                                    foreach ($lista_carrito as $productos) {
                                        $_id = $productos['id_pieza'];
                                        $nombre = $productos['nombre_pieza'];
                                        $codigo = $productos['codigo'];
                                        $descripcion = $productos['descripcion'];
                                        $cantidad = $productos['cantidad'];
                                        $marca = $productos['marca'];
                                        $precio = $productos['precio'];
                                        $subtotal = $cantidad * $precio;
                                        $total += $subtotal;
                                ?>
                                        <tr>
                                            <td><?php echo $nombre; ?></td>
                                            <td>
                                                <div id="subtotal_<?php echo $_id; ?>" name="subtotal[]">
                                                    <?php echo MONEDA . number_format($subtotal, 2, '.', ','); ?></div>
                                            </td>
                                        </tr>
                                    <?php } ?>

                                    <tr>
                                        <td colspan="2">
                                            <p class="h3 text-end" id="total"><?php echo MONEDA . number_format($total, 2, '.', ','); ?></p>
                                        </td>
                                    </tr>
                            </tbody>
                        <?php } ?>

                        </table>
                    </div>


                </div>
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>
    <script src="https://www.paypal.com/sdk/js?client-id=<?php echo CLIENT_ID; ?>&currency=<?php echo CURRENCY; ?>"></script>
    <script>
        // Render the PayPal button into #paypal-button-container
        paypal.Buttons({
            style: {
                color: 'blue',
                shape: 'pill',
                label: 'pay'
            },
            // Call your server to set up the transaction
            createOrder: function(data, actions) {
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: <?php echo $total; ?>
                        }
                    }],
                    //ELIMINAR SOLICITUD DE DATOS DE DIRECCION DEL CLIENTE
                    application_context: {
                        shipping_preference: "NO_SHIPPING"
                    }
                });
            },

            onApprove: function(data, actions) {
                let URL = 'clases/captura.php'
                actions.order.capture().then(function(detalles) {
                    console.log(detalles);

                    let url = 'clases/captura.php'

                    return fetch(url, {
                        method: 'post',
                        headers: {
                            'content-type': 'application/json'
                        },
                        body: JSON.stringify({
                            detalles: detalles
                        })
                    }).then(function(response) {
                        window.location.href = "completado.php?key=" + detalles['id'];
                    })
                });
            },
            onCancel: function(data) {
                alert("Pago cancelado");
                console.log(data);
            }
            // Call your server to finalize the transaction
        }).render('#paypal-button-container');
    </script>
</body>

</html>