<?php
require 'dbcon.php';
//$email = $_SESSION['email'];
?>
<link rel="stylesheet" href="css/sidenav.css">
<script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3" href="dashboard.php"><img style="width: 80px;" src="images/logo.png" alt=""></a>
        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>

        <!-- Espacio entre el logo y el botón de salir -->
        <div class="d-flex justify-content-end w-100">
            <a style="margin-right: 15px;" class="btn btn-warning" href="logout.php">Salir <i class="bi bi-box-arrow-right"></i></a>
        </div>
    </nav>

    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <div class="sb-sidenav-menu-heading">Principal</div>
                        <a class="nav-link" href="dashboard.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Dashboard
                        </a>
                        <a class="nav-link" href="usuarios.php">
                            <div class="sb-nav-link-icon"><i class="bi bi-person-fill"></i></div>
                            Usuarios
                        </a>
                        <!-- <a class="nav-link" href="cotizaciones.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Cotizaciones
                        </a> -->
                        <div class="sb-sidenav-menu-heading">Modulos</div>
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseAereo" aria-expanded="false" aria-controls="collapseAereo">
                            <div class="sb-nav-link-icon"><i class="bi bi-airplane-fill"></i></div>
                            Aéreo
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseAereo" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="aereo-importacion.php">- Importación</a>
                                <a class="nav-link" href="aereo-exportacion.php">- Exportación</a>
                            </nav>
                        </div>

                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseTerrestre" aria-expanded="false" aria-controls="collapseTerrestre">
                            <div class="sb-nav-link-icon"><i class="bi bi-truck-front-fill"></i></div>
                            Terrestre
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseTerrestre" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="ltl.php">- LTL</a>
                                <a class="nav-link" href="ftl.php">- FTL</a>
                            </nav>
                        </div>

                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseMaritimo" aria-expanded="false" aria-controls="collapseMaritimo">
                            <div class="sb-nav-link-icon"><i class="bi bi-tsunami"></i></div>
                            Marítimo
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseMaritimo" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="lcl.php">- LCL</a>
                                <a class="nav-link" href="fcl.php">- FCL</a>
                            </nav>
                        </div>
                        <div class="sb-sidenav-menu-heading">Panel de control</div>
                        <a class="nav-link" href="clientes.php">
                            <div class="sb-nav-link-icon"><i class="bi bi-person-vcard-fill"></i></div>
                            Clientes
                        </a>
                        <a class="nav-link" href="proveedores.php">
                            <div class="sb-nav-link-icon"><i class="bi bi-box-fill"></i></div>
                            Proveedores
                        </a>
                        <a class="nav-link" href="alta-servicios.php">
                            <div class="sb-nav-link-icon"><i class="bi bi-clipboard-fill"></i></div>
                            Servicios
                        </a>
                        <a class="nav-link" href="alta-incrementables.php">
                            <div class="sb-nav-link-icon"><i class="bi bi-graph-up-arrow"></i></div>
                            Incrementables
                        </a>
                        <a class="nav-link" href="transportistas.php">
                            <div class="sb-nav-link-icon"><i class="bi bi-truck"></i></div>
                            Transportistas
                        </a>
                        <a class="nav-link" href="transfers.php">
                            <div class="sb-nav-link-icon"><i class="bi bi-buildings"></i></div>
                            Transfers
                        </a>
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Usuario:</div>
                    <?php
                    if (isset($_SESSION['email'])) {
                        $registro_id = mysqli_real_escape_string($con, $_SESSION['email']);
                        $query = "SELECT * FROM usuarios WHERE email='$registro_id' ";
                        $query_run = mysqli_query($con, $query);

                        if (mysqli_num_rows($query_run) > 0) {
                            $registro = mysqli_fetch_array($query_run);
                    ?>
                            <p><?= $registro['nombre']; ?> <?= $registro['apellidop']; ?> <?= $registro['apellidom']; ?></p>

                    <?php
                        } else {
                            echo "<p>Error contacte a soporte</p>";
                        }
                    }
                    ?>
                </div>
            </nav>
        </div>
    </div>
</body>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="js/sidenav.js"></script>