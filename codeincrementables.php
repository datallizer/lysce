<?php
require 'dbcon.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_POST['delete'])) {
    $registro_id = mysqli_real_escape_string($con, $_POST['delete']);

    $query = "DELETE FROM tipoincrementable WHERE id='$registro_id' ";
    $query_run = mysqli_query($con, $query);

    if ($query_run) {
        $_SESSION['alert'] = [
            'title' => 'ELIMINADO EXITOSAMENTE',
            'icon' => 'success'
        ];
        header("Location: alta-incrementables.php");
        exit(0);
    } else {
        $_SESSION['alert'] = [
            'message' => 'Contacte a soporte',
            'title' => 'ERROR AL ELIMINAR',
            'icon' => 'error'
        ];
        header("Location: alta-incrementables.php");
        exit(0);
    }
}

if (isset($_POST['update'])) {
    $id = mysqli_real_escape_string($con, $_POST['id']);
    $incrementable = mysqli_real_escape_string($con, $_POST['incrementable']);
    $tipo = mysqli_real_escape_string($con, $_POST['tipo']);

    $query = "UPDATE `tipoincrementable` SET `incrementable` = '$incrementable',`tipo` = '$tipo' WHERE `tipoincrementable`.`id` = '$id'";
    $query_run = mysqli_query($con, $query);

    if ($query_run) {
        $_SESSION['alert'] = [
            'title' => 'EDITADO EXITOSAMENTE',
            'icon' => 'success'
        ];
        header("Location: alta-incrementables.php");
        exit(0);
    } else {
        $_SESSION['alert'] = [
            'message' => 'Contacte a soporte',
            'title' => 'ERROR AL EDITAR',
            'icon' => 'error'
        ];
        header("Location: alta-incrementables.php");
        exit(0);
    }
}



if (isset($_POST['save'])) {
    $incrementable = mysqli_real_escape_string($con, $_POST['incrementable']);
    $tipo = mysqli_real_escape_string($con, $_POST['tipo']);

    $query = "INSERT INTO tipoincrementable (incrementable, tipo) VALUES ('$incrementable', '$tipo')";
    $query_run = mysqli_query($con, $query);

    if ($query_run) {
        $_SESSION['alert'] = [
            'title' => 'REGISTRADO EXITOSAMENTE',
            'icon' => 'success'
        ];
        header("Location: alta-incrementables.php");
        exit(0);
    } else {
        $_SESSION['alert'] = [
            'message' => 'Contacte a soporte',
            'title' => 'ERROR AL REGISTRAR',
            'icon' => 'error'
        ];
        header("Location: alta-incrementables.php");
        exit(0);
    }
}
