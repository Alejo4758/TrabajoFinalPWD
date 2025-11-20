<!DOCTYPE html>
<html lang="es">
<head>
    <?php include_once __DIR__ . '/../includes/head.php'; ?>
</head>
<body>
    <?php include_once __DIR__ . '/../includes/header.php'; ?>

    <main class="container my-5">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Detalle del Pedido #<?= $pedido->getIdPedido() ?></h2>
            <a href="../Control/controladorGet.php?accion=verHistorial" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Volver al Historial
            </a>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Items Comprados</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Producto</th>
                                    <th>Precio Unit.</th>
                                    <th>Cant.</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($pedido->getItemsProducto() as $item): 
                                    $producto = $item->getProducto();
                                ?>
                                <tr>
                                    <td>
                                        <span class="fw-bold"><?= htmlspecialchars($producto->getNombre()) ?></span>
                                        <br>
                                        <small class="text-muted">Ref: <?= htmlspecialchars($producto->getCodigoReferencia()) ?></small>
                                    </td>
                                    <td>$<?= number_format($item->getPrecio(), 2) ?></td>
                                    <td><?= $item->getCantidad() ?></td>
                                    <td class="text-end fw-bold">
                                        $<?= number_format($item->getPrecio() * $item->getCantidad(), 2) ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Información del Pedido</h5>
                        
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between px-0">
                                <span>Fecha:</span>
                                <strong><?= $pedido->getFechaPedido()->format('d/m/Y H:i') ?></strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between px-0">
                                <span>Estado:</span>
                                <?php 
                                    $estado = $pedido->getEstado();
                                    $badge = match($estado) {
                                        'PAGADO' => 'estado-pagado',
                                        'ENTREGADO' => 'estado-entregado',
                                        'CANCELADO' => 'estado-cancelado',
                                        default => 'bg-secondary'
                                    };
                                ?>
                                <span class="badge <?= $badge ?>"><?= $estado ?></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between px-0">
                                <span>Método de Envío:</span>
                                <strong><?= htmlspecialchars($pedido->getMetodoEnvio()) ?></strong>
                            </li>
                            <li class="list-group-item px-0">
                                <span>Dirección de Entrega:</span><br>
                                <small class="text-muted"><?= htmlspecialchars($pedido->getDireccion()) ?></small>
                            </li>
                            
                            <hr>

                            <li class="list-group-item d-flex justify-content-between px-0">
                                <span>Costo Envío:</span>
                                <span>$<?= number_format($pedido->getCostoEnvio(), 2) ?></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between px-0 fs-5">
                                <strong>Total Pagado:</strong>
                                <strong class="text-success">$<?= number_format($pedido->getMontoTotal(), 2) ?></strong>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include_once __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>