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
        $_SESSION['message'] = "Eliminado exitosamente";
        header("Location: alta-incrementables.php");
        exit(0);
    } else {
        $_SESSION['message'] = "Error al eliminar, contacte a soporte";
        header("Location: alta-incrementables.php");
        exit(0);
    }
}

if (isset($_POST['update'])) {
    $id = mysqli_real_escape_string($con, $_POST['id']);
    $nombre = mysqli_real_escape_string($con, $_POST['nombre']);
    $apellidop = mysqli_real_escape_string($con, $_POST['apellidop']);
    $apellidom = mysqli_real_escape_string($con, $_POST['apellidom']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $rol = mysqli_real_escape_string($con, $_POST['rol']);
    $estatus = mysqli_real_escape_string($con, $_POST['estatus']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);


    // Actualizar los datos del usuario
    $query = "UPDATE `usuarios` SET `nombre` = '$nombre',`password` = '$hashed_password', `apellidop` = '$apellidop', `apellidom` = '$apellidom', `email` = '$email', `rol` = '$rol', `estatus` = '$estatus' WHERE `usuarios`.`id` = '$id'";
    $query_run = mysqli_query($con, $query);

    if ($query_run) {
        $_SESSION['message'] = "Usuario editado exitosamente";
        header("Location: alta-incrementables.php");
        exit(0);
    } else {
        $_SESSION['message'] = "Error al editar el usuario, contácte a soporte";
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
        $_SESSION['message'] = "Registrado exitosamente";
        header("Location: alta-incrementables.php");
        exit(0);
    } else {
        $_SESSION['message'] = "Error al registrar, contacte a soporte";
        header("Location: alta-incrementables.php");
        exit(0);
    }
}
