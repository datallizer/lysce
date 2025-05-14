<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require 'dbcon.php';

if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];

    $query = "SELECT * FROM usuarios WHERE email = '$email'";
    $result = mysqli_query($con, $query);

    if (mysqli_num_rows($result) > 0) {
    } else {
        $_SESSION['alert'] = [
            'title' => 'USUARIO NO ENCONTRADO',
            'icon' => 'ERROR'
        ];
        header('Location: login.php');
        exit();
    }
} else {
    $_SESSION['alert'] = [
        'message' => 'Para acceder debes iniciar sesión primero',
        'title' => 'SESIÓN NO INICIADA',
        'icon' => 'info'
    ];
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Editar cliente | LYSCE</title>
    <link rel="shortcut icon" type="image/x-icon" href="images/ics.ico" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/styles.css">
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBynovZcvSlXHZvqYnF3NXE6TWR3reHUFc&libraries=places&callback=initMap" async defer loading="async"></script>
    <script>
        let autocompleteInstances = {}; // Objeto para guardar las instancias de Autocomplete

        function initMap() {
            const addressInputs = document.querySelectorAll('input[name="calle"]');
            const postalCodeInput = document.getElementById('cpostal');

            addressInputs.forEach(input => {
                if (!autocompleteInstances[input.name]) { // Verifica si ya existe una instancia para este input
                    autocompleteInstances[input.name] = new google.maps.places.Autocomplete(input, {
                        fields: ["place_id", "address_components"],
                        componentRestrictions: {
                            country: ["mx", "us", "ca"]
                        }
                    });

                    console.log(autocompleteInstances);
                    autocompleteInstances[input.name].addListener("place_changed", () => {
                        const place = autocompleteInstances[input.name].getPlace();
                        handlePlaceChange(place);
                        console.log(place)
                    });
                }
            });
        }

        function handlePlaceChange(place) {
            const addressInputs = document.querySelectorAll('input[name="calle"]');
            const postalCodeInput = document.getElementById('cpostal');

            if (!place.address_components) {
                console.log("No se encontró información para este lugar.");
                addressInputs.forEach(input => input.value = '');
                postalCodeInput.value = '';
                return;
            }

            place.address_components.forEach(component => {

                console.log(place);
                const type = component.types[0];
                const longName = component.long_name;

                switch (type) {
                    case "street_number":
                        document.querySelector('input[name="numexterior"]').value = longName;
                        break;
                    case "route":
                        document.querySelector('input[name="calle"]').value = longName;
                        break;
                    case "sublocality_level_1":
                        document.querySelector('input[name="colonia"]').value = longName;
                        break;
                    case "locality":
                        document.querySelector('input[name="city"]').value = longName;
                        break;
                    case "administrative_area_level_1":
                        document.querySelector('input[name="state"]').value = longName;
                        break;
                    case "country":
                        document.querySelector('input[name="pais"]').value = longName;
                        break;
                    case "postal_code":
                        postalCodeInput.value = longName;
                        break;
                }
            });
        }

        document.addEventListener("DOMContentLoaded", () => {
            initMap(); // Inicializa el mapa y los autocompletes UNA VEZ

            const modal = document.getElementById('exampleModal');
            if (modal) {
                modal.addEventListener('shown.bs.modal', () => {
                    // NO necesitas volver a inicializar aquí.  El autocompletado ya está listo.
                });
            }
        });
    </script>
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
                                                    <input type="text" class="form-control" name="cliente" id="cliente" value="<?= $registro['cliente']; ?>" autocomplete="off">
                                                    <label for="cliente">Cliente / Client name</label>
                                                </div>

                                                <div class="form-floating col-12 col-md-3">
                                                    <select class="form-select" name="estatus" id="estatus">
                                                    <option value="" disabled selected>Selecciona una opción</option>
                                                        <option value="0" <?= ($estatus_actual == 0) ? 'selected' : ''; ?>>Inactivo</option>
                                                        <option value="1" <?= ($estatus_actual == 1) ? 'selected' : ''; ?>>Activo</option>
                                                    </select>
                                                    <label for="estatus">Estatus</label>
                                                </div>

                                                <div class="form-floating col-12 col-md-7 mt-3">
                                                    <input type="text" class="form-control" name="calle" id="calle" value="<?= $registro['calle']; ?>" autocomplete="off">
                                                    <label for="calle">Calle / Street</label>
                                                </div>

                                                <div class="form-floating col-12 col-md-5 mt-3">
                                                    <input type="text" class="form-control" name="numexterior" id="numexterior" value="<?= $registro['numexterior']; ?>" autocomplete="off">
                                                    <label for="numexterior">Número exterior / Outside number</label>
                                                </div>

                                                <div class="form-floating col-4 mt-3">
                                                    <input type="text" class="form-control" name="numinterior" id="numinterior" value="<?= $registro['numinterior']; ?>" autocomplete="off">
                                                    <label for="numinterior">Número interior / Inside number</label>
                                                </div>

                                                <div class="form-floating col-4 mt-3">
                                                    <input type="text" class="form-control" name="colonia" id="colonia" value="<?= $registro['colonia']; ?>" autocomplete="off">
                                                    <label for="colonia">Colonia / Neighborhood</label>
                                                </div>

                                                <div class="form-floating col-4 mt-3">
                                                    <input type="text" class="form-control" name="city" id="city" value="<?= $registro['city']; ?>" autocomplete="off">
                                                    <label for="city">Ciudad / City</label>
                                                </div>

                                                <div class="form-floating col-6 mt-3">
                                                    <input type="text" class="form-control" name="state" id="state" value="<?= $registro['state']; ?>" autocomplete="off">
                                                    <label for="state">Estado / State</label>
                                                </div>

                                                <div class="form-floating col-3 mt-3">
                                                    <input type="text" class="form-control" name="pais" id="pais" value="<?= $registro['pais']; ?>" autocomplete="off">
                                                    <label for="pais">País / Country</label>
                                                </div>

                                                <div class="form-floating col-3 mt-3">
                                                    <input type="text" class="form-control" name="cpostal" id="cpostal" value="<?= $registro['cpostal']; ?>" autocomplete="off">
                                                    <label for="cpostal">Código postal / ZIP code</label>
                                                </div>

                                                <div class="form-floating col-3 mt-3">
                                                    <input type="text" class="form-control" name="telefono" id="telefono" value="<?= $registro['telefono']; ?>" autocomplete="off" autocomplete="off">
                                                    <label for="telefono">Teléfono / Phone</label>
                                                </div>

                                                <div class="form-floating col-5 mt-3">
                                                    <input type="text" class="form-control" name="contacto" id="contacto" value="<?= $registro['contacto']; ?>" autocomplete="off">
                                                    <label for="contacto">Representante / Agent</label>
                                                </div>

                                                <div class="form-floating col-4 mt-3">
                                                    <input type="text" class="form-control" name="rfc" id="rfc" value="<?= $registro['rfc']; ?>" autocomplete="off">
                                                    <label for="rfc">RFC / Tax ID</label>
                                                </div>

                                                <div class="form-floating col-12 mt-3">
                                                    <input type="text" class="form-control" name="correo" id="correo" value="<?= $registro['correo']; ?>" autocomplete="off">
                                                    <label for="correo">Correo / Email</label>
                                                </div>

                                                <input type="hidden" name="tipo" value="<?= $registro['tipo']; ?>">

                                                <div class="col-12 text-center mt-3">
                                                    <button type="submit" name="update" class="btn btn-warning">
                                                        Actualizar
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