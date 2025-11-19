<!DOCTYPE html>
<html lang="es">
<head>
    <?php include_once __DIR__ . '/../includes/head.php'; ?>
</head>
<body>
    <?php include_once __DIR__ . '/../includes/header.php'; ?>

    <main class="container my-5">
        <h2 class="mb-4">Mis Pedidos</h2>

        <?php if (empty($historialPedidos)): ?>
            <div class="alert alert-info">
                Aún no has realizado ninguna compra. <a href="index.php">¡Ir a la tienda!</a>
            </div>
        <?php else: ?>
            
            <div class="table-responsive shadow-sm rounded">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th># Pedido</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                            <th>Método Envío</th>
                            <th>Total</th>
                            <th>Detalles</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($historialPedidos as $pedido): 
                            /** @var \BritosGab\PruebaseCommerce\entidades\Pedido $pedido */
                            
                            // Definir color del badge según estado
                            $badgeClass = match($pedido->getEstado()) {
                                'PAGADO' => 'bg-success',
                                'ENTREGADO' => 'bg-primary',
                                'CANCELADO' => 'bg-danger',
                                default => 'bg-secondary'
                            };
                        ?>
                            <tr>
                                <td class="fw-bold">#<?= $pedido->getIdPedido() ?></td>
                                
                                <td>
                                    <?= $pedido->getFechaPedido()->format('d/m/Y H:i') ?>
                                </td>
                                
                                <td>
                                    <span class="badge <?= $badgeClass ?>">
                                        <?= $pedido->getEstado() ?>
                                    </span>
                                </td>

                                <td><?= htmlspecialchars($pedido->getMetodoEnvio()) ?></td>
                                
                                <td class="fw-bold">
                                    $<?= number_format($pedido->getMontoTotal(), 2) ?>
                                </td>
                                
                                <td>
                                    <a href="../Control/controladorGet.php?accion=verDetallePedido&id=<?= $pedido->getIdPedido() ?>" class="btn btn-sm btn-primary">
                                        <i class="bi bi-eye"></i> Ver Items
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        <?php endif; ?>
    </main>

    <?php if (isset($_SESSION['pedido_creado_id'])): ?>
        
        <div class="modal fade" id="modalExito" tabindex="-1" aria-labelledby="modalExitoLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content text-center p-4">
                    <div class="modal-header border-0 justify-content-end">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3 text-success">
                            <i class="bi bi-check-circle-fill" style="font-size: 4rem;"></i>
                        </div>
                        <h3 class="mb-3">¡Compra Realizada!</h3>
                        <p class="text-muted">
                            Tu pedido <strong>#<?= $_SESSION['pedido_creado_id'] ?></strong> se ha procesado correctamente.
                        </p>
                        <div class="d-grid gap-2 mt-4">
                            <a href="../Control/controladorGet.php?accion=verDetallePedido&id=<?= $_SESSION['pedido_creado_id'] ?>" class="btn btn-primary">
                                Ver detalle del pedido
                            </a>
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                Cerrar y ver historial
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Seleccionamos el modal por su ID
                var myModal = new bootstrap.Modal(document.getElementById('modalExito'));
                // Lo mostramos
                myModal.show();
            });
        </script>

        <?php 
        // ¡IMPORTANTE! Borramos la variable para que el popup 
        // no vuelva a salir si el usuario recarga la página (F5).
        unset($_SESSION['pedido_creado_id']); 
        ?>

    <?php endif; ?>
    
    <?php include_once __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>