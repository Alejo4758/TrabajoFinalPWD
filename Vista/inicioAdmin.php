<!DOCTYPE html>
<html lang="en">
<head>
<?php include_once '../includes/head.php'; ?>
</head>
<body class="d-flex flex-column min-vh-100">
<?php include_once '../includes/header.php'; ?>

<!-- MAIN CONTENT -->
<main class="justify-content-center">
    <div class="menu-usuario">
        <ul class="nav nav-underline">
            <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="#">Inicio</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="stock.php">Stock</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="listaUsuarios.php">Usuarios</a>
            </li>
             <li class="nav-item">
                <a class="nav-link" href="#">Pedidos</a>
            </li>
        </ul>
    </div>

    <div class="container-agregar w-50 mx-auto align-items-center text-center">

        <h1>¿Quieres agregar algo nuevo?</h1>

        <div class="btn-group d-flex justify-content-center" role="group" aria-label="Basic example">
            <button type="button" class="btn-agregar" data-bs-toggle="modal" data-bs-target="#nuevoProductoModal">
                Nuevo Producto
            </button>
            <button type="button" class="btn-agregar" data-bs-toggle="modal" data-bs-target="#nuevaMarcaModal">
                Nueva Marca
            </button>
            <button type="button" class="btn-agregar" data-bs-toggle="modal" data-bs-target="#nuevaCategoriaModal">
                Nueva Categoria
            </button>
        </div>
    </div>


        <!-- Modales -->

     <!-- MODAL AGREGAR PRODUCTO  -->
    <div class="modal fade" id="nuevoProductoModal" tabindex="-1" aria-labelledby="nuevoProductoModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Ingrese un nuevo Producto:</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" class="agregar-producto">
                    <div class="row">
                        <div class="col-md-8 mb-4">
                            <label for="nombre-producto" class="form-label">Nombre:</label>
                            <input type="text" class="form-control" id="nombre-producto" placeholder="nombre del producto">
                        </div>
                        <div class="col-md-4 mb-2">
                            <label for="precio-producto" class="form-label">Precio:</label>
                            <input type="text" class="form-control" id="precio-producto" placeholder="$">
                        </div>
                    </div>
                        
                    <div class="row">
                        <div class="col-md-12 mb-6">
                            <label for="descripcion-producto" class="form-label">Descripción:</label>
                            <textarea class="form-control" id="descripcion-producto" rows="3"></textarea>
                        </div>
                    </div> 
                    
                    <div class="row">
                        <div class="col-md-8 mb-4">
                            <label for="precio-producto" class="form-label">Codifo de Referencia:</label>
                            <input type="text" class="form-control" id="precio-producto" placeholder="0000000000">
                        </div>
                        <div class="col-md-4 mb-2">
                            <label for="stock" class="form-label">Stock:</label>
                            <input type="text" class="form-control" id="stock" placeholder="00000">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="precio-producto" class="form-label">ID Marca:</label>
                            <input type="text" class="form-control" id="precio-producto" placeholder="0">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="stock" class="form-label">ID Categoria:</label>
                            <input type="text" class="form-control" id="stock" placeholder="0">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="reset" class="btn-limpiar">Limpiar</button>
                <button type="submit" class="btn-guardar-cambios">Guardar cambios</button>
            </div>
            </div>
        </div>
    </div>

    <!-- MODAL AGREGAR MARCA -->
    <div class="modal fade" id="nuevaMarcaModal" tabindex="-1" aria-labelledby="nuevaMarcaModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="nuevaMarcaModal">Ingrese una nueva Marca:</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" class="agregar-marca">
                    <div class="row">
                        <div class="col-md-12 mb-8">
                            <label for="nombre-producto" class="form-label">Nombre:</label>
                            <input type="text" class="form-control" id="nombre-producto" placeholder="nombre de la marca">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="reset" class="btn-limpiar">Limpiar</button>
                <button type="submit" class="btn-guardar-cambios">Guardar cambios</button>
            </div>
            </div>
        </div>
    </div>

    <!-- MODAL AGREGAR CATEGORIA -->
    <div class="modal fade" id="nuevaCategoriaModal" tabindex="-1" aria-labelledby="nuevaCategoriaModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="nuevaCategoriaModal">Ingrese una nueva Categoria:</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" class="agregar-categoria">
                    <div class="row">
                        <div class="col-md-12 mb-8">
                            <label for="nombre-producto" class="form-label">Nombre:</label>
                            <input type="text" class="form-control" id="nombre-producto" placeholder="nombre de la categoria">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="reset" class="btn-limpiar">Limpiar</button>
                <button type="submit" class="btn-guardar-cambios">Guardar cambios</button>
            </div>
            </div>
        </div>
    </div>
</main>
        

<?php include_once '../includes/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>