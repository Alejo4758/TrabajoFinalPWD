<?php
session_start();
// Dependencias
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

// Cargar el autoloader de Composer y config.php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/config.php';

// Configurar Doctrine
$paths = [__DIR__ . '/../Modelo']; // La ruta a las entidades
$isDevMode = true; // Para pruebas

// Parametros de la base de datos
$dbParametros = [
    'driver'   => 'pdo_mysql',
    'host'     => $db_servidor,
    'user'     => $db_usuario,
    'password' => $db_password,
    'dbname'   => $db_nombre
];

// Inicialización de configuración y conexión
$config = ORMSetup::createAttributeMetadataConfiguration($paths, $isDevMode);
$conexion = DriverManager::getConnection($dbParametros, $config);

// Creamos la entidad manager para que otro archivos lo usen
$entidadManager =  new EntityManager($conexion, $config);