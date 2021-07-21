<?php

/*
Функция для подключения к базе данных
*/

function connect(
    $host = 'localhost',
    $username = 'id16910710_shirokov_anton',
    $password = '8an961toN!!!',
    $dbName = 'id16910710_avito'
) {
    static $connection;
    if ($connection === null) {
        $connection = mysqli_connect($host, $username, $password, $dbName);
    }
    return $connection;
}