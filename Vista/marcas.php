<!DOCTYPE html>
<html lang="en">
<head>
<?php include_once '../includes/head.php'; ?>
</head>
<body class="d-flex flex-column min-vh-100">
<?php include_once '../includes/header.php'; ?>

<!-- MAIN CONTENT -->
<main>
    <div class="menu-usuario">
        <ul class="nav nav-underline">
            <li class="nav-item">
                <a class="nav-link" href="index.php">Inicio</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="productos.php">Productos</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" aria-current="page"href="#">Marcas</a>
            </li>
        </ul>
    </div>

  <section class="perfume-brands">
        <h2>Marcas de Perfumes Disponibles</h2>
        <div class="brands-container">
          <div class="brand">
            <img src="../logo.png" alt="Chanel">
            <p>Chanel</p>
          </div>
          <div class="brand">
            <img src="../logo.png" alt="Dior">
            <p>Dior</p>
          </div>
          <div class="brand">
            <img src="../logo.png" alt="Gucci">
            <p>Gucci</p>
          </div>
          <div class="brand">
            <img src="../logo.png" alt="Armani">
            <p>Armani</p>
          </div>
          <div class="brand">
            <img src="../logo.png" alt="Lancôme">
            <p>Lancôme</p>
          </div>
        </div>
  </section>
</main>
        
<?php include_once '../includes/footer.php'; ?>
</body>
</html>