<?php
session_start();
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
    <!-- <link rel="shortcut icon" type="image/x-icon" href="images/ico.png"> -->
    <title>Nuevo cliente | LYSCE</title>
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
            <div class="container-fluid">
                <div class="row mt-4 mb-5">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 style="color:#fff" class="m-1">NUEVO CLIENTE

                                    <div class="float-end">
                                        <a href="clientes.php" class="btn btn-danger btn-sm me-2">
                                            Regresar
                                        </a>
                                    </div>

                                </h4>
                            </div>
                            <div class="card-body" style="overflow-y:scroll;">
                                
                                <form action="codeclientes.php" method="POST" class="row">

                                    <div class="col-12 col-md-12 form-floating mb-3">
                                        <input type="text" class="form-control" name="cliente" placeholder="Cliente" autocomplete="off">
                                        <label for="proveedor">Cliente / Name</label>
                                    </div>
                                    <div class="col-12 col-md-8 form-floating mb-3">
                                        <input type="text" class="form-control" name="calle" placeholder="Calle" autocomplete="off">
                                        <label for="calle">Calle / Street</label>
                                    </div>

                                    <div class="col-12 col-md-4 form-floating mb-3">
                                        <input type="text" class="form-control" name="numexterior" placeholder="numExterior" autocomplete="off">
                                        <label for="numexterior">Número exterior / Outside number</label>
                                    </div>

                                    <div class="col-12 col-md-4 form-floating mb-3">
                                        <input type="text" class="form-control" name="numinterior" placeholder="numInterior" autocomplete="off">
                                        <label for="numinterior">Número interior / Inside number</label>
                                    </div>
                                    <div class="col-12 col-md-8 form-floating mb-3">
                                        <input type="text" class="form-control" name="colonia" placeholder="Colonia" autocomplete="off">
                                        <label for="colonia">Colonia / Neighbourhood</label>
                                    </div>
                                    <div class="col-12 col-md-6 form-floating mb-3">
                                        <input type="text" class="form-control" name="city" placeholder="City" autocomplete="off">
                                        <label for="city">Ciudad / City</label>
                                    </div>
                                    <div class="col-12 col-md-6 form-floating mb-3">
                                        <input type="text" class="form-control" name="state" placeholder="State" autocomplete="off">
                                        <label for="state">Estado/ State</label>
                                    </div>
                                    <div class="col-12 col-md-7 form-floating mb-3">
                                        <input type="text" class="form-control" name="cpostal" id="cpostal" placeholder="Postal" autocomplete="off">
                                        <label for="cpostal">Código postal / ZIP code</label>
                                    </div>
                                    <div class="col-12 col-md-5 form-floating mb-3">
                                        <input type="text" class="form-control" name="pais" placeholder="Pais" autocomplete="off">
                                        <label for="web">País / Country</label>
                                    </div>
                                    <div class="col-12 col-md-5 form-floating mb-3">
                                        <input type="text" class="form-control" name="telefono" placeholder="Telefono" autocomplete="off" autocomplete="off">
                                        <label for="telefono">Teléfono / Phone</label>
                                    </div>
                                    <div class="col-12 col-md-7 form-floating mb-3">
                                        <input type="text" class="form-control" name="correo" placeholder="Correo" autocomplete="off">
                                        <label for="correo">Correo / Email</label>
                                    </div>
                                    <div class="col-12 col-md-6 form-floating mb-3">
                                        <input type="text" class="form-control" name="rfc" placeholder="Rfc" autocomplete="off">
                                        <label for="rfc">RFC / Tax ID</label>
                                    </div>
                                    <div class="col-12 col-md-6 form-floating mb-3">
                                        <input type="text" class="form-control" name="contacto" placeholder="Contacto" autocomplete="off" required>
                                        <label for="contacto">Representante / Agent</label>
                                    </div>

                                    <div class="form-floating mb-3 mb-3">
                                        <select class="form-select" name="idProveedor" id="floatingSelect">
                                            <option selected>Selecciona un proveedor para asociar</option>
                                            <?php
                                            $query = "SELECT * FROM proveedores WHERE estatus = 1";
                                            $result = mysqli_query($con, $query);

                                            if (mysqli_num_rows($result) > 0) {
                                                while ($registro = mysqli_fetch_assoc($result)) {
                                                    $nombre = $registro['proveedor'];
                                                    $contacto = $registro['contact'];
                                                    $idOrigen = $registro['id'];
                                                    echo "<option value='$idOrigen'>" . $nombre . ' - ' . $contacto . "</option>";
                                                }
                                            }
                                            ?>
                                        </select>
                                        <label for="floatingSelect">Cliente</label>
                                    </div>
                            </div>
                            <div class="modal-footer">
                                <a href="clientes.php" class="btn btn-secondary">Cerrar</a>
                                <button type="submit" class="btn btn-primary" name="save">Guardar</button>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>



        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@10'></script>


</body>

</html>