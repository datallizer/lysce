<?php
require 'dbcon.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_POST['delete'])) {
    $id = mysqli_real_escape_string($con, $_POST['id']);
    $tipo = mysqli_real_escape_string($con, $_POST['tipo']);

    $query = "DELETE FROM clientes WHERE id='$id' ";
    $query_run = mysqli_query($con, $query);

    if ($query_run) {
        $_SESSION['alert'] = [
            'title' => 'ELIMINADO EXITOSAMENTE',
            'icon' => 'success'
        ];
        if ($tipo == 'Cliente') {
            header("Location: clientes.php");
        } elseif ($tipo == 'Proveedor') {
            header("Location: proveedores.php");
        }
        exit(0);
    } else {
        $_SESSION['alert'] = [
            'message' => 'Contacte a soporte',
            'title' => 'ERROR AL ELIMINAR',
            'icon' => 'error'
        ];
        if ($tipo == 'Cliente') {
            header("Location: clientes.php");
        } elseif ($tipo == 'Proveedor') {
            header("Location: proveedores.php");
        }
        exit(0);
    }
}

if (isset($_POST['update'])) {
    $id = mysqli_real_escape_string($con, $_POST['id']);
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
    $web = mysqli_real_escape_string($con, $_POST['web']);
    $tipo = mysqli_real_escape_string($con, $_POST['tipo']);
    $estatus = mysqli_real_escape_string($con, $_POST['estatus']);

    $query = "UPDATE clientes SET cliente = '$cliente', calle = '$calle', numinterior = '$numinterior', numexterior = '$numexterior', cpostal = '$cpostal', state = '$state', colonia = '$colonia', city = '$city', telefono = '$telefono', correo = '$correo', rfc = '$rfc', pais = '$pais', contacto = '$contacto', estatus = '$estatus' WHERE id = '$id'";
    $query_run = mysqli_query($con, $query);

    if ($query_run) {
        $_SESSION['alert'] = [
            'title' => 'ACTUALIZADO EXITOSAMENTE',
            'icon' => 'success'
        ];
        if ($tipo == 'Cliente') {
            header("Location: clientes.php");
        } elseif ($tipo == 'Proveedor') {
            header("Location: proveedores.php");
        }
        exit(0);
    } else {
        $_SESSION['alert'] = [
            'message' => 'Contacte a soporte',
            'title' => 'ERROR AL ACTUALIZAR',
            'icon' => 'error'
        ];
        if ($tipo == 'Cliente') {
            header("Location: clientes.php");
        } elseif ($tipo == 'Proveedor') {
            header("Location: proveedores.php");
        }
        exit(0);
    }
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
    $web = mysqli_real_escape_string($con, $_POST['web']);
    $tipo = mysqli_real_escape_string($con, $_POST['tipo']);
    $estatus = '1';

    // Insertar el proveedor en la tabla proveedores
    $query = "INSERT INTO clientes (cliente, calle, numinterior, numexterior, cpostal, state, colonia, city, telefono, correo, rfc, pais, contacto, web, tipo, estatus) 
              VALUES ('$cliente', '$calle', '$numinterior', '$numexterior', '$cpostal', '$state', '$colonia', '$city', '$telefono', '$correo', '$rfc', '$pais', '$contacto', '$web', '$tipo', '$estatus')";
    $query_run = mysqli_query($con, $query);

    if ($query_run) {
        // Obtener el ID del último proveedor insertado
        $idCliente = mysqli_insert_id($con);

        // Insertar en la tabla proveedorcliente
        $query_proveedor_cliente = "INSERT INTO proveedorcliente (idProveedor, idCliente) VALUES ('$idProveedor', '$idCliente')";
        $query_run_cliente = mysqli_query($con, $query_proveedor_cliente);

        if ($query_run_cliente) {
            $_SESSION['alert'] = [
                'title' => 'REGISTRADO EXITOSAMENTE',
                'icon' => 'success'
            ];
            if ($tipo == 'Cliente') {
                header("Location: clientes.php");
            } elseif ($tipo == 'Proveedor') {
                header("Location: proveedores.php");
            }
            exit(0);
        } else {
            $_SESSION['alert'] = [
                'message' => 'Contacte a soporte',
                'title' => 'ERROR AL VINCULAR CLIENTE-PROVEEDOR',
                'icon' => 'error'
            ];
            if ($tipo == 'Cliente') {
                header("Location: clientes.php");
            } elseif ($tipo == 'Proveedor') {
                header("Location: proveedores.php");
            }
            exit(0);
        }
    } else {
        $_SESSION['alert'] = [
            'message' => 'Contacte a soporte',
            'title' => 'ERROR AL REGISTRAR EL CLIENTE',
            'icon' => 'error'
        ];
        if ($tipo == 'Cliente') {
            header("Location: clientes.php");
        } elseif ($tipo == 'Proveedor') {
            header("Location: proveedores.php");
        }
        exit(0);
    }
}
