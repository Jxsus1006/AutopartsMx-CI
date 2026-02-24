<?php
require 'config/config.php';
require 'clases/clienteFunciones.php';

$db = new Database();
$con = $db->conectar();

$proceso = isset($_GET['pago']) ? 'pago' : 'login';

$errors = [];

if (!empty($_POST)) {


    $usuario = trim($_POST['usuario']);
    $password = trim($_POST['password']);
    $proceso = $_POST['proceso'] ?? 'login';

    if (esNulo([$usuario, $password])) {
        $errors[] = "Debe llenar todos los campos";
    }

    if(count($errors) == 0){
        $errors[]= login($usuario, $password, $con, $proceso);
    }
 
}

?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="CSS/DieselPR-c.png" />
    <title>Autoparts mx</title>
    <link rel="stylesheet" href="CSS/inicioA.css">
    <script src="inicioA.js" defer></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css"
        rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3"
        crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Enlace a Font Awesome -->
</head>

<body>
    <header>
        <div class="navbar">

            <a href="../presentacion/index.html" class="navbar-brand">
                <strong style="color: white;">Autoparts mx</strong>
            </a>
            <nav>
                <ul>
                    <li><a href="../presentacion/index.html">Inicio</a></li>
                    <li><a href="catalogo.php">Catálogo</a></li>
                </ul>
            </nav>
            <div class="login-icon">
                <a href="registro.php" class="fa-solid fa-user"></a>
                <p>Registrar</p>
            </div>
        </div>
    </header>

    <section class="banner">
        <div class="banner-content m-auto pt-4">
            <img src="CSS/autos.png" alt="Autoparts mx Logo" width="2500px" class="logo-img">
            <main class="form-login">
                <h2>Iniciar sesión</h2>

                <?php mostrarMensajes($errors); ?>

                <form class="row g-3" action="login.php" method="post" autocomplete="off">

                    <input type="hidden" name="proceso" value="<?php echo $proceso; ?>">

                    <div class="form-floating">
                        <input class="form-control" type="text" name="usuario" id="usuario" placeholder="Ingresa tu usuario" required>
                        <label for="usuario">Usuario</label>
                    </div>

                    <div class="form-floating">
                        <input class="form-control" type="password" name="password" id="password" placeholder="Ingresa tu contraseña" required>
                        <label for="password">Contraseña</label>
                    </div>

                    <div class="col-12">
                        <a href="recupera.php">¿Olvidó su contraseña?</a>
                    </div>

                    <div class="d-grid gap-3 col-12">
                        <button type="submit" class="btn btn-primary">Ingresar</button>
                    </div>

                    <hr>

                    <div>
                        ¿No tiene cuenta? <a href="registro.php">Realiza tu registro aquí</a>
                    </div>
                    <div class="feature-item">
                        <a href="../datos/index.php" class="fa-solid fa-truck-fast"></a>
                        <p>Inicio administrador</p>
                    </div>
                </form>
            </main>


        
        </div>
    </section>

    <section class="features">
        <h2>Nuestras Características</h2>
        <div class="feature-container">
            <div class="feature-item">
                <a href="#" class="fa-solid fa-truck-fast"></a>
                <p>Atendemos sus pedidos rápidamente</p>
            </div>
            <div class="feature-item">
                <a href="#" class="fa-solid fa-landmark"></a>
                <p>Ampliamos su cartera de productos</p>
            </div>
            <div class="feature-item">
                <a href="#" class="fa-solid fa-cart-shopping"></a>
                <p>Descubra los productos más nuevos</p>
            </div>
        </div>
    </section>

    <footer>
        <div class="footer-container">
            <div class="footer-links">
                <ul>
                    <li><a href="../presentacion/index.html">Página principal</a></li>
                </ul>
            </div>
            <!-- Sección de redes sociales -->
            <div class="pie-pagina">
                <div class="grupo-1">
                    <div class="red-social">
                        <p class="social-title">Síguenos en nuestras redes sociales</p>
                        <div class="social-icons">
                            <a href="#" class="fab fa-youtube"></a>
                            <a href="#" class="fab fa-facebook"></a>
                            <a href="#" class="fab fa-instagram"></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <div id="greeting-message" class="greeting-message"></div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>

    </script>
</body>

</html>