<?php 
require_once 'config/config.php';
require_once 'config/database.php';
require_once 'clases/clienteFunciones.php';

$id = isset($_GET['id_cliente']) ? $_GET['id_cliente'] : '';
$token = isset($_GET['token']) ? $_GET['token'] : '';

if($id == '' || $token == ''){
    header("Location: catalogo.php");
    exit;
}


$db = new Database();
$con = $db->conectar();

echo validaToken($id, $token, $con);


?>