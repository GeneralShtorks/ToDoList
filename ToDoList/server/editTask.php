<?php
    include 'mysql.php';
    if ($_SERVER['REQUEST_METHOD'] === "POST") {
        $link = connectionToBd();
        $edit_task = $_POST['editedTask'];
        $edit_id = $_POST['editId'];
        mysqli_query($link, "UPDATE `Tasks` SET `task` = '$edit_task' WHERE `id` = '$edit_id'");
        mysqli_close($link);
        
    }
?>