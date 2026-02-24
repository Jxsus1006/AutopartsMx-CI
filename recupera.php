<?php
require 'config/config.php';
require 'clases/clienteFunciones.php';

// ESTE ARCHIVO SIRVE PARA LA RECUPERACIÓN DE LA CONTRASEÑA DEL USUARIO
//conexion a base de datos
$db = new Database();
$con = $db->conectar();


//arreglo donde se mostraran los errores posibles
$errors = [];

if (!empty($_POST)) {

    $correo = trim($_POST['correo']);


    if (esNulo([$correo,])) {
        $errors[] = "Debe llenar todos los campos";
    }

    if (!esEmail($correo)) {
        $errors[] = "Su correo no es valido";
    }

    if (count($errors) == 0) {
        if (emailExiste($correo, $con)) {
            $sql = $con->prepare("SELECT usuario.id_usuario, cliente.nombre FROM usuario 
            INNER JOIN cliente ON usuario.id_cliente=cliente.id_cliente WHERE cliente.correo LIKE ? LIMIT 1");
            $sql->execute([$correo]);
            $row = $sql->fetch(PDO::FETCH_ASSOC);
            $user_id = $row['id_usuario'];
            $nombre = $row['nombre'];

            $token = solicitaPassword($user_id, $con);

            if ($token !== null) {
                require 'clases/mailer.php';
                $mailer = new Mailer();

                $url = SITE_URL . '/negocio/reset_password.php?id_usuario=' . $user_id . '&token=' . $token;
                $asunto = "Recupera tu contraseña";
                $cuerpo = "Apreciable $nombre: <br> Si has solicitado el 
                 de tu contraseña, da clic en el siguiente link <a href='$url'>$url</a>.";
                $cuerpo .= "<br>Si no hiciste esta solicitud, favor de ignorar este correo.";

                if ($mailer->enviarEmail($correo, $asunto, $cuerpo)) {
                    echo "<p><b>Correo enviado</b></p>";
                    echo "<p>Hemos enviado un correo electrónico a la dirección $correo para restablecer su contraseña.</p>";
                    echo "<p>Asegúrate de revisar tu bandeja de spam.</p>";
                    exit;
                }
            }
        } else {
            $errors[] = "No existe una cuenta asociada a esta dirección de correo.";
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
                <img src="CSS/autos.png" alt="Diesel PR 2000 Logo" class="logo-img" id="animated-logo">
            </div>
            <nav>
            </nav>
            <div class="login-icon">
                <a href="registro.php" class="fa-solid fa-user"></a>
                <p>Registrar</p>
            </div>
        </div>
    </header>

    <section class="banner">
        <div class="banner-content">
            <img src="CSS/autos.png" alt="" width="2500px" class="logo-img">

            <main class="form-login m-auto pt-4">
                <h3>Recuperar contraseña</h3>

                <?php mostrarMensajes($errors); ?>

                <form action="recupera.php" method="post" class="row g-3" autocomplete="off">

                    <div class="form-floating">
                        <input class="form-control" type="email" name="correo" id="correo" placeholder="Correo electrónico" required>
                        <label for="correo">Correo electrónico</label>
                    </div>

                    <div class="d-grid gap-3 col-12">
                        <button type="submit" class="btn btn-primary">Solicitar cambio</button>
                    </div>

                    <div>
                        ¿No tiene cuenta? <a href="registro.php">Realiza tu registro aquí</a>
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
                <p>Administramos sus pedidos pendientes</p>
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