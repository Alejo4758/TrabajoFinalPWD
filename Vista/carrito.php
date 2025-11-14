<!DOCTYPE html>
<html lang="en">
<head>
<?php include_once '../includes/head.php'; ?>
</head>
<body class="d-flex flex-column min-vh-100">
<?php include_once '../includes/header.php'; ?>

    <!-- MAIN CONTENT -->
<main>
    <div class="menu-usuario">
        <ul class="nav nav-underline">
            <li class="nav-item">
                <a class="nav-link" href="index.php">Inicio</a>
            </li>
            <li class="nav-item">
                <a class="nav-link"href="productos.php">Productos</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="marcas.php">Marcas</a>
            </li>
        </ul>
    </div>

    <div class="container-carrito d-flex flex-column w-50 mx-auto text-center align-items-center justify-content-center">
        <h1>TU CARRITO DE COMPRAS</h1>
            <table class="table">
                <thead>
                    <tr>
                    <th scope="col">Producto</th>
                    <th scope="col">Precio</th>
                    <th scope="col">Cantidad</th>
                    <th scope="col">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                    <th scope="row">Perfume nombre</th>
                    <td>$$</td>
                    <td>nro</td>
                    <td>$</td>
                    </tr>
                </tbody>
            </table>
        <button class="finalizar-compra" action="">Finalizar Compra</button>
    </div>
</main>
        

<?php include_once '../includes/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>