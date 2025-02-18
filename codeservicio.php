<?php
require 'dbcon.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_POST['delete'])) {
    $registro_id = mysqli_real_escape_string($con, $_POST['delete']);

    $query = "DELETE FROM usuarios WHERE id='$registro_id' ";
    $query_run = mysqli_query($con, $query);

    if ($query_run) {
        $_SESSION['message'] = "Usuario eliminado exitosamente";
        header("Location: usuarios.php");
        exit(0);
    } else {
        $_SESSION['message'] = "Error al eliminar el usuario, contácte a soporte";
        header("Location: usuarios.php");
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

    // Directorio donde se guardarán las imágenes
    $upload_dir = './usuarios/';
    $file_path = $upload_dir . $id . '.jpg';

    // Verificar si se ha subido una nueva imagen
    if ($_FILES['nuevaFoto']['size'] > 0) {
        // Obtener información de la imagen
        $image_tmp_name = $_FILES['nuevaFoto']['tmp_name'];
        $image_info = getimagesize($image_tmp_name);
        $image_type = $image_info[2];

        // Convertir la imagen a formato jpg
        switch ($image_type) {
            case IMAGETYPE_JPEG:
                $src_image = imagecreatefromjpeg($image_tmp_name);
                break;
            case IMAGETYPE_PNG:
                $src_image = imagecreatefrompng($image_tmp_name);
                break;
            case IMAGETYPE_GIF:
                $src_image = imagecreatefromgif($image_tmp_name);
                break;
            default:
                $_SESSION['message'] = "Formato de imagen no soportado";
                header("Location: usuarios.php");
                exit(0);
        }

        // Guardar la imagen convertida como .jpg
        imagejpeg($src_image, $file_path);
        imagedestroy($src_image);

        // Actualizar la base de datos con la ruta de la imagen
        $medio = mysqli_real_escape_string($con, $file_path);
        $update_query = "UPDATE usuarios SET medio='$medio' WHERE id='$id'";
        $update_result = mysqli_query($con, $update_query);

        if (!$update_result) {
            $_SESSION['message'] = "Error al actualizar la imagen del usuario, contácte a soporte";
            header("Location: usuarios.php");
            exit(0);
        }
    }

    // Actualizar los datos del usuario
    $query = "UPDATE `usuarios` SET `nombre` = '$nombre',`password` = '$hashed_password', `apellidop` = '$apellidop', `apellidom` = '$apellidom', `email` = '$email', `rol` = '$rol', `estatus` = '$estatus' WHERE `usuarios`.`id` = '$id'";
    $query_run = mysqli_query($con, $query);

    if ($query_run) {
        $_SESSION['message'] = "Usuario editado exitosamente";
        header("Location: usuarios.php");
        exit(0);
    } else {
        $_SESSION['message'] = "Error al editar el usuario, contácte a soporte";
        header("Location: usuarios.php");
        exit(0);
    }
}



if (isset($_POST['save'])) {
    $nombreServicio = mysqli_real_escape_string($con, $_POST['nombreServicio']);
    $tipoServicio = mysqli_real_escape_string($con, $_POST['tipoServicio']);

    $query = "INSERT INTO tiposervicio (nombreServicio, tipoServicio) VALUES ('$nombreServicio', '$tipoServicio')";
    $query_run = mysqli_query($con, $query);

    if ($query_run) {
        $_SESSION['message'] = "Registrado exitosamente";
        header("Location: alta-servicio.php");
        exit(0);
    } else {
        $_SESSION['message'] = "Error al registrar, contacte a soporte";
        header("Location: alta-servicio.php");
        exit(0);
    }
}
