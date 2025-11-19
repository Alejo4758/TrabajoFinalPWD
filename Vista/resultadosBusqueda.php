<!DOCTYPE html>
<html lang="es">
<head>
    <?php include_once __DIR__ . '/../includes/head.php'; ?>
</head>
<body class="d-flex flex-column min-vh-100">
    <?php include_once __DIR__ . '/../includes/header.php'; ?>

    <h1>Resultados para "<?= htmlspecialchars($terminoDeBusqueda) ?>"</h1>
    <hr>

    <div class="row">
        <?php if (empty($productos)): ?>
            <div class="col-12">
                <p class="alert alert-warning">No se encontraron productos que coincidan con tu búsqueda.</p>
            </div>
        <?php else: ?>
            
            <?php foreach ($productos as $producto): ?>
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($producto->getNombre()) ?></h5>
                            <p class="card-text"><?= htmlspecialchars($producto->getDescripcion()) ?></p>
                            <div class="d-flex justify-content-center gap-2">
                                <!-- Boton VER PRODUCTO -->
                                <a href="../Control/controladorGet.php?accion=verProducto&id=<?= $producto->getIdProducto() ?>"
                                    class="btn btn-primary">
                                    Ver Producto
                                </a>

                                <!-- Boton AGREGAR CARRITO -->
                                <form action="../Control/controladorPost.php" method="POST">
                                    <input type="hidden" name="accion" value="agregarAlCarrito">
                                    <input type="hidden" name="idProducto" value="<?= $producto->getIdProducto() ?>">
                                    
                                    <button type="submit" class="btn btn-success">
                                        Agregar al Carrito
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

        <?php endif; ?>
    </div>

    <?php include_once __DIR__ . '/../includes/footer.php'; ?>
    </body>
</html>