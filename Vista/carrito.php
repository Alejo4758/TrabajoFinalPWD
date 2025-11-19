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
                <a class="nav-link"href="productos.php">Productos</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="marcas.php">Marcas</a>
            </li>
        </ul>
    </div>

    <div class="container-carrito d-flex flex-column w-50 mx-auto text-center align-items-center justify-content-center">
        <h1 class="mb-4">Mi Carrito de Compras</h1>

        <?php 
            if (isset($_SESSION['mensaje'])) {
                echo "<div class='alert alert-danger'>" . $_SESSION['mensaje'] . "</div>";
                unset($_SESSION['mensaje']); // Limpiar para que no se repita
            }
            if (!$carrito || $itemsDelCarrito->isEmpty()): ?>
            
            <div class="alert alert-info text-center">
                <p class="h4">Tu carrito está vacío.</p>
                <a href="index.php" class="btn btn-primary mt-2">Seguir comprando</a>
            </div>

        <?php else: ?>
            <div class="row">
                <div class="col-lg-8">
                    <div class="card shadow-sm">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col">Producto</th>
                                        <th scope="col">Precio</th>
                                        <th scope="col">Cantidad</th>
                                        <th scope="col">Total</th>
                                        <th scope="col">Quitar/Agregar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($itemsDelCarrito as $item): 
                                        $producto = $item->getProducto(); ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <span class="ms-2 fw-bold">
                                                        <?= htmlspecialchars($producto->getNombre()) ?>
                                                    </span>
                                                </div>
                                            </td>
                                            <td>$<?= number_format($item->getPrecio(), 2) ?></td>
                                            <td><?= $item->getCantidad() ?></td>
                                            <td>$<?= number_format($item->getPrecio() * $item->getCantidad(), 2) ?></td>
                                            <td style="min-width: 140px;">
                                                <div class="d-flex align-items-center gap-1">
                                                    
                                                    <form action="controladorPost.php" method="POST" class="d-inline">
                                                        <input type="hidden" name="accion" value="agregarAlCarrito">
                                                        <input type="hidden" name="idProducto" value="<?= $producto->getIdProducto() ?>">
                                                        <button type="submit" class="btn btn-success btn-sm" title="Sumar unidad">
                                                            <i class="bi bi-plus-lg"></i>
                                                        </button>
                                                    </form>

                                                    <form action="controladorPost.php" method="POST" class="d-inline">
                                                        <input type="hidden" name="accion" value="restarItem">
                                                        <input type="hidden" name="idItem" value="<?= $item->getIdItem() ?>">
                                                        <button type="submit" class="btn btn-warning btn-sm text-white" title="Restar unidad" <?= $item->getCantidad() <= 1 ? 'disabled' : '' ?>>
                                                            <i class="bi bi-dash-lg"></i>
                                                        </button>
                                                    </form>

                                                    <form action="../Control/controladorPost.php" method="POST" class="d-inline">
                                                        <input type="hidden" name="accion" value="eliminarItem">
                                                        <input type="hidden" name="idItem" value="<?= $item->getIdItem() ?>">
                                                        <button type="submit" class="btn btn-danger btn-sm" title="Eliminar del carrito">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                    
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 mt-3 mt-lg-0">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Resumen del Pedido</h5>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Subtotal</span>
                                    <strong>$<?= number_format($subtotal, 2) ?></strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Costo de Envío</span>
                                    <strong>$<?= number_format($costoEnvio, 2) ?>(estandar)</strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center fs-5">
                                    <strong>Total</strong>
                                    <strong class="text-success">$<?= number_format($total, 2) ?></strong>
                                </li>
                            </ul>
                            <a href="controladorGet.php?accion=verCheckout" class="btn btn-success w-100 mt-3">Finalizar Compra</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</main>
        

<?php include_once '../includes/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>