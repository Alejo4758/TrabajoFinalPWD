<!DOCTYPE html>
<html lang="en">
<head>
<?php include_once '../includes/head.php'; ?>
</head>
<body class="d-flex flex-column min-vh-100">
<?php include_once '../includes/header.php'; ?>

    <div class="menu-usuario">
        <ul class="nav nav-underline">
            <li class="nav-item">
                <a class="nav-link" href="inicioAdmin.php">Inicio</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="#">Stock</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="listaUsuarios.php">Usuarios</a>
            </li>
             <li class="nav-item">
                <a class="nav-link" href="#">Pedidos</a>
            </li>
        </ul>
    </div>
    
<!-- MAIN CONTENT -->
<main class>
    <div class="container align-items-center text-center">
        <div class="productos-container display-flex justify-content-space-around align-items-center mt-5 mb-5">
            <div class="card-producto">
                <img src="../logo.png" class="card-img-top" alt="Perfume">
                <div class="card-body">
                    <h5 class="card-title">Nombre Perfume</h5>
                    <button class="btn-ver-detalle">Ver Detalle</button>
                    <div class="stock-control align-items-center">
                        <button onclick="decrementar()">-</button>
                        <input type="text" id="cantidad" value="" readonly>
                        <button onclick="incrementar()">+</button>
                    </div>
                </div>
            </div>

            <div class="card-producto">
                <img src="../logo.png" class="card-img-top" alt="Perfume">
                <div class="card-body">
                    <h5 class="card-title">Nombre Perfume</h5>
                    <button class="btn-ver-detalle">Ver Detalle</button>
                    <div class="stock-control align-items-center">
                        <button onclick="decrementar()">-</button>
                        <input type="text" id="cantidad" value="" readonly>
                        <button onclick="incrementar()">+</button>
                    </div>
                </div>
            </div>

            <div class="card-producto">
                <img src="../logo.png" class="card-img-top" alt="Perfume">
                <div class="card-body">
                    <h5 class="card-title">Nombre Perfume</h5>
                    <button class="btn-ver-detalle">Ver Detalle</button>
                    <div class="stock-control align-items-center">
                        <button onclick="decrementar()">-</button>
                        <input type="text" id="cantidad" value="" readonly>
                        <button onclick="incrementar()">+</button>
                    </div>
                </div>
            </div>

             <div class="card-producto">
                    <img src="../logo.png" class="card-img-top" alt="Perfume">
                <div class="card-body">
                    <h5 class="card-title">Nombre Perfume</h5>
                    <button class="btn-ver-detalle">Ver Detalle</button>
                    <div class="stock-control align-items-center">
                        <button onclick="decrementar()">-</button>
                        <input type="text" id="cantidad" value="" readonly>
                        <button onclick="incrementar()">+</button>
                    </div>
                </div>
            </div>
        </div>   
    </div>
</main>
        
<?php include_once '../includes/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>