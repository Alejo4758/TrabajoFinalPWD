<?php
session_start();
require 'conexion.php'; // Incluimos el archivo de conexión

$mensaje = '';

// Verificamos si el formulario ha sido enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Validamos que los campos no estén vacíos
    if (empty($_POST['usuario']) || empty($_POST['password'])) {
        $mensaje = '<div class="alert alert-danger" role="alert">Por favor, complete todos los campos.</div>';
    } else {
        $usuario = $_POST['usuario'];
        $password = $_POST['password'];
        $mail = $_POST['email'];

        // Revisamos si el usuario ya existe
        $stmt_check = $conexion->prepare("SELECT idUsuario FROM usuario WHERE username = ?");
        $stmt_check->bind_param("s", $usuario);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows > 0) {
            $mensaje = '<div class="alert alert-danger" role="alert">El nombre de usuario ya está en uso.</div>';
        } else {
            // Hashear la contraseña por seguridad
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            // Preparar la consulta para insertar el nuevo usuario
            // El rol se inserta por defecto como 'cliente' gracias a la configuración de la tabla
            $stmt_insert = $conexion->prepare("INSERT INTO usuario (username, password, mail, idRol) VALUES (?, ?, ?, 3)");
            $stmt_insert->bind_param("sss", $usuario, $hashed_password, $mail); // Asignamos el rol 'cliente' con idRol = 3

            // Ejecutar y verificar
            if ($stmt_insert->execute()) {
                $mensaje = '<div class="alert alert-success" role="alert">¡Cuenta creada con éxito! Ya puedes <a href="login.php" class="alert-link">iniciar sesión</a>.</div>';
            } else {
                $mensaje = '<div class="alert alert-danger" role="alert">Error al crear la cuenta. Inténtelo de nuevo.</div>';
            }
            // Cerramos conexiones
            $stmt_insert->close();
        }
        $stmt_check->close();
    }
    $conexion->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include_once '../includes/head.php'; ?>
</head>
<body class="d-flex flex-column min-vh-100">
    <?php include_once '../includes/header.php'; ?>

    
        <!-- MAIN CONTENT -->
    <main class="container d-flex flex-column w-50 mx-auto  align-items-center justify-content-center">
        <div class="card shadow-5 p-4 m-5"> 

            <form action="registro.php"  method="POST" novalidate>
                <h5 class="card-title mb-4 text-center">¡Crea una cuenta!</h5>
                <div class="mb-3">
                <label for="usuario" class="form-label">Usuario</label>
                <input type="text" id="usuario" class="form-control" name="usuario" placeholder="Elija un nombre de usuario" required>
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
                <label for="direccion" class="form-label">Dirección</label>
                <input type="text" id="direccion" class="form-control" name="direccion" placeholder="Ej: Av. Siempre Viva 123" required>
            </div>
            <div class="mb-3">
                <label for="mail" class="form-label">Email</label>
                <input type="email" id="mail" class="form-control" name="mail" placeholder="Escriba su Email" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" id="password" class="form-control" name="password" placeholder="Cree una contraseña" required>
            </div>
                    <!-- Mostrar mensaje -->
                 <?php if(!empty($mensaje)) { echo $mensaje; } ?>
                 
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