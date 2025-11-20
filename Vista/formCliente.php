<!DOCTYPE html>
<html lang="es">
<head>
    <?php include_once __DIR__ . '/../includes/head.php'; ?>
</head>
<body>
    <?php include_once __DIR__ . '/../includes/header.php'; ?>


    
        <main class="container d-flex flex-column min-vh-100 w-50 mx-auto align-items-center justify-content-center">
            <div class="card-datos"> 
                <div class="card-header p-3">
                    <h4 class="mb-0"><?= $cliente ? 'Editar Cliente' : 'Nuevo Cliente' ?></h4>
                </div>

                    <div class="card-body p-4">

                        <form action="../Control/controladorPost.php"
                              method="POST"
                              class="needs-validation"
                              novalidate>

                            <input type="hidden" name="accion" value="guardarCliente">

                            <?php if ($cliente): ?>
                                <input type="hidden" name="idUsuario" value="<?= $cliente->getIdUsuario() ?>">
                            <?php endif; ?>

                            <div class="row g-3">

                                <div class="col-md-6">
                                    <label class="form-label">Nombre de Usuario (Login)</label>
                                    <input type="text"
                                           class="form-control"
                                           name="usuario"
                                           value="<?= $cliente ? htmlspecialchars($cliente->getUsername()) : '' ?>"
                                           <?= $cliente ? 'readonly' : 'required minlength="3" pattern="[A-Za-z0-9_]{3,20}"' ?>>
                                    
                                    <div class="invalid-feedback">
                                        El usuario debe tener entre 3 y 20 caracteres (letras, números o guión bajo).
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Rol</label>
                                    <select class="form-select" name="idRol" required>
                                        <?php foreach ($listaRoles as $r): ?>
                                            <option value="<?= $r->getIdRol() ?>"
                                                <?= ($cliente && $cliente->getRol() === $r) ? 'selected' : '' ?>>
                                                <?= ucfirst($r->getNombre()) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="invalid-feedback">
                                        Selecciona un rol válido.
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Nombre</label>
                                    <input type="text"
                                           class="form-control"
                                           name="nombre"
                                           value="<?= $cliente ? htmlspecialchars($cliente->getNombre()) : '' ?>"
                                           required
                                           minlength="2">
                                    <div class="invalid-feedback">
                                        El nombre debe tener al menos 2 caracteres.
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Apellido</label>
                                    <input type="text"
                                           class="form-control"
                                           name="apellido"
                                           value="<?= $cliente ? htmlspecialchars($cliente->getApellido()) : '' ?>"
                                           required
                                           minlength="2">
                                    <div class="invalid-feedback">
                                        El apellido debe tener al menos 2 caracteres.
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Email</label>
                                    <input type="email"
                                           class="form-control"
                                           name="email"
                                           value="<?= $cliente ? htmlspecialchars($cliente->getEmail()) : '' ?>"
                                           required>
                                    <div class="invalid-feedback">
                                        Ingrese un email válido.
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Dirección</label>
                                    <input type="text"
                                           class="form-control"
                                           name="direccion"
                                           value="<?= $cliente ? htmlspecialchars($cliente->getDireccion()) : '' ?>"
                                           required
                                           minlength="5">
                                    <div class="invalid-feedback">
                                        La dirección debe tener mínimo 5 caracteres.
                                    </div>
                                </div>

                                <hr class="my-3">

                                <!-- CONTRASEÑA -->
                                <div class="col-12">
                                    <label class="form-label">Contraseña</label>
                                    <input type="password"
                                           class="form-control"
                                           name="password"
                                           <?= $cliente ? '' : 'required minlength="6"' ?>
                                           placeholder="<?= $cliente ? 'Dejar vacío para mantener la actual' : 'Ingrese una contraseña' ?>">
                                    <div class="invalid-feedback">
                                        La contraseña debe tener mínimo 6 caracteres.
                                    </div>
                                </div>

                            </div>

                            <div class="d-flex justify-content-between mt-4 text-center">
                                <a href="../Control/controladorGet.php?accion=panelAdmin&vista=clientes" class="cancelar">Cancelar</a>
                                <button type="submit" class="guardarCambios">
                                    <i class="bi bi-save"></i> Guardar Cliente
                                </button>
                            </div>

                        </form>

                    </div>
                </div>
            </main>

    <?php include_once __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>
