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
                <a class="nav-link active" aria-current="page"href="#">Productos</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../Control/controladorGet.php?accion=inicioUsuario&vista=marcas">Marcas</a>
            </li>
        </ul>
    </div>

    <div class="container align-items-center">
        <div class="row justify-content-center">

                    <?php foreach ($productos as $producto):                     
                        // --- LÓGICA DE IMAGEN (Igual al Index) ---
                        // 1. Imagen por defecto
                        $srcImagen = "../img/logo.png"; 
                        
                        // 2. Si tiene adjuntos, usamos la real
                        if (!$producto->getAdjuntos()->isEmpty()) {
                            $srcImagen = $producto->getAdjuntos()->first()->getRutaUrl();
                        }
                    ?>

                       <div class="col-md-6 col-lg-3 mb-4">
                            <div class="card-producto">

                                <div style="position: relative;">
                                    <img src="<?= $srcImagen ?>" 
                                        class="card-img-top mx-auto d-block" 
                                        alt="<?= htmlspecialchars($producto->getNombre()) ?>"
                                        style="height: 250px; object-fit: contain;">
                                        
                                    <?php if ($producto->getStock() <= 0): ?>
                                        <span class="badge bg-danger position-absolute top-0 end-0 m-2">Agotado</span>
                                    <?php endif; ?>
                                </div>

                                <div class="card-body d-flex flex-column">
                                        <h5 class="card-title text-dark text-decoration-none">
                                            <?= htmlspecialchars($producto->getNombre()) ?>
                                        </h5>
                                        
                                        <p class="card-text text-muted small flex-grow-1">
                                            <?= substr(htmlspecialchars($producto->getDescripcion() ?? ''), 0, 60) ?>...
                                        </p>
                                        
                                        <div class="mt-auto text-center">
                                            <div class="fs-5 fw-bold text-primary mb-3">
                                                $<?= number_format($producto->getPrecio(), 2) ?>
                                            </div>

                                            <div class="d-grid gap-2 text-center">
                                                <a href="../Control/controladorGet.php?accion=verProducto&id=<?= $producto->getIdProducto() ?>" 
                                                class="btn-ver-detalle">
                                                    Ver Detalle
                                                </a>

                                                <?php if ($producto->getStock() > 0): ?>
                                                    <form action="../Control/controladorPost.php" method="POST" class="d-block w-100">
                                                        <input type="hidden" name="accion" value="agregarAlCarrito">
                                                        <input type="hidden" name="idProducto" value="<?= $producto->getIdProducto() ?>">
                                                        <button type="submit" class="btn-comprar">
                                                            <i class="bi bi-cart-plus"></i> Agregar
                                                        </button>
                                                    </form>
                                                <?php else: ?>
                                                    <button class="btn btn-secondary w-100" disabled>Sin Stock</button>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                </div>
        </div>

</main>
        
<?php include_once __DIR__ . '/../includes/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>