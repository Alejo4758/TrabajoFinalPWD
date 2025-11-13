<?php
// Tipado estricto
declare(strict_types=1);

/**
 * ----------------------------------------------------------------
 *                CONFIGURACIÓN DE SEGURIDAD 🔑
 * ----------------------------------------------------------------
 * Clave secreta para firmar los JSON Web Tokens (JWT).
 * DEBE SER UNA CADENA LARGA, ALEATORIA Y SECRETA EN PRODUCCIÓN
*/
define('claveSecreta', "mi_clave_secreta_super_segura_para_el_proyecto_final");


/**
 * ----------------------------------------------------------------
 *          CONFIGURACIÓN DE LA BASE DE DATOS 💾
 * ----------------------------------------------------------------
 * Datos para conectar base de datos MySQL.
*/
$db_servidor = "localhost";
$db_nombre = "perfumeria";
$db_usuario = "root";
$db_password = "";


/**
 * ----------------------------------------------------------------
 *         CONFIGURACIÓN DE ERRORES (PARA DESARROLLO) 🐞
 * ----------------------------------------------------------------
 * Estas líneas fuerzan a PHP a mostrar todos los errores.
 * Son muy útiles para depurar, pero deberían desactivarse
 * en un sitio en producción por seguridad.
*/
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);