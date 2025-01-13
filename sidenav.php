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
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Usuarios
                        </a>
                        <!-- <a class="nav-link" href="cotizaciones.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Cotizaciones
                        </a> -->
                        <div class="sb-sidenav-menu-heading">Modulos</div>
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseAereo" aria-expanded="false" aria-controls="collapseAereo">
                            <div class="sb-nav-link-icon"><i class="bi bi-shield-fill-check"></i></div>
                            Aéreo
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseAereo" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="aereoimpointernacional.php">Importación carga internacional</a>
                                <a class="nav-link" href="cotizaciones.php">Exportación carga internacional</a>
                            </nav>
                        </div>

                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapsePages" aria-expanded="false" aria-controls="collapsePages">
                            <div class="sb-nav-link-icon"><i class="fas fa-book-open"></i></div>
                            Terrestre
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapsePages" aria-labelledby="headingTwo" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav accordion" id="sidenavAccordionPages">
                                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#ftlUsa" aria-expanded="false" aria-controls="ftlUsa">
                                    FTL USA
                                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                </a>
                                <div class="collapse" id="ftlUsa" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordionPages">
                                    <nav class="sb-sidenav-menu-nested nav">
                                        <a class="nav-link" href="ftlplataformasobrepeso.php">Plataforma con sobrepeso</a>
                                        <a class="nav-link" href="ftlhotshotusa.php">Hotshot trailer completo</a>
                                        <!-- <a class="nav-link" href="dashboard.php">Flete consolidado</a> -->
                                        <a class="nav-link" href="expoftlusa.php">Dry van 53 ft</a>
                                        <a class="nav-link" href="ftlfletesobredimensionado.php">Plataforma con sobredimensionado</a>
                                        <a class="nav-link" href="ftlinbondfreightcanada.php.php">In bond freight Canada</a>
                                        <a class="nav-link" href="dashboard.php">Carga completa 2 Stops</a>
                                    </nav>
                                </div>
                                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#ftlMex" aria-expanded="false" aria-controls="ftlMex">
                                    FTL MEX
                                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                </a>
                                <div class="collapse" id="ftlMex" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordionPages">
                                    <nav class="sb-sidenav-menu-nested nav">
                                        <a class="nav-link" href="dashboard.php">Camioneta dedicada, operador sencillo</a>
                                        <a class="nav-link" href="dashboard.php">Caja 53 pies, materiales peligrosos</a>
                                        <a class="nav-link" href="dashboard.php">Sobredimensionado</a>
                                    </nav>
                                </div>
                                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#ltlImpo" aria-expanded="false" aria-controls="ltlImpo">
                                    LTL IMPO
                                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                </a>
                                <div class="collapse" id="ltlImpo" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordionPages">
                                    <nav class="sb-sidenav-menu-nested nav">
                                        <a class="nav-link" href="impoterrestreltlusa.php">Flete consolidado USA</a>
                                        <a class="nav-link" href="dashboard.php">In bond freight Canada</a>
                                    </nav>
                                </div>
                                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#ltlExpo" aria-expanded="false" aria-controls="ltlExpo">
                                    LTL EXPO
                                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                </a>
                                <div class="collapse" id="ltlExpo" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordionPages">
                                    <nav class="sb-sidenav-menu-nested nav">
                                        <a class="nav-link" href="dashboard.php">Flete consolidado USA</a>
                                        <a class="nav-link" href="dashboard.php">Flete directo Hotshot USA</a>
                                    </nav>
                                </div>
                            </nav>
                        </div>

                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseMaritimo" aria-expanded="false" aria-controls="collapseMaritimo">
                            <div class="sb-nav-link-icon"><i class="bi bi-shield-fill-check"></i></div>
                            Marítimo
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseMaritimo" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="dashboard.php">LCL Contenedor importación</a>
                                <a class="nav-link" href="dashboard.php">FCL Contenedor completo 40 ft</a>
                            </nav>
                        </div>
                        <div class="sb-sidenav-menu-heading">Panel de control</div>
                        <a class="nav-link" href="clientes.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                            Clientes
                        </a>
                        <a class="nav-link" href="proveedores.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                            Proveedores
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