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
    <title>Proveedores | LYSCE</title>
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
                                <h4 style="color:#fff" class="m-1">PROVEEDOR / AGENTE ADUANAL   
                                    
                                <div class="float-end">
                                <button type="button" class="btn btn-primary btn-sm me-2" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                    Nuevo proveedor / agente aduanal
                                </button>
                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#asociarModal">
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
                                            <th>Millas</th>
                                            <th>Accion</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $query = "SELECT * FROM proveedores ORDER BY id DESC";
                                        $query_run = mysqli_query($con, $query);
                                        if (mysqli_num_rows($query_run) > 0) {
                                            foreach ($query_run as $registro) {
                                        ?>
                                                <tr>
                                                    <td>
                                                        <p><?= $registro['id']; ?></p>
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
                                                        <p><?= $registro['millas']; ?></p>
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
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">NUEVO PROVEEDOR / AGENTE ADUANAL</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="codeproveedores.php" method="POST" class="row">

                        <div class="col-12 col-md-12 form-floating mb-3">
                            <input type="text" class="form-control" name="proveedor" placeholder="Proveedor" autocomplete="off">
                            <label for="proveedor">Proveedor / Agente aduanal</label>
                        </div>
                        <div class="col-12 col-md-6 form-floating mb-3">
                            <input type="text" class="form-control" name="domicilio" placeholder="Domicilio" autocomplete="off">
                            <label for="domicilio">Calle y número</label>
                        </div>
                        <div class="col-12 col-md-6 form-floating mb-3">
                            <input type="text" class="form-control" name="fraccionamiento" placeholder="Fraccionamiento" autocomplete="off">
                            <label for="fraccionamiento">Colonia</label>
                        </div>
                        <div class="col-12 col-md-7 form-floating mb-3">
                            <input type="text" class="form-control" name="ciudad" placeholder="Ciudad" autocomplete="off">
                            <label for="ciudad">Ciudad y CP</label>
                        </div>
                        <div class="col-12 col-md-5 form-floating mb-3">
                            <input type="text" class="form-control" name="phone" placeholder="Phone" autocomplete="off">
                            <label for="phone">Teléfono</label>
                        </div>
                        <div class="col-12 col-md-6 form-floating mb-3">
                            <input type="text" class="form-control" name="email" placeholder="Email" autocomplete="off">
                            <label for="email">Email</label>
                        </div>
                        <div class="col-12 col-md-6 form-floating mb-3">
                            <input type="text" class="form-control" name="tax" placeholder="Tax" autocomplete="off">
                            <label for="tax">Tax ID</label>
                        </div>
                        <div class="col-12 col-md-4 form-floating">
                            <input type="text" class="form-control" name="millas" placeholder="Millas" autocomplete="off">
                            <label for="millas">Millas</label>
                        </div>
                        <div class="col-12 col-md-4 form-floating">
                            <input type="text" class="form-control" name="web" placeholder="Web" autocomplete="off">
                            <label for="web">Web</label>
                        </div>
                        <div class="col-12 col-md-4 form-floating">
                            <input type="text" class="form-control" name="contact" placeholder="Contact" autocomplete="off" required>
                            <label for="contact">Contacto</label>
                        </div>

                        <div class="mb-3">
                        <label for="clienteSelect" class="form-label"></label>
                        <select class="form-select" id="clienteSelect" required>
                            <option value="">Seleccione un cliente</option>
                            <option value="cliente1">Cliente 1</option>
                            <option value="cliente2">Cliente 2</option>
                            <option value="cliente3">Cliente 3</option>
                            
                        </select>
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

<!-- Modal Asociar -->
<div class="modal fade" id="asociarModal" tabindex="-1" aria-labelledby="asociarLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="asociarLabel">Asociar Cliente con Proveedor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formAsociar">
                    
                    <div class="mb-3">
                        <label for="clienteSelect" class="form-label">Seleccionar Cliente</label>
                        <select class="form-select" id="clienteSelect" required>
                            <option value="">Seleccione un cliente</option>
                            <option value="cliente1">Cliente 1</option>
                            <option value="cliente2">Cliente 2</option>
                            <option value="cliente3">Cliente 3</option>
                            
                        </select>
                    </div>
                   
                    <div class="mb-3">
                        <label for="proveedorSelect" class="form-label">Seleccionar Proveedor</label>
                        <select class="form-select" id="proveedorSelect" required>
                            <option value="">Seleccione un proveedor</option>
                            <option value="proveedor1">Proveedor 1</option>
                            <option value="proveedor2">Proveedor 2</option>
                            <option value="proveedor3">Proveedor 3</option>
                            
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="guardarAsociacion">Guardar Asociación</button>
            </div>
        </div>
    </div>
</div>





    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@10'></script>

</body>

</html>