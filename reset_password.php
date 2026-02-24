<?php
require 'config/config.php';
require 'clases/clienteFunciones.php';

//validacion de exitencia de atributos por metodo GET POST
//si no existe id en get, buscará user_id por POST, SIENDO UNA VALIDACIÓN MULTIPLE.
$user_id = $_GET['id_usuario'] ?? $_POST['user_id'] ?? '';
$token = $_GET['token'] ?? $_POST['token'] ?? '';

if($user_id == '' || $token == ''){
    header("Location: ../presentacion/index.html");
    exit;
}

$db = new Database();
$con = $db->conectar();

$errors = [];

if(!verificaTokenRequest($user_id, $token, $con)){
    echo "No se pudo verificar la información";
    exit;

}

if (!empty($_POST)) {

    $password = trim($_POST['password']);
    $repassword = trim($_POST['repassword']);

    if (esNulo([$user_id, $token, $password, $repassword])) {
        $errors[] = "Debe llenar todos los campos";
    }

    if (!validaPassword($password, $repassword)) {
        $errors[] = "Las contraseñas no coinciden";
    }

    if(count($errors) == 0){
        $pass_hash = password_hash($password, PASSWORD_DEFAULT);
        if(actualizaPassword($user_id, $pass_hash, $con)){
            echo "Contraseña modificada exitosamente.<br><a href='login.php'>Iniciar sesión</a>";
            exit;
        }else{
            $errors[] = "Error al hacer el cambio de su contraseña. Favor de intentarlo nuevamente.";
        }
    }
}

?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            <div class="logo">
                <img src="CSS/autos.png" alt="Autoparts mx Logo" class="logo-img" id="animated-logo">
            </div>
            <nav>
                <ul>
                    <li><a href="../presentacion/index.html">Inicio</a></li>
                    <li><a href="catalogo.php">Catálogo</a></li>
                </ul>
            </nav>
            <div class="login-icon">
                <a href="#" class="fa-solid fa-user"></a>
                <p>Registrar</p>
            </div>
        </div>
    </header>

    <section class="banner">
        <div class="banner-content">
            <img src="CSS/autos.png" alt="Autoparts mx Logo" width="2500px" class="logo-img">
            
            



            <main class="form-login m-auto pt-4">
                <h3>Cambiar contraseña</h3>

                <?php mostrarMensajes($errors); ?>

                <form action="reset_password.php" method="post" class="row g-3" autocomplete="off">

                <input type="hidden" name="user_id" id="user_id" value="<?= $user_id; ?>" />
                <input type="hidden" name="token" id="token" value="<?= $token; ?>" />


                    <div class="form-floating">
                        <input class="form-control" type="password" name="password" id="password" placeholder="Nueva contraseña" required>
                        <label for="password">Nueva Contraseña</label>
                    </div>

                    <div class="form-floating">
                        <input class="form-control" type="password" name="repassword" id="repassword" placeholder="Confirmar contraseña" required>
                        <label for="repassword">Confirmar Contraseña</label>
                    </div>

                    <div class="d-grid gap-3 col-12">
                        <button type="submit" class="btn btn-primary">Cambiar contraseña</button>
                    </div>

                    <div>
                        <a href="login.php">Iniciar sesión</a>
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
                <p>Administrar sus pedidos pendientes</p>
            </div>
            <div class="feature-item">
                <a href="#" class="fa-solid fa-landmark"></a>
                <p>Ampliar su cartera de productos</p>
            </div>
            <div class="feature-item">
                <a href="#" class="fa-solid fa-cart-shopping"></a>
                <p>Descubre los productos más nuevos</p>
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