<?php
require 'dbcon.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_POST['delete'])) {
    $registro_id = mysqli_real_escape_string($con, $_POST['delete']);

    $query = "DELETE FROM transfers WHERE id='$registro_id' ";
    $query_run = mysqli_query($con, $query);

    if ($query_run) {
        $_SESSION['alert'] = [
            'title' => 'ELIMINADO EXITOSAMENTE',
            'icon' => 'success'
        ];
        header("Location: transfers.php");
        exit(0);
    } else {
        $_SESSION['alert'] = [
            'message' => 'Contacte a soporte',
            'title' => 'ERROR AL ELIMINAR',
            'icon' => 'error'
        ];
        header("Location: transfers.php");
        exit(0);
    }
}

if (isset($_POST['update'])) {
    $id = mysqli_real_escape_string($con, $_POST['id']);
    $transfer = mysqli_real_escape_string($con, $_POST['transfer']);
    $caat = mysqli_real_escape_string($con, $_POST['caat']);
    $scac = mysqli_real_escape_string($con, $_POST['scac']);
    $estatus = mysqli_real_escape_string($con, $_POST['estatus']);
    $password = mysqli_real_escape_string($con, $_POST['password']);

    // Actualizar los datos del usuario
    $query = "UPDATE `transfers` SET `transfer` = '$transfer', `caat` = '$caat', `scac` = '$scac', `estatus` = '$estatus' WHERE `transfers`.`id` = '$id'";
    $query_run = mysqli_query($con, $query);

    if ($query_run) {
        $_SESSION['alert'] = [
            'title' => 'EDITADO EXITOSAMENTE',
            'icon' => 'success'
        ];
        header("Location: transfers.php");
        exit(0);
    } else {
        $_SESSION['alert'] = [
            'message' => 'Contacte a soporte',
            'title' => 'ERROR AL EDITAR',
            'icon' => 'error'
        ];
        header("Location: transfers.php");
        exit(0);
    }
}

if (isset($_POST['save'])) {
    $transfer = mysqli_real_escape_string($con, $_POST['transfer']);
    $caat = mysqli_real_escape_string($con, $_POST['caat']);
    $scac = mysqli_real_escape_string($con, $_POST['scac']);

    $query = "INSERT INTO transfers (transfer, caat, scac, estatus) VALUES ('$transfer', '$caat', '$scac', '1')";
    $query_run = mysqli_query($con, $query);

    if ($query_run) {
        $_SESSION['alert'] = [
            'title' => 'REGISTRO EXITOSO',
            'icon' => 'success'
        ];
        header("Location: transfers.php");
        exit(0);
    } else {
        $_SESSION['alert'] = [
            'message' => 'Contacte a soporte',
            'title' => 'ERROR AL REGISTRAR',
            'icon' => 'error'
        ];
        header("Location: transfers.php");
        exit(0);
    }
}
