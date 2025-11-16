<!DOCTYPE html>
<html lang="en">
<head>
<?php include_once '../includes/head.php'; ?>
</head>
<body class="d-flex flex-column min-vh-100">
<?php include_once '../includes/header.php'; ?>

<!-- MAIN CONTENT -->
<main class="container d-flex flex-column min-vh-100 w-50 mx-auto align-items-center justify-content-center">
    <div class="card-datos-usuario "> 
        <div class="card-header p-3">
            <h4 class="mb-0">Modificar Usuario</h4>
        </div>
        <div class="card-body">
            <form action="" method="POST">

                <div class="row">
                    <div class="col">
                        <label for="idusuario" class="form-label">ID Usuario</label>
                        <input type="text" class="form-control" id="idusuario" name="idusuario" disabled>
                    </div>
                    <div class="col">
                        <label for="usnombre" class="form-label">Nombre de usuario</label>
                        <input type="text" class="form-control" id="usnombre" name="usnombre" placeholder="Nombre de usuario" required>
                    </div>
                </div>  
                
                <div class="row">
                    <div class="col">
                        <label for="uspass" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" id="uspass" name="uspass" placeholder="Contraseña" disabled>
                    </div>
                    <div class="col">
                        <label for="usmail" class="form-label">Correo electrónico</label>
                        <input type="email" class="form-control" id="usmail" name="usmail" placeholder="Correo electrónico" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <label for="usnombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="usnombre" name="usnombre" placeholder="Nombre" required>
                    </div>
                    <div class="col">
                        <label for="usapellido" class="form-label">Apellido</label>
                        <input type="text" class="form-control" id="usapellido" name="usapellido" placeholder="Apellido" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <label for="usdireccion" class="form-label">Direccion</label>
                        <input type="text" class="form-control" id="usdireccion" name="usdireccion" placeholder="Direccion" required>
                    </div>
                    <div class="col">
                        <label for="usfechacreacion" class="form-label">Fecha de Creacion</label>
                        <input type="date" class="form-control" id="usfechacreacion" name="usfechacreacion" placeholder="0000/00/00" disabled>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <label for="usrol" class="form-label">Rol</label>
                        <input type="text" class="form-control" id="usrol" name="usrol" placeholder="Rol" required>
                    </div>
                    <div class="col">
                        <label for="usestado" class="form-label">Estado</label>
                        <input type="text" class="form-control" id="usestado" name="usestado" placeholder="usestado" required>
                    </div>
                </div>

                <div class="d-flex justify-content-around mt-4">
                    <button class="cancelar"><a href="panelAdmin.php">Cancelar</a></button>
                    <button type="submit" class="guardarCambios">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</main>

<?php include_once '../includes/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>