<!DOCTYPE html>
<html lang="es">
<head>
    <?php include_once '../Includes/head.php'; ?>
</head>
<body>
    <?php include_once '../Includes/header.php'; ?>

    <main class="container-fluid px-4 my-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="bi bi-speedometer2"></i> Panel de Administración</h2>
        </div>

        <?php if (isset($_SESSION['mensaje'])): ?>
            <?= $_SESSION['mensaje']; unset($_SESSION['mensaje']); ?>
        <?php endif; ?>

        <ul class="nav nav-tabs mb-4">
            <li class="nav-item">
                <a class="nav-link <?= $vista === 'pedidos' ? 'active' : '' ?>" href="../Control/controladorGet.php?accion=panelAdmin&vista=pedidos">
                    <i class="bi bi-cart-check"></i> Pedidos
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $vista === 'productos' ? 'active' : '' ?>" href="../Control/controladorGet.php?accion=panelAdmin&vista=productos">
                    <i class="bi bi-box-seam"></i> Productos
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $vista === 'clientes' ? 'active' : '' ?>" href="../Control/controladorGet.php?accion=panelAdmin&vista=clientes">
                    <i class="bi bi-people"></i> Clientes
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $vista === 'marcas' ? 'active' : '' ?>" href="../Control/controladorGet.php?accion=panelAdmin&vista=marcas">
                    <i class="bi bi-tags"></i> Marcas
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $vista === 'categorias' ? 'active' : '' ?>" href="../Control/controladorGet.php?accion=panelAdmin&vista=categorias">
                    <i class="bi bi-list-ul"></i> Categorías
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $vista === 'adjuntos' ? 'active' : '' ?>" href="../Control/controladorGet.php?accion=panelAdmin&vista=adjuntos">
                    <i class="bi bi-images"></i> Galería
                </a>
            </li>
        </ul>

        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                
                <?php if ($vista === 'pedidos'): ?>
                    <div class="p-3">
                        <h5>Gestión de Pedidos</h5>
                        <?php if(empty($datos)): ?>
                            <p class="text-muted">No hay pedidos.</p>
                        <?php else: ?>
                           <table class="table">
                               <thead><tr><th>ID</th><th>Usuario</th><th>Estado</th><th>Total</th><th>Acciones</th></tr></thead>
                               <tbody>
                                   <?php foreach($datos as $p): 
                                        $estado = $p->getEstado();
                                   ?>
                                    <tr>
                                        <td>#<?= $p->getIdPedido() ?></td>
                                        <td>
                                            <?= $p->getUsuario()->getUsername() ?><br>
                                            <small class="text-muted"><?= $p->getFechaPedido()->format('d/m/Y') ?></small>
                                        </td>
                                        <td>
                                            <?php if ($estado === 'PAGADO'): ?>
                                                <span class="badge bg-warning text-dark">Pendiente</span>
                                            <?php elseif ($estado === 'ENTREGADO'): ?>
                                                <span class="badge bg-success">Entregado</span>
                                            <?php elseif ($estado === 'CANCELADO'): ?>
                                                <span class="badge bg-danger">Cancelado</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="fw-bold">$<?= number_format($p->getMontoTotal(), 2) ?></td>
                                        
                                        <td>
                                            <div class="d-flex gap-1">
                                                
                                                <a href="../Control/controladorGet.php?accion=verDetallePedido&id=<?= $p->getIdPedido() ?>" 
                                                   class="btn btn-sm btn-info text-white" 
                                                   title="Ver Detalle">
                                                    <i class="bi bi-eye"></i>
                                                </a>

                                                <?php if ($estado === 'PAGADO'): ?>
                                                    
                                                    <form action="../Control/controladorPost.php" method="POST" class="d-inline">
                                                        <input type="hidden" name="accion" value="cambiarEstado">
                                                        <input type="hidden" name="idPedido" value="<?= $p->getIdPedido() ?>">
                                                        <input type="hidden" name="nuevoEstado" value="ENTREGADO">
                                                        
                                                        <button type="submit" class="btn btn-sm btn-success" title="Finalizar / Entregar">
                                                            <i class="bi bi-check-lg"></i>
                                                        </button>
                                                    </form>

                                                    <form action="../Control/controladorPost.php" method="POST" class="d-inline">
                                                        <input type="hidden" name="accion" value="cambiarEstado">
                                                        <input type="hidden" name="idPedido" value="<?= $p->getIdPedido() ?>">
                                                        <input type="hidden" name="nuevoEstado" value="CANCELADO">
                                                        
                                                        <button type="submit" class="btn btn-sm btn-danger" 
                                                                title="Cancelar Pedido"
                                                                onclick="return confirm('¿Seguro? Esto devolverá el stock al inventario.');">
                                                            <i class="bi bi-x-lg"></i>
                                                        </button>
                                                    </form>

                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                   <?php endforeach; ?>
                               </tbody>
                           </table>
                        <?php endif; ?>
                    </div>

                <?php elseif ($vista === 'productos'): ?>
                    <div class="p-3">
                        <div class="d-flex justify-content-between mb-3">
                            <h5>Listado de Productos</h5>
                            <a href="../Control/controladorGet.php?accion=nuevoProducto" class="btn btn-success btn-sm">
                                <i class="bi bi-plus-lg"></i> Nuevo Producto
                            </a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Ref</th>
                                        <th>Nombre</th>
                                        <th>Precio</th>
                                        <th>Stock</th>
                                        <th>Marca/Cat</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($datos as $prod): ?>
                                        <tr>
                                            <td><?= $prod->getCodigoReferencia() ?></td>
                                            <td class="fw-bold"><?= $prod->getNombre() ?></td>
                                            <td>$<?= number_format($prod->getPrecio(), 2) ?></td>
                                            <td>
                                                <form action="../Control/controladorPost.php" method="POST">
                                                    <input type="hidden" name="accion" value="actualizarStockRapido">
                                                    <input type="hidden" name="idProducto" value="<?= $prod->getIdProducto() ?>">
                                                    
                                                    <div class="input-group input-group-sm" style="width: 150px;">
                                                        <button type="submit" name="operacion" value="restar" class="btn btn-outline-secondary">
                                                            <i class="bi bi-dash"></i>
                                                        </button>
                                                        
                                                        <input type="number" name="stock" value="<?= $prod->getStock() ?>" class="form-control text-center p-0" min="0">
                                                        
                                                        <button type="submit" name="operacion" value="sumar" class="btn btn-outline-secondary">
                                                            <i class="bi bi-plus"></i>
                                                        </button>

                                                        <button type="submit" name="operacion" value="fijar" class="btn btn-primary" title="Guardar valor manual">
                                                            <i class="bi bi-save"></i>
                                                        </button>
                                                    </div>
                                                </form>
                                            </td>
                                            <td class="small text-muted">
                                                <?= $prod->getMarca()->getNombre() ?><br>
                                                <?= $prod->getCategoria()->getNombre() ?>
                                            </td>
                                            <td>
                                                <div class="d-flex gap-1">
                                                    <a href="../Control/controladorGet.php?accion=editarProducto&id=<?= $prod->getIdProducto() ?>" class="btn btn-sm btn-warning" title="Editar">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>

                                                    <?php if ($prod->getDeshabilitado() === null): ?>
                                                        
                                                        <form action="../Control/controladorPost.php" method="POST" class="d-inline">
                                                            <input type="hidden" name="accion" value="eliminarProducto">
                                                            <input type="hidden" name="idProducto" value="<?= $prod->getIdProducto() ?>">
                                                            <input type="hidden" name="tipo" value="baja"> <button type="submit" class="btn btn-sm btn-danger" 
                                                                    title="Deshabilitar Producto"
                                                                    onclick="return confirm('¿Seguro? El producto dejará de ser visible en la tienda.');">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </form>

                                                    <?php else: ?>

                                                        <form action="../Control/controladorPost.php" method="POST" class="d-inline">
                                                            <input type="hidden" name="accion" value="eliminarProducto">
                                                            <input type="hidden" name="idProducto" value="<?= $prod->getIdProducto() ?>">
                                                            <input type="hidden" name="tipo" value="alta"> <button type="submit" class="btn btn-sm btn-success" title="Restaurar Producto">
                                                                <i class="bi bi-arrow-counterclockwise"></i>
                                                            </button>
                                                        </form>

                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                <?php elseif ($vista === 'clientes'): ?>
                    <div class="p-3">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">Listado de Clientes</h5>
                            <a href="../Control/controladorGet.php?accion=nuevoCliente" class="btn btn-success btn-sm">
                                <i class="bi bi-person-plus"></i> Nuevo Cliente
                            </a>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Usuario</th>
                                        <th>Nombre Completo</th>
                                        <th>Email</th>
                                        <th>Rol</th>
                                        <th>Estado</th>
                                        <th class="text-end">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($datos as $u): /** @var \BritosGab\PruebaseCommerce\entidades\Usuario $u */ ?>
                                        <tr class="<?= $u->getDeshabilitado() ? 'table-secondary text-muted' : '' ?>">
                                            <td><?= $u->getIdUsuario() ?></td>
                                            <td class="fw-bold"><?= htmlspecialchars($u->getUsername()) ?></td>
                                            <td><?= htmlspecialchars($u->getNombre() . ' ' . $u->getApellido()) ?></td>
                                            <td><?= htmlspecialchars($u->getEmail()) ?></td>
                                            <td>
                                                <span class="badge bg-info text-dark">
                                                    <?= $u->getRol()->getNombre() ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if ($u->getDeshabilitado()): ?>
                                                    <span class="badge bg-danger">Inactivo</span>
                                                <?php else: ?>
                                                    <span class="badge bg-success">Activo</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-end">
                                                <div class="d-flex justify-content-end gap-1">
                                                    <a href="../Control/controladorGet.php?accion=editarCliente&id=<?= $u->getIdUsuario() ?>" class="btn btn-sm btn-warning" title="Editar">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>

                                                    <?php if ($u->getDeshabilitado() === null): ?>
                                                        <form action="../Control/controladorPost.php" method="POST" class="d-inline">
                                                            <input type="hidden" name="accion" value="eliminarCliente">
                                                            <input type="hidden" name="idUsuario" value="<?= $u->getIdUsuario() ?>">
                                                            <input type="hidden" name="tipo" value="baja">
                                                            <button type="submit" class="btn btn-sm btn-danger" title="Deshabilitar" onclick="return confirm('¿Deshabilitar acceso a este usuario?');">
                                                                <i class="bi bi-person-x"></i>
                                                            </button>
                                                        </form>
                                                    <?php else: ?>
                                                        <form action="../Control/controladorPost.php" method="POST" class="d-inline">
                                                            <input type="hidden" name="accion" value="eliminarCliente">
                                                            <input type="hidden" name="idUsuario" value="<?= $u->getIdUsuario() ?>">
                                                            <input type="hidden" name="tipo" value="alta">
                                                            <button type="submit" class="btn btn-sm btn-success" title="Reactivar">
                                                                <i class="bi bi-person-check"></i>
                                                            </button>
                                                        </form>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                
                <?php elseif ($vista === 'marcas' || $vista === 'categorias'): ?>
                    
                    <?php 
                        // Definimos el tipo en singular para pasarlo a los controladores
                        // Si $vista es 'marcas' -> 'marca'. Si es 'categorias' -> 'categoria'
                        $tipoSingular = ($vista === 'marcas') ? 'marca' : 'categoria';
                    ?>

                    <div class="p-3">
                        <div class="d-flex justify-content-between mb-3">
                            <h5 class="text-capitalize">Gestión de <?= $vista ?></h5>
                            
                            <a href="../Control/controladorGet.php?accion=formularioAuxiliar&tipo=<?= $tipoSingular ?>" class="btn btn-success btn-sm">
                                <i class="bi bi-plus-lg"></i> Nueva
                            </a>
                        </div>

                        <ul class="list-group shadow-sm">
                            <?php foreach ($datos as $obj): 
                                $id = ($tipoSingular === 'marca') ? $obj->getIdMarca() : $obj->getIdCategoria();
                                $nombre = $obj->getNombre(); 
                                $estaDeshabilitado = $obj->getDeshabilitado() !== null;
                            ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center <?= $estaDeshabilitado ? 'bg-light text-muted' : '' ?>">
                                    <span>
                                        <span class="badge bg-secondary border me-2">#<?= $id ?></span>
                                        <?= htmlspecialchars($nombre) ?>
                                            
                                        <?php if ($estaDeshabilitado): ?>
                                            <span class="badge bg-danger ms-2">Inactivo</span>
                                        <?php endif; ?>
                                    </span>
                                        
                                    <div class="d-flex gap-1">
                                        <a href="../Control/controladorGet.php?accion=formularioAuxiliar&tipo=<?= $tipoSingular ?>&id=<?= $id ?>" 
                                            class="btn btn-sm btn-warning" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        <?php if (!$estaDeshabilitado): ?>
                                            <form action="../Control/controladorPost.php" method="POST" class="d-inline">
                                                <input type="hidden" name="accion" value="eliminarAuxiliar">
                                                <input type="hidden" name="tipo" value="<?= $tipoSingular ?>">
                                                <input type="hidden" name="id" value="<?= $id ?>">
                                                <input type="hidden" name="operacion" value="baja">
                                                    
                                                <button type="submit" class="btn btn-sm btn-danger" 
                                                        title="Deshabilitar"
                                                        onclick="return confirm('¿Deshabilitar? Los productos existentes NO se borrarán, pero no podrás asignar esta opción a nuevos productos.');">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        <?php else: ?>
                                            <form action="../Control/controladorPost.php" method="POST" class="d-inline">
                                                <input type="hidden" name="accion" value="eliminarAuxiliar">
                                                <input type="hidden" name="tipo" value="<?= $tipoSingular ?>">
                                                <input type="hidden" name="id" value="<?= $id ?>">
                                                <input type="hidden" name="operacion" value="alta">
                                                    
                                                <button type="submit" class="btn btn-sm btn-success" title="Restaurar">
                                                    <i class="bi bi-arrow-counterclockwise"></i>
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php elseif ($vista === 'adjuntos'): ?>
                    <div class="p-3">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">Galería de Imágenes</h5>
                            <a href="../Control/controladorGet.php?accion=nuevoAdjunto" class="btn btn-success btn-sm">
                                <i class="bi bi-cloud-upload"></i> Subir Imagen
                            </a>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Vista Previa</th>
                                        <th>Nombre Archivo</th>
                                        <th>Producto Asignado</th>
                                        <th>Tipo</th>
                                        <th class="text-end">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(empty($datos)): ?>
                                        <tr><td colspan="5" class="text-center p-4">No hay imágenes cargadas.</td></tr>
                                    <?php else: ?>
                                        <?php foreach ($datos as $adj): /** @var \BritosGab\PruebaseCommerce\entidades\Adjunto $adj */ ?>
                                            <tr>
                                                <td style="width: 100px;">
                                                    <a href="<?= $adj->getRutaUrl() ?>" target="_blank">
                                                        <img src="<?= $adj->getRutaUrl() ?>" class="img-thumbnail" style="height: 60px; object-fit: cover;">
                                                    </a>
                                                </td>
                                                <td><?= htmlspecialchars($adj->getNombreEntidad()) ?></td>
                                                <td>
                                                    <?php if ($adj->getProducto()): ?>
                                                        <span class="badge bg-light text-dark border">
                                                            <?= htmlspecialchars($adj->getProducto()->getNombre()) ?>
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="text-danger">Huérfana</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><span class="badge bg-secondary"><?= $adj->getTipoProducto() ?></span></td>
                                                <td class="text-end">
                                                    <form action="../Control/controladorPost.php" method="POST" class="d-inline">
                                                        <input type="hidden" name="accion" value="eliminarAdjunto">
                                                        <input type="hidden" name="idAdjunto" value="<?= $adj->getIdAdjunto() ?>">
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Eliminar Imagen" onclick="return confirm('¿Eliminar esta imagen permanentemente del servidor?');">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </main>

    <?php include_once '../Includes/footer.php'; ?>
</body>
</html>