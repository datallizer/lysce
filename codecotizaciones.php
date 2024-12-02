<?php
require 'dbcon.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_POST['delete'])) {
    $registro_id = mysqli_real_escape_string($con, $_POST['delete']);

    $query = "DELETE FROM cotizaciones WHERE id='$registro_id' ";
    $query_run = mysqli_query($con, $query);

    if ($query_run) {
        $_SESSION['message'] = "Usuario eliminado exitosamente";
        header("Location: cotizaciones.php");
        exit(0);
    } else {
        $_SESSION['message'] = "Error al eliminar el usuario, contácte a soporte";
        header("Location: cotizaciones.php");
        exit(0);
    }
}

if (isset($_POST['update'])) {
    $id = mysqli_real_escape_string($con, $_POST['id']);
    $nomina = mysqli_real_escape_string($con, $_POST['nomina']);
    // Obtener la nueva imagen cargada
    $query = "UPDATE `cotizaciones` SET `nomina` = '$nomina' WHERE `cotizaciones`.`id` = '$id'";
        $query_run = mysqli_query($con, $query);

        if ($query_run) {
            $idcodigo = $_SESSION['codigo'];
            $fecha_actual = date("Y-m-d"); // Obtener fecha actual en formato Año-Mes-Día
            $hora_actual = date("H:i"); // Obtener hora actual en formato Hora:Minutos:Segundos
            $querydos = "INSERT INTO historial SET idcodigo='$idcodigo', detalles='Edito la nómina, nombre: $nombre $apellidop $apellidom, codigo: $codigo, rol: $rol, estatus: $estatus', hora='$hora_actual', fecha='$fecha_actual'";
            $query_rundos = mysqli_query($con, $querydos);
            $_SESSION['message'] = "Editado exitosamente";
            header("Location: cotizaciones.php");
            exit(0);
        } else {
            $_SESSION['message'] = "Error al editar, contacte a soporte";
            header("Location: cotizaciones.php");
            exit(0);
        }
}


if (isset($_POST['save'])) {
    $solicitante = mysqli_real_escape_string($con, $_POST['solicitante']);
    $rol = mysqli_real_escape_string($con, $_POST['rol']);
    $proyecto = mysqli_real_escape_string($con, $_POST['proyecto']);
    $cotizacion = mysqli_real_escape_string($con, $_POST['cotizacion']);
    $notas = mysqli_real_escape_string($con, $_POST['notas']);

    // Inserta el registro en la base de datos sin el campo 'medio'
    $query = "INSERT INTO quotes SET solicitante='$solicitante', rol='$rol', proyecto='$proyecto', cotizacion='$cotizacion', estatusq='1', notas='$notas'";
    $query_run = mysqli_query($con, $query);

    if ($query_run) {
        $quote_id = mysqli_insert_id($con); // Obtén el ID del registro insertado

        // Manejo del archivo PDF
        if (isset($_FILES['medio']) && $_FILES['medio']['error'] === UPLOAD_ERR_OK) {
            $file_tmp_name = $_FILES['medio']['tmp_name'];
            $file_name = $quote_id . '.pdf';
            $upload_dir = './quotes/';
            $file_path = $upload_dir . $file_name;

            if (move_uploaded_file($file_tmp_name, $file_path)) {
                // Actualiza la base de datos con la ruta del archivo
                $update_query = "UPDATE quotes SET medio='$file_path' WHERE id='$quote_id'";
                mysqli_query($con, $update_query);
                
                $_SESSION['message'] = "Quote creado exitosamente";
            } else {
                $_SESSION['message'] = "Error al subir el archivo PDF, contacte a soporte";
            }
        } else {
            $_SESSION['message'] = "No se ha subido ningún archivo PDF";
        }

        header("Location: quotes.php");
        exit(0);
    } else {
        $_SESSION['message'] = "Error al crear el quote, contacte a soporte";
        header("Location: quotes.php");
        exit(0);
    }
}

?>