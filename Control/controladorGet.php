<?php
    // Cargar configuración y autoloader
    require_once __DIR__ . '/../config/bootstrap.php';
    require_once __DIR__ . '/../config/config.php';

    // Usar las clases necesarias
    use Perfumeria\Control\Sesion;
    use Perfumeria\Modelo\Usuario;
    use Perfumeria\Modelo\Adjunto;
    use Perfumeria\Modelo\Pedido;
    use Perfumeria\Modelo\Marca;
    use Perfumeria\Modelo\Rol;
    use Perfumeria\Modelo\Categoria;
    use Perfumeria\Modelo\Producto;
    use Doctrine\Common\Collections\ArrayCollection;

    // Inicializar la sesión (siempre necesaria para las vistas)
    $sesion = new Sesion (claveSecreta, $entidadManager);
    $esAdmin = false; // Por defecto no es admin

    if ($sesion -> activa ()) {
        $roles = $sesion -> getRol () ?? [];
        if (in_array ('administrador', $roles) || in_array ('superAdministrador', $roles)) {
            $esAdmin = true;
        }
    }

    // Determinar la acción a realizar (leída desde la URL)
    $accion = $_GET['accion'] ?? 'index'; // 'index' será la acción por defecto

    try {
        switch ($accion) {
            case 'inicioUsuario' :
                $vista = $_GET['vista'];

                $datos = [];

                switch ($vista) {
                    case 'productosUser':
                         // Traemos productos con Marca y Categoria (Eager Loading)
                        $repo = $entidadManager->getRepository(Producto::class);
                        $query = $repo->createQueryBuilder('p')
                        ->addSelect('m', 'c')
                        ->leftJoin('p.marca', 'm')
                        ->leftJoin('p.categoria', 'c')
                        ->orderBy('p.idProducto', 'DESC')
                        ->getQuery();
                        $productos = $query->getResult();

                       require __DIR__ . '/../Vista/productos.php';
                    break;

                    case 'marcasUser':

                        $repo = $entidadManager->getRepository(Marca::class);
                        $query = $repo->createQueryBuilder('m')
                            ->select('m') // seleccionamos la entidad Marca
                            ->orderBy('m.idMarca', 'DESC') // orden descendente por id
                            ->getQuery();

                        $marcas = $query->getResult();

                        require __DIR__ . '/../Vista/marcas.php';
                    break;

                    default :
                  
                    break;
                }
            // ===============================================
            //  ACCIÓN PARA VER EL CARRITO
            // ===============================================
            case 'panelAdmin':
            // 1. Validaciones de seguridad (Igual que antes)
            if (!$sesion->validar()) { header('Location: ../Vista/login.php'); exit(); }
            
            $roles = $sesion->getRol() ?? [];
            if (!in_array('administrador', $roles) && !in_array('superAdministrador', $roles)) {
                $_SESSION['mensaje'] = "<div class='alert alert-danger'>Acceso Denegado.</div>";
                header('Location: controladorGet.php?accion=index');
                exit();
            }

            // 2. Determinar qué vista mostrar (Por defecto: 'pedidos')
            $vista = $_GET['vista'] ?? 'pedidos';
            
            // Variables para la vista (se llenarán según el case)
            $datos = []; 
            
            // 3. Obtener datos según la vista seleccionada
            switch ($vista) {
                case 'pedidos':
                    $repo = $entidadManager->getRepository(Pedido::class);
                    $query = $repo->createQueryBuilder('p')
                        ->addSelect('u')
                        ->leftJoin('p.usuario', 'u')
                        ->where('p.estado != :estadoCarrito')
                        ->setParameter('estadoCarrito', 'CARRITO')
                        ->orderBy('p.fechaPedido', 'DESC')
                        ->getQuery();
                    $datos = $query->getResult();
                    break;

                case 'productos':
                    // Traemos productos con Marca y Categoria (Eager Loading)
                    $repo = $entidadManager->getRepository(Producto::class);
                    $query = $repo->createQueryBuilder('p')
                        ->addSelect('m', 'c')
                        ->leftJoin('p.marca', 'm')
                        ->leftJoin('p.categoria', 'c')
                        ->orderBy('p.idProducto', 'DESC')
                        ->getQuery();
                    $datos = $query->getResult();
                    break;

                case 'clientes':
                    $datos = $entidadManager->getRepository(Usuario::class)->findBy([], ['idUsuario' => 'DESC']);
                    break;

                case 'marcas':
                    $datos = $entidadManager->getRepository(Marca::class)->findAll();
                    break;

                case 'categorias':
                    $datos = $entidadManager->getRepository(Categoria::class)->findAll();
                    break;
                case 'adjuntos':
                    // Traemos todos los adjuntos ordenados por ID descendente (los nuevos primero)
                    $datos = $entidadManager->getRepository(Adjunto::class)->findBy([], ['idAdjunto' => 'DESC']);
                    break;
            }

            // 4. Cargar la vista (ahora es inteligente)
            require __DIR__ . '/../Vista/panelAdmin.php';
            break;

            case 'verCarrito':
                // 1. Validar sesión
                if (!$sesion -> validar ()) {
                    $_SESSION['mensaje']= "Debes iniciar sesión para ver tu carrito";
                    header ('Location: ../Vista/login.php');
                    exit ();
                }

                // 2. Definir variables
                $carrito = null;
                $itemsDelCarrito = new ArrayCollection ();
                $subtotal = 0;
                $costoEnvio = 2500;
                $total = 0;

                // 3. Obtener usuario y carrito
                $usuario = $entidadManager -> getRepository (Usuario :: class) -> findOneBy (['username' => $sesion -> getUsuario ()]);
                $carrito = $entidadManager -> getRepository (Pedido :: class) -> findOneBy (['usuario' => $usuario, 'estado' => 'CARRITO']);

                // 4. Calcular totales
                if ($carrito) {
                    $itemsDelCarrito = $carrito -> getItemsProducto ();
                    if (!$itemsDelCarrito -> isEmpty ()) {
                        foreach ($itemsDelCarrito as $item) {
                            /** @var \BritosGab\PruebaseCommerce\entidades\ItemProducto $item */
                            $subtotal += $item -> getPrecio () * $item -> getCantidad ();
                        }
                    }
                }
                $total = $subtotal + $costoEnvio;

                // 5. Cargar la VISTA del carrito
                require __DIR__ . '/../Vista/carrito.php';
                break;

            // ===============================================
            //  ACCIÓN PARA VER EL CHECKOUT
            // ===============================================
            case 'verCheckout':
                // 1. Validar sesión
                if (!$sesion -> validar ()) {
                    $_SESSION['mensaje'] = "Debes iniciar sesión para finalizar la compra";
                    header ('Location: ../Vista//login.php');
                    exit ();
                }

                // 2. Definir variables
                $carrito = null;
                $itemsDelCarrito = new ArrayCollection ();
                $subtotal = 0;
                $costoEnvio = 2500;
                $total = 0;

                // 3. Obtener usuario y carrito
                $usuario = $entidadManager -> getRepository (Usuario :: class) -> findOneBy (['username' => $sesion -> getUsuario ()]);
                $carrito = $entidadManager -> getRepository (Pedido :: class) -> findOneBy(['usuario' => $usuario, 'estado' => 'CARRITO']);

                // 4. Calcular totales
                if ($carrito) {
                    $itemsDelCarrito = $carrito -> getItemsProducto ();
                    if (!$itemsDelCarrito -> isEmpty ()) {
                        foreach ($itemsDelCarrito as $item) {
                            /** @var \BritosGab\PruebaseCommerce\entidades\ItemProducto $item */
                            $subtotal += $item -> getPrecio () * $item -> getCantidad ();
                        } 
                    }
                }

                // 5. VALIDACIÓN: Si el carrito está vacío, no se puede pagar
                if ($itemsDelCarrito -> isEmpty ()) {
                    $_SESSION['mensaje'] = "Tu carrito está vacío";
                    header ('Location: controladorGet.php?accion=verCarrito'); // Redirige a la acción del carrito
                    exit ();
                }

                // 6. Calcular Total final
                $total = $subtotal + $costoEnvio;

                // 7. Cargar la VISTA de checkout
                require __DIR__ . '/../Vista/checkout.php';
                break;

            // ===============================================
            //  ACCIÓN PARA VER EL INDEX (PÁGINA PRINCIPAL)
            // ===============================================
            case 'verHistorial':
                // 1. Validar sesión
                if (!$sesion -> validar ()) {
                    header ('Location: ../Vista/login.php');
                    exit ();
                }

                // 2. Obtener usuario
                $usuario = $entidadManager -> getRepository (Usuario :: class) -> findOneBy (['username' => $sesion -> getUsuario ()]);

                // 3. Buscar pedidos FINALIZADOS (No carrito)
                // Usamos createQueryBuilder para filtrar por "distinto de CARRITO" y ordenar por fecha
                $repo = $entidadManager -> getRepository (Pedido :: class);
                $query = $repo -> createQueryBuilder ('p')
                -> where ('p.usuario = :usuario')
                -> andWhere ('p.estado != :estado') // Diferente de CARRITO
                -> setParameter ('usuario', $usuario)
                -> setParameter ('estado', 'CARRITO')
                -> orderBy ('p.fechaPedido', 'DESC') // Los más recientes primero
                -> getQuery ();

                $historialPedidos = $query -> getResult ();

                // 4. Cargar la VISTA
                require __DIR__ . '/../Vista/historial.php';
                break;

            case 'verProducto':
                // 1. Validar que venga el ID
                $idProducto = $_GET['id'] ?? null;

                if (!$idProducto) {
                    $_SESSION['mensaje'] = "Producto no especificado.";
                    header ('Location: controladorGet.php?accion=index');
                    exit ();
                }

                $producto = $entidadManager -> getRepository (Producto :: class) -> find ($idProducto);

                // Validación: ¿Existe? ¿Está habilitado?
                // Agregamos la condición: || $producto->getDeshabilitado() !== null
                if (!$producto || $producto -> getDeshabilitado () !== null) {
                    $_SESSION['mensaje'] = "El producto no está disponible.";
                    header ('Location: controladorGet.php?accion=index');
                    exit ();
                }

                require __DIR__ . '/../Vista/productoDetalle.php';
                break;

            case 'verDetallePedido':
                // 1. Validar sesión
                if (!$sesion -> validar ()) {
                    header ('Location: ../Vista/login.php');
                    exit ();
                }

                // 2. Obtener ID del pedido
                $idPedido = $_GET['id'] ?? null;
                if (!$idPedido) {
                    $_SESSION['mensaje'] = "Pedido no especificado.";
                    header ('Location: controladorGet.php?accion=verHistorial');
                    exit ();
                }

                // 3. Obtener el usuario actual
                $usuario = $entidadManager -> getRepository (Usuario :: class) -> findOneBy (['username' => $sesion -> getUsuario ()]);

                // 4. Buscar el pedido
                $pedido = $entidadManager -> getRepository (Pedido :: class) -> find ($idPedido);

                // 5. Validaciones de Seguridad (MODIFICADA PARA ADMIN)
                $esPropietario = ($pedido -> getUsuario () === $usuario);
                $roles = $sesion -> getRol () ?? [];
                $esAdmin = in_array ('administrador', $roles) || in_array ('superAdministrador', $roles);

                // Si NO es el dueño Y TAMPOCO es admin => Fuera.
                if (!$esPropietario && !$esAdmin) {
                    $_SESSION['mensaje'] = "<div class='alert alert-danger'>No tienes permiso para ver este pedido.</div>";
                    header ('Location: controladorGet.php?accion=verHistorial'); // O al index
                    exit ();
                }

                // 6. Cargar vista
                // La vista tendrá acceso a la variable $pedido y sus items ($pedido->getItemsProducto())
                require __DIR__ . '/../Vista/pedidoDetalle.php';
                break;

            case 'editarProducto':
            case 'nuevoProducto': // Usamos el mismo case para ambos
                // 1. Validar Admin
                if (!$sesion->validar()) { header('Location: vistas/login.php'); exit(); }
                $roles = $sesion->getRol() ?? [];
                if (!in_array('administrador', $roles) && !in_array('superAdministrador', $roles)) {
                    header('Location: controladorGet.php?accion=index'); exit();
                }

                // 2. Preparar datos
                $idProducto = $_GET['id'] ?? null;
                $producto = null;
                
                // Traemos listas para los select (Marcas y Categorías)
                $marcas = $entidadManager->getRepository(Marca::class)->findAll();
                $categorias = $entidadManager->getRepository(Categoria::class)->findAll();

                if ($idProducto) {
                    // MODO EDICIÓN: Buscamos el producto
                    $producto = $entidadManager->getRepository(Producto::class)->find($idProducto);
                } 
                // Si no hay ID, $producto queda null (MODO CREACIÓN)

                require __DIR__ . '/../Vista/formProducto.php';
                break;
        
            case 'editarCliente':
            case 'nuevoCliente':
                // 1. Validar Admin
                if (!$sesion -> validar ()) {
                    header ('Location: ../Vista/login.php');
                    exit ();
                }
                $roles = $sesion -> getRol () ?? [];
                if (!in_array ('administrador', $roles) && !in_array ('superAdministrador', $roles)) {
                    header ('Location: controladorGet.php?accion=index');
                    exit ();
                }

                $idUsuario = $_GET['id'] ?? null;
                $cliente = null;

                // Traemos los roles para el select
                $listaRoles = $entidadManager -> getRepository (Rol :: class) -> findAll ();

                if ($idUsuario) {
                    $cliente = $entidadManager -> getRepository (Usuario :: class) -> find ($idUsuario);
                }

                require __DIR__ . '/../Vista/formCliente.php';
                break;

                case 'nuevoAdjunto':
            // 1. Validar Admin
            if (!$sesion->validar()) { header('Location: ../Vista/login.php'); exit(); }
            $roles = $sesion->getRol() ?? [];
            if (!in_array('administrador', $roles) && !in_array('superAdministrador', $roles)) { exit(); }

            // 2. Traer productos para el Select (Solo los habilitados para evitar líos)
            $productos = $entidadManager->getRepository(Producto::class)->findBy(['deshabilitado' => null]);

            require __DIR__ . '/../Vista/formAdjunto.php';
            break;

            // ===============================================
            //  ABM GENÉRICO (Marcas y Categorías)
            // ===============================================
            case 'formularioAuxiliar':
                // 1. Validar Admin
                if (!$sesion -> validar ()) {
                    header ('Location: ../Vista/login.php');
                    exit ();
                }
                $roles = $sesion -> getRol () ?? [];
                if (!in_array ('administrador', $roles) && !in_array ('superAdministrador', $roles)) {
                    header ('Location: controladorGet.php?accion=index');
                    exit ();
                }

                // 2. Detectar qué estamos editando ('marca' o 'categoria')
                $tipo = $_GET['tipo'] ?? null; // Debe venir en la URL
                $id = $_GET['id'] ?? null;
                $entidad = null;

                if (!in_array ($tipo, ['marca', 'categoria'])) {
                    $_SESSION['mensaje'] = "Tipo de dato no válido.";
                    header ('Location: controladorGet.php?accion=panelAdmin');
                    exit ();
                }

                // 3. Buscar el dato si es edición
                if ($id) {
                    if ($tipo === 'marca') {
                        $entidad = $entidadManager -> getRepository (Marca :: class) -> find ($id);
                    } else {
                        $entidad = $entidadManager -> getRepository (Categoria :: class) -> find ($id);
                    }
                }

                // 4. Cargar vista genérica
                require __DIR__ . '/../Vista/formAuxiliar.php';
                break;

            case 'miPerfil':
            // 1. Validar sesión
            if (!$sesion->validar()) { header('Location: ./../Vista/miPerfil.php'); exit(); }

            // 2. Obtener el usuario ACTUAL (desde el token de sesión)
            $usuario = $entidadManager->getRepository(Usuario::class)->findOneBy(['username' => $sesion->getUsuario()]);

            if (!$usuario) {
                // Caso raro: sesión válida pero usuario borrado de BD
                $sesion->cerrar();
                header('Location: ./../Vista/login.php');
                exit();
            }

            // 3. Cargar vista
            require __DIR__ . '/../Vista/miPerfil.php';
            break;
            
            default: // INDEX (Catálogo)
                $repo = $entidadManager -> getRepository (Producto :: class);
                
                $query = $repo -> createQueryBuilder ('p')
                    // FILTRO: Solo mostramos si la fecha de deshabilitado es NULA
                    -> where ('p.deshabilitado IS NULL') 
                    -> andWhere ('p.stock > 0') // (Opcional) Ocultar si no hay stock
                    -> orderBy ('p.idProducto', 'DESC')
                    -> getQuery ();

                $productos = $query -> getResult ();
                
                require __DIR__ ."/../Vista/index.php";
                break;
        }
    }   
    catch (Exception $e) {
        // Manejo de errores
        echo "Error: " . $e->getMessage();
    }
?>