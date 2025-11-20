<!DOCTYPE html>
<html lang="es">
<head>
    <?php include_once '../includes/head.php'; ?>
</head>
<body>
    <?php include_once '../includes/header.php'; ?>

    <main class="container my-5">
        
        <a href="../Control/controladorGet.php?accion=index" class="btn btn-outline-secondary mb-4">
            <i class="bi bi-arrow-left"></i> Volver al catálogo
        </a>

        <?php if (isset($_SESSION['mensaje'])): ?>
            <div class="alert alert-info">
                <?= $_SESSION['mensaje']; unset($_SESSION['mensaje']); ?>
            </div>
        <?php endif; ?>

        <div class="card shadow-sm border-0">
            <div class="row g-0">
                
                <div class="col-md-6 bg-light d-flex align-items-center justify-content-center p-5">
                    
                    <?php if (!$producto->getAdjuntos()->isEmpty()): ?>
                        <img src="<?= $producto->getAdjuntos()->first()->getRutaUrl() ?>" class="img-fluid rounded" ...>
                    <?php else: ?>
                        <img src="../img/producto" class="img-fluid rounded" ...>
                    <?php endif; ?>
                </div>

                <div class="col-md-6">
                    <div class="card-body p-4 p-md-5">
                        
                        <div class="text-uppercase tracking-wide text-muted small fw-bold mb-2">
                            <?= htmlspecialchars($producto->getCategoria()->getNombre()) ?> &bull; <?= htmlspecialchars($producto->getMarca()->getNombre()) ?>
                        </div>

                        <h1 class="display-6 fw-bold mb-3 text-dark">
                            <?= htmlspecialchars($producto->getNombre()) ?>
                        </h1>

                        <div class="fs-2 fw-bold text-primary mb-4">
                            $<?= number_format($producto->getPrecio(), 2) ?>
                        </div>

                        <p class="lead text-muted mb-4">
                            <?= nl2br(htmlspecialchars($producto->getDescripcion() ?? 'Sin descripción disponible.')) ?>
                        </p>

                        <div class="mb-4">
                            <?php if ($producto->getStock() > 0): ?>
                                <span class="activo p-2">En Stock (<?= $producto->getStock() ?> disponibles)</span>
                            <?php else: ?>
                                <span class="inactivo p-2">Agotado</span>
                            <?php endif; ?>
                        </div>

                        <hr>
                        
                        <?php if ($producto->getStock() > 0): ?>
                            <form action="../Control/controladorPost.php" method="POST" class="d-grid gap-2">
                                <input type="hidden" name="accion" value="agregarAlCarrito">
                                <input type="hidden" name="idProducto" value="<?= $producto->getIdProducto() ?>">
                                
                                <button type="submit" class="btn-comprar">
                                    <i class="bi bi-cart-plus me-2"></i> Agregar al Carrito
                                </button>
                            </form>
                        <?php else: ?>
                            <button class="btn btn-secondary btn-lg w-100" disabled>No disponible</button>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include_once '../Includes/footer.php'; ?>
</body>
</html>