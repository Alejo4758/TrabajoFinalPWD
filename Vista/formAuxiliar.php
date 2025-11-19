<!DOCTYPE html>
<html lang="es">
<head>
    <?php include_once __DIR__ . '/../includes/head.php'; ?>
</head>
<body>
    <?php include_once __DIR__ . '/../includes/header.php'; ?>

    <main class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white text-capitalize">
                        <h4 class="mb-0">
                            <?= $entidad ? 'Editar' : 'Nueva' ?> <?= $tipo ?>
                        </h4>
                    </div>
                    <div class="card-body p-4">
                        
                        <form action="../Control/controladorPost.php" method="POST">
                            <input type="hidden" name="accion" value="guardarAuxiliar">
                            <input type="hidden" name="tipo" value="<?= $tipo ?>">
                            
                            <?php if ($entidad): ?>
                                <?php $idVal = ($tipo === 'marca') ? $entidad->getIdMarca() : $entidad->getIdCategoria(); ?>
                                <input type="hidden" name="id" value="<?= $idVal ?>">
                            <?php endif; ?>

                            <div class="mb-3">
                                <label for="nombre" class="form-label text-capitalize">Nombre de la <?= $tipo ?></label>
                                <input type="text" class="form-control" id="nombre" name="nombre" 
                                       value="<?= $entidad ? htmlspecialchars($entidad->getNombre()) : '' ?>" 
                                       required autofocus>
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <a href="../Control/controladorGet.php?accion=panelAdmin&vista=<?= $tipo ?>s" class="btn btn-outline-secondary">Cancelar</a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i> Guardar
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include_once __DIR__ .  '/../includes/footer.php'; ?>
</body>
</html>