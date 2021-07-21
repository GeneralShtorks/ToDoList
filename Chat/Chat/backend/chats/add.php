<?php

include '../core.php';
header('Content-type: application/json');

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $dataChat = json_decode(file_get_contents('php://input'), true);
    $response['id'] = addNewChatToBd($dataChat['chat'], $dataChat['usernames']);
    if ($response['id'] !== 0) {
        echo json_encode($response);
    } else {
        http_response_code(400);
    }
}