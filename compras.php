<?php
require 'config/config.php';
require 'clases/clienteFunciones.php';

$db = new Database();
$con = $db->conectar();

$token = generarToken();
$_SESSION['token'] = $token;

if (!isset($_SESSION['user_cliente'])) {
    header("Location: login.php");
    exit;
}

$id_cliente = $_SESSION['user_cliente'];

// Preparar y ejecutar el procedimiento almacenado
$sql = $con->prepare("SELECT id_transaccion, fecha, status, 
total FROM compra WHERE id_cliente = ? ORDER BY DATE(fecha) DESC");
$sql->execute([$id_cliente]);

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
            <h4>Mis compras:</h4>
            <hr>
            <?php while($row = $sql->fetch(PDO::FETCH_ASSOC)) { ?>
                <div class="card border-primary mb-3">
                    <div class="card-header">
                        <?php echo $row['fecha']; ?>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Folio de su compra: <?php echo $row['id_transaccion']; ?></h5>
                        <p class="card-text">El total de su compra fue de: <?php echo $row['total']; ?></p>
                        <a href="compra_detalle.php?orden=<?php echo $row['id_transaccion']; ?>&token=<?php echo $token; ?>" class="btn btn-primary">Detalles de la compra</a>
                    </div>
                </div>
            <?php } ?>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
            crossorigin="anonymous"></script>
</body>
</html>
