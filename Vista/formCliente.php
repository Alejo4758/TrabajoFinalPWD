<!DOCTYPE html>
<html lang="es">
<head>
    <?php include_once __DIR__ . '/../includes/head.php'; ?>
</head>
<body>
    <?php include_once __DIR__ . '/../includes/header.php'; ?>

    <main class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white">
                        <h4 class="mb-0"><?= $cliente ? 'Editar Cliente' : 'Nuevo Cliente' ?></h4>
                    </div>
                    <div class="card-body p-4">
                        
                        <form action="../Control/controladorPost.php" method="POST" class="needs-validation" novalidate>
                            <input type="hidden" name="accion" value="guardarCliente">
                            <?php if ($cliente): ?>
                                <input type="hidden" name="idUsuario" value="<?= $cliente->getIdUsuario() ?>">
                            <?php endif; ?>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Nombre de Usuario (Login)</label>
                                    <input type="text" class="form-control" name="usuario" 
                                           value="<?= $cliente ? htmlspecialchars($cliente->getUsername()) : '' ?>" 
                                           required <?= $cliente ? 'readonly' : '' ?>> 
                                           </div>

                                <div class="col-md-6">
                                    <label class="form-label">Rol</label>
                                    <select class="form-select" name="idRol" required>
                                        <?php foreach ($listaRoles as $r): ?>
                                            <option value="<?= $r->getIdRol() ?>" 
                                                <?= ($cliente && $cliente->getRol() === $r) ? 'selected' : '' ?>>
                                                <?= ucfirst($r->getNombre()) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Nombre</label>
                                    <input type="text" class="form-control" name="nombre" value="<?= $cliente ? htmlspecialchars($cliente->getNombre()) : '' ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Apellido</label>
                                    <input type="text" class="form-control" name="apellido" value="<?= $cliente ? htmlspecialchars($cliente->getApellido()) : '' ?>" required>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" name="email" value="<?= $cliente ? htmlspecialchars($cliente->getEmail()) : '' ?>" required>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Dirección</label>
                                    <input type="text" class="form-control" name="direccion" value="<?= $cliente ? htmlspecialchars($cliente->getDireccion()) : '' ?>" required>
                                </div>

                                <hr class="my-3">

                                <div class="col-12">
                                    <label class="form-label">Contraseña</label>
                                    <input type="password" class="form-control" name="password" 
                                           placeholder="<?= $cliente ? 'Dejar vacío para mantener la actual' : 'Ingresa una contraseña' ?>"
                                           <?= $cliente ? '' : 'required' ?>>
                                </div>

                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <a href="../Control/controladorGet.php?accion=panelAdmin&vista=clientes" class="btn btn-outline-secondary">Cancelar</a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i> Guardar Cliente
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </main>
    <?php include_once __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>