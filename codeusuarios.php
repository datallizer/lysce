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
        $_SESSION['alert'] = [
            'title' => 'ELIMINADO EXITOSAMENTE',
            'icon' => 'success'
        ];
        header("Location: usuarios.php");
        exit(0);
    } else {
        $_SESSION['alert'] = [
            'message' => 'Contacte a soporte',
            'title' => 'ERROR AL ELIMINAR',
            'icon' => 'error'
        ];
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
            $_SESSION['alert'] = [
                'message' => 'Selecciona otra imagen',
                'title' => 'FORMATO NO SOPORTADO',
                'icon' => 'error'
            ];
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
            $_SESSION['alert'] = [
                'message' => 'Contacte a soporte',
                'title' => 'ERROR AL ACTUALIZAR LA IMAGEN',
                'icon' => 'error'
            ];
            header("Location: usuarios.php");
            exit(0);
        }
    }

    // Actualizar los datos del usuario
    $query = "UPDATE `usuarios` SET `nombre` = '$nombre',`password` = '$hashed_password', `apellidop` = '$apellidop', `apellidom` = '$apellidom', `email` = '$email', `rol` = '$rol', `estatus` = '$estatus' WHERE `usuarios`.`id` = '$id'";
    $query_run = mysqli_query($con, $query);

    if ($query_run) {
        $_SESSION['alert'] = [
            'title' => 'EDITADO EXITOSAMENTE',
            'icon' => 'success'
        ];
        header("Location: usuarios.php");
        exit(0);
    } else {
        $_SESSION['alert'] = [
            'message' => 'Contacte a soporte',
            'title' => 'ERROR AL EDITAR',
            'icon' => 'error'
        ];
        header("Location: usuarios.php");
        exit(0);
    }
}



if (isset($_POST['save'])) {
    $nombre = mysqli_real_escape_string($con, $_POST['nombre']);
    $apellidop = mysqli_real_escape_string($con, $_POST['apellidop']);
    $apellidom = mysqli_real_escape_string($con, $_POST['apellidom']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $rol = mysqli_real_escape_string($con, $_POST['rol']);

    // Verificar si el email ya existe en la tabla
    $query_check = "SELECT * FROM usuarios WHERE email='$email'";
    $result_check = mysqli_query($con, $query_check);

    if (mysqli_num_rows($result_check) > 0) {
        // Si el email ya existe
        $_SESSION['alert'] = [
            'message' => 'Inicia sesión o intenta registrando otro email',
            'title' => 'USUARIO REGISTRADO',
            'icon' => 'warning'
        ];
        header("Location: usuarios.php");
        exit(0);
    } else {
        // Encriptar la contraseña
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Preparar los datos para la inserción
        $query = "INSERT INTO usuarios (nombre, apellidop, apellidom, email, password, estatus, rol) 
                  VALUES ('$nombre', '$apellidop', '$apellidom', '$email', '$hashed_password', '1', '$rol')";
        $query_run = mysqli_query($con, $query);

        if ($query_run) {
            $id = mysqli_insert_id($con); // Obtener el ID del nuevo registro

            // Inicializar la ruta de la imagen
            $ruta_imagen = '';

            // Procesar la imagen si se ha subido
            if (isset($_FILES['medio']) && $_FILES['medio']['error'] == 0) {
                $imagen_tmp = $_FILES['medio']['tmp_name'];
                $imagen_ext = strtolower(pathinfo($_FILES['medio']['name'], PATHINFO_EXTENSION));
                $imagen_nombre = $id . '.jpg'; // Nombre del archivo con extensión .jpg
                $imagen_destino = 'usuarios/' . $imagen_nombre;

                // Convertir a JPG
                $imagen = imagecreatefromstring(file_get_contents($imagen_tmp));
                if ($imagen) {
                    imagejpeg($imagen, $imagen_destino, 100); // Guardar como JPG con calidad 100
                    imagedestroy($imagen);

                    // Establecer la ruta de la imagen
                    $ruta_imagen = $imagen_destino;
                }
            }

            // Actualizar la base de datos con la ruta de la imagen
            $query_update = "UPDATE usuarios SET medio='./$ruta_imagen' WHERE id='$id'";
            $query_update_run = mysqli_query($con, $query_update);
            $_SESSION['alert'] = [
                'title' => 'REGISTRO EXITOSO',
                'icon' => 'success'
            ];
            header("Location: usuarios.php");
            exit(0);
        } else {
            $_SESSION['alert'] = [
                'message' => 'Contacte a soporte',
                'title' => 'ERROR AL REGISTRAR',
                'icon' => 'error'
            ];
            header("Location: usuarios.php");
            exit(0);
        }
    }
}
