<?php
require 'config/config.php';
$db = new Database();
$con = $db->conectar();

// Validar valores de GET
$idCategoria = isset($_GET['cat']) ? intval($_GET['cat']) : null;
$orden = isset($_GET['orden']) ? $_GET['orden'] : '';
$buscar = isset($_GET['q']) ? trim($_GET['q']) : '';

// Llamar al procedimiento almacenado para obtener productos
$sql = $con->prepare("CALL sp_selprodu_jesus(?, ?, ?)");
$sql->execute([$idCategoria, $buscar, $orden]);
$resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
$sql->closeCursor();  // Liberar la consulta para evitar errores

// Obtener categorías activas
$sqlCategorias = $con->prepare("CALL sp_selcat_jesus()");
$sqlCategorias->execute();
$categorias = $sqlCategorias->fetchAll(PDO::FETCH_ASSOC);
$sqlCategorias->closeCursor();  // Liberar la consulta

?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Catálogo</title>
  <link rel="shortcut icon" href="CSS/autos.png" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://kit.fontawesome.com/2f26ebf69b.js" crossorigin="anonymous"></script>
  <link href="CSS/estilocat.css" rel="stylesheet">
</head>

<body class="d-flex flex-column h-100">
  <?php include 'menu.php'; ?>

  <main class="flex-shrink-0">
    <div class="container">
      <div class="row">
        <div class="col-3">
          <div class="card shadow-sm">
            <div class="card-header">Categorías:</div>
            <div class="list-group">
              <a href="catalogo.php" class="list-group-item list-group-item-action">Mostrar todo</a>
              <?php foreach ($categorias as $categoria) { ?>
                <a href="catalogo.php?cat=<?php echo $categoria['id_cat']; ?>" class="list-group-item list-group-item-action 
                  <?php if ($idCategoria == $categoria['id_cat']) echo 'active'; ?>">
                  <?php echo htmlspecialchars($categoria['nombre']); ?>
                </a>
              <?php } ?>
            </div>
          </div>
        </div>

        <div class="col-12 col-md-9">
          <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 justify-content-end g-4">
            <div class="col-mb-2">
              <form action="catalogo.php" id="ordenForm" method="get">
                <input type="hidden" name="cat" id="cat" value="<?php echo $idCategoria; ?>">
                <select name="orden" id="orden" class="form-select form-select-sm" onchange="submitForm()">
                  <option value="">Ordenar por:</option>
                  <option value="precio-alto" <?php echo ($orden === 'precio-alto') ? 'selected' : ''; ?>>De mayor precio</option>
                  <option value="precio-bajo" <?php echo ($orden === 'precio-bajo') ? 'selected' : ''; ?>>De menor precio</option>
                  <option value="asc" <?php echo ($orden === 'asc') ? 'selected' : ''; ?>>De A-Z</option>
                  <option value="desc" <?php echo ($orden === 'desc') ? 'selected' : ''; ?>>De Z-A</option>
                </select>
              </form>
            </div>
          </div>

          <hr>
          <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
            <?php foreach ($resultado as $row) { ?>
              <div class="col mb-2">
                <div class="card border-primary shadow-sm h-100">
                  <?php
                  $id = $row['id_pieza'];
                  $imagen = "imagenes1/productos/" . $id . "/principal.jpg";
                  if (!file_exists($imagen)) {
                    $imagen = "imagenes1/noimg.png";
                  }
                  ?>
                  <img src="<?php echo $imagen; ?>" width="260px" height="240px">
                  <div class="card-body">
                    <h5 class="card-title"><?php echo htmlspecialchars($row['nombre_pieza']); ?></h5>
                    <p class="card-text"><?php echo htmlspecialchars($row['codigo']); ?></p>
                    <p class="card-text"><?php echo htmlspecialchars($row['descripcion']); ?></p>
                    <p class="card-text"><?php echo htmlspecialchars($row['marca']); ?></p>
                    <p class="card-text">$ <?php echo number_format($row['precio'], 2, '.', ','); ?></p>

                    <hr>
                    <div class="d-flex justify-content-between align-items-center">
                      <div class="btn-group">
                        <a href="detalle.php?id_pieza=<?php echo $row['id_pieza']; ?>&token=<?php echo hash_hmac(
                                                                                              'sha1',
                                                                                              $row['id_pieza'],
                                                                                              KEY_TOKEN
                                                                                            ); ?>" class="btn btn-primary">Detalles</a>
                      </div>
                      <button class="btn btn-outline-success" type="button"
                        onclick="addProducto(<?php echo $row['id_pieza']; ?>, 
                                '<?php echo hash_hmac('sha1', $row['id_pieza'], KEY_TOKEN); ?>')">Agregar al carrito</button>
                    </div>
                  </div>
                </div>
              </div>
            <?php } ?>
          </div>
        </div>
      </div>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    </div>
  </main>

  <script>
    /* Función para agregar productos al carrito */
    function addProducto(id, token) {
      let url = 'clases/carrito.php';
      let formData = new FormData();
      formData.append('id', id);
      formData.append('token', token);

      fetch(url, {
          method: 'POST',
          body: formData,
          mode: 'cors'
        }).then(response => response.json())
        .then(data => {
          if (data.ok) {
            let elemento = document.getElementById("num_cart");
            elemento.innerHTML = data.numero;
          } else {
            alert("No hay suficientes productos en stock");
          }
        })
    }

    /* Función para enviar formulario de ordenamiento */
    function submitForm() {
      document.getElementById('ordenForm').submit();
    }
  </script>

</body>
</html>
