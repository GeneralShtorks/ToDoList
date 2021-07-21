<?php

include 'connectionWithBd.php';

function checkUserIntoBd($username): int
{
    $queryCheckUsername = "SELECT * FROM users WHERE username = '{$username}' LIMIT 1";
    $responseCheckUsername =  mysqli_fetch_assoc(mysqli_query(connect(), $queryCheckUsername));
    return $responseCheckUsername['id'] ?? 0;
}

function addNewUserToBd($username)
{
    $safeUsername = mysqli_real_escape_string(connect(), $username);
    if (strlen($safeUsername) !== 0 && checkUserIntoBd($safeUsername) === 0) {
        $currentDate = date("Y-m-d H:i:s");
        $queryAddNewUser = "INSERT INTO users (username, created_at) VALUE ('{$safeUsername}', '{$currentDate}')";
        if (mysqli_query(connect(), $queryAddNewUser)) {
            return checkUserIntoBd($safeUsername);
        } else {
            echo "failed to add user to the database for some reason";
        }  
    } else {
        echo "user with this username is already exitst";
    }
}