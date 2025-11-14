<!DOCTYPE html>
<html lang="en">
<head>
<?php include_once '../includes/head.php'; ?>
</head>
<body class="d-flex flex-column min-vh-100">
<?php include_once '../includes/header.php'; ?>

      <!-- MAIN CONTENT -->
    <main>
        <div class="container-panelAdmin mt-5 mx-auto">
            <div class="card shadow-lg">
                <div class="card-header">
                    <h4 class="mb-0">Registro de Usuarios</h4>
                </div>

                <div class="card-body text-center">
                    <table class="table">
                    <thead>
                        <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Usuario</th>
                        <th scope="col">Contraseña</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Apellido</th>
                        <th scope="col">Email</th>
                        <th scope="col">Direccion</th>
                        <th scope="col">Fecha de Creacion</th>
                        <th scope="col">Rol</th>
                        <th scope="col">Estado</th>
                        <th scope="col">-</th>       
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                        <th scope="row">1</th>
                        <td>juanita</td>
                        <td>*****</td>
                        <td>Juana</td>
                        <td>Vicencio</td>
                        <td>jvicen@gmail</td>
                        <td>tu casita de la esquina</td>
                        <td>15/10/2025</td>
                        <td>Cliente</td>
                        <td><span class="estado-activo">Activo</span></td>
                        <td>
                            <a href="modificarUsuario.php"><button class="editar">Editar</button></a>
                            <a href=""><button class="eliminar">Eliminar</button></a> 
                        </td>
                    </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

<?php include_once '../includes/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>