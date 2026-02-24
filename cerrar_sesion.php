<?php

//clase de cerrar sesion del administrador
require 'config/config.php';

unset($_SESSION['user_id']);
unset($_SESSION['user_name']);
unset($_SESSION['user_type']);

session_destroy();

header('Location: index.php');
