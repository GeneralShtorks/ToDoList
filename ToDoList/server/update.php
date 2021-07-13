<?php
    if ($_SERVER['REQUEST_METHOD'] === "POST") {
        include 'mysql.php';
        $link = connectionToBd();
        $sql = mysqli_query($link , "SELECT `id`, `task`, `color_id` FROM `Tasks`");
        $response = array();
        while ($result = mysqli_fetch_array($sql)) {
            $response[(string)$result['id']] = array('id'=>(string)$result['id'], 'textTask'=>$result['task'], 'colorId' => $result['color_id']);

        }
        $json_response = json_encode($response);
        echo $json_response;
        mysqli_close($link);
    } else {
        echo "<br>";
        echo "error method";
    }

    
?>