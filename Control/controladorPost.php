<?php
    // Carga configuracion y motor ORM
    require_once __DIR__ . '/../config/bootstrap.php';
    require_once __DIR__ . '/../config/config.php';
    require_once __DIR__ . '/../Includes/formData.php';

    use Perfumeria\Control\Sesion;
    use Perfumeria\Modelo\Usuario;
    use Perfumeria\Modelo\Categoria;
    use Perfumeria\Modelo\Marca;
    use Perfumeria\Modelo\Producto;
    use Perfumeria\Modelo\Pedido;
    use Perfumeria\Modelo\Rol;
    use Perfumeria\Modelo\ItemProducto;

    // Solo se permite el acceso a este archivo a través de una petición POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header ('Location: ../Vista/login.php');
        exit ('Acceso no permitido.');
    }

    // Obtengo datos del form y acción ejecutada
    $datos = datosEnviados (); 
    $accion = $datos['accion'] ?? null;

    // Verifico acción
    if (!$accion) {
        $_SESSION['error'] = 'Acción no especificada.';
        header ('Location: ../Vista/login.php');
        exit ();
    }

    // Inicializo clase Sesion (JWT)
    $sesion = new Sesion (claveSecreta, $entidadManager);
    $mensaje = "";

    // Según la acción, ejecutamos
    try {
        switch ($accion) {
            case 'buscar': // ========== BUSCAR ==========
                $terminoDeBusqueda = $datos['terminoBusqueda'] ?? '';

                if (empty ($terminoDeBusqueda)) {
                    $productos = [];
                }
                else {
                    // Usamos QueryBuilder para filtrar explícitamente los deshabilitados
                    $qb = $entidadManager -> getRepository (Producto :: class) -> createQueryBuilder ('p');
                
                    $query = $qb -> where ('p.nombre LIKE :termino OR p.descripcion LIKE :termino')
                    -> andWhere ('p.deshabilitado IS NULL') // <--- ¡EL FILTRO IMPORTANTE!
                    -> setParameter ('termino', '%' . $terminoDeBusqueda . '%')
                    -> getQuery ();
                    
                    $productos = $query -> getResult ();
                }

                // Ajusta la ruta si es necesario según tu estructura
                require_once __DIR__ . '/../Vista/resultadosBusqueda.php';
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
                if (empty ($username) || empty ($password) || empty ($email) || empty ($nombre) || empty ($apellido) || empty ($direccion)) {
                    throw new Exception ("Por favor, complete todos los campos.");
                }

                // ===========================================
                // ----- VALIDO DE EXISTENCIA DE USUARIO -----
                // ===========================================
                $repositorio = $entidadManager -> getRepository (Usuario :: class);

                $usuarioExistente = $repositorio -> findOneBy (['username' => $username]);

                if ($usuarioExistente) {
                    throw new Exception ("El nombre de usuario ya está en uso.");
                }
                // ===========================================

                $hashed_password = password_hash ($password, PASSWORD_BCRYPT);

                $nuevoUsuario = new Usuario ();
            
                $nuevoUsuario -> setUsername ($username);
                $nuevoUsuario -> setEmail ($email);
                $nuevoUsuario -> setContrasenia ($hashed_password);
                $nuevoUsuario -> setNombre ($nombre);
                $nuevoUsuario -> setApellido ($apellido);
                $nuevoUsuario -> setDireccion ($direccion);

                // Asignar el rol 'cliente' por defecto
                $rolCliente = $entidadManager -> getRepository (Rol :: class) -> findOneBy (['nombre' => 'cliente']);
            
                if (!$rolCliente) {
                    throw new Exception ("No se pudo encontrar el rol de cliente.");
                }

                $nuevoUsuario -> setRol ($rolCliente);

                // Ejecutar y verificar
                $entidadManager -> persist ($nuevoUsuario);
                $entidadManager -> flush (); 

                $_SESSION['mensaje'] = "¡Cuenta creada con éxito! Ya puedes iniciar sesión.";
                header ('Location: ../Vista/login.php');
                exit ();
                break;

            case 'login':// ========== LOGIN ==========
                $username = $datos['usuario'] ?? '';
                $password = $datos['password'] ?? '';

                // ===========================================
                // --- VALIDACIÓN DE USUARIO DESHABILITADO ---
                // ===========================================
                $repositorio = $entidadManager -> getRepository (Usuario :: class);
            
                $usuarioObj = $repositorio -> findOneBy (['username' => $username]);

                // Verifico que no este deshabilitado
                if ($usuarioObj && $usuarioObj->getDeshabilitado() !== null) {
                    $_SESSION['mensaje'] = "Esta cuenta ha sido deshabilitada";
                    header ('Location: ../Vista/login.php');
                    exit ();
                }
                // ===========================================

                // Valido usando la clase Sesion.
                if ($sesion -> iniciar ($username, $password)) {
                    // Si es true, la clase Sesion YA CREÓ las cookies JWT
                    $_SESSION['bienvenida'] = "Bienvenido $username!";
                    header ("Location: controladorGet.php?accion=index");
                    exit ();
                }
                else {
                    $_SESSION['mensaje'] = "Usuario o contraseña incorrectos";
                    header ('Location: ../Vista/login.php');
                    exit ();
                }
                break;

            case 'agregarAlCarrito': // ========== AGREGAR AL CARRITO ==========
                // Verificar si el usuario está logueado
                if (!$sesion -> validar ()) {
                    // Si no está logueado, no puede tener un carrito en la BD
                    $_SESSION['mensaje'] = "Iniciar sesión para agregar productos al carrito.";
                    header ('Location: ../Vista/login.php');
                    exit ();
                }

                // Obtengo el id del producto que se quiso agregar al carrito
                // Asumiendo que $datos es tu $_POST
                $idProducto = $datos['idProducto'] ?? null;
                if (!$idProducto) {
                    throw new Exception ("No se especificó ningún producto.");
                }

                // Obtener el usuario y el producto
                $usuario = $entidadManager -> getRepository (Usuario :: class) -> findOneBy (['username' => $sesion -> getUsuario ()]);
                $producto = $entidadManager -> getRepository (Producto :: class) -> find ($idProducto);

                if (!$usuario) {
                    // Esto puede pasar si la sesión está corrupta o el usuario fue eliminado
                    session_destroy (); // Destruimos la sesión inválida
                    throw new Exception ("Error de sesión. Por favor, inicie sesión de nuevo.");
                }
            
                if (!$producto) {
                    throw new Exception ("El producto no existe.");
                }

                if ($producto -> getDeshabilitado () !== null) {
                    // Es un error de usuario, no de sistema. Redirigimos con un mensaje.
                    $_SESSION['mensaje'] = "Este producto ya no está disponible";
                    header ('Location: controladorGet.php'); // Redirigir al carrito
                    exit ();
                }

                // 2. Verificar si hay stock en general
                $stockDisponible = $producto -> getStock ();
                if ($stockDisponible <= 0) {
                    $_SESSION['mensaje'] = "Lo sentimos, este producto está agotado";
                    header ('Location: ../Vista/index.php'); // Redirigir al carrito
                    exit ();
                }

                // 3. Buscar el carrito activo del usuario
                $carrito = $entidadManager -> getRepository (Pedido :: class) -> findOneBy (['usuario' => $usuario,'estado'    => 'CARRITO']);

                // Si no tiene carrito, crear uno nuevo
                if (!$carrito) {
                    $carrito = new Pedido ();
                    $carrito -> setUsuario ($usuario);
                    // El estado 'CARRITO' se pone por defecto en el constructor
                    $entidadManager -> persist ($carrito);
                }

                // Revisar si el producto YA ESTÁ en el carrito
                $itemExistente = null;
                /** @var ItemProducto $item */
                foreach ($carrito -> getItemsProducto () as $item) {
                    if ($item -> getProducto() === $producto) {
                        $itemExistente = $item;
                        break;
                    }
                }

                $cantidadActualEnCarrito = 0;
                if ($itemExistente) {
                    $cantidadActualEnCarrito = $itemExistente -> getCantidad ();
                }

                // 3. Verificar si agregar 1 MÁS excede el stock disponible
                if (($cantidadActualEnCarrito + 1) > $stockDisponible) {
                    $_SESSION['mensaje'] = "No puedes agregar más unidades de este producto. Stock máximo alcanzado ($stockDisponible)";
                    header ('Location: controladorGet.php'); // Redirigir al carrito
                    exit ();
                }

                if ($itemExistente) {
                    // Si ya existe, solo suma 1 a la cantidad
                    $itemExistente -> setCantidad ($itemExistente -> getCantidad () + 1);
                }
                else {
                    // Si no existe, crea un nuevo ItemProducto
                    $nuevoItem = new ItemProducto ();
                    $nuevoItem -> setProducto ($producto);
                    $nuevoItem -> setCantidad (1);
                    $nuevoItem -> setPrecio ($producto -> getPrecio ()); // Guarda el precio actual
                    // Asocia el item al pedido
                    $carrito -> agregarItemProducto ($nuevoItem);
                }

                // Guardar todo en la BD
                $entidadManager -> flush ();

                // Redirigir al carrito
                $_SESSION['mensaje'] = "¡Producto agregado al carrito!";
                header ('Location: controladorGet.php?accion=verCarrito'); // apunta al controlador GET del carrito
                exit ();
                break;

            case 'restarItem': // ========== RESTAR UNIDAD ==========
                // 1. Validar sesión
                if (!$sesion -> validar ()) {
                    header ('Location: ../Vista/login.php');
                    exit ();
                }

                // 2. Obtener datos
                $idItem = $datos['idItem'] ?? null;
                if (!$idItem) {
                    throw new Exception ("Error al identificar el producto.");
                }

                // 3. Buscar usuario e item
                $usuario = $entidadManager -> getRepository (Usuario :: class) -> findOneBy (['username' => $sesion->getUsuario()]);
                /** @var ItemProducto $item */
                $item = $entidadManager -> getRepository (ItemProducto :: class) -> find ($idItem);

                // 4. Validar seguridad (que el item sea del usuario)
                $carrito = $entidadManager -> getRepository (Pedido :: class) -> findOneBy (['usuario' => $usuario, 'estado' => 'CARRITO']);

                if (!$item || !$carrito || $item -> getPedido () !== $carrito) {
                    $_SESSION['mensaje'] = "<div class='alert alert-danger'>No se pudo modificar el item.</div>";
                    header ('Location: controladorGet.php?accion=verCarrito');
                    exit ();
                }

                // 5. Lógica de resta
                $cantidadActual = $item->getCantidad();

                if ($cantidadActual > 1) {
                    // Si hay más de 1, restamos
                    $item -> setCantidad ($cantidadActual - 1);
                    $entidadManager -> flush ();
                    $_SESSION['mensaje'] = "<div class='alert alert-success'>Cantidad actualizada.</div>";
                }
                else {
                    // Si es 1, podemos optar por eliminarlo o lanzar error.
                    // Como pusimos 'disabled' en la vista, esto es solo una protección extra.
                    $_SESSION['mensaje'] = "La cantidad mínima es 1. Usa el botón de eliminar para quitarlo";
                }

                // 6. Redirigir
                header ('Location: controladorGet.php?accion=verCarrito');
                exit ();
                break;

            case 'eliminarItem': // ========== ELIMINAR ITEM DEL CARRITO ==========
                // 1. Verificar que el usuario esté logueado
                if (!$sesion->validar()) {
                    $_SESSION['mensaje'] = "Iniciar sesión para modificar tu carrito.";
                    header ('Location: ../Vista/login.php');
                    exit ();
                }

                // 2. Obtener el ID del item a eliminar
                $idItem = $datos['idItem'] ?? null;
                if (!$idItem) {
                    throw new Exception ("No se especificó ningún item para eliminar.");
                }

                // 3. Obtener el usuario y el item
                $usuario = $entidadManager -> getRepository (Usuario :: class) -> findOneBy (['username' => $sesion -> getUsuario ()]);
            
                /** @var ItemProducto $item */
                $item = $entidadManager -> getRepository (ItemProducto :: class) -> find ($idItem);

                // 4. Validar que el item exista
                if (!$item) {
                    $_SESSION['mensaje'] = "El item que intentas quitar ya no existe";
                    header ('Location: controladorGet.php');
                    exit ();
                }

                // ==============================================================
                //  VERIFICACIÓN DE SEGURIDAD
                // ==============================================================
                // Comprobar que el item pertenezca al carrito activo del usuario
            
                $carrito = $entidadManager -> getRepository (Pedido :: class) -> findOneBy (['usuario' => $usuario, 'estado'  => 'CARRITO']);

                // Si el item no pertenece al carrito actual, lanzamos un error.
                // Esto evita que un usuario pueda eliminar items de otro usuario
                // si adivina el ID del item.
                if (!$carrito || $item -> getPedido() !== $carrito) {
                    $_SESSION['mensaje'] = "Acción no permitida";
                    header ('Location: controladorGet.php');
                    exit ();
                }
                // ==============================================================

                // 5. Eliminar el item
                // (Doctrine se encarga de quitarlo de la colección del Pedido
                // gracias a tu método Pedido::eliminarItemProducto y 'cascade')
                $entidadManager -> remove ($item);
            
                // 6. Guardar los cambios en la BD
                $entidadManager -> flush ();

                // 7. Redirigir al carrito
                $_SESSION['mensaje'] = "Producto eliminado del carrito";
                header ('Location: controladorGet.php?accion=verCarrito');
                exit ();
                break;
        
            case 'procesarPedido': // ========== FINALIZAR COMPRA ==========
                // 1. Validar sesión
                if (!$sesion -> validar()) {
                    header ('Location: ../Vista/login.php');
                    exit ();
                }

                // 2. Obtener datos RELEVANTES del formulario
                // Solo nos interesa lo que puede cambiar: dirección y método de envío
                $direccion = trim ($datos['direccion'] ?? '');
                $metodoEnvio = $datos['metodoEnvio'] ?? 'Estandar';
            
                if (empty ($direccion)) {
                    throw new Exception ("La dirección de envío es obligatoria.");
                }

                // 3. Obtener Usuario y Carrito
                // Usamos los datos del usuario DIRECTO de la BD (seguro)
                $usuario = $entidadManager -> getRepository (Usuario :: class) -> findOneBy (['username' => $sesion -> getUsuario ()]);
            
                $carrito = $entidadManager -> getRepository (Pedido :: class) -> findOneBy (['usuario' => $usuario, 'estado' => 'CARRITO']);

                if (!$carrito || $carrito -> getItemsProducto () -> isEmpty ()) {
                    $_SESSION['mensaje'] = "<div class='alert alert-warning'>Tu carrito está vacío.</div>";
                    header ('Location: controladorGet.php?accion=verCarrito');
                    exit ();
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
                $items = $carrito -> getItemsProducto ();

                /** @var ItemProducto $item */
                foreach ($items as $item) {
                    $producto = $item -> getProducto ();
                    $cantidad = $item -> getCantidad ();
                    $stockActual = $producto -> getStock ();

                    if ($stockActual < $cantidad) {
                        $_SESSION['mensaje'] = "<div class='alert alert-danger'>Stock insuficiente para: " . $producto->getNombre() . "</div>";
                        header ('Location: controladorGet.php?accion=verCarrito');
                        exit ();
                    }

                    $producto -> setStock ($stockActual - $cantidad);
                    $subtotal += $item -> getPrecio() * $cantidad;
                }

                // 6. ACTUALIZAR PEDIDO CON VALORES FINALES
                $totalFinal = $subtotal + $costoEnvio;

                // Guardamos la dirección específica para ESTE pedido (historial)
                // No modificamos la dirección del perfil del usuario ($usuario->setDireccion), 
                // solo la del pedido.
                $carrito -> setDireccion ($direccion);
                $carrito -> setMetodoEnvio ($metodoEnvio);
                $carrito -> setCostoEnvio ($costoEnvio);
                $carrito -> setMontoTotal ($totalFinal);
                $carrito -> setFechaFinalizado (new DateTime ());
                $carrito -> setEstado ('PAGADO');

                // ... (guardar en BD con flush) ...
                $entidadManager -> flush ();

                // 8. PREPARAR EL POPUP Y REDIRIGIR
                // Guardamos el ID del pedido en una variable especial de sesión
                $_SESSION['pedido_creado_id'] = $carrito -> getIdPedido ();
            
                // Redirigimos directamente al historial
                header ('Location: controladorGet.php?accion=verHistorial');
                exit ();
                break;

            case 'cambiarEstado': // ========== GESTIÓN ADMIN: CAMBIAR ESTADO ==========
                // 1. Validar Sesión
                if (!$sesion -> validar ()) {
                    header ('Location: ../Vista/login.php');
                    exit ();
                }

                // 2. Validar Roles (¡AQUÍ SOLÍA ESTAR EL ERROR!)
                // Debemos usar los nombres EXACTOS de tu base de datos
                $roles = $sesion->getRol() ?? [];
                if (!in_array ('administrador', $roles) && !in_array ('superAdministrador', $roles)) {
                    $_SESSION['mensaje'] = "<div class='alert alert-danger'>No tienes permisos para gestionar pedidos.</div>";
                    header ('Location: controladorGet.php?accion=index');
                    exit ();
                }

                // 3. Obtener datos
                $idPedido = $datos['idPedido'] ?? null;
                $nuevoEstado = $datos['nuevoEstado'] ?? null;
            
                /** @var Pedido $pedido */
                $pedido = $entidadManager -> getRepository (Pedido :: class) -> find ($idPedido);

                if (!$pedido || !$nuevoEstado) {
                    throw new Exception ("Datos inválidos.");
                }

                // 4. Lógica de Cancelación (Devolver Stock)
                if ($nuevoEstado === 'CANCELADO' && $pedido -> getEstado () !== 'CANCELADO') {
                    /** @var ItemProducto $item */
                    foreach ($pedido -> getItemsProducto () as $item) {
                        $producto = $item -> getProducto ();
                        // Restauramos el stock
                        $producto -> setStock ($producto -> getStock () + $item -> getCantidad ());
                    }
                    $_SESSION['mensaje'] = "<div class='alert alert-warning'>Pedido #{$idPedido} cancelado. Stock restaurado.</div>";
                }
                else {
                    $_SESSION['mensaje'] = "<div class='alert alert-success'>Pedido #{$idPedido} marcado como {$nuevoEstado}.</div>";
                }

                // 5. Actualizar y Guardar
                $pedido -> setEstado ($nuevoEstado);
                $entidadManager -> flush ();

                // 6. Redirigir al panel de pedidos
                header ('Location: controladorGet.php?accion=panelAdmin&vista=pedidos');
                exit ();
                break;
        
            case 'actualizarStockRapido':
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

                $idProducto = $datos['idProducto'] ?? null;
                $operacion = $datos['operacion'] ?? null; // 'sumar', 'restar', 'fijar'
                $stockInput = (int)($datos['stock'] ?? 0);

                $producto = $entidadManager -> getRepository (Producto :: class) -> find ($idProducto);
            
                if ($producto) {
                    $stockActual = $producto -> getStock ();

                    if ($operacion === 'sumar') {
                        $producto -> setStock ($stockActual + 1);
                    }
                    elseif ($operacion === 'restar') {
                        // Evitar stock negativo
                        $nuevoStock = max (0, $stockActual - 1);
                        $producto -> setStock ($nuevoStock);
                    }
                    elseif ($operacion === 'fijar') {
                        // Usamos el valor que escribió en el input
                        $producto -> setStock (max(0, $stockInput));
                    }
                    $entidadManager -> flush ();
                }

                // Volver a la misma pestaña
                header ('Location: controladorGet.php?accion=panelAdmin&vista=productos');
                exit ();
                break;

            case 'guardarProducto':
                // Validar Admin...
                if (!$sesion -> validar ()) {
                    header ('Location: ../Vista/login.php');
                    exit ();
                }
                $roles = $sesion -> getRol () ?? [];
                if (!in_array ('administrador', $roles) && !in_array ('superAdministrador', $roles)) {
                    header ('Location: controladorGet.php?accion=index'); exit();
                }
            
                $idProducto = $datos['idProducto'] ?? null;
                $nombre = $datos['nombre'] ?? '';
                $precio = $datos['precio'] ?? 0;
                $stock = $datos['stock'] ?? 0;
                $idMarca = $datos['idMarca'] ?? null;
                $idCategoria = $datos['idCategoria'] ?? null;
                $codigo = $datos['codigo'] ?? ''; 

                // Buscar entidades relacionadas
                $marca = $entidadManager -> getRepository (Marca :: class) -> find ($idMarca);
                $categoria = $entidadManager -> getRepository (Categoria :: class) -> find ($idCategoria);

                if ($idProducto) {
                    // --- EDICIÓN ---
                    $producto = $entidadManager -> getRepository (Producto :: class) -> find ($idProducto);
                    if (!$producto) throw new Exception("Producto no encontrado");
                    
                    // NOTA: No actualizamos el código referencia en edición porque el input estaba disabled
                    $_SESSION['mensaje'] = "<div class='alert alert-success'>Producto actualizado.</div>";
                }    
                else {
                    // --- CREACIÓN ---
                    $producto = new Producto ();
                    // En creación, el código sí viene
                    $producto -> setCodigoReferencia ($codigo);
                    $_SESSION['mensaje'] = "<div class='alert alert-success'>Producto creado.</div>";
                }

                // Actualizamos los campos comunes
                $producto -> setNombre ($nombre);
                $producto -> setPrecio ((float)$precio);
                $producto -> setStock ((int)$stock);
                $producto -> setDescripcion ($datos['descripcion'] ?? '');
                $producto -> setMarca ($marca);
                $producto -> setCategoria ($categoria);

                // Guardar
                $entidadManager -> persist ($producto); 
                $entidadManager -> flush ();

                header ('Location: controladorGet.php?accion=panelAdmin&vista=productos');
                exit ();
                break;

            case 'eliminarProducto': // ========== ABM PRODUCTO: DESHABILITAR ==========
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

                // 2. Obtener datos
                $idProducto = $datos['idProducto'] ?? null;
                $tipo = $datos['tipo'] ?? 'baja'; // 'baja' o 'alta'

                $producto = $entidadManager -> getRepository (Producto :: class) -> find ($idProducto);

                if (!$producto) {
                    $_SESSION['mensaje'] = "<div class='alert alert-danger'>Producto no encontrado.</div>";
                }
                else {
                    if ($tipo === 'baja') {
                        // SOFT DELETE: Ponemos la fecha de hoy
                        $producto -> setDeshabilitado (new DateTime ());
                        $_SESSION['mensaje'] = "<div class='alert alert-warning'>Producto deshabilitado correctamente.</div>";
                    }
                    else {
                        // RESTAURAR: Volvemos a null
                        $producto -> setDeshabilitado (null);
                        $_SESSION['mensaje'] = "<div class='alert alert-success'>Producto restaurado y visible.</div>";
                    }
                    $entidadManager -> flush ();
                }

                // 3. Redirigir al panel
                header ('Location: controladorGet.php?accion=panelAdmin&vista=productos');
                exit ();
                break;

            // ===============================================
            //  ABM GENÉRICO POST (Guardar)
            // ===============================================
            case 'guardarAuxiliar':
                // Validar Admin... (Copia tu validación aquí)
                if (!$sesion -> validar ()) {
                    exit();
                } // ...etc

                $tipo = $datos['tipo'] ?? null;
                $id = $datos['id'] ?? null;
                $nombre = trim ($datos['nombre'] ?? '');

                if (empty ($nombre)) throw new Exception ("El nombre es obligatorio.");

                // Instanciamos según el tipo
                if ($tipo === 'marca') {
                    $entidad = $id ? $entidadManager -> getRepository (Marca :: class) -> find ($id) : new Marca ();
                }
                elseif ($tipo === 'categoria') {
                    $entidad = $id ? $entidadManager -> getRepository (Categoria :: class) -> find ($id) : new Categoria ();
                }
                else {
                    throw new Exception ("Tipo de dato inválido.");
                }

                // Como ambas clases tienen setNombre(), esto funciona para las dos
                $entidad -> setNombre ($nombre);

                if (!$id) $entidadManager -> persist ($entidad);
                $entidadManager -> flush ();

                $_SESSION['mensaje'] = "<div class='alert alert-success'>" . ucfirst($tipo) . " guardada correctamente.</div>";
            
                // Redirigir a la vista correcta (marcas o categorias)
                header ("Location: controladorGet.php?accion=panelAdmin&vista={$tipo}s");
                exit ();
                break;

            // ===============================================
            //  ABM GENÉRICO POST (Eliminar)
            // ===============================================
            case 'eliminarAuxiliar':
                // Validar Admin...
                if (!$sesion -> validar ()) {
                    header ('Location: ../Vista/login.php');
                    exit ();
                }
                $roles = $sesion -> getRol () ?? [];
                if (!in_array ('administrador', $roles) && !in_array ('superAdministrador', $roles)) {
                    header ('Location: controladorGet.php?accion=index'); exit();
                }

                $tipo = $datos['tipo'] ?? null;
                $id = $datos['id'] ?? null;

                if ($tipo === 'marca') {
                    $entidad = $entidadManager -> getRepository (Marca :: class) -> find ($id);
                }
                elseif ($tipo === 'categoria') {
                    $entidad = $entidadManager -> getRepository (Categoria :: class) -> find ($id);
                }
                else {
                    $entidad = null;
                }

                if ($entidad) {
                    try {
                        $entidadManager -> remove ($entidad);
                        $entidadManager -> flush ();
                        $_SESSION['mensaje'] = "<div class='alert alert-warning'>" . ucfirst($tipo) . " eliminada correctamente.</div>";
                    }
                    catch (\Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException $e) {
                        // CAPTURAMOS EL ERROR DE RESTRICCIÓN (RESTRICT)
                        $_SESSION['mensaje'] = "<div class='alert alert-danger'>
                            <strong>Error:</strong> No se puede eliminar esta " . ucfirst($tipo) . " porque tiene productos asociados. 
                            <br>Por favor, elimina los productos o asígnales otra " . ucfirst($tipo) . " antes de continuar.
                            </div>";
                    }
                    catch (Exception $e) {
                        // Cualquier otro error
                        $_SESSION['mensaje'] = "<div class='alert alert-danger'>Ocurrió un error al intentar eliminar: " . $e->getMessage() . "</div>";
                    }
                }
                else {
                    $_SESSION['mensaje'] = "<div class='alert alert-danger'>El elemento no existe.</div>";
                }

                header ("Location: controladorGet.php?accion=panelAdmin&vista={$tipo}s");
                exit ();
                break;

            case 'guardarCliente':
                // Validar Admin...
                if (!$sesion -> validar ()) {
                    header('Location: ../Vistas/login.php');
                    exit ();
                }
                $roles = $sesion -> getRol () ?? [];
                if (!in_array ('administrador', $roles) && !in_array ('superAdministrador', $roles)) {
                    exit();
                }

                $idUsuario = $datos['idUsuario'] ?? null;
                $username = trim ($datos['usuario'] ?? '');
                $nombre = trim ($datos['nombre'] ?? '');
                $apellido = trim ($datos['apellido'] ?? '');
                $email = trim ($datos['email'] ?? '');
                $direccion = trim ($datos['direccion'] ?? '');
                $password = $datos['password'] ?? '';
                $idRol = $datos['idRol'] ?? null;

                if (empty ($username) || empty ($email)) throw new Exception ("Datos obligatorios faltantes.");

                // Buscar Rol
                $rolObj = $entidadManager -> getRepository (Rol :: class) -> find ($idRol);

                if ($idUsuario) {
                    // --- EDICIÓN ---
                    $cliente = $entidadManager -> getRepository (Usuario :: class) -> find ($idUsuario);
                    if (!$cliente) throw new Exception("Usuario no encontrado");

                    // Si escribieron password, la actualizamos. Si no, la dejamos igual.
                    if (!empty ($password)) {
                        $cliente -> setContrasenia (password_hash ($password, PASSWORD_BCRYPT));
                    }
                    $_SESSION['mensaje'] = "<div class='alert alert-success'>Cliente actualizado.</div>";
                }
                else {
                    // --- CREACIÓN ---
                    // Verificar duplicados de usuario
                    $existe = $entidadManager -> getRepository (Usuario :: class) -> findOneBy (['username' => $username]);
                    if ($existe) throw new Exception ("El nombre de usuario ya existe.");

                    $cliente = new Usuario ();
                    $cliente -> setUsername ($username);
                
                    if (empty ($password)) throw new Exception ("La contraseña es obligatoria para nuevos usuarios.");
                    $cliente -> setContrasenia (password_hash ($password, PASSWORD_BCRYPT));
                
                    $_SESSION['mensaje'] = "<div class='alert alert-success'>Cliente creado exitosamente.</div>";
                }

                // Actualizar datos comunes
                $cliente -> setNombre ($nombre);
                $cliente -> setApellido ($apellido);
                $cliente -> setEmail ($email);
                $cliente -> setDireccion ($direccion);
                $cliente -> setRol ($rolObj);

                if (!$idUsuario) $entidadManager -> persist ($cliente);
                $entidadManager -> flush ();

                header ('Location: controladorGet.php?accion=panelAdmin&vista=clientes');
                exit ();
                break;

            case 'eliminarCliente':
                // Validar Admin...
                if (!$sesion -> validar ()) {
                    exit ();
                }
                $roles = $sesion -> getRol () ?? [];
                if (!in_array ('administrador', $roles) && !in_array ('superAdministrador', $roles)) {
                    exit ();
                }

                $idUsuario = $datos['idUsuario'] ?? null;
                $tipo = $datos['tipo'] ?? 'baja';

                $cliente = $entidadManager -> getRepository (Usuario :: class) -> find ($idUsuario);

                if ($cliente) {
                    if ($tipo === 'baja') {
                        $cliente -> setDeshabilitado (new DateTime ()); // SOFT DELETE
                        $_SESSION['mensaje'] = "<div class='alert alert-warning'>Usuario desactivado.</div>";
                    }
                    else {
                        $cliente -> setDeshabilitado (null); // RESTAURAR
                        $_SESSION['mensaje'] = "<div class='alert alert-success'>Usuario reactivado.</div>";
                    }
                $entidadManager -> flush ();
                }

                header ('Location: controladorGet.php?accion=panelAdmin&vista=clientes');
                exit ();
                break;
            
            default:
                throw new Exception ("La acción '{$accion}' no es válida.");
                break;
        }
    }
    catch (Exception $e) {
        // Si algo sale mal, guardamos el mensaje de error y redirigimos
        // (Usar 'error' o 'mensaje' según cómo lo muestres)
        $_SESSION['mensaje'] = "<div class='alert alert-danger'>Error: " . $e->getMessage() . "</div>";
        // Redirigir a la página de origen o al login
        if ($accion === 'registro') {
            header('Location: ../Vista/registro.php');
        }
        else {
            header('Location: ../Vista/login.php');
        }
        exit ();
    }
?>