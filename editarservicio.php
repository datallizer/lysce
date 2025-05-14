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
    <title>Editar cotización | LYSCE</title>
    <link rel="shortcut icon" type="image/x-icon" href="images/ics.ico" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
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
                                <h4>EDITAR COTIZACION
                                    <a href="alta-servicios.php" class="btn btn-danger btn-sm float-end">Regresar</a>
                                </h4>
                            </div>
                            <div class="card-body">

                                <?php

                                if (isset($_GET['id'])) {
                                    $registro_id = mysqli_real_escape_string($con, $_GET['id']);
                                    $query = "SELECT * FROM tiposervicio WHERE id='$registro_id' ";
                                    $query_run = mysqli_query($con, $query);

                                    if (mysqli_num_rows($query_run) > 0) {
                                        $registro = mysqli_fetch_array($query_run);
                                        $tipoServicio = $registro['tipoServicio'];

                                ?>

                                        <form action="codeservicios.php" method="POST" class="row">
                                            <input type="hidden" name="id" value="<?= $registro['id']; ?>">
                                            <div class="form-floating col-12 col-md-7">
                                                <input type="text" class="form-control" name="nombreServicio" id="nombreServicio" value="<?= $registro['nombreServicio']; ?>" autocomplete="off">
                                                <label for="nombreServicio">Título</label>
                                            </div>

                                            <div class="form-floating col-12 col-md-5">
                                                <select class="form-select" name="tipoServicio" id="tipoServicio">
                                                    <option value="" disabled selected>Selecciona una opción</option>
                                                    <option value="aereoimpo" <?= ($tipoServicio == 'aereoimpo') ? 'selected' : ''; ?>>Aéreo importación</option>
                                                    <option value="aereoexpo" <?= ($tipoServicio == 'aereoexpo') ? 'selected' : ''; ?>>Aéreo exportación</option>
                                                    <option value="ltl" <?= ($tipoServicio == 'ltl') ? 'selected' : ''; ?>>Terrestre LTL</option>
                                                    <option value="ftl" <?= ($tipoServicio == 'ftl') ? 'selected' : ''; ?>>Terrestre FTL</option>
                                                    <option value="lcl" <?= ($tipoServicio == 'lcl') ? 'selected' : ''; ?>>Marítimo LCL</option>
                                                    <option value="fcl" <?= ($tipoServicio == 'fcl') ? 'selected' : ''; ?>>Marítimo FCL</option>
                                                </select>
                                                <label for="tipoServicio">Tipo de servicio</label>
                                            </div>

                                            <div class="col-12 text-center mt-3">
                                                <button type="submit" name="update" class="btn btn-primary">
                                                    Actualizar
                                                </button>
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
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
</body>

</html>