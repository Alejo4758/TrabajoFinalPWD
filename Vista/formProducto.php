<!DOCTYPE html>
<html lang="es">
<head>
    <?php include_once __DIR__ . '/../includes/head.php'; ?>
</head>
<body>
    <?php include_once __DIR__ . '/../includes/header.php'; ?>

    <main class="container d-flex flex-column align-items-center justify-content-center">
        <div class="card-datos"> 

                    <div class="card-header">
                        <h4 class="mb-0">
                            <?= $producto ? 'Editar Producto' : 'Nuevo Producto' ?>
                        </h4>
                    </div>
                    <div class="card-body p-4">

                        <form action="../Control/controladorPost.php" method="POST" 
                              class="needs-validation" novalidate>

                            <input type="hidden" name="accion" value="guardarProducto">

                            <?php if ($producto): ?>
                                <input type="hidden" name="idProducto" value="<?= $producto->getIdProducto() ?>">
                            <?php endif; ?>

                            <div class="row g-3">

                                <!-- ID (solo lectura en modo edición) -->
                                <?php if ($producto): ?>
                                <div class="col-md-2">
                                    <label class="form-label">ID</label>
                                    <input type="text" class="form-control" 
                                           value="<?= $producto->getIdProducto() ?>" disabled>
                                </div>
                                <?php endif; ?>

                                <!-- CÓDIGO -->
                                <div class="col-md-<?= $producto ? '5' : '6' ?>">
                                    <label for="codigo" class="form-label">Código Referencia</label>
                                    <input type="text" class="form-control" id="codigo" name="codigo" 
                                           value="<?= $producto ? $producto->getCodigoReferencia() : '' ?>"
                                           <?= $producto ? 'disabled' : 'required pattern="[A-Za-z0-9-]{3,20}"' ?>>
                                    <div class="invalid-feedback">
                                        Ingrese un código válido (solo letras, números y guiones).
                                    </div>
                                </div>

                                <!-- NOMBRE -->
                                <div class="col-md-<?= $producto ? '5' : '6' ?>">
                                    <label for="nombre" class="form-label">Nombre</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" 
                                           value="<?= $producto ? htmlspecialchars($producto->getNombre()) : '' ?>" 
                                           required minlength="3">
                                    <div class="invalid-feedback">El nombre debe tener al menos 3 caracteres.</div>
                                </div>

                                <!-- PRECIO -->
                                <div class="col-md-4">
                                    <label for="precio" class="form-label">Precio</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" step="0.01" min="0.01" class="form-control" 
                                               id="precio" name="precio"
                                               value="<?= $producto ? $producto->getPrecio() : '' ?>" 
                                               required>
                                        <div class="invalid-feedback">Ingrese un precio válido (mayor a 0).</div>
                                    </div>
                                </div>

                                <!-- STOCK -->
                                <div class="col-md-4">
                                    <label for="stock" class="form-label">Stock</label>
                                    <input type="number" class="form-control" id="stock" name="stock" 
                                           min="0" 
                                           value="<?= $producto ? $producto->getStock() : '0' ?>" required>
                                    <div class="invalid-feedback">El stock debe ser 0 o mayor.</div>
                                </div>

                                <!-- MARCA -->
                                <div class="col-md-4">
                                    <label for="marca" class="form-label">Marca</label>
                                    <select class="form-select" id="marca" name="idMarca" required>
                                        <option value="">Seleccionar...</option>
                                        <?php foreach ($marcas as $m): ?>
                                            <option value="<?= $m->getIdMarca() ?>"
                                                <?= ($producto && $producto->getMarca() === $m) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($m->getNombre()) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="invalid-feedback">Seleccione una marca.</div>
                                </div>

                                <!-- CATEGORÍA -->
                                <div class="col-md-6">
                                    <label for="categoria" class="form-label">Categoría</label>
                                    <select class="form-select" id="categoria" name="idCategoria" required>
                                        <option value="">Seleccionar...</option>
                                        <?php foreach ($categorias as $c): ?>
                                            <option value="<?= $c->getIdCategoria() ?>"
                                                <?= ($producto && $producto->getCategoria() === $c) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($c->getNombre()) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="invalid-feedback">Seleccione una categoría.</div>
                                </div>

                                <!-- DESCRIPCIÓN -->
                                <div class="col-12">
                                    <label for="descripcion" class="form-label">Descripción</label>
                                    <textarea class="form-control" id="descripcion" name="descripcion" 
                                              rows="3" minlength="5"><?= $producto ? htmlspecialchars($producto->getDescripcion()) : '' ?></textarea>
                                    <div class="invalid-feedback">La descripción debe tener mínimo 5 caracteres.</div>
                                </div>

                                <!-- BOTONES -->
                               <div class="col-12 d-flex justify-content-between mt-4 text-center">
                                    <a href="../Control/controladorGet.php?accion=panelAdmin&vista=productos" class="cancelar">Cancelar</a>
                                    <button type="submit" class="guardarCambios">
                                        <i class="bi bi-save"></i> Guardar Cambios
                                    </button>
                                </div>

                            </div>
                        </form>
               
            </div>
        </div>
    </main>

    <?php include_once __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>
