<?php
    if ($_SERVER['REQUEST_METHOD'] === "POST") {
        if (isset($_POST['input'])) {
            include 'mysql.php';
            
            addTaskIntoBd($_POST);
            $link = connectionToBd();
            $sql = mysqli_query($link , "SELECT id, task, color_id FROM Tasks");
            $response = array();
            while ($result = mysqli_fetch_array($sql)) {
                $response[(string)$result['id']] = array('id'=>(string)$result['id'], 'textTask'=>$result['task'], 'colorId' => $result['color_id']);
            }
            //$file = fopen('error.txt', "w");
            
            $needs_key = 0;
            foreach($response as $key => $value) {
                if ($needs_key < (int)$key) {
                    $needs_key = $key;
                }
            }
           // $new_response[$needs_key] = array('id'=>(string)$needs_key, 'text_task'=>$response[$needs_key]);
            
            //fwrite($file, $json_response);
            $json_response = json_encode($response[$needs_key]);
            echo $json_response;
            mysqli_close($link);
            
        }
    } else {
        echo "<br>";
        echo "error method";
    }

    
?>