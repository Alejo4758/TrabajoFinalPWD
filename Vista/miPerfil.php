<!DOCTYPE html>
<html lang="es">
<head>
    <?php include_once '../Includes/head.php'; ?>
</head>
<body>
    <?php include_once '../Includes/header.php'; ?>

    <main class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                
                <?php if (isset($_SESSION['mensaje'])): ?>
                    <div class="alert alert-info alert-dismissible fade show">
                        <?= $_SESSION['mensaje']; unset($_SESSION['mensaje']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white p-3">
                        <h4 class="mb-0"><i class="bi bi-person-lines-fill me-2"></i> Mi Información Personal</h4>
                    </div>
                    <div class="card-body p-4">
                        
                        <form action="../Control/controladorPost.php" method="POST">
                            <input type="hidden" name="accion" value="actualizarPerfil">
                            
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label text-muted">Nombre de Usuario</label>
                                    <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($usuario->getUsername()) ?>" readonly>
                                    <div class="form-text">El nombre de usuario no se puede cambiar.</div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Nombre</label>
                                    <input type="text" class="form-control" name="nombre" value="<?= htmlspecialchars($usuario->getNombre()) ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Apellido</label>
                                    <input type="text" class="form-control" name="apellido" value="<?= htmlspecialchars($usuario->getApellido()) ?>" required>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($usuario->getEmail()) ?>" required>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Dirección (Predeterminada para envíos)</label>
                                    <input type="text" class="form-control" name="direccion" value="<?= htmlspecialchars($usuario->getDireccion()) ?>" required>
                                </div>

                                <hr class="my-4">

                                <div class="col-12">
                                    <h5 class="mb-3">Seguridad</h5>
                                    <label class="form-label">Nueva Contraseña</label>
                                    <input type="password" class="form-control" name="password" placeholder="Dejar vacío para mantener la contraseña actual">
                                    <div class="form-text">Solo llena este campo si deseas cambiar tu contraseña.</div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end mt-4">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-save me-1"></i> Guardar Cambios
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include_once '../Includes/footer.php'; ?>
</body>
</html>