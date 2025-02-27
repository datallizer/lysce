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
        $_SESSION['alert'] = [
            'title' => 'SE ASOCIO CORRECTAMENTE',
            'icon' => 'success'
        ];
        header("Location: proveedores.php");
        exit(0);
    } else {
        $_SESSION['alert'] = [
            'message' => 'Contacte a soporte',
            'title' => 'ERROR AL ASOCIAR',
            'icon' => 'error'
        ];
        header("Location: proveedores.php");
        exit(0);
    }
}

if (isset($_POST['delete'])) {
    $registro_id = mysqli_real_escape_string($con, $_POST['delete']);

    $query = "DELETE FROM proveedores WHERE id='$registro_id' ";
    $query_run = mysqli_query($con, $query);

    if ($query_run) {
        $_SESSION['alert'] = [
            'title' => 'ELIMINADO EXITOSAMENTE',
            'icon' => 'success'
        ];
        header("Location: proveedores.php");
        exit(0);
    } else {
        $_SESSION['alert'] = [
            'message' => 'Contacte a soporte',
            'title' => 'ERROR AL ELIMINAR',
            'icon' => 'error'
        ];
        header("Location: proveedores.php");
        exit(0);
    }
}

if (isset($_POST['update'])) {
    $id = mysqli_real_escape_string($con, $_POST['id']);
    $proveedor = mysqli_real_escape_string($con, $_POST['proveedor']);
    $domicilio = mysqli_real_escape_string($con, $_POST['domicilio']);
    $interior = mysqli_real_escape_string($con, $_POST['interior']);
    $exterior = mysqli_real_escape_string($con, $_POST['exterior']);
    $estado = mysqli_real_escape_string($con, $_POST['estado']);
    $fraccionamiento = mysqli_real_escape_string($con, $_POST['fraccionamiento']);
    $ciudad = mysqli_real_escape_string($con, $_POST['ciudad']);
    $phone = mysqli_real_escape_string($con, $_POST['phone']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $postal = mysqli_real_escape_string($con, $_POST['postal']);
    $tax = mysqli_real_escape_string($con, $_POST['tax']);
    $country = mysqli_real_escape_string($con, $_POST['country']);
    $contact = mysqli_real_escape_string($con, $_POST['contact']);
    $web = mysqli_real_escape_string($con, $_POST['web']);
    $estatus = mysqli_real_escape_string($con, $_POST['estatus']);
    
    $query = "UPDATE proveedores SET proveedor = '$proveedor', domicilio = '$domicilio', interior = '$interior', exterior = '$exterior', postal = '$postal', estado = '$estado', fraccionamiento = '$fraccionamiento', ciudad = '$ciudad', phone = '$phone', email = '$email', tax = '$tax', country = '$country', contact = '$contact', web = '$web', estatus = '$estatus' WHERE id = '$id'";
    $query_run = mysqli_query($con, $query);
    
    if ($query_run) {
        $_SESSION['alert'] = [
            'title' => 'ACTUALIZADO EXITOSAMENTE',
            'icon' => 'success'
        ];
        header("Location: proveedores.php");
        exit(0);
    } else {
        $_SESSION['alert'] = [
            'message' => 'Contacte a soporte',
            'title' => 'ERROR AL ACTUALIZAR',
            'icon' => 'error'
        ];
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
    $web = mysqli_real_escape_string($con, $_POST['web']);
    $contact = mysqli_real_escape_string($con, $_POST['contact']);
    $idCliente = mysqli_real_escape_string($con, $_POST['idCliente']);
    $estatus = '1';

    // Insertar el proveedor en la tabla proveedores
    $query = "INSERT INTO proveedores (proveedor, domicilio, interior, exterior, postal, estado, fraccionamiento, ciudad, country, phone, email, tax, web, contact, estatus) 
              VALUES ('$proveedor', '$domicilio', '$interior', '$exterior', '$postal', '$estado', '$fraccionamiento', '$ciudad', '$country', '$phone', '$email', '$tax', '$web', '$contact', '$estatus')";
    $query_run = mysqli_query($con, $query);

    if ($query_run) {
        // Obtener el ID del último proveedor insertado
        $idProveedor = mysqli_insert_id($con);

        // Insertar en la tabla proveedorcliente
        $query_proveedor_cliente = "INSERT INTO proveedorcliente (idProveedor, idCliente) VALUES ('$idProveedor', '$idCliente')";
        $query_run_cliente = mysqli_query($con, $query_proveedor_cliente);

        if ($query_run_cliente) {
            $_SESSION['alert'] = [
                'title' => 'SE REGISTRO EXITOSAMENTE',
                'icon' => 'success'
            ];
            header("Location: proveedores.php");
            exit(0);
        } else {
            $_SESSION['alert'] = [
                'message' => 'Contacte a soporte',
                'title' => 'ERROR AL ASOCIAR',
                'icon' => 'error'
            ];
            header("Location: proveedores.php");
            exit(0);
        }
    } else {
        $_SESSION['alert'] = [
            'message' => 'Contacte a soporte',
            'title' => 'ERROR AL REGISTRAR',
            'icon' => 'error'
        ];
        header("Location: proveedores.php");
        exit(0);
    }
}
