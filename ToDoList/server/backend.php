<?php

function connectToBd(
    $host = 'localhost',
    $user = 'id16910710_anton',
    $password = '8an961toN!!!',
    $nameBd = 'id16910710_test'
) {
    static $connection;
    if ($connection === null) {
        $connection = mysqli_connect($host, $user, $password, $nameBd);
    }
    return $connection;
}

function sendDataTasksToFrontend()
{
    $queryAllData = "SELECT * FROM Tasks";
    $data = mysqli_query(connectToBd(), $queryAllData);
    $response = [];
    while ($row = mysqli_fetch_assoc($data)) {
        $response[$row['id']] = $row;
    }
    $responseJson = json_encode($response);
    echo $responseJson;
}

var_dump(sendDataTasksToFrontend());