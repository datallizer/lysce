<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require 'dbcon.php';
$message = isset($_SESSION['message']) ? $_SESSION['message'] : ''; // Obtener el mensaje de la sesión

if (!empty($message)) {
    // HTML y JavaScript para mostrar la alerta...
    echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                const message = " . json_encode($message) . ";
                Swal.fire({
                    title: 'NOTIFICACIÓN',
                    text: message,
                    icon: 'info',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Hacer algo si se confirma la alerta
                    }
                });
            });
        </script>";
    unset($_SESSION['message']); // Limpiar el mensaje de la sesión
}

//Verificar si existe una sesión activa y los valores de usuario y contraseña están establecidos
if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];

    // Consultar la base de datos para verificar si los valores coinciden con algún registro en la tabla de usuarios
    $query = "SELECT * FROM usuarios WHERE email = '$email'";
    $result = mysqli_query($con, $query);

    // Si se encuentra un registro coincidente, el usuario está autorizado
    if (mysqli_num_rows($result) > 0) {
        // El usuario está autorizado, se puede acceder al contenido
    } else {
        // Redirigir al usuario a una página de inicio de sesión
        header('Location: login.php');
        exit(); // Finalizar el script después de la redirección
    }
} else {
    // Redirigir al usuario a una página de inicio de sesión si no hay una sesión activa
    header('Location: login.php');
    exit(); // Finalizar el script después de la redirección
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Editar cliente | LYSCE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <link rel="shortcut icon" type="image/x-icon" href="images/logo.png" />
    <link rel="stylesheet" href="css/styles.css">
</head>

<body class="sb-nav-fixed">
    <?php include 'sidenav.php'; ?>
    <div id="layoutSidenav">
        <div id="layoutSidenav_content">
            <div class="container mt-3">
                <div class="row justify-content-center mb-5">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>EDITAR CLIENTE
                                    <a href="clientes.php" class="btn btn-danger btn-sm float-end">Regresar</a>
                                </h4>
                            </div>
                            <div class="card-body">

                                <?php

                                if (isset($_GET['id'])) {
                                    $registro_id = mysqli_real_escape_string($con, $_GET['id']);
                                    $query = "SELECT * FROM clientes WHERE id='$registro_id' ";
                                    $query_run = mysqli_query($con, $query);

                                    if (mysqli_num_rows($query_run) > 0) {
                                        $registro = mysqli_fetch_array($query_run);
                                        $estatus_actual = $registro['estatus'];

                                ?>

                                        <form action="codeclientes.php" method="POST">
                                            <input type="hidden" name="id" value="<?= $registro['id']; ?>">

                                            <div class="row mt-1">

                                                <div class="form-floating col-9">
                                                    <input type="text" class="form-control" name="cliente" id="cliente" value="<?= $registro['cliente']; ?>">
                                                    <label for="cliente">Cliente / Client name</label>
                                                </div>

                                                <div class="form-floating col-12 col-md-3">
                                                    <select class="form-select" name="estatus" id="estatus">
                                                        <option disabled>Seleccione un estatus</option>
                                                        <option value="0" <?= ($estatus_actual == 0) ? 'selected' : ''; ?>>Inactivo</option>
                                                        <option value="1" <?= ($estatus_actual == 1) ? 'selected' : ''; ?>>Activo</option>
                                                    </select>
                                                    <label for="estatus">Estatus</label>
                                                </div>

                                                <div class="form-floating col-12 col-md-7 mt-3">
                                                    <input type="text" class="form-control" name="calle" id="calle" value="<?= $registro['calle']; ?>">
                                                    <label for="calle">Calle / Street</label>
                                                </div>

                                                <div class="form-floating col-12 col-md-5 mt-3">
                                                    <input type="text" class="form-control" name="numexterior" id="numexterior" value="<?= $registro['numexterior']; ?>">
                                                    <label for="numexterior">Número exterior / Outside number</label>
                                                </div>

                                                <div class="form-floating col-4 mt-3">
                                                    <input type="text" class="form-control" name="numinterior" id="numinterior" value="<?= $registro['numinterior']; ?>">
                                                    <label for="numinterior">Número interior / Inside number</label>
                                                </div>

                                                <div class="form-floating col-4 mt-3">
                                                    <input type="text" class="form-control" name="colonia" id="colonia" value="<?= $registro['colonia']; ?>">
                                                    <label for="colonia">Colonia / Neighborhood</label>
                                                </div>

                                                <div class="form-floating col-4 mt-3">
                                                    <input type="text" class="form-control" name="city" id="city" value="<?= $registro['city']; ?>">
                                                    <label for="city">Ciudad / City</label>
                                                </div>

                                                <div class="form-floating col-6 mt-3">
                                                    <input type="text" class="form-control" name="state" id="state" value="<?= $registro['state']; ?>">
                                                    <label for="state">Estado / State</label>
                                                </div>

                                                <div class="form-floating col-3 mt-3">
                                                    <input type="text" class="form-control" name="pais" id="pais" value="<?= $registro['pais']; ?>">
                                                    <label for="pais">País / Country</label>
                                                </div>

                                                <div class="form-floating col-3 mt-3">
                                                    <input type="text" class="form-control" name="cpostal" id="cpostal" value="<?= $registro['cpostal']; ?>">
                                                    <label for="cpostal">Código postal / ZIP code</label>
                                                </div>

                                                <div class="form-floating col-3 mt-3">
                                                    <input type="text" class="form-control" name="telefono" id="telefono" value="<?= $registro['telefono']; ?>">
                                                    <label for="telefono">Teléfono / Phone</label>
                                                </div>

                                                <div class="form-floating col-5 mt-3">
                                                    <input type="text" class="form-control" name="contacto" id="contacto" value="<?= $registro['contacto']; ?>">
                                                    <label for="contacto">Representante / Agent</label>
                                                </div>

                                                <div class="form-floating col-4 mt-3">
                                                    <input type="text" class="form-control" name="rfc" id="rfc" value="<?= $registro['rfc']; ?>">
                                                    <label for="rfc">RFC / Tax ID</label>
                                                </div>

                                                <div class="form-floating col-12 mt-3">
                                                    <input type="text" class="form-control" name="correo" id="correo" value="<?= $registro['correo']; ?>">
                                                    <label for="correo">Correo / Email</label>
                                                </div>

                                                <div class="col-12 text-center mt-3">
                                                    <button type="submit" name="update" class="btn btn-primary">
                                                        Actualizar usuario
                                                    </button>
                                                </div>


                                            </div>
                            </div>

                            </form>
                    <?php
                                    } else {
                                        echo "<h4>No Such Id Found</h4>";
                                    }
                                }
                    ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
</body>

</html>