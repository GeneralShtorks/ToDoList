<?php

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

function checkUserIntoBd(string $username): int
{
    $queryCheckUsername = "SELECT * FROM users WHERE username = '{$username}' LIMIT 1";
    $responseCheckUsername =  mysqli_fetch_assoc(mysqli_query(connect(), $queryCheckUsername));
    return $responseCheckUsername['id'] ?? 0;
}

function addNewUserToBd(string $username): int
{
    $safeUsername = mysqli_real_escape_string(connect(), $username);
    if (strlen($safeUsername) !== 0 && checkUserIntoBd($safeUsername) === 0) {
        $currentDate = date("Y-m-d H:i:s");
        $queryAddNewUser = "INSERT INTO users (username, created_at) VALUE ('{$safeUsername}', '{$currentDate}')";
        mysqli_query(connect(), $queryAddNewUser);
        return checkUserIntoBd($safeUsername);
    } else {
        return 0;
    }
}

function checkChatIntoBd(string $chat): int
{
    $queryCheckChat = "SELECT * FROM chats WHERE name = '{$chat}' LIMIT 1";
    $responseCheckChat =  mysqli_fetch_assoc(mysqli_query(connect(), $queryCheckChat));
    return $responseCheckChat['id'] ?? 0;
}

function addNewChatToBd(string $chat, array $users): int
{
    $safeChatname = mysqli_real_escape_string(connect(), $chat);
    $safeUsers = [];
    foreach ($users as $user) {
        $safeUser = mysqli_real_escape_string(connect(), $user);
        if (checkUserIntoBd($safeUser) !== 0 && strlen($safeUser) !== 0) {
            array_push($safeUsers, $safeUser);
        } else {
            return 0;
        }
    }
    
    if (strlen($safeChatname) !== 0 && checkChatIntoBd($safeChatname) === 0) {
        $currentDate = date("Y-m-d H:i:s");
        $queryAddNewChat = "INSERT INTO chats (name, created_at) VALUE ('{$safeChatname}', '{$currentDate}')";
        mysqli_query(connect(), $queryAddNewChat);
        $chatId = checkChatIntoBd($safeChatname);
        
        foreach ($safeUsers as $safeUser) {
            $userId = checkUserIntoBd($safeUser);
            $queryAddUsersIntoChat = "INSERT INTO chats_users (user_id, chat_id) VALUE ('{$userId}', '{$chatId}')";
            mysqli_query(connect(), $queryAddUsersIntoChat);
        }
        return $chatId;
    } else {
        return 0;
    }
}

function checkMessageIntoBd(int $userId, int $chatId, string $currentTime): int
{
    $querySearchMessage = "SELECT id FROM messages WHERE chat = '{$chatId}' AND author = '{$userId}' AND created_at = '{$currentTime}'";
    $responseSearchMessage =  mysqli_fetch_assoc(mysqli_query(connect(), $querySearchMessage));
    return $responseSearchMessage['id'] ?? 0;
}

function checkUserIntoChat(int $userId, int $chatId): bool
{
    $checkUserIntoChat = "SELECT * FROM chats_users WHERE chat_id = '{$chatId}' AND user_id = '{$userId}' LIMIT 1";
    $responseCheckUserIntoChat =  mysqli_fetch_assoc(mysqli_query(connect(), $checkUserIntoChat));
    return ($responseCheckUserIntoChat !== null);
}

function addNewMessageIntoChatToBd(int $userId, int $chatId, string $text): int
{
    $safeText = mysqli_real_escape_string(connect(), $text);
    if (checkUserIntoChat($userId, $chatId) === false) {
        return 0;
    }

    if (strlen($text) !== 0) {
        $currentDate = date("Y-m-d H:i:s");
        $queryAddMessageIntoChatToBd = "INSERT INTO messages (chat, author, text, created_at) VALUES ('{$chatId}', '{$userId}', '{$safeText}', '{$currentDate}')";
        mysqli_query(connect(), $queryAddMessageIntoChatToBd);
        return checkMessageIntoBd($userId, $chatId, $currentDate);
    } else {
        return 0;
    }
}

function getMessageFromBd(int $chatId): array
{
    $queryGetMessageFromChat = "SELECT * FROM messages WHERE chat = '{$chatId}' ORDER BY created_at ASC";
    $responseGetMessage = mysqli_query(connect(), $queryGetMessageFromChat);
    if ($responseGetMessage !== null) {
        $resultListMessage = [];
        while ($message = mysqli_fetch_assoc($responseGetMessage)) {
            array_push($resultListMessage, $message);
        }
        return $resultListMessage;
    }
    return [];
}

function getChatsUserFromBd(int $userId): array
{
    $queryGetChatsUser = "SELECT chats.id, chats.name, chats.created_at FROM messages JOIN chats ON chats.id = messages.chat JOIN chats_users ON chats_users.chat_id = chats.id WHERE chats_users.user_id = '{$userId}' GROUP BY messages.chat ORDER BY messages.created_at DESC";
    $responseGetChatsUser = mysqli_query(connect(), $queryGetChatsUser);
    if ($responseGetChatsUser !== null) {
        $resultListChats = [];
        while ($chat = mysqli_fetch_assoc($responseGetChatsUser)) {
            array_push($resultListChats, $chat);    
        }
        return $resultListChats;
    }
    return [];
}