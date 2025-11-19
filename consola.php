<?php
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Console\EntityManagerProvider\SingleManagerProvider;

// 1. Carga el autoloader
require_once 'vendor/autoload.php';

// 2. Carga tu archivo de configuración
// (Ajusta la ruta si bootstrap.php no está dentro de una carpeta 'config')
require_once 'config/bootstrap.php'; 

// 3. ¡Aquí está el truco! 
// Pasamos TU variable '$entidadManager' al proveedor de Doctrine.
ConsoleRunner::run(
    new SingleManagerProvider($entidadManager)
);