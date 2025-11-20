<header> 
       <nav class="navbar navbar-expand-lg d-flex">
            <div class="container-fluid justify-content-between">
                <!-- Logo -->
                <a class="navbar-brand" href="../Vista/index.php">
                    <div class="d-flex align-items-center">
                        <img src="../img/logo.png" alt="Logo" width="100" height="auto" class="d-inline-block align-text-top"/>
                        <span class="ms-2">Tu Esencia</span>
                    </div>
                </a>

                <!-- Buscador -->
                 <?php if (!($esAdmin ?? false)): ?>
                <form class="d-flex search-form" role="search" method="POST" action="../Control/controladorPost.php">
                    <input class="form-control search-input" type="search" name="terminoBusqueda" placeholder="Buscar" aria-label="Buscar">
                    <input type="hidden" name="accion" value="buscar">
                    <button class="btn search-button" type="submit"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16"><path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/></svg></button>
                </form>
                <?php endif; ?>

                <!-- Links -->
                <div class="navbar-nav">
                    <?php if (!$sesion->activa()): ?>
                        <!-- NO ACTIVO -->
                        <a class="nav-link" href="../Vista/login.php">
                            <i class="bi bi-person-fill"></i> Login
                        </a>
                        <a class="nav-link" href="../Control/controladorGet.php?accion=verCarrito">
                            <i class="bi bi-cart3"></i> Carrito
                        </a>

                    <?php elseif ($sesion->activa() && ($esAdmin ?? false)): ?>
                        <!-- ACTIVO ADMIN -->
                        <a class="nav-link" href="../Vista/logout.php">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </a>
                        <a class="nav-link" href="../Control/controladorGet.php?accion=miPerfil">
                            <i class="bi bi-person-circle"></i> Mi Perfil
                        </a>
                        <a class="nav-link" href="../Control/controladorGet.php?accion=panelAdmin">
                            <i class="bi bi-gear"></i> Panel Admin
                        </a>

                    <?php else: ?>
                        <!-- ACTIVO CLIENTE -->
                        <a class="nav-link" href="../Vista/logout.php">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </a>
                        <a class="nav-link" href="../Control/controladorGet.php?accion=verHistorial">
                            <i class="bi bi-clock-history"></i> Mis Pedidos
                        </a>
                        <a class="nav-link" href="../Control/controladorGet.php?accion=miPerfil">
                            <i class="bi bi-person-circle"></i> Mi Perfil
                        </a>
                        <a class="nav-link" href="../Control/controladorGet.php?accion=verCarrito">
                            <i class="bi bi-cart3"></i> Carrito
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
    </header>