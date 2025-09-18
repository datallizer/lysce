<?php
include "dbcon.php"; 

if (isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $query = "SELECT * FROM transfers 
              WHERE id = $id AND estatus = 1 LIMIT 1";
    $result = mysqli_query($con, $query);

    if ($row = mysqli_fetch_assoc($result)) {
        echo json_encode($row, JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode([]);
    }
}
?>
