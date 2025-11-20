<!DOCTYPE html>
<html lang="en">
<head>
    <?php include_once '../includes/head.php'; ?>
</head>
<body class="d-flex flex-column min-vh-100">
<?php include_once '../includes/header.php'; ?>
    
<!-- MAIN CONTENT -->
<main class="container d-flex flex-column w-50 mx-auto  align-items-center justify-content-center">
    <div class="card-registro shadow-5 p-4 m-5"> 
        <h5 class="card-title mb-4 text-center">¡Crea una cuenta!</h5>

        <form action="../Control/controladorPost.php"  method="POST" novalidate class="needs-validation">
            
            <div class="mb-3">
                <label for="usuario" class="form-label">Usuario</label>
                <input type="text" id="usuario" class="form-control" name="usuario" placeholder="Elija un nombre de usuario" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" id="password" class="form-control" name="password" placeholder="Cree una contraseña" required>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" id="nombre" class="form-control" name="nombre" placeholder="Gabriel" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="apellido" class="form-label">Apellido</label>
                    <input type="text" id="apellido" class="form-control" name="apellido" placeholder="Apellido" required>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="mail" class="form-label">Email</label>
                <input type="email" id="mail" class="form-control" name="mail" placeholder="Escriba su Email" required>
            </div>

            <div class="mb-3">
                <label for="direccion" class="form-label">Dirección</label>
                <input type="text" id="direccion" class="form-control" name="direccion" placeholder="Ej: Av. Siempre Viva 123" required>
            </div>
  
            <input type="hidden" name="accion" value="registro">
                 
            <div class="d-flex justify-content-between mt-4">
                <button type="reset" class="reset">Limpiar</button>
                <button type="submit" class="registro">Enviar</button>
            </div>
        </form>

        <div class="text-center mt-3">
            <small>¿Ya tienes una cuenta? <a href="login.php">Inicia Sesión</a></small>
        </div>
    </div>
</main>
        
<?php include_once '../includes/footer.php'; ?>
</body>
</html>