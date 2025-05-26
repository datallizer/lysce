<?php
$token = 'ea4cccf26c102391e1ae1798ce52fd4c3e083e81987675d8572569c91f7464c2';

$hoy = '2025-05-27';

// Construye la URL con fecha actual
$url = "https://www.banxico.org.mx/SieAPIRest/service/v1/series/SF60653/datos/$hoy/$hoy";

$ch = curl_init($url);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Bmx-Token: $token",
    "Accept: application/json"
]);

$response = curl_exec($ch);

if (curl_errno($ch)) {
    http_response_code(500);
    echo json_encode(["error" => "Error en la solicitud cURL"]);
    exit;
}

curl_close($ch);

// Devuelve JSON al frontend
header('Content-Type: application/json');
echo $response;
?>
