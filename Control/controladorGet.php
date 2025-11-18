<?php
// Cargar configuración y autoloader
require_once __DIR__ . '/../config/bootstrap.php';
require_once __DIR__ . '/../config/config.php';

// Usar las clases necesarias
use Perfumeria\Control\Sesion;
use Perfumeria\Modelo\Usuario;
use Perfumeria\Modelo\Pedido;
use Perfumeria\Modelo\Producto;
use Doctrine\Common\Collections\ArrayCollection;

// Inicializar la sesión (siempre necesaria para las vistas)
$sesion = new Sesion(claveSecreta, $entidadManager);
$esAdmin = false; // Por defecto no es admin

if ($sesion->activa()) {
    $roles = $sesion->getRol() ?? [];
    if (in_array('administrador', $roles) || in_array('superAdministrador', $roles)) {
        $esAdmin = true;
    }
}

// Determinar la acción a realizar (leída desde la URL)
$accion = $_GET['accion'] ?? 'index'; // 'index' será la acción por defecto

try {
    switch ($accion) {

        // ===============================================
        //  ACCIÓN PARA VER EL CARRITO
        // ===============================================
        case 'inicioAdmin':
            // 1. Seguridad: Validar sesión
            if (!$sesion->validar()) {
                header('Location: login.php');
                exit();
            }

            // 2. Seguridad: Validar Rol (Redundancia necesaria)
            // Aunque el header oculte el botón, debemos proteger la ruta lógica.
            $roles = $sesion->getRol() ?? [];
            
            // Verificamos si NO tiene ninguno de los dos roles permitidos
            if (!in_array('administrador', $roles) && !in_array('superAdministrador', $roles)) {
                
                $_SESSION['mensaje'] = "<div class='alert alert-danger'>Acceso Denegado.</div>";
                header('Location: controladorGet.php?accion=index');
                exit();
            }

            // 3. Obtener Pedidos para gestionar
            // Queremos ver TODO menos los carritos activos.
            $repo = $entidadManager->getRepository(Pedido::class);
            
            $query = $repo->createQueryBuilder('p')
                ->addSelect('u') // Traemos datos del usuario también (Eager Loading)
                ->leftJoin('p.usuario', 'u')
                ->where('p.estado != :estadoCarrito')
                ->setParameter('estadoCarrito', 'CARRITO')
                ->orderBy('p.fechaPedido', 'DESC') // Los más recientes arriba
                ->getQuery();

            $pedidosAdmin = $query->getResult();

            // 4. Cargar la vista
            require __DIR__ . '/vistas/panelAdmin.php';
            break;

        case 'verCarrito':
            // 1. Validar sesión
            if (!$sesion->validar()) {
                $_SESSION['mensaje']= "Debes iniciar sesión para ver tu carrito";
                header('Location: vistas/login.php'); // O vistas/login.php
                exit();
            }

            // 2. Definir variables
            $carrito = null;
            $itemsDelCarrito = new ArrayCollection();
            $subtotal = 0;
            $costoEnvio = 2500;
            $total = 0;

            // 3. Obtener usuario y carrito
            $usuario = $entidadManager->getRepository(Usuario::class)->findOneBy(['username' => $sesion->getUsuario()]);
            $carrito = $entidadManager->getRepository(Pedido::class)->findOneBy([
                'usuario' => $usuario,
                'estado' => 'CARRITO'
            ]);

            // 4. Calcular totales
            if ($carrito) {
                $itemsDelCarrito = $carrito->getItemsProducto();
                if (!$itemsDelCarrito->isEmpty()) {
                    foreach ($itemsDelCarrito as $item) {
                        /** @var \BritosGab\PruebaseCommerce\entidades\ItemProducto $item */
                        $subtotal += $item->getPrecio() * $item->getCantidad();
                    } 
                }
            }
            $total = $subtotal + $costoEnvio;

            // 5. Cargar la VISTA del carrito
            require __DIR__ . '/vistas/carrito.php';
            break;

        // ===============================================
        //  ACCIÓN PARA VER EL CHECKOUT
        // ===============================================
        case 'verCheckout':
            // 1. Validar sesión
            if (!$sesion->validar()) {
                $_SESSION['mensaje'] = "Debes iniciar sesión para finalizar la compra";
                header('Location: vistas/login.php');
                exit();
            }

            // 2. Definir variables
            $carrito = null;
            $itemsDelCarrito = new ArrayCollection();
            $subtotal = 0;
            $costoEnvio = 2500;
            $total = 0;

            // 3. Obtener usuario y carrito
            $usuario = $entidadManager->getRepository(Usuario::class)->findOneBy(['username' => $sesion->getUsuario()]);
            $carrito = $entidadManager->getRepository(Pedido::class)->findOneBy([
                'usuario' => $usuario,
                'estado' => 'CARRITO'
            ]);

            // 4. Calcular totales
            if ($carrito) {
                $itemsDelCarrito = $carrito->getItemsProducto();
                if (!$itemsDelCarrito->isEmpty()) {
                    foreach ($itemsDelCarrito as $item) {
                        /** @var \BritosGab\PruebaseCommerce\entidades\ItemProducto $item */
                        $subtotal += $item->getPrecio() * $item->getCantidad();
                    } 
                }
            }

            // 5. VALIDACIÓN: Si el carrito está vacío, no se puede pagar
            if ($itemsDelCarrito->isEmpty()) {
                $_SESSION['mensaje'] = "Tu carrito está vacío";
                header('Location: controladorGet.php?accion=verCarrito'); // Redirige a la acción del carrito
                exit();
            }

            // 6. Calcular Total final
            $total = $subtotal + $costoEnvio;

            // 7. Cargar la VISTA de checkout
            require __DIR__ . '/vistas/checkout.php';
            break;

        // ===============================================
        //  ACCIÓN PARA VER EL INDEX (PÁGINA PRINCIPAL)
        // ===============================================
        case 'verHistorial':
            // 1. Validar sesión
            if (!$sesion->validar()) {
                header('Location: login.php');
                exit();
            }

            // 2. Obtener usuario
            $usuario = $entidadManager->getRepository(Usuario::class)->findOneBy(['username' => $sesion->getUsuario()]);

            // 3. Buscar pedidos FINALIZADOS (No carrito)
            // Usamos createQueryBuilder para filtrar por "distinto de CARRITO" y ordenar por fecha
            $repo = $entidadManager->getRepository(Pedido::class);
            $query = $repo->createQueryBuilder('p')
                ->where('p.usuario = :usuario')
                ->andWhere('p.estado != :estado') // Diferente de CARRITO
                ->setParameter('usuario', $usuario)
                ->setParameter('estado', 'CARRITO')
                ->orderBy('p.fechaPedido', 'DESC') // Los más recientes primero
                ->getQuery();

            $historialPedidos = $query->getResult();

            // 4. Cargar la VISTA
            require __DIR__ . '/vistas/historial.php';
            break;

        case 'verProducto':
            // 1. Validar que venga el ID
            $idProducto = $_GET['id'] ?? null;

            if (!$idProducto) {
                $_SESSION['mensaje'] = "Producto no especificado.";
                header('Location: controladorGet.php?accion=index');
                exit();
            }

            // 2. Buscar el producto en la BD
            $producto = $entidadManager->getRepository(Producto::class)->find($idProducto);

            // 3. Validar que exista
            if (!$producto) {
                $_SESSION['mensaje'] = "El producto no existe.";
                header('Location: controladorGet.php?accion=index');
                exit();
            }

            // 4. Cargar la VISTA de detalle
            // La variable $producto estará disponible en la vista
            require __DIR__ . '/vistas/productoDetalle.php';
            break;

            case 'verDetallePedido':
            // 1. Validar sesión
            if (!$sesion->validar()) {
                header('Location: login.php');
                exit();
            }

            // 2. Obtener ID del pedido
            $idPedido = $_GET['id'] ?? null;
            if (!$idPedido) {
                $_SESSION['mensaje'] = "Pedido no especificado.";
                header('Location: controladorGet.php?accion=verHistorial');
                exit();
            }

            // 3. Obtener el usuario actual
            $usuario = $entidadManager->getRepository(Usuario::class)->findOneBy(['username' => $sesion->getUsuario()]);

            // 4. Buscar el pedido
            /** @var \BritosGab\PruebaseCommerce\entidades\Pedido $pedido */
            $pedido = $entidadManager->getRepository(Pedido::class)->find($idPedido);

            // 5. Validaciones de Seguridad
            if (!$pedido) {
                $_SESSION['mensaje'] = "El pedido no existe.";
                header('Location: controladorGet.php?accion=verHistorial');
                exit();
            }

            // ¡IMPORTANTE! Verificamos que el pedido pertenezca al usuario logueado
            if ($pedido->getUsuario() !== $usuario) {
                $_SESSION['mensaje'] = "<div class='alert alert-danger'>No tienes permiso para ver este pedido.</div>";
                header('Location: controladorGet.php?accion=verHistorial');
                exit();
            }

            // 6. Cargar vista
            // La vista tendrá acceso a la variable $pedido y sus items ($pedido->getItemsProducto())
            require __DIR__ . '/vistas/pedidoDetalle.php';
            break;
        default:
            // (Aquí puedes poner la lógica para cargar los productos del index)
            // $productos = $entidadManager->getRepository(Producto::class)->findAll();
            
            // Cargar la VISTA del index
            require __DIR__ ."/../Vista/index.php";
            break;
    }
} catch (Exception $e) {
    // Manejo de errores
    echo "Error: " . $e->getMessage();
}
?>