<?php
require_once __DIR__ . '/../config/bootstrap.php';
require_once __DIR__ . '/../config/config.php';

// Traer la clase Sesion (JWT)
use Perfumeria\Control\Sesion;

// Instanciar y Validar la Sesion
$sesion = new Sesion(claveSecreta, $entidadManager);
$sesion->validar();
?>
<!-- Metadatos básicos -->
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
  
<!-- SEO -->
<title>Perfumería Tu Esencia</title>
<meta name="description" content="Perfumería Tu Esencia - Fragancias exclusivas y cosmética de calidad.">
<meta name="keywords" content="perfumería, fragancias, cosmética, belleza, tu esencia">
<meta name="author" content="Tu Esencia">

<!-- Favicon -->
<link rel="icon" href="img/favicon.ico" type="image/x-icon">

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

<!-- Estilos propios -->
<link rel="stylesheet" href="../Vista/css/estilos.css">

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
<script src="../Vista/js/validaciones.js"></script>