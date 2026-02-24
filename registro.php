<?php

//ESTE ARCHIVO SIRVE PARA EL REGISTRO DE CLIENTEs EN LA BASE DE DATOS
require 'config/config.php';
require 'clases/clienteFunciones.php';

$db = new Database();
$con = $db->conectar();

$errors = [];

if(!empty($_POST)){

    $nombre = trim($_POST['nombre']);
    $apellido_pat = trim($_POST['apellido_pat']);
    $apellido_mat = trim($_POST['apellido_mat']);
    $correo = trim($_POST['correo']);
    $telefono = trim($_POST['telefono']);
    $curp = trim($_POST['curp']);
    $usuario = trim($_POST['usuario']);
    $password = trim($_POST['password']);
    $repassword = trim($_POST['repassword']);

    if(esNulo([$nombre, $apellido_pat, $apellido_mat, $correo, $telefono, $curp, $usuario, $password, $repassword])){
        $errors[]= "Debe llenar todos los campos";
    }

    if(!esEmail($correo)){
        $errors[]= "Su correo no es valido";
    }

    if(!validaPassword($password, $repassword)){
        $errors[]= "Las contraseñas no coinciden";
    }

    if(usuarioExiste($usuario, $con)){
        $errors[]= "El nombre del usuario $usuario ya existe";
    }

    if(emailExiste($correo, $con)){
        $errors[]= "El correo ingresado $correo ya existe";
    }

    if(count($errors) == 0){

        $id= registraCliente([$nombre, $apellido_pat, $apellido_mat, $correo, $telefono, $curp], $con);

        if($id > 0){

            require 'clases/mailer.php';
            $mailer = new Mailer();
            $token = generarToken();

            $pass_hash = password_hash($password, PASSWORD_DEFAULT);

            $idUsuario = registraUsuario([$usuario, $pass_hash, $token, $id], $con);
            if($idUsuario > 0){
                $url = SITE_URL . '/negocio/activar_cliente.php?id_cliente=' . $idUsuario .'&token='.$token;
                $asunto = "Activa tu cuenta en Autoparts mx";
                $cuerpo = "Apreciable $nombre: <br> Para continuar con su proceso de registro es necesario que dé 
                clic en la siguiente liga <a href='$url'>Activar cuenta</a>";
                
                if($mailer->enviarEmail($correo, $asunto, $cuerpo)){
                    echo "Para terminar el proceso de registro, siga las instrucciones 
                    que le enviamos a su dirección de su correo electrónico $correo";

                    exit;
                }
            }else{
                $errors[] = "Error al registrar usuario";
            }

        }else{
        $errors[] = "Error al registrar cliente";
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

        </div>
    </header>

    <section class="banner">
        <div class="banner-content">
            <img src="CSS/autos.png" alt="Autoparts mx Logo" width="2500px" class="logo-img">
            <div class="container">
                <h2>Registrate</h2>
                
                <?php mostrarMensajes($errors); ?>

                <form class="row g-3" action="registro.php" method="post">
            <div class="col-md-6">
                <label for="nombre"><span class="text-danger">*</span> Nombre</label>
                <input type="text" name="nombre" id="nombre" class="form-control" requireda>
            </div>

            <div class="col-md-6">
                <label for="apellido_pat"><span class="text-danger">*</span> Apellido paterno</label>
                <input type="text" name="apellido_pat" id="apellido_pat" class="form-control" requireda>
            </div>

            <div class="col-md-6">
                <label for="apellido_mat"><span class="text-danger">*</span> Apellido materno</label>
                <input type="text" name="apellido_mat" id="apellido_mat" class="form-control" requireda>
            </div>

            <div class="col-md-6">
                <label for="correo"><span class="text-danger">*</span> Correo</label>
                <input type="email" name="correo" id="correo" class="form-control" requireda>
                <span id="validaEmail" class="text-danger"></span>
            </div>

            <div class="col-md-6">
                <label for="telefono"><span class="text-danger">*</span> Telefono</label>
                <input type="tel" name="telefono" id="telefono" class="form-control" requireda>
            </div>

            <div class="col-md-6">
                <label for="curp"><span class="text-danger">*</span> CURP</label>
                <input type="text" name="curp" id="curp" class="form-control" requireda>
            </div>

            <div class="col-md-6">
                <label for="usuario"><span class="text-danger">*</span> Usuario</label>
                <input type="text" name="usuario" id="usuario" class="form-control" requireda>
                <span id="validaUsuario" class="text-danger"></span>
            </div>

            <div class="col-md-6">
                <label for="password"><span class="text-danger">*</span> Contraseña</label>
                <input type="password" name="password" id="password" class="form-control" requireda>
            </div>

            <div class="col-md-6">
                <label for="repassword"><span class="text-danger">*</span> Repetir contraseña</label>
                <input type="password" name="repassword" id="repassword" class="form-control" requireda>
            </div>

            <i><b>Nota:</b> Los campos con * son obligatorios</i>

            <div class="col-6">
                <button type="submit" class="btn btn-primary">Registrarse</button>
            </div>

            
       </form>
            </div>
        </div>
    </section>

    <section class="features">
        <h2>Nuestras Características</h2>
        <div class="feature-container">
            <div class="feature-item">
                <a href="#" class="fa-solid fa-truck-fast"></a>
                <p>Administramos sus pedidos rápidamente</p>
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

<script>
    let txtUsuario = document.getElementById('usuario')
    txtUsuario.addEventListener("blur", function(){
        existeUsuario(txtUsuario.value)
    }, false)

    let txtEmail = document.getElementById('correo')
    txtEmail.addEventListener("blur", function(){
        existeEmail(txtEmail.value)
    }, false)

    function existeEmail(correo){

        let url = "clases/clienteAjax.php"
        let formData = new FormData()
        formData.append("action", "existeEmail")
        formData.append("correo", correo)

        fetch(url, {   //Hacemos la peticion Ajax declarando metodo POST y los datos que se enviaran por medio de body, devolviendo la respuesta en formato JSON
            method: 'POST',
            body: formData
        }).then(response => response.json())
        .then(data => {  //procesar la peticion
    
        if(data.ok){
            document.getElementById('correo').value = ''
            document.getElementById('validaEmail').innerHTML = 'Correo no disponible' //mensaje de validación de usuario

        }else {
            document.getElementById('validaEmail').innerHTML = '' //mensaje de validación de usuario
        }
        })
    }

    function existeUsuario(usuario){

        let url = "clases/clienteAjax.php"
        let formData = new FormData()
        formData.append("action", "existeUsuario")
        formData.append("usuario", usuario)

        fetch(url, {   //Hacemos la peticion Ajax declarando metodo POST y los datos que se enviaran por medio de body, devolviendo la respuesta en formato JSON
            method: 'POST',
            body: formData
        }).then(response => response.json())
        .then(data => {  //procesar la peticion
            
            if(data.ok){
                document.getElementById('usuario').value = ''
                document.getElementById('validaUsuario').innerHTML = 'Usuario no disponible' //mensaje de validación de usuario

            }else {
                document.getElementById('validaUsuario').innerHTML = '' //mensaje de validación de usuario
            }
        })
    }


</script>
</body>
</html>
