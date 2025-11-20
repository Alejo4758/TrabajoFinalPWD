<!DOCTYPE html>
<html lang="es">
<head>
<?php include_once __DIR__ . '/../includes/head.php'; ?>
</head>
<body class="d-flex flex-column min-vh-100">
<?php include_once __DIR__ . '/../includes/header.php'; ?>

<!-- MAIN CONTENT -->
<main>
    <div class="menu">
        <ul class="nav nav-underline">
            <li class="nav-item">
                <a class="nav-link" href="../Control/controladorGet.php?accion=inicioUsuario&vista=inicio">Inicio</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../Control/controladorGet.php?accion=inicioUsuario&vista=productosUser">Productos</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" aria-current="page"href="#">Marcas</a>
            </li>
        </ul>
    </div>

  <section class="perfume-brands text-center">
        <h2>Marcas de Perfumes Disponibles</h5>
        <div class="brands-container">
          <div class="row justify-content-center">
        <?php foreach ($marcas as $marca): 
          $srcLogo = "../img/logo.png"; // puedes personalizar esto si tienes logos reales
        ?>
          <div class="col-6 col-md-4 col-lg-4 mb-4">
            <div class="brand-card p-3 shadow-sm border rounded text-center h-100">
              <img src="<?= $srcLogo ?>" 
                   alt="<?= htmlspecialchars($marca->getNombre()) ?>" 
                   class="brand-logo mb-2 mx-auto d-block">
              <p class="fw-semibold"><?= htmlspecialchars($marca->getNombre()) ?></p>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
        </div>
  </section>
</main>
        
<?php include_once __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>