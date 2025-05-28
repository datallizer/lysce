<?php
include 'dbcon.php';

if (isset($_POST['idCliente'])) {
    $idCliente = intval($_POST['idCliente']);
    
    $query = "
        SELECT c.id, c.cliente, c.tipo
        FROM proveedorcliente pc
        INNER JOIN clientes c ON c.id = pc.idProveedor
        WHERE pc.idCliente = $idCliente AND c.estatus = 1
    ";
    
    $result = mysqli_query($con, $query);
    $options = "";

    while ($row = mysqli_fetch_assoc($result)) {
        $id = $row['id'];
        $nombre = $row['cliente'];
        $tipo = $row['tipo'];
        $options .= "<option value='$id'>$nombre - $tipo</option>";
    }

    echo $options;
}
