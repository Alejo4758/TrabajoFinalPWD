<!DOCTYPE html>
<html lang="es">
<head>
    <?php include_once '../Includes/head.php'; ?>
</head>
<body class="d-flex flex-column min-vh-100">
    <?php include_once '../includes/header.php'; ?>

    <main class="container my-5">
        <h2 class="mb-4">Resultados para "<?= htmlspecialchars($terminoDeBusqueda) ?>"</h2>
        <hr>

        <div class="row">
            <?php if (empty($productos)): ?>
                <div class="col-12">
                    <div class="alert alert-warning text-center p-5">
                        <i class="bi bi-search" style="font-size: 3rem;"></i>
                        <p class="mt-3 h4">No encontramos productos que coincidan.</p>
                        <a href="../Control/controladorGet.php?accion=index" class="btn btn-outline-dark mt-2">Ver todo el catálogo</a>
                    </div>
                </div>
            <?php else: ?>
                
                <?php foreach ($productos as $producto):                     
                    // --- LÓGICA DE IMAGEN (Igual al Index) ---
                    // 1. Imagen por defecto
                    $srcImagen = "../img/producto-default.png"; 
                    
                    // 2. Si tiene adjuntos, usamos la real
                    if (!$producto->getAdjuntos()->isEmpty()) {
                        $srcImagen = $producto->getAdjuntos()->first()->getRutaUrl();
                    }
                ?>
                    <div class="col-md-4 col-lg-3 mb-4">
                        <div class="card h-100 shadow-sm border-0">
                            
                            <div style="position: relative;">
                                <img src="<?= $srcImagen ?>" 
                                     class="card-img-top p-3" 
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

            <?php endif; ?>
        </div>
    </main>

    <?php include_once '../includes/footer.php'; ?>
</body>
</html>