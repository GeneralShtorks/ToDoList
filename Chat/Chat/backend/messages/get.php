<?php

include '../core.php';
header('Content-type: application/json');

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $dataMessage = json_decode(file_get_contents('php://input'), true);
    $response = getMessageFromBd($dataMessage['chatId']);
    if (!empty($response)) {
        echo json_encode($response);
    } else {
        http_response_code(400);
    }
}