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
                            <h3>Recuperar Contraseña</h3>
                            <p class="text-muted">Ingresa tu email y te enviaremos un enlace.</p>
                        </div>

                        <form action="controladorPost.php" method="POST">
                            <input type="hidden" name="accion" value="solicitarRecuperacion">
                            
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Enviar Enlace</button>
                                <a href="../Vista/login.php" class="btn btn-link text-decoration-none">Volver al Login</a>
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