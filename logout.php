<?php
    session_start();
    session_destroy(); // Elimino la sesion
    header("Location: index.php"); //redirijo al index
    exit();
?>