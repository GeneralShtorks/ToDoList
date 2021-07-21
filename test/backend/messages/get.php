<?php

 header('Content-type: application/json');

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    //Получаем данные и переводим их в ассоциативный массив
    $dataMessage = json_decode(file_get_contents('php://input'), true);
    include '../core.php';
    $response = getMessageFromBd($dataMessage['chatId']);
    if (!empty($response)) {
        echo json_encode($response);
    } else {
        http_response_code(400);
    }
}