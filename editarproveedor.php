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
                                <h4>EDITAR PROVEEDOR
                                    <a href="proveedores.php" class="btn btn-danger btn-sm float-end">Regresar</a>
                                </h4>
                            </div>
                            <div class="card-body">

                                <?php

                                if (isset($_GET['id'])) {
                                    $registro_id = mysqli_real_escape_string($con, $_GET['id']);
                                    $query = "SELECT * FROM proveedores WHERE id='$registro_id' ";
                                    $query_run = mysqli_query($con, $query);

                                    if (mysqli_num_rows($query_run) > 0) {
                                        $registro = mysqli_fetch_array($query_run);
                                        $estatus_actual = $registro['estatus'];

                                ?>

                                        <form action="codeproveedores.php" method="POST">
                                            <input type="hidden" name="id" value="<?= $registro['id']; ?>">

                                            <div class="row mt-1">

                                                <div class="form-floating col-9">
                                                    <input type="text" class="form-control" name="proveedor" id="proveedor" value="<?= $registro['proveedor']; ?>">
                                                    <label for="proveedor">Proveedor / Supplier name</label>
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
                                                    <input type="text" class="form-control" name="domicilio" id="domicilio" value="<?= $registro['domicilio']; ?>">
                                                    <label for="domicilio">Calle / Street</label>
                                                </div>

                                                <div class="form-floating col-12 col-md-5 mt-3">
                                                    <input type="text" class="form-control" name="exterior" id="exterior" value="<?= $registro['exterior']; ?>">
                                                    <label for="exterior">Número exterior / Outside number</label>
                                                </div>

                                                <div class="form-floating col-4 mt-3">
                                                    <input type="text" class="form-control" name="interior" id="interior" value="<?= $registro['interior']; ?>">
                                                    <label for="interior">Número interior / Inside number</label>
                                                </div>

                                                <div class="form-floating col-4 mt-3">
                                                    <input type="text" class="form-control" name="fraccionamiento" id="fraccionamiento" value="<?= $registro['fraccionamiento']; ?>">
                                                    <label for="fraccionamiento">Colonia / Neighborhood</label>
                                                </div>

                                                <div class="form-floating col-4 mt-3">
                                                    <input type="text" class="form-control" name="ciudad" id="ciudad" value="<?= $registro['ciudad']; ?>">
                                                    <label for="ciudad">Ciudad / City</label>
                                                </div>

                                                <div class="form-floating col-6 mt-3">
                                                    <input type="text" class="form-control" name="estado" id="estado" value="<?= $registro['estado']; ?>">
                                                    <label for="estado">Estado / State</label>
                                                </div>

                                                <div class="form-floating col-3 mt-3">
                                                    <input type="text" class="form-control" name="country" id="country" value="<?= $registro['country']; ?>">
                                                    <label for="country">País / Country</label>
                                                </div>

                                                <div class="form-floating col-3 mt-3">
                                                    <input type="text" class="form-control" name="postal" id="postal" value="<?= $registro['postal']; ?>">
                                                    <label for="postal">Código postal / ZIP code</label>
                                                </div>

                                                <div class="form-floating col-3 mt-3">
                                                    <input type="text" class="form-control" name="phone" id="phone" value="<?= $registro['phone']; ?>">
                                                    <label for="phone">Teléfono / Phone</label>
                                                </div>

                                                <div class="form-floating col-5 mt-3">
                                                    <input type="text" class="form-control" name="contact" id="contact" value="<?= $registro['contact']; ?>">
                                                    <label for="contact">Representante / Agent</label>
                                                </div>

                                                <div class="form-floating col-4 mt-3">
                                                    <input type="text" class="form-control" name="tax" id="tax" value="<?= $registro['tax']; ?>">
                                                    <label for="tax">RFC / Tax ID</label>
                                                </div>

                                                <div class="form-floating col-7 mt-3">
                                                    <input type="text" class="form-control" name="email" id="email" value="<?= $registro['email']; ?>">
                                                    <label for="email">Correo / Email</label>
                                                </div>

                                                <div class="form-floating col-5 mt-3">
                                                    <input type="text" class="form-control" name="web" id="web" value="<?= $registro['web']; ?>">
                                                    <label for="web">Sitio web / Web site</label>
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