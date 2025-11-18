<?php
// Carga configuracion y motor ORM
require_once __DIR__ . '/../config/bootstrap.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../Includes/formData.php';

use Perfumeria\Control\Sesion;
use Perfumeria\Modelo\Usuario;
use Perfumeria\Modelo\Producto;
use Perfumeria\Modelo\Pedido;
use Perfumeria\Modelo\Rol;
use Perfumeria\Modelo\ItemProducto;

// Solo se permite el acceso a este archivo a través de una petición POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit('Acceso no permitido.');
}

// obtengo datos del form y acción ejecutada
$datos = datosEnviados(); 
$accion = $datos['accion'] ?? null;

// Verifico accion
if (!$accion) {
    $_SESSION['error'] = 'Acción no especificada.';
    header('Location: login.php');
    exit();
}

// Inicializo clase Sesion (JWT)
$sesion = new Sesion(claveSecreta, $entidadManager);
$mensaje = "";

// Según la acción, ejecutamos
try {
    switch ($accion) {
        
        case 'buscar':// ========== BUSCAR ==========
            // obtengo el termino de busqueda
            // (Usar los nombres de las PROPIEDADES de la entidad, no de la BD)
            $terminoDeBusqueda = $datos['terminoBusqueda'] ?? '';

            if (empty($terminoDeBusqueda)) {
                // No buscar si está vacío
                $productos = [];
            } else {
                $productos = buscarPorTermino(
                    $entidadManager,
                    Producto::class,
                    $terminoDeBusqueda
                );
            }

            require_once __DIR__ . '/../public/vistas/resultadosBusqueda.php';
            break;
            
        case 'registro':// ========== REGISTRO ==========
            // 'usuario' de tu formulario DEBE ser el 'username'
            $username = $datos['usuario'] ?? '';
            $nombre = $datos['nombre'] ?? '';
            $apellido = $datos['apellido'] ?? '';
            $direccion = $datos['direccion'] ?? '';
            $email = $datos['mail'] ?? '';
            $password = $datos['password'] ?? '';

            // Validamos que los campos no estén vacíos
            if (empty($username) || empty($password) || empty($email) || empty($nombre) || empty($apellido) || empty($direccion)) {
                throw new Exception("Por favor, complete todos los campos.");
            }

            // ===========================================
            // ----- VALIDO DE EXISTENCIA DE USUARIO -----
            // ===========================================
            $repositorio = $entidadManager->getRepository(Usuario::class);

            $usuarioExistente = $repositorio->findOneBy(['username' => $username]);

            if ($usuarioExistente) {
                throw new Exception("El nombre de usuario ya está en uso.");
            }
            // ===========================================

            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            $nuevoUsuario = new Usuario();
            
            $nuevoUsuario->setUsername($username);
            $nuevoUsuario->setEmail($email);
            $nuevoUsuario->setContrasenia($hashed_password);
            $nuevoUsuario->setNombre($nombre);
            $nuevoUsuario->setApellido($apellido);
            $nuevoUsuario->setDireccion($direccion);

            // Asignar el rol 'cliente' por defecto
            $rolCliente = $entidadManager->getRepository(Rol::class)->findOneBy(['nombre' => 'cliente']);
            
            if (!$rolCliente) {
                throw new Exception("No se pudo encontrar el rol de cliente.");
            }

            $nuevoUsuario->setRol($rolCliente);

            // Ejecutar y verificar
            $entidadManager->persist($nuevoUsuario);
            $entidadManager->flush(); 

            $_SESSION['mensaje'] = "¡Cuenta creada con éxito! Ya puedes iniciar sesión.";
            header('Location: ../Vista/login.php');
            exit();
            break;

        case 'login':// ========== LOGIN ==========
            $username = $datos['usuario'] ?? '';
            $password = $datos['password'] ?? '';

            // ===========================================
            // --- VALIDACIÓN DE USUARIO DESHABILITADO ---
            // ===========================================
            $repositorio = $entidadManager->getRepository(Usuario::class);
            
            $usuarioObj = $repositorio->findOneBy(['username' => $username]);

            // Verifico que no este deshabilitado
            if ($usuarioObj && $usuarioObj->getDeshabilitado() !== null) {
                $_SESSION['mensaje'] = "Esta cuenta ha sido deshabilitada";
                header('Location: vistas/login.php');
                exit();
            }
            // ===========================================

            // Valido usando la clase Sesion.
            if ($sesion->iniciar($username, $password)) {
                // Si es true, la clase Sesion YA CREÓ las cookies JWT
                $_SESSION['bienvenida'] = "Bienvenido $username!";
                header("Location: controladorGet.php?accion=index");
                exit();
            } else {
                $_SESSION['mensaje'] = "Usuario o contraseña incorrectos";
                header('Location: vistas/login.php');
                exit();
            }
            break;

        case 'agregarAlCarrito': // ========== AGREGAR AL CARRITO ==========
            // Verificar si el usuario está logueado
            if (!$sesion->validar()) {
                // Si no está logueado, no puede tener un carrito en la BD
                $_SESSION['mensaje'] = "Iniciar sesión para agregar productos al carrito.";
                header('Location: vistas/login.php');
                exit();
            }

            // Obtengo el id del producto que se quiso agregar al carrito
            // Asumiendo que $datos es tu $_POST
            $idProducto = $datos['idProducto'] ?? null;
            if (!$idProducto) {
                throw new Exception("No se especificó ningún producto.");
            }

            // Obtener el usuario y el producto
            $usuario = $entidadManager->getRepository(Usuario::class)->findOneBy([
                'username' => $sesion->getUsuario()
            ]);
            $producto = $entidadManager->getRepository(Producto::class)->find($idProducto);

            if (!$usuario) {
                // Esto puede pasar si la sesión está corrupta o el usuario fue eliminado
                session_destroy(); // Destruimos la sesión inválida
                throw new Exception("Error de sesión. Por favor, inicie sesión de nuevo.");
            }
            
            if (!$producto) {
                throw new Exception("El producto no existe.");
            }

            if ($producto->getDeshabilitado() !== null) {
                // Es un error de usuario, no de sistema. Redirigimos con un mensaje.
                $_SESSION['mensaje'] = "Este producto ya no está disponible";
                header('Location: controladorGet.php'); // Redirigir al carrito
                exit();
            }

            // 2. Verificar si hay stock en general
            $stockDisponible = $producto->getStock();
            if ($stockDisponible <= 0) {
                $_SESSION['mensaje'] = "Lo sentimos, este producto está agotado";
                header('Location: vistas/index.php'); // Redirigir al carrito
                exit();
            }

            // 3. Buscar el carrito activo del usuario
            $carrito = $entidadManager->getRepository(Pedido::class)->findOneBy([
                'usuario' => $usuario,
                'estado'    => 'CARRITO'
            ]);

            // Si no tiene carrito, crear uno nuevo
            if (!$carrito) {
                $carrito = new Pedido();
                $carrito->setUsuario($usuario);
                // El estado 'CARRITO' se pone por defecto en el constructor
                $entidadManager->persist($carrito);
            }

            // Revisar si el producto YA ESTÁ en el carrito
            $itemExistente = null;
            /** @var ItemProducto $item */
            foreach ($carrito->getItemsProducto() as $item) {
                if ($item->getProducto() === $producto) {
                    $itemExistente = $item;
                    break;
                }
            }

            $cantidadActualEnCarrito = 0;
            if ($itemExistente) {
                $cantidadActualEnCarrito = $itemExistente->getCantidad();
            }

            // 3. Verificar si agregar 1 MÁS excede el stock disponible
            if (($cantidadActualEnCarrito + 1) > $stockDisponible) {
                $_SESSION['mensaje'] = "No puedes agregar más unidades de este producto. Stock máximo alcanzado ($stockDisponible)";
                header('Location: controladorGet.php'); // Redirigir al carrito
                exit();
            }
            if ($itemExistente) {
                // Si ya existe, solo suma 1 a la cantidad
                $itemExistente->setCantidad($itemExistente->getCantidad() + 1);
            } else {
                // Si no existe, crea un nuevo ItemProducto
                $nuevoItem = new ItemProducto();
                $nuevoItem->setProducto($producto);
                $nuevoItem->setCantidad(1);
                $nuevoItem->setPrecio($producto->getPrecio()); // Guarda el precio actual
                // Asocia el item al pedido
                $carrito->agregarItemProducto($nuevoItem);
            }

            // Guardar todo en la BD
            $entidadManager->flush();

            // Redirigir al carrito
            $_SESSION['mensaje'] = "¡Producto agregado al carrito!";
            header('Location: controladorGet.php?accion=verCarrito'); // apunta al controlador GET del carrito
            exit();

            break;

        case 'restarItem': // ========== RESTAR UNIDAD ==========
            
            // 1. Validar sesión
            if (!$sesion->validar()) {
                header('Location: vistas/login.php');
                exit();
            }

            // 2. Obtener datos
            $idItem = $datos['idItem'] ?? null;
            if (!$idItem) {
                throw new Exception("Error al identificar el producto.");
            }

            // 3. Buscar usuario e item
            $usuario = $entidadManager->getRepository(Usuario::class)->findOneBy(['username' => $sesion->getUsuario()]);
            /** @var ItemProducto $item */
            $item = $entidadManager->getRepository(ItemProducto::class)->find($idItem);

            // 4. Validar seguridad (que el item sea del usuario)
            $carrito = $entidadManager->getRepository(Pedido::class)->findOneBy([
                'usuario' => $usuario,
                'estado' => 'CARRITO'
            ]);

            if (!$item || !$carrito || $item->getPedido() !== $carrito) {
                $_SESSION['mensaje'] = "<div class='alert alert-danger'>No se pudo modificar el item.</div>";
                header('Location: controladorGet.php?accion=verCarrito');
                exit();
            }

            // 5. Lógica de resta
            $cantidadActual = $item->getCantidad();

            if ($cantidadActual > 1) {
                // Si hay más de 1, restamos
                $item->setCantidad($cantidadActual - 1);
                $entidadManager->flush();
                $_SESSION['mensaje'] = "<div class='alert alert-success'>Cantidad actualizada.</div>";
            } else {
                // Si es 1, podemos optar por eliminarlo o lanzar error.
                // Como pusimos 'disabled' en la vista, esto es solo una protección extra.
                $_SESSION['mensaje'] = "<div class='alert alert-warning'>La cantidad mínima es 1. Usa el botón de eliminar para quitarlo.</div>";
            }

            // 6. Redirigir
            header('Location: controladorGet.php?accion=verCarrito');
            exit();
            break;

        case 'eliminarItem': // ========== ELIMINAR ITEM DEL CARRITO ==========
            // 1. Verificar que el usuario esté logueado
            if (!$sesion->validar()) {
                $_SESSION['mensaje'] = "Iniciar sesión para modificar tu carrito.";
                header('Location: vistas/login.php');
                exit();
            }

            // 2. Obtener el ID del item a eliminar
            $idItem = $datos['idItem'] ?? null;
            if (!$idItem) {
                throw new Exception("No se especificó ningún item para eliminar.");
            }

            // 3. Obtener el usuario y el item
            $usuario = $entidadManager->getRepository(Usuario::class)->findOneBy([
                'username' => $sesion->getUsuario()
            ]);
            
            /** @var ItemProducto $item */
            $item = $entidadManager->getRepository(ItemProducto::class)->find($idItem);

            // 4. Validar que el item exista
            if (!$item) {
                $_SESSION['mensaje'] = "El item que intentas quitar ya no existe";
                header('Location: controladorGet.php');
                exit();
            }

            // ==============================================================
            //  VERIFICACIÓN DE SEGURIDAD
            // ==============================================================
            // Comprobar que el item pertenezca al carrito activo del usuario
            
            $carrito = $entidadManager->getRepository(Pedido::class)->findOneBy([
                'usuario' => $usuario,
                'estado'  => 'CARRITO'
            ]);

            // Si el item no pertenece al carrito actual, lanzamos un error.
            // Esto evita que un usuario pueda eliminar items de otro usuario
            // si adivina el ID del item.
            if (!$carrito || $item->getPedido() !== $carrito) {
                $_SESSION['mensaje'] = "Acción no permitida";
                header('Location: controladorGet.php');
                exit();
            }
            // ==============================================================

            // 5. Eliminar el item
            // (Doctrine se encarga de quitarlo de la colección del Pedido
            // gracias a tu método Pedido::eliminarItemProducto y 'cascade')
            $entidadManager->remove($item);
            
            // 6. Guardar los cambios en la BD
            $entidadManager->flush();

            // 7. Redirigir al carrito
            $_SESSION['mensaje'] = "Producto eliminado del carrito";
            header('Location: controladorGet.php?accion=verCarrito');
            exit();
            break;
        
        case 'procesarPedido': // ========== FINALIZAR COMPRA ==========
            // 1. Validar sesión
            case 'procesarPedido': // ========== FINALIZAR COMPRA ==========
            
            // 1. Validar sesión
            if (!$sesion->validar()) {
                header('Location: vistas/login.php');
                exit();
            }

            // 2. Obtener datos RELEVANTES del formulario
            // Solo nos interesa lo que puede cambiar: dirección y método de envío
            $direccion = trim($datos['direccion'] ?? '');
            $metodoEnvio = $datos['metodoEnvio'] ?? 'Estandar';
            
            if (empty($direccion)) {
                throw new Exception("La dirección de envío es obligatoria.");
            }

            // 3. Obtener Usuario y Carrito
            // Usamos los datos del usuario DIRECTO de la BD (seguro)
            $usuario = $entidadManager->getRepository(Usuario::class)->findOneBy(['username' => $sesion->getUsuario()]);
            
            $carrito = $entidadManager->getRepository(Pedido::class)->findOneBy([
                'usuario' => $usuario,
                'estado' => 'CARRITO'
            ]);

            if (!$carrito || $carrito->getItemsProducto()->isEmpty()) {
                $_SESSION['mensaje'] = "<div class='alert alert-warning'>Tu carrito está vacío.</div>";
                header('Location: controladorGet.php?accion=verCarrito');
                exit();
            }

            // 4. CÁLCULO DE ENVÍO (Lógica PHP pura)
            $tarifasEnvio = [
                'Estandar' => 2500,
                'Express'  => 5000,
                'Andreani' => 3800
            ];
            // Si manipulan el HTML y envian 'Gratis', esto lo frena y pone 2500 por defecto
            $costoEnvio = $tarifasEnvio[$metodoEnvio] ?? 2500;

            // 5. PROCESAR STOCK Y SUBTOTAL
            $subtotal = 0;
            $items = $carrito->getItemsProducto();

            /** @var ItemProducto $item */
            foreach ($items as $item) {
                $producto = $item->getProducto();
                $cantidad = $item->getCantidad();
                $stockActual = $producto->getStock();

                if ($stockActual < $cantidad) {
                    $_SESSION['mensaje'] = "<div class='alert alert-danger'>Stock insuficiente para: " . $producto->getNombre() . "</div>";
                    header('Location: controladorGet.php?accion=verCarrito');
                    exit();
                }

                $producto->setStock($stockActual - $cantidad);
                $subtotal += $item->getPrecio() * $cantidad;
            }

            // 6. ACTUALIZAR PEDIDO CON VALORES FINALES
            $totalFinal = $subtotal + $costoEnvio;

            // Guardamos la dirección específica para ESTE pedido (historial)
            // No modificamos la dirección del perfil del usuario ($usuario->setDireccion), 
            // solo la del pedido.
            $carrito->setDireccion($direccion);
            
            $carrito->setMetodoEnvio($metodoEnvio);
            $carrito->setCostoEnvio($costoEnvio);
            $carrito->setMontoTotal($totalFinal);
            $carrito->setFechaFinalizado(new DateTime());
            $carrito->setEstado('PAGADO');

            // ... (guardar en BD con flush) ...
            $entidadManager->flush();

            // 8. PREPARAR EL POPUP Y REDIRIGIR
            // Guardamos el ID del pedido en una variable especial de sesión
            $_SESSION['pedido_creado_id'] = $carrito->getIdPedido();
            
            // Redirigimos directamente al historial
            header('Location: controladorGet.php?accion=verHistorial');
            exit();

            break;
            
        default:
            throw new Exception("La acción '{$accion}' no es válida.");
            break;
    }
} catch (Exception $e) {
    // Si algo sale mal, guardamos el mensaje de error y redirigimos
    // (Usar 'error' o 'mensaje' según cómo lo muestres)
    $_SESSION['mensaje'] = "<div class='alert alert-danger'>Error: " . $e->getMessage() . "</div>";
    // Redirigir a la página de origen o al login
    if ($accion === 'registro') {
        header('Location: ../Vista/registro.php');
    } else {
        header('Location: vistas/login.php');
    }
    exit();
}