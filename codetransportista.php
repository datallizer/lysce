<?php
require 'dbcon.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_POST['delete'])) {
    $registro_id = mysqli_real_escape_string($con, $_POST['delete']);

    $query = "DELETE FROM transportistas WHERE id='$registro_id' ";
    $query_run = mysqli_query($con, $query);

    if ($query_run) {
        $_SESSION['alert'] = [
            'title' => 'ELIMINADO EXITOSAMENTE',
            'icon' => 'success'
        ];
        header("Location: transportistas.php");
        exit(0);
    } else {
        $_SESSION['alert'] = [
            'message' => 'Contacte a soporte',
            'title' => 'ERROR AL ELIMINAR',
            'icon' => 'error'
        ];
        header("Location: transportistas.php");
        exit(0);
    }
}

if (isset($_POST['update'])) {
    $id = mysqli_real_escape_string($con, $_POST['id']);
    $transportista = mysqli_real_escape_string($con, $_POST['transportista']);
    $unidad = mysqli_real_escape_string($con, $_POST['unidad']);
    $numero = mysqli_real_escape_string($con, $_POST['numero']);
    $placas = mysqli_real_escape_string($con, $_POST['placas']);
    $estatus = mysqli_real_escape_string($con, $_POST['estatus']);
    $password = mysqli_real_escape_string($con, $_POST['password']);

    // Actualizar los datos del usuario
    $query = "UPDATE `transportistas` SET `transportista` = '$transportista', `unidad` = '$unidad', `numero` = '$numero', `placas` = '$placas', `estatus` = '$estatus' WHERE `transportistas`.`id` = '$id'";
    $query_run = mysqli_query($con, $query);

    if ($query_run) {
        $_SESSION['alert'] = [
            'title' => 'EDITADO EXITOSAMENTE',
            'icon' => 'success'
        ];
        header("Location: transportistas.php");
        exit(0);
    } else {
        $_SESSION['alert'] = [
            'message' => 'Contacte a soporte',
            'title' => 'ERROR AL EDITAR',
            'icon' => 'error'
        ];
        header("Location: transportistas.php");
        exit(0);
    }
}

if (isset($_POST['save'])) {
    $transportista = mysqli_real_escape_string($con, $_POST['transportista']);
    $unidad = mysqli_real_escape_string($con, $_POST['unidad']);
    $numero = mysqli_real_escape_string($con, $_POST['numero']);
    $placas = mysqli_real_escape_string($con, $_POST['placas']);

    $query = "INSERT INTO transportistas (transportista, unidad, numero, placas, estatus) VALUES ('$transportista', '$unidad', '$numero', '$placas', '1')";
    $query_run = mysqli_query($con, $query);

    if ($query_run) {
        $_SESSION['alert'] = [
            'title' => 'REGISTRO EXITOSO',
            'icon' => 'success'
        ];
        header("Location: transportistas.php");
        exit(0);
    } else {
        $_SESSION['alert'] = [
            'message' => 'Contacte a soporte',
            'title' => 'ERROR AL REGISTRAR',
            'icon' => 'error'
        ];
        header("Location: transportistas.php");
        exit(0);
    }
}
