<?php
require 'dbcon.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_POST['delete'])) {
    $id = mysqli_real_escape_string($con, $_POST['id']);

    $query = "DELETE FROM servicios WHERE id='$id' ";
    $query_run = mysqli_query($con, $query);

    if ($query_run) {
        $_SESSION['alert'] = [
            'title' => 'ELIMINADO EXITOSAMENTE',
            'icon' => 'success'
        ];
        header("Location: alta-servicio.php");
        exit(0);
    } else {
        $_SESSION['alert'] = [
            'message' => 'Contacte a soporte',
            'title' => 'ERROR AL ELIMINAR',
            'icon' => 'error'
        ];
        header("Location: alta-servicio.php");
        exit(0);
    }
}

if (isset($_POST['update'])) {
    $id = mysqli_real_escape_string($con, $_POST['id']);
    $concepto = mysqli_real_escape_string($con, $_POST['concepto']);
    $tipoServicio = mysqli_real_escape_string($con, $_POST['tipoServicio']);

    $query = "UPDATE `servicios` SET `concepto` = '$concepto',`tipoServicio` = '$tipoServicio' WHERE `servicios`.`id` = '$id'";
    $query_run = mysqli_query($con, $query);

    if ($query_run) {
        $_SESSION['alert'] = [
            'title' => 'EDITADO EXITOSAMENTE',
            'icon' => 'success'
        ];
        header("Location: alta-servicio.php");
        exit(0);
    } else {
        $_SESSION['alert'] = [
            'message' => 'Contacte a soporte',
            'title' => 'ERROR AL EDITAR',
            'icon' => 'error'
        ];
        header("Location: alta-servicio.php");
        exit(0);
    }
}

if (isset($_POST['save'])) {
    $concepto = mysqli_real_escape_string($con, $_POST['concepto']);
    $tipoServicio = mysqli_real_escape_string($con, $_POST['tipoServicio']);

    $query = "INSERT INTO servicios (concepto, tipoServicio) VALUES ('$concepto', '$tipoServicio')";
    $query_run = mysqli_query($con, $query);

    if ($query_run) {
        $_SESSION['alert'] = [
            'title' => 'REGISTRO EXITOSO',
            'icon' => 'success'
        ];
        header("Location: alta-servicio.php");
        exit(0);
    } else {
        $_SESSION['alert'] = [
            'message' => 'Contacte a soporte',
            'title' => 'ERROR AL REGISTRAR',
            'icon' => 'error'
        ];
        header("Location: alta-servicio.php");
        exit(0);
    }
}
