<?php
require 'dbcon.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_POST['save'])) {
    $cliente = mysqli_real_escape_string($con, $_POST['cliente']);
    $calle = mysqli_real_escape_string($con, $_POST['calle']);
    $colonia = mysqli_real_escape_string($con, $_POST['colonia']);
    $municipio = mysqli_real_escape_string($con, $_POST['municipio']);
    $telefono = mysqli_real_escape_string($con, $_POST['telefono']);
    $contacto = mysqli_real_escape_string($con, $_POST['contacto']);
    $rfc = mysqli_real_escape_string($con, $_POST['rfc']);
    $estatus = '1';

    $query = "INSERT INTO clientes SET cliente='$cliente', calle='$calle', colonia='$colonia', municipio='$municipio', telefono='$telefono', contacto='$contacto', rfc='$rfc', estatus='$estatus'";

    $query_run = mysqli_query($con, $query);
    if ($query_run) {
        $_SESSION['message'] = "Se registro exitosamente";
        header("Location: clientes.php");
        exit(0);
    } else {
        $_SESSION['message'] = "Error, contacte a soporte";
        header("Location: clientes.php");
        exit(0);
    }
}
