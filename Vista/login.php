<!DOCTYPE html>
<html lang="en">
<head>
    <?php include_once '../includes/head.php'; ?>
</head>
<body class="d-flex flex-column min-vh-100">
    <?php include_once '../includes/header.php'; ?>

<!-- MAIN CONTENT -->
<main class="container d-flex flex-column w-50 mx-auto text-center align-items-center justify-content-center">
    <div class="card-login shadow-5 p-4 m-5 w-50"> 
        <h5 class="card-title mb-4">Iniciar Sesión</h5>

            <?php
                if (isset($_SESSION['mensaje'])) {
                    echo "<div class='alert alert-danger'>" . $_SESSION['mensaje'] . "</div>";
                    unset($_SESSION['mensaje']); // Limpiar para que no se repita
                }
            ?> 
             
        <form  method="POST" action="../Control/controladorPost.php">
            <div class="input-group mb-3">
                <span class="input-group-text" id="visible-addon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16"><path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/></svg></span>
                <input id="usuario" name="usuario" type="text" class="form-control" placeholder="Usuario" aria-label="Username" aria-describedby="visible-addon" required >
            </div>

            <div class="input-group mb-3">
                <span class="input-group-text" id="visible-addon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-lock-fill" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M8 0a4 4 0 0 1 4 4v2.05a2.5 2.5 0 0 1 2 2.45v5a2.5 2.5 0 0 1-2.5 2.5h-7A2.5 2.5 0 0 1 2 13.5v-5a2.5 2.5 0 0 1 2-2.45V4a4 4 0 0 1 4-4m0 1a3 3 0 0 0-3 3v2h6V4a3 3 0 0 0-3-3"/></svg></span>
                <input id="password" name="password" type="password" class="form-control" placeholder="Contraseña" aria-label="Password" aria-describedby="visible-addon" required>
            </div>

            <input type="hidden" name="accion" value="login">

            <div class="d-grid gap-2">
                <button type="submit" class="form">Ingresar</button>
            </div>
        </form>

        <div class="text-center mt-3">
            <small>¿No tienes una cuenta? <a href="registro.php">Regístrate aquí</a></small>
        </div>
    </div>
</main>
        
<?php include_once '../includes/footer.php'; ?>
</body>
</html>