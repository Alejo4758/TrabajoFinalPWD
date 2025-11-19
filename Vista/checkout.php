<!DOCTYPE html>
<html lang="es">
<head>
    <?php include_once __DIR__ . '/../includes/head.php'; ?>
</head>
<body>
    <?php include_once __DIR__ . '/../includes/header.php'; ?>

    <main class="container my-4">
        <h1 class="mb-4 text-center">Finalizar Compra</h1>

        <div class="row g-5">
            <!-- Columna izquierda: formulario -->
            <div class="col-lg-7">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4 p-md-5">
                        <h4 class="mb-3">Información de Facturación y Envío</h4>
                        
                        <form action="../Control/controladorPost.php" method="POST" class="needs-validation" novalidate>
                            <input type="hidden" name="accion" value="procesarPedido">
                            
                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <label for="nombre" class="form-label">Nombre</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" 
                                           value="<?= htmlspecialchars($usuario->getNombre()) ?>" required disabled>
                                    <div class="invalid-feedback">
                                        Se requiere un nombre válido.
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <label for="apellido" class="form-label">Apellido</label>
                                    <input type="text" class="form-control" id="apellido" name="apellido" 
                                           value="<?= htmlspecialchars($usuario->getApellido()) ?>" required disabled>
                                    <div class="invalid-feedback">
                                        Se requiere un apellido válido.
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           value="<?= htmlspecialchars($usuario->getEmail()) ?>" disabled>
                                    <div class="invalid-feedback">
                                        Por favor, ingrese un email válido.
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label for="direccion" class="form-label">Dirección de Envío</label>
                                    <input type="text" class="form-control" id="direccion" name="direccion" 
                                           value="<?= htmlspecialchars($usuario->getDireccion()) ?>" required>
                                    <div class="invalid-feedback">
                                        Se requiere una dirección de envío.
                                    </div>
                                </div>

                                <!-- AGREGAR JS PARA CAMBIAR EL VALOR SEGUN METODO DE ENVIO -->
                                <div class="col-12">
                                    <label for="metodoEnvio" class="form-label">Método de Envío</label>
                                    <select class="form-select" id="metodoEnvio" name="metodoEnvio" required>
                                        <option value="">Selecciona una opción...</option>
                                        <option value="Estandar">Envío Estándar ($2,500)</option>
                                        <option value="Express">Envío Express ($5,000)</option>
                                        <option value="Andreani">Andreani a Sucursal ($3,800)</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Por favor selecciona un método de envío.
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <h4 class="mb-3">Información de Pago (Simulado)</h4>

                            <div class="row gy-3">
                                <div class="col-md-12">
                                    <label for="cc-number" class="form-label">Número de Tarjeta</label>
                                    <input type="text" class="form-control" id="cc-number" 
                                           placeholder="XXXX XXXX XXXX XXXX" required>
                                    <div class="invalid-feedback">
                                        Se requiere un número de tarjeta.
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="cc-expiration" class="form-label">Vencimiento</label>
                                    <input type="text" class="form-control" id="cc-expiration" 
                                           placeholder="MM/AA" required>
                                    <div class="invalid-feedback">
                                        Se requiere fecha de vencimiento.
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="cc-cvv" class="form-label">CVV</label>
                                    <input type="text" class="form-control" id="cc-cvv" 
                                           placeholder="123" required>
                                    <div class="invalid-feedback">
                                        Se requiere el código CVV.
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <button class="w-100 btn btn-success btn-lg" type="submit">
                                Realizar Pedido ($<?= number_format($total, 2) ?>)
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Columna derecha: resumen -->
            <div class="col-lg-5">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-light py-3">
                        <h4 class="card-title mb-0 d-flex justify-content-between align-items-center">
                            Resumen de tu Pedido
                            <span class="badge bg-primary rounded-pill"><?= count($itemsDelCarrito) ?></span>
                        </h4>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <?php 
                                /** @var \BritosGab\PruebaseCommerce\entidades\ItemProducto $item */
                                foreach ($itemsDelCarrito as $item): 
                                    $producto = $item->getProducto(); 
                            ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <div>
                                        <h6 class="my-0"><?= htmlspecialchars($producto->getNombre()) ?></h6>
                                        <small class="text-muted">Cantidad: <?= $item->getCantidad() ?></small>
                                    </div>
                                    <span class="text-muted">
                                        $<?= number_format($item->getPrecio() * $item->getCantidad(), 2) ?>
                                    </span>
                                </li>
                            <?php endforeach; ?>
                            
                            <li class="list-group-item d-flex justify-content-between px-0">
                                <span>Subtotal</span>
                                <strong>$<?= number_format($subtotal, 2) ?></strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between px-0">
                                <span>Costo de Envío</span>
                                <strong>$<?= number_format($costoEnvio, 2) ?></strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center fs-5 px-0">
                                <strong>Total</strong>
                                <strong class="text-success">$<?= number_format($total, 2) ?></strong>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include_once __DIR__ . '/../includes/footer.php'; ?>

    <script>
    (() => {
      'use strict'
      const forms = document.querySelectorAll('.needs-validation')
      Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
          if (!form.checkValidity()) {
            event.preventDefault()
            event.stopPropagation()
          }
          form.classList.add('was-validated')
        }, false)
      })
    })()
    </script>
</body>
</html>
