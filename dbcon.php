<?php

$con = mysqli_connect("localhost","root","","lysce");
// $con = mysqli_connect("datallizer.com","datallizer_lysce","P_LySgRy123$&AVOM","datallizer_lysce");

if(!$con){
    die('Connection Failed'. mysqli_connect_error());
}

// 🔧 Establece codificación UTF-8 (muy importante)
mysqli_set_charset($con, "utf8mb4");
?>