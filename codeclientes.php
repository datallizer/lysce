<?php
require 'dbcon.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_POST['save'])) {
    $cliente = mysqli_real_escape_string($con, $_POST['cliente']);
    $calle = mysqli_real_escape_string($con, $_POST['calle']);
    $numinterior = mysqli_real_escape_string($con, $_POST['numinterior']);
    $numexterior = mysqli_real_escape_string($con, $_POST['numexterior']);
    $state = mysqli_real_escape_string($con, $_POST['state']);
    $colonia = mysqli_real_escape_string($con, $_POST['colonia']);
    $city = mysqli_real_escape_string($con, $_POST['city']);
    $telefono = mysqli_real_escape_string($con, $_POST['telefono']);
    $correo = mysqli_real_escape_string($con, $_POST['correo']);
    $cpostal = mysqli_real_escape_string($con, $_POST['cpostal']);
    $rfc = mysqli_real_escape_string($con, $_POST['rfc']);
    $pais = mysqli_real_escape_string($con, $_POST['pais']);
    $contacto = mysqli_real_escape_string($con, $_POST['contacto']);
    $idProveedor = mysqli_real_escape_string($con, $_POST['idProveedor']);
    $estatus = '1';

    // Insertar el proveedor en la tabla proveedores
    $query = "INSERT INTO clientes (cliente, calle, numinterior, numexterior, cpostal, state, colonia, city, telefono, correo, rfc, pais, contacto, estatus) 
              VALUES ('$cliente', '$calle', '$numinterior', '$numexterior', '$cpostal', '$state', '$colonia', '$city', '$telefono', '$correo', '$rfc', '$pais', '$contacto', '$estatus')";
    $query_run = mysqli_query($con, $query);

    if ($query_run) {
        // Obtener el ID del último proveedor insertado
        $idCliente = mysqli_insert_id($con);

        // Insertar en la tabla proveedorcliente
        $query_proveedor_cliente = "INSERT INTO proveedorcliente (idProveedor, idCliente) VALUES ('$idProveedor', '$idCliente')";
        $query_run_cliente = mysqli_query($con, $query_proveedor_cliente);

        if ($query_run_cliente) {
            $_SESSION['message'] = "Se registro exitosamente";
            header("Location: clientes.php");
            exit(0);
        } else {
            $_SESSION['message'] = "Error al registrar el proveedor cliente, contacte a soporte";
            header("Location: clientes.php");
            exit(0);
        }
    } else {
        $_SESSION['message'] = "Error, contacte a soporte";
        header("Location: clientes.php");
        exit(0);
    }
}
