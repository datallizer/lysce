<?php
require 'dbcon.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_POST['save'])) {
    $proveedor = mysqli_real_escape_string($con, $_POST['proveedor']);
    $domicilio = mysqli_real_escape_string($con, $_POST['domicilio']);
    $fraccionamiento = mysqli_real_escape_string($con, $_POST['fraccionamiento']);
    $ciudad = mysqli_real_escape_string($con, $_POST['ciudad']);
    $phone = mysqli_real_escape_string($con, $_POST['phone']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $tax = mysqli_real_escape_string($con, $_POST['tax']);
    $millas = mysqli_real_escape_string($con, $_POST['millas']);
    $web = mysqli_real_escape_string($con, $_POST['web']);
    $contact = mysqli_real_escape_string($con, $_POST['contact']);
    $estatus = '1';

    $query = "INSERT INTO proveedores SET proveedor='$proveedor', domicilio='$domicilio', fraccionamiento='$fraccionamiento', ciudad='$ciudad', phone='$phone', email='$email', tax='$tax', millas='$millas', web='$web', contact='$contact', estatus='$estatus'";

    $query_run = mysqli_query($con, $query);
    if ($query_run) {
        $_SESSION['message'] = "Se registro exitosamente";
        header("Location: proveedores.php");
        exit(0);
    } else {
        $_SESSION['message'] = "Error, contacte a soporte";
        header("Location: proveedores.php");
        exit(0);
    }
}
