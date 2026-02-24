<?php
require 'config/config.php';
$db = new Database();
$con = $db->conectar();

$id = isset($_GET['id_pieza']) ? $_GET['id_pieza'] : '';
$token = isset($_GET['token']) ? $_GET['token'] : '';

if ($id == '' || $token == '') {
    echo 'Error al procesar la petición';
    exit;
}

$token_tmp = hash_hmac('sha1', $id, KEY_TOKEN);

if ($token == $token_tmp) {
    // Verificar si el producto está activo
    $sql = $con->prepare("CALL sp_sel_prod_activo_jesus(?, @existe)");
    $sql->execute([$id]);

    // Obtener el valor de @existe
    $result = $con->query("SELECT @existe AS existe");
    $row = $result->fetch(PDO::FETCH_ASSOC);
    if ($row['existe'] > 0) {
        // Obtener datos del producto
        $sql = $con->prepare("CALL sp_sel_prodDetalle_jesus(?)");
        $sql->execute([$id]);
        $row = $sql->fetch(PDO::FETCH_ASSOC);

        $nombre = $row['nombre_pieza'];
        $codigo = $row['codigo'];
        $descripcion = $row['descripcion'];
        $marca = $row['marca'];
        $precio = $row['precio'];
        $spin = $row['spin'];

        // Verificar imágenes
        $dir_imagenes = 'imagenes1/productos/' . $id . '/';
        $rutaImg = $dir_imagenes . 'principal.jpg';

        if (!file_exists($rutaImg)) {
            $rutaImg = 'imagenes1/noimg.png';
        }

        $imagenes1 = [];
        if (file_exists($dir_imagenes)) {
            $dir = dir($dir_imagenes);
            while (($archivo = $dir->read()) !== false) {
                if ($archivo != 'principal.jpg' && (strpos($archivo, 'jpg') || strpos($archivo, 'jpeg') || strpos($archivo, 'png'))) {
                    $imagenes1[] = $dir_imagenes . $archivo;
                }
            }
            $dir->close();
        }
    } else {
        echo 'Error al procesar la petición';
    }
} else {
    echo 'Error al procesar la petición';
}



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
  <link href="CSS/estilocat.css" rel="stylesheet">
</head>

<body>
  <?php include 'menu.php'; ?>


  <main>
    <div class="container">
      <div class="row">
        <div class="col-md-6 order-md-1">

          <div id="carouselImages" class="carousel slide">
            <div class="carousel-inner">
              <div class="carousel-item active">
                <img src="<?php echo $rutaImg; ?>" class="d-block w-100">
              </div>

              <?php foreach ($imagenes1 as $img) { ?>
                <div class="carousel-item">
                  <img src="<?php echo $img; ?>" class="d-block w-100">
                </div>
              <?php } ?>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselImages" data-bs-slide="prev">
              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselImages" data-bs-slide="next">
              <span class="carousel-control-next-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Next</span>
            </button>
          </div>

          

          <div class="Sirv" data-src="<?php echo $spin; ?>"></div>
          <script src="https://scripts.sirv.com/sirvjs/v3/sirv.js"></script>
        </div>
        <div class="col-md-6 order-md-2">
          <h2><?php echo $nombre; ?></h2>
          <h2> <?php echo MONEDA . number_format($precio, 2, '.', ','); ?></h2>
          <p class="lead">
            <?php echo $codigo; ?>
          </p>
          <p class="lead">
            <?php echo $descripcion; ?>
          </p>
          <p class="lead">
            <?php echo $marca; ?>
          </p>

          <div class="col-3 my-3">
            Cantidad:<input class="form-control" id="cantidad" name="cantidad"
              type="number" min="1" max="10" value="1">
          </div>

          <div class="d-grid gap-3 col-10 mx auto">
            <button class="btn btn-outline-primary" type="button" onclick="addProducto(<?php echo
                                                                                        $id; ?>, cantidad.value, '<?php echo $token_tmp; ?>')">Agregar al carrito</button>

          </div>
        </div>

      </div>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
      crossorigin="anonymous"></script>


    <script>
      function addProducto(id, cantidad, token) {
        let url = 'clases/carrito.php'
        let formData = new FormData()
        formData.append('id', id)
        formData.append('cantidad', cantidad)
        formData.append('token', token)

        fetch(url, {
            method: 'POST',
            body: formData,
            mode: 'cors'
          }).then(response => response.json())
          .then(data => {
            if (data.ok) {
              let elemento = document.getElementById("num_cart")
              elemento.innerHTML = data.numero
            } else {
              alert("No hay suficientes productos en stock")
            }
          })
      }
    </script>
</body>

</html>