<?php
// Libreria JWT
require __DIR__ . '/../vendor/autoload.php';

// Dependencias necesarias
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

// Clave secreta que se debe guardar en un estado global de la aplicación
$clave_secreta = "mi_clave_secreta_super_segura";

$mensaje = "";

// Si el formulario fue enviado para iniciar sesion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Incluir la conexión a la base de datos
    require 'conexion.php'; 

    $usuarioIngresado = $_POST['usuario'] ?? '';
    $passwordIngresado = $_POST['password'] ?? '';

    // Preparar la consulta para evitar inyección SQL
    $stmt = $conexion->prepare("SELECT idUsuario, username, password, idRol FROM usuario WHERE username = ?");
    $stmt->bind_param("s", $usuarioIngresado);
    $stmt->execute();
    $resultado = $stmt->get_result();

    // Verificar si se encontró un usuario
    if ($resultado->num_rows === 1) {
        $usuario = $resultado->fetch_assoc();

        // Verificar la contraseña usando password_verify()
        if (password_verify($passwordIngresado, $usuario['password'])) {
            // ¡Contraseña correcta! El usuario es válido.
            
            // Access Token (duración: 15 minutos)
            $access_payload = [
                'usuario' => $usuario['username'],
                'rol' => $usuario['idRol'],
                'iat' => time(),          // Fecha de emisión
                'exp' => time() + 225
            ];

            // Generar token
            $access_token = JWT::encode($access_payload, $clave_secreta, 'HS256');

            // Refresh Token (cadena aleatoria de larga duración: 7 días)
            $refresh_token = bin2hex(random_bytes(32));
            $refresh_token_expiry = time() + 604800; // 7 días

            // Guardamos el refresh token en la base de datos
            // Guardar el Refresh Token en la base de datos
            $stmt_refresh = $conexion->prepare("INSERT INTO refreshtoken (idUsuario, token, expiracion) VALUES (?, ?, ?)");
            $expiry_datetime = date('Y-m-d H:i:s', $refresh_token_expiry);
            $stmt_refresh->bind_param("iss", $usuario['idUsuario'], $refresh_token, $expiry_datetime);
            $stmt_refresh->execute();
            $stmt_refresh->close();

            // Enviar ambos tokens al navegador como cookies seguras
            setcookie('jwt_token', $access_token, ['expires' => time() + 900, 'path' => '/', 'httponly' => true, 'samesite' => 'Strict']);
            setcookie('refresh_token', $refresh_token, ['expires' => $refresh_token_expiry, 'path' => '/', 'httponly' => true, 'samesite' => 'Strict']);

            header("Location: inicio.php");
            exit();

        } else {
            // La contraseña no coincide
            $mensaje = "<div class='alert alert-danger text-center'>Usuario o contraseña incorrectos ❌</div>";
        }
    } else {
        // El usuario no fue encontrado en la base de datos
        $mensaje = "<div class='alert alert-danger text-center'>Usuario o contraseña incorrectos ❌</div>";
    }

    // Cerrar la conexión y el statement
    $stmt->close();
    $conexion->close();
}
?>

<!-- HTML -->
<!DOCTYPE html>
<html lang="en">
<head>
 <?php include_once '../includes/head.php'; ?>
</head>
<body class="d-flex flex-column min-vh-100">
    <?php include_once '../includes/header.php'; ?>

      <!-- MAIN CONTENT -->
    <main class="container d-flex flex-column w-50 mx-auto text-center align-items-center justify-content-center">
        <div class="card shadow-5 p-4 m-5 w-50"> 
            <form action="login.php" method="POST" novalidate>
                <h5 class="card-title mb-4">Iniciar Sesion</h5>
                <div class="input-group mb-3">
                    <span class="input-group-text" id="visible-addon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16"><path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/></svg></span>
                    <input id="usuario" name="usuario" type="text" class="form-control" placeholder="Usuario" aria-label="Username" aria-describedby="visible-addon" required >
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text" id="visible-addon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-lock-fill" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M8 0a4 4 0 0 1 4 4v2.05a2.5 2.5 0 0 1 2 2.45v5a2.5 2.5 0 0 1-2.5 2.5h-7A2.5 2.5 0 0 1 2 13.5v-5a2.5 2.5 0 0 1 2-2.45V4a4 4 0 0 1 4-4m0 1a3 3 0 0 0-3 3v2h6V4a3 3 0 0 0-3-3"/></svg></span>
                    <input id="password" name="password" type="password" class="form-control" placeholder="Contraseña" aria-label="Password" aria-describedby="visible-addon" required>
                </div>
                
                <!-- Mostrar mensaje -->
                <?= $mensaje ?>

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