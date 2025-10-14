<?php
include 'dbcon.php';

function obtenerDetallesCliente($idCliente) {
    global $con;

    $query = "SELECT * FROM clientes WHERE id = '$idCliente' AND estatus = 1 ORDER BY cliente ASC";
    $result = mysqli_query($con, $query);

    if (mysqli_num_rows($result) > 0) {
        $registro = mysqli_fetch_assoc($result);
        $calle = $registro['calle'];
        $numexterior = $registro['numexterior'];
        $numinterior = $registro['numinterior'];
        $colonia = $registro['colonia'];
        $city = $registro['city'];
        $state = $registro['state'];
        $pais = $registro['pais'];
        $cpostal = $registro['cpostal'];
        $telefono = $registro['telefono'];
        $contacto = $registro['contacto'];
        $rfc = $registro['rfc'];
        $correo = $registro['correo'];

        // Mostrar la información formateada
        echo "$calle $numexterior $numinterior, $colonia, $city, $state, $pais, CP $cpostal<br>";
        echo "<div class='row justify-content-evenly'><div class='col-12'><b>Teléfono:</b> $telefono</div>
              <div class='col-12'><b>Contacto:</b> $contacto</div>
              <div class='col-12'><b>RFC:</b> $rfc</div>
              <div class='col-12'><b>Email:</b> $correo</div></div>";
    } else {
        echo "No se encontraron datos.";
    }
}

if (isset($_POST['idCliente'])) {
    obtenerDetallesCliente($_POST['idCliente']);
}

if (isset($_POST['idOrigen'])) {
    obtenerDetallesCliente($_POST['idOrigen']);
}

if (isset($_POST['idAduana'])) {
    obtenerDetallesCliente($_POST['idAduana']);
}

if (isset($_POST['idDestino'])) {
    obtenerDetallesCliente($_POST['idDestino']);
}
?>
