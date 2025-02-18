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
    <title>Proveedores | LYSCE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/styles.css">
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBynovZcvSlXHZvqYnF3NXE6TWR3reHUFc&libraries=places&callback=initMap" async defer loading="async"></script>
    <script>
        let autocompleteInstances = {}; // Objeto para guardar las instancias de Autocomplete

        function initMap() {
            const addressInputs = document.querySelectorAll('input[name="domicilio"], input[name="fraccionamiento"], input[name="ciudad"], input[name="estado"]');
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
            const addressInputs = document.querySelectorAll('input[name="domicilio"], input[name="fraccionamiento"], input[name="ciudad"], input[name="estado"]');
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
                                <h4 style="color:#fff">PROVEEDOR / AGENTE ADUANAL

                                    <div class="float-end">
                                        <a href="nuevoproveedor.php" class="btn btn-primary btn-sm m-1">
                                            Nuevo proveedor / agente aduanal
                                        </a>
                                        <button type="button" class="btn btn-primary btn-sm m-1" data-bs-toggle="modal" data-bs-target="#asociarModal">
                                            Asociar
                                        </button>
                                    </div>

                                </h4>
                            </div>
                            <div class="card-body" style="overflow-y:scroll;">
                                <table id="miTabla" class="table table-bordered table-striped" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Proveedor / Agente aduanal</th>
                                            <th>Domicilio</th>
                                            <th>Teléfono</th>
                                            <th>Correo</th>
                                            <th>Tax ID / RFC</th>
                                            <th>Cliente asociado</th>
                                            <th>Accion</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $query = "SELECT 
            p.id AS idProveedor, 
            p.proveedor, 
            p.domicilio, 
            p.fraccionamiento, 
            p.ciudad, 
            p.phone, 
            p.email, 
            p.tax, 
            GROUP_CONCAT(c.cliente SEPARATOR ', ') AS clientes_asociados
          FROM proveedores p
          LEFT JOIN proveedorcliente pc ON p.id = pc.idProveedor
          LEFT JOIN clientes c ON pc.idCliente = c.id
          GROUP BY p.id
          ORDER BY p.id DESC";

                                        $query_run = mysqli_query($con, $query);

                                        if (mysqli_num_rows($query_run) > 0) {
                                            foreach ($query_run as $registro) {
                                        ?>
                                                <tr>
                                                    <td>
                                                        <p><?= $registro['idProveedor']; ?></p>
                                                    </td>
                                                    <td>
                                                        <p><?= $registro['proveedor']; ?></p>
                                                    </td>
                                                    <td>
                                                        <p><?= $registro['domicilio']; ?>, <?= $registro['fraccionamiento']; ?>, <?= $registro['ciudad']; ?></p>
                                                    </td>
                                                    <td>
                                                        <p><?= $registro['phone']; ?></p>
                                                    </td>
                                                    <td>
                                                        <p><?= $registro['email']; ?></p>
                                                    </td>
                                                    <td>
                                                        <p><?= $registro['tax']; ?></p>
                                                    </td>
                                                    <td>
                                                        <p><?= !empty($registro['clientes_asociados']) ? $registro['clientes_asociados'] : 'Sin clientes asociados'; ?></p>
                                                    </td>
                                                    <td>
                                                        <a href="editarproveedor.php?id=<?= $registro['idProveedor']; ?>" class="btn btn-warning btn-sm m-1"><i class="bi bi-pencil-square"></i></a>

                                                        <form action="codeproveedores.php" method="POST" class="d-inline">
                                                            <button type="submit" name="delete" value="<?= $registro['idProveedor']; ?>" class="btn btn-danger btn-sm m-1"><i class="bi bi-trash-fill"></i></button>
                                                        </form>
                                                    </td>
                                                </tr>
                                        <?php
                                            }
                                        } else {
                                            echo "<td colspan='9'><p>No se encontró ningún registro</p></td>";
                                        }
                                        ?>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Asociar -->
    <div class="modal fade" id="asociarModal" tabindex="-1" aria-labelledby="asociarLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="asociarLabel">ASOCIAR CLIENTE - PROVEEDOR</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form action="codeproveedores.php" method="post">
                    <div class="modal-body row">

                        <div class="form-floating mt-3 mb-3">
                            <select class="form-select" name="idCliente" id="floatingSelect">
                                <option selected>Selecciona un cliente para asociar</option>
                                <?php
                                $query = "SELECT * FROM clientes WHERE estatus = 1";
                                $result = mysqli_query($con, $query);

                                if (mysqli_num_rows($result) > 0) {
                                    while ($registro = mysqli_fetch_assoc($result)) {
                                        $nombre = $registro['cliente'];
                                        $contacto = $registro['contacto'];
                                        $idOrigen = $registro['id'];
                                        echo "<option value='$idOrigen'>" . $nombre . ' - ' . $contacto . "</option>";
                                    }
                                }
                                ?>
                            </select>
                            <label for="floatingSelect">Cliente</label>
                        </div>

                        <div class="form-floating mt-3 mb-3">
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
                            <label for="floatingSelect">Proveedor</label>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary" name="asociar">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@10'></script>


</body>

</html>