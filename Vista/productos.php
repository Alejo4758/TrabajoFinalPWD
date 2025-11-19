<!DOCTYPE html>
<html lang="es">
<head>
<?php include_once __DIR__ . '/../includes/head.php'; ?>
</head>
<body class="d-flex flex-column min-vh-100">
<?php include_once __DIR__ . '/../includes/header.php'; ?>

<!-- MAIN CONTENT -->
<main>
    <div class="menu-usuario">
        <ul class="nav nav-underline">
            <li class="nav-item">
                <a class="nav-link" href="index.php">Inicio</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" aria-current="page"href="#">Productos</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="marcas.php">Marcas</a>
            </li>
        </ul>
    </div>

    <div class="container align-items-center">
        <div class="productos-container d-flex justify-content-around align-items-center mt-5 mb-5">
            <div class="card-producto">
                <img src="../img/logo.png" class="card-img-top" alt="Perfume">
                <div class="card-body text-center">
                    <h5 class="card-title">Nombre Perfume</h5>
                    <h6 class="card-subtitle mb-2 text-muted">Precio$</h6>
                        <!-- Button trigger modal -->
                    <button type="button" class="btn-ver-producto" data-bs-toggle="modal" data-bs-target="#producto">
                        Ver Producto
                    </button>
                    <button class="btn-comprar">Agregar al carrito</button>
                </div>
            </div>
                <!-- Modal -->
                <div class="modal fade" id="producto" tabindex="-1" aria-labelledby="producto" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">  
                                <h1 class="modal-title fs-5" id="productoLabel">Nombre Producto</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body d-flex">
                                <div class="img-producto">
                                    <img src="../img/logo.png" alt="producto" width="250px">
                                </div>
                                <div class="texto">
                                    <p>$</p>
                                    <p>Marca</p>
                                    <p>Categoria</p>
                                    <p>Descripción</p>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn-comprar text-center">Agregar al carrito</button>
                            </div>
                        </div>
                    </div>
                </div>

            <div class="card-producto">
                <img src="../img/logo.png" class="card-img-top" alt="Perfume">
                <div class="card-body text-center">
                    <h5 class="card-title">Nombre Perfume</h5>
                    <h6 class="card-subtitle mb-2 text-muted">Precio$</h6>
                        <!-- Button trigger modal -->
                    <button type="button" class="btn-ver-producto" data-bs-toggle="modal" data-bs-target="#producto1">
                        Ver Producto
                    </button>
                    <button class="btn-comprar">Agregar al carrito</button>
                </div>
            </div>
                <!-- Modal -->
                <div class="modal fade" id="producto1" tabindex="-1" aria-labelledby="producto" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">  
                                <h1 class="modal-title fs-5" id="productoLabel">Nombre Producto</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body d-flex">
                                <div class="img-producto">
                                    <img src="../img/logo.png" alt="producto" width="250px">
                                </div>
                                <div class="texto">
                                    <p>$</p>
                                    <p>Marca</p>
                                    <p>Categoria</p>
                                    <p>Descripción 2</p>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn-comprar text-center">Agregar al carrito</button>
                            </div>
                        </div>
                    </div>
                </div>

            <div class="card-producto">
                <img src="../img/logo.png" class="card-img-top" alt="Perfume">
                <div class="card-body text-center">
                    <h5 class="card-title">Nombre Perfume</h5>
                    <h6 class="card-subtitle mb-2 text-muted">Precio$</h6>
                        <!-- Button trigger modal -->
                    <button type="button" class="btn-ver-producto" data-bs-toggle="modal" data-bs-target="#producto2">
                        Ver Producto
                    </button>
                    <button class="btn-comprar">Agregar al carrito</button>
                </div>
            </div>
                <!-- Modal -->
                <div class="modal fade" id="producto2" tabindex="-1" aria-labelledby="producto" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">  
                                <h1 class="modal-title fs-5" id="productoLabel">Nombre Producto</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body d-flex">
                                <div class="img-producto">
                                    <img src="../img/logo.png" alt="producto" width="250px">
                                </div>
                                <div class="texto">
                                    <p>$</p>
                                    <p>Marca</p>
                                    <p>Categoria</p>
                                    <p>Descripción 3</p>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn-comprar text-center">Agregar al carrito</button>
                            </div>
                        </div>
                    </div>
                </div>

            <div class="card-producto">
                <img src="../img/logo.png" class="card-img-top" alt="Perfume">
                <div class="card-body text-center">
                    <h5 class="card-title">Nombre Perfume</h5>
                    <h6 class="card-subtitle mb-2 text-muted">Precio$</h6>
                        <!-- Button trigger modal -->
                    <button type="button" class="btn-ver-producto" data-bs-toggle="modal" data-bs-target="#producto3">
                        Ver Producto
                    </button>
                    <button class="btn-comprar">Agregar al carrito</button>
                </div>
            </div>
                <!-- Modal -->
                <div class="modal fade" id="producto3" tabindex="-1" aria-labelledby="producto" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">  
                                <h1 class="modal-title fs-5" id="productoLabel">Nombre Producto</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body d-flex">
                                <div class="img-producto">
                                    <img src="../img/logo.png" alt="producto" width="250px">
                                </div>
                                <div class="texto">
                                    <p>$</p>
                                    <p>Marca</p>
                                    <p>Categoria</p>
                                    <p>Descripción 3</p>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn-comprar text-center">Agregar al carrito</button>
                            </div>
                        </div>
                    </div>
                </div>
     
        </div>   
    </div>
</main>
        
<?php include_once __DIR__ . '/../includes/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>