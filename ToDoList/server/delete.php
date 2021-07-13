<?php
    if ($_SERVER['REQUEST_METHOD'] === "POST") {
        include 'mysql.php';
        $link = connectionToBd();
        $del_id = $_POST['delId'];
        $sql = mysqli_query($link , "DELETE FROM `Tasks` WHERE `id` = '$del_id'");
        mysqli_close($link);
    } else {
        echo "<br>";
        echo "error method";
    }

    
?>