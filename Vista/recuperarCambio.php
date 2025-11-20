<!DOCTYPE html>
<html lang="es">
<head>
    <?php include_once '../Includes/head.php'; ?>
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-5">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <h3>Nueva Contraseña</h3>
                            <p class="text-muted">Ingresa tu nueva contraseña.</p>
                        </div>

                        <form action="controladorPost.php" method="POST">
                            <input type="hidden" name="accion" value="cambiarClaveOlvidada">
                            <!-- Enviamos el token oculto para saber quién es -->
                            <input type="hidden" name="token" value="<?= htmlspecialchars($_GET['token'] ?? '') ?>">

                            <div class="mb-3">
                                <label class="form-label">Nueva Contraseña</label>
                                <input type="password" name="password" class="form-control" required minlength="4">
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-success">Actualizar Contraseña</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include_once '../Includes/footer.php'; ?>
</body>
</html>