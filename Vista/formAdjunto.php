<!DOCTYPE html>
<html lang="es">
<head>
    <?php include_once '../Includes/head.php'; ?>
</head>
<body>
    <?php include_once '../includes/header.php'; ?>

    <main class="container d-flex flex-column align-items-center justify-content-center">
        <div class="card-datos"> 
            <div class="card-header p-3">
                <h4 class="mb-0">Subir Nueva Imagen</h4>
            </div>
                <div class="card-body p-4">
                        
                        <form action="../Control/controladorPost.php" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>

                            <input type="hidden" name="accion" value="guardarAdjunto">

                            
                            <div class="mb-3">
                                <label class="form-label">Seleccionar Producto</label>
                                <select class="form-select" name="idProducto" required>
                                    <option value="">-- Asignar a --</option>
                                    <?php foreach ($productos as $p): ?>
                                        <option value="<?= $p->getIdProducto() ?>">
                                            <?= htmlspecialchars($p->getNombre()) ?> (Ref: <?= $p->getCodigoReferencia() ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="invalid-feedback">
                                    Debe seleccionar un producto.
                                </div>
                            </div>

                            
                            <div class="mb-4">
                                <label class="form-label">Archivo de Imagen</label>
                                <input type="file"
                                       class="form-control"
                                       name="imagen"
                                       accept="image/*"
                                       required>
                                <div class="invalid-feedback">
                                    Debe subir una imagen válida.
                                </div>
                            </div>

                            <div class="d-flex justify-content-between text-center">
                                <a href="../Control/controladorGet.php?accion=panelAdmin&vista=adjuntos" class="cancelar">Cancelar</a>
                                <button type="submit" class="guardarCambios">
                                    <i class="bi bi-cloud-upload"></i> Subir
                                </button>
                            </div>

                        </form>

                </div>
        </div>
    </main>

    <?php include_once '../Includes/footer.php'; ?>
</body>
</html>
