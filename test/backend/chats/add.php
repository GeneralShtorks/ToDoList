<?php

 header('Content-type: application/json');

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    //Получаем данные и переводим их в ассоциативный массив
    $dataChat = json_decode(file_get_contents('php://input'), true);
    include '../core.php';
    $response['id'] = addNewChatToBd($dataChat['chat'], $dataChat['usernames']);
    if ($response['id'] !== 0) {
        echo json_encode($response);
    } else {
        http_response_code(400);
    }
}