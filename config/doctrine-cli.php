<?php
// Dependencias
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Console\EntityManagerProvider\SingleManagerProvider;

// Carga la configuración del motor de Doctrine
require_once __DIR__ . '/bootstrap.php';

// Ejecutamos la consola
ConsoleRunner::run(
    new SingleManagerProvider($entidadManager)
);