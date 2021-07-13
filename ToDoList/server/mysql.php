<?php
    function connectionToBd() {
        $host_bd = 'localhost';
        $user_bd = 'id16910710_anton';
        $password_bd = '8an961toN!!!';
        $name_bd = 'id16910710_test';
        $connection = mysqli_connect($host_bd, $user_bd, $password_bd, $name_bd);
        return $connection;
    }
    
    function addTaskIntoBd($task) {
        $file = fopen('test.txt', 'a');
        fwrite($file, $task['input']);
        fwrite($file, $task['color']);
        $sql = "INSERT INTO Tasks (task, color_id) VALUES ('{$task['input']}', '{$task['color']}')";
        $connection = connectionToBd();
        //Добавить запись обработку ошибок в 
        
        mysqli_query($connection, $sql);
        mysqli_close($connection);
    }
?>
