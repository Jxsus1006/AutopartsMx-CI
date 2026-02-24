<?php

//clase de inicio de sesion del administrador
//traer configuracion de la base de datos
require 'config/config.php';
require 'config/database.php';
require 'clases/adminFunciones.php';

$db = new Database();
$con = $db->conectar();

/*$password = password_hash('admin', PASSWORD_DEFAULT);
$sql = "INSERT INTO administrador (usuario, password, nombre, correo, activo, fecha_alta)
VALUES ('admin', '$password', 'Administrador', 'rorschach115935@gmail.com', '1', NOW())";
$con->query($sql);*/

$errors = [];
if (!empty($_POST)) {
    $usuario = trim($_POST['usuario']);
    $password = trim($_POST['password']);

    if (esNulo([$usuario, $password])) {
        $errors[] = "Debe llenar todos los campos";
    }

    if (count($errors) == 0) {
        $errors[] = login($usuario, $password, $con);
    }
}
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../Imagenes/Logo.jpeg" />
    <title>Autoparts mx</title>
    <link rel="stylesheet" href="CSS/inicioA.css">
    <script src="inicioA.js" defer></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css"
        rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3"
        crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Enlace a Font Awesome -->
</head>

<body onload="greetUser()">
    <header>
        <div class="navbar">
            
        </div>
    </header>

    <section class="banner">
        <div class="banner-content m-auto pt-4">
            <img src="css/autos.png"  width="2500px" class="logo-img">
            <main class="form-login">
                <h2>Iniciar sesión administrador</h2>

                <?php mostrarMensajes($errors); ?>

                <form class="row g-3" action="index.php" method="post" autocomplete="off">

                    <div class="form-floating">
                        <input class="form-control" type="text" name="usuario" id="usuario" placeholder="Ingresa tu usuario" autofocus>
                        <label for="usuario">Usuario</label>
                    </div>

                    <div class="form-floating">
                        <input class="form-control" type="password" name="password" id="password" placeholder="Ingresa tu contraseña" >
                        <label for="password">Contraseña</label>
                    </div>

                    <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                        <a class="small" href="password.html">Forgot Password?</a><br>
                        <button type="submit" class="btn btn-primary">Ingresar</button>
                    </div>
                    <hr>
                </form>
            </main>
        </div>
    </section>


    <footer>
        <div class="footer-container">
            <div class="footer-links">
                <ul>
                    <li><a href="../index.html">Página principal</a></li>
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