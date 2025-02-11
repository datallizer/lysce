<?php
require 'dbcon.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_POST['asociar'])) {
    $idProveedor = mysqli_real_escape_string($con, $_POST['idProveedor']);
    $idCliente = mysqli_real_escape_string($con, $_POST['idCliente']);

    $query = "INSERT INTO proveedorcliente (idProveedor, idCliente) VALUES ('$idProveedor', '$idCliente')";
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



if (isset($_POST['save'])) {
    $proveedor = mysqli_real_escape_string($con, $_POST['proveedor']);
    $domicilio = mysqli_real_escape_string($con, $_POST['domicilio']);
    $interior = mysqli_real_escape_string($con, $_POST['interior']);
    $exterior = mysqli_real_escape_string($con, $_POST['exterior']);
    $estado = mysqli_real_escape_string($con, $_POST['estado']);
    $fraccionamiento = mysqli_real_escape_string($con, $_POST['fraccionamiento']);
    $ciudad = mysqli_real_escape_string($con, $_POST['ciudad']);
    $country = mysqli_real_escape_string($con, $_POST['country']);
    $phone = mysqli_real_escape_string($con, $_POST['phone']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $postal = mysqli_real_escape_string($con, $_POST['postal']);
    $tax = mysqli_real_escape_string($con, $_POST['tax']);
    $millas = mysqli_real_escape_string($con, $_POST['millas']);
    $web = mysqli_real_escape_string($con, $_POST['web']);
    $contact = mysqli_real_escape_string($con, $_POST['contact']);
    $idCliente = mysqli_real_escape_string($con, $_POST['idCliente']);
    $estatus = '1';

    // Insertar el proveedor en la tabla proveedores
    $query = "INSERT INTO proveedores (proveedor, domicilio, interior, exterior, postal, estado, fraccionamiento, ciudad, country, phone, email, tax, millas, web, contact, estatus) 
              VALUES ('$proveedor', '$domicilio', '$interior', '$exterior', '$postal', '$estado', '$fraccionamiento', '$ciudad', '$country', '$phone', '$email', '$tax', '$millas', '$web', '$contact', '$estatus')";
    $query_run = mysqli_query($con, $query);

    if ($query_run) {
        // Obtener el ID del último proveedor insertado
        $idProveedor = mysqli_insert_id($con);

        // Insertar en la tabla proveedorcliente
        $query_proveedor_cliente = "INSERT INTO proveedorcliente (idProveedor, idCliente) VALUES ('$idProveedor', '$idCliente')";
        $query_run_cliente = mysqli_query($con, $query_proveedor_cliente);

        if ($query_run_cliente) {
            $_SESSION['message'] = "Se registro exitosamente";
            header("Location: proveedores.php");
            exit(0);
        } else {
            $_SESSION['message'] = "Error al registrar el proveedor cliente, contacte a soporte";
            header("Location: proveedores.php");
            exit(0);
        }
    } else {
        $_SESSION['message'] = "Error, contacte a soporte";
        header("Location: proveedores.php");
        exit(0);
    }
}
