<?php
// Cargo configuración y motor ORM
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/bootstrap.php';

// Dependencias
use Perfumeria\Control\Sesion;

// Inicializo clase sesion con $sesion
$sesion = new Sesion(claveSecreta, $entidadManager);
$sesion->cerrar(); // Cierra la sesión

// Redirigimos al login
header("Location: index.php");
exit();