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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <link rel="shortcut icon" type="image/x-icon" href="images/ics.ico" />
    <link rel="stylesheet" href="css/styles.css">
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBynovZcvSlXHZvqYnF3NXE6TWR3reHUFc&libraries=places&callback=initMap" async defer loading="async"></script>
    <script>
        let autocompleteInstances = {}; // Objeto para guardar las instancias de Autocomplete

        function initMap() {
            const addressInputs = document.querySelectorAll('input[name="domicilio"]');
            const postalCodeInput = document.getElementById('postal');

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
            const addressInputs = document.querySelectorAll('input[name="domicilio"]');
            const postalCodeInput = document.getElementById('postal');

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
                        document.querySelector('input[name="exterior"]').value = longName;
                        break;
                    case "route":
                        document.querySelector('input[name="domicilio"]').value = longName;
                        break;
                    case "sublocality_level_1":
                        document.querySelector('input[name="fraccionamiento"]').value = longName;
                        break;
                    case "locality":
                        document.querySelector('input[name="ciudad"]').value = longName;
                        break;
                    case "administrative_area_level_1":
                        document.querySelector('input[name="estado"]').value = longName;
                        break;
                    case "country":
                        document.querySelector('input[name="country"]').value = longName;
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
                                                    <input type="text" class="form-control" name="proveedor" id="proveedor" value="<?= $registro['proveedor']; ?>" autocomplete="off">
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
                                                    <input type="text" class="form-control" name="domicilio" id="domicilio" value="<?= $registro['domicilio']; ?>" autocomplete="off">
                                                    <label for="domicilio">Calle / Street</label>
                                                </div>

                                                <div class="form-floating col-12 col-md-5 mt-3">
                                                    <input type="text" class="form-control" name="exterior" id="exterior" value="<?= $registro['exterior']; ?>" autocomplete="off">
                                                    <label for="exterior">Número exterior / Outside number</label>
                                                </div>

                                                <div class="form-floating col-4 mt-3">
                                                    <input type="text" class="form-control" name="interior" id="interior" value="<?= $registro['interior']; ?>" autocomplete="off">
                                                    <label for="interior">Número interior / Inside number</label>
                                                </div>

                                                <div class="form-floating col-4 mt-3">
                                                    <input type="text" class="form-control" name="fraccionamiento" id="fraccionamiento" value="<?= $registro['fraccionamiento']; ?>" autocomplete="off">
                                                    <label for="fraccionamiento">Colonia / Neighborhood</label>
                                                </div>

                                                <div class="form-floating col-4 mt-3">
                                                    <input type="text" class="form-control" name="ciudad" id="ciudad" value="<?= $registro['ciudad']; ?>" autocomplete="off">
                                                    <label for="ciudad">Ciudad / City</label>
                                                </div>

                                                <div class="form-floating col-6 mt-3">
                                                    <input type="text" class="form-control" name="estado" id="estado" value="<?= $registro['estado']; ?>" autocomplete="off">
                                                    <label for="estado">Estado / State</label>
                                                </div>

                                                <div class="form-floating col-3 mt-3">
                                                    <input type="text" class="form-control" name="country" id="country" value="<?= $registro['country']; ?>" autocomplete="off">
                                                    <label for="country">País / Country</label>
                                                </div>

                                                <div class="form-floating col-3 mt-3">
                                                    <input type="text" class="form-control" name="postal" id="postal" value="<?= $registro['postal']; ?>" autocomplete="off">
                                                    <label for="postal">Código postal / ZIP code</label>
                                                </div>

                                                <div class="form-floating col-3 mt-3">
                                                    <input type="text" class="form-control" name="phone" id="phone" value="<?= $registro['phone']; ?>" autocomplete="off">
                                                    <label for="phone">Teléfono / Phone</label>
                                                </div>

                                                <div class="form-floating col-5 mt-3">
                                                    <input type="text" class="form-control" name="contact" id="contact" value="<?= $registro['contact']; ?>" autocomplete="off">
                                                    <label for="contact">Representante / Agent</label>
                                                </div>

                                                <div class="form-floating col-4 mt-3">
                                                    <input type="text" class="form-control" name="tax" id="tax" value="<?= $registro['tax']; ?>" autocomplete="off">
                                                    <label for="tax">RFC / Tax ID</label>
                                                </div>

                                                <div class="form-floating col-7 mt-3">
                                                    <input type="text" class="form-control" name="email" id="email" value="<?= $registro['email']; ?>" autocomplete="off">
                                                    <label for="email">Correo / Email</label>
                                                </div>

                                                <div class="form-floating col-5 mt-3">
                                                    <input type="text" class="form-control" name="web" id="web" value="<?= $registro['web']; ?>" autocomplete="off">
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