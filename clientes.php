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

// //Verificar si existe una sesión activa y los valores de usuario y contraseña están establecidos
// if (isset($_SESSION['username'])) {
//     $username = $_SESSION['username'];

//     // Consultar la base de datos para verificar si los valores coinciden con algún registro en la tabla de usuarios
//     $query = "SELECT * FROM user WHERE username = '$username'";
//     $result = mysqli_query($con, $query);

//     // Si se encuentra un registro coincidente, el usuario está autorizado
//     if (mysqli_num_rows($result) > 0) {
//         // El usuario está autorizado, se puede acceder al contenido
//     } else {
//         // Redirigir al usuario a una página de inicio de sesión
//         header('Location: login.php');
//         exit(); // Finalizar el script después de la redirección
//     }
// } else {
//     // Redirigir al usuario a una página de inicio de sesión si no hay una sesión activa
//     header('Location: login.php');
//     exit(); // Finalizar el script después de la redirección
// }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" type="image/x-icon" href="images/ico.ico">
    <title>Clientes | LYSCE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/styles.css">
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
                                <h4 style="color:#fff" class="m-1">
                                    <button type="button" class="btn btn-primary btn-sm float-end btn-sm m-1" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                        Nuevo cliente
                                    </button>
                                    <a href="proveedores.php" class="btn btn-primary btn-sm float-end btn-sm m-1">
                                        Proveedores
                                    </a>
                                    CLIENTES
                                </h4>
                            </div>
                            <div class="card-body" style="overflow-y:scroll;">
                                <table id="miTabla" class="table table-bordered table-striped" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Cliente</th>
                                            <th>Calle</th>
                                            <th>Colonia</th>
                                            <th>Municipio</th>
                                            <th>Teléfono</th>
                                            <th>Contacto</th>
                                            <th>RFC</th>
                                            <th>Accion</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $query = "SELECT * FROM clientes ORDER BY id DESC";
                                        $query_run = mysqli_query($con, $query);
                                        if (mysqli_num_rows($query_run) > 0) {
                                            foreach ($query_run as $registro) {
                                        ?>
                                                <tr>
                                                    <td>
                                                        <p><?= $registro['id']; ?></p>
                                                    </td>
                                                    <td>
                                                        <p><?= $registro['cliente']; ?></p>
                                                    </td>
                                                    <td>
                                                        <p><?= $registro['calle']; ?></p>
                                                    </td>
                                                    <td>
                                                        <p><?= $registro['colonia']; ?></p>
                                                    </td>
                                                    <td>
                                                        <p><?= $registro['municipio']; ?></p>
                                                    </td>
                                                    <td>
                                                        <p><?= $registro['telefono']; ?></p>
                                                    </td>
                                                    <td>
                                                        <p><?= $registro['contacto']; ?></p>
                                                    </td>
                                                    <td>
                                                        <p><?= $registro['rfc']; ?></p>
                                                    </td>
                                                    <td>
                                                        <a href="editarcliente.php?id=<?= $registro['id']; ?>" class="btn btn-warning btn-sm m-1"><i class="bi bi-pencil-square"></i></a>

                                                        <form action="codecotizaciones.php" method="POST" class="d-inline">
                                                            <button type="submit" name="delete" value="<?= $registro['id']; ?>" class="btn btn-danger btn-sm m-1"><i class="bi bi-trash-fill"></i></button>
                                                        </form>

                                                    </td>
                                                </tr>
                                        <?php
                                            }
                                        } else {
                                            echo "<td colspan='9'><p> No se encontro ningun registro </p></td>";
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

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">NUEVO CLIENTE</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="clienteForm" action="codeclientes.php" method="POST" class="row">

                        <div class="col-12 col-md-12 form-floating mb-3">
                            <input type="text" class="form-control" name="cliente" placeholder="Cliete" autocomplete="off" required>
                            <label for="cliente">Cliente</label>
                        </div>
                        <div class="col-12 col-md-6 form-floating mb-3">
                            <input type="text" class="form-control" name="calle" placeholder="Calle" autocomplete="off" required>
                            <label for="calle">Calle y número</label>
                        </div>
                        <div class="col-12 col-md-6 form-floating mb-3">
                            <input type="text" class="form-control" name="colonia" placeholder="Colonia" autocomplete="off" required>
                            <label for="Colonia">Colonia</label>
                        </div>
                        <div class="col-12 col-md-7 form-floating mb-3">
                            <input type="text" class="form-control" name="municipio" placeholder="Municipio" autocomplete="off" required>
                            <label for="municipio">Municipio</label>
                        </div>
                        <div class="col-12 col-md-5 form-floating mb-3">
                            <input type="text" class="form-control" name="telefono" placeholder="Telefono" autocomplete="off" required>
                            <label for="telefono">Teléfono</label>
                        </div>
                        <div class="col-12 col-md-12 form-floating mb-3">
                            <input type="text" class="form-control" name="contacto" placeholder="Contacto" autocomplete="off" required>
                            <label for="contacto">Contacto</label>
                        </div>
                        <div class="col-12 col-md-12 form-floating mb-3">
                            <input type="text" class="form-control" name="rfc" placeholder="RFC" autocomplete="off" required>
                            <label for="rfc">RFC</label>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary" name="save">Guardar</button>
                </div>
                </form>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@10'></script>
    <script>
        document.getElementById('clienteForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Evita el envío del formulario de manera tradicional

            const form = event.target;
            const formData = new FormData(form);

            fetch('codeclientes.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text()) // O .json() si esperas una respuesta JSON
                .then(data => {
                    console.log(data); // Procesa la respuesta aquí
                    alert('Formulario enviado con éxito');
                    // Aquí podrías actualizar el DOM según la respuesta
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Hubo un problema al enviar el formulario');
                });
        });
    </script>
</body>

</html>