<?php

include('../check_auth/check_auth.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_COOKIE["r_place_login_token"];

    $result_table = check_auth($token);

    if ($result_table[0][0] == "") {
        echo "Forbidden. You do not have access to this resource.";
        http_response_code(403);
    } else {
        $user_id = $result_table[0][2];
        $data = json_decode(file_get_contents('php://input'), true);
        $grid_name = mysqli_real_escape_string($link, $data["grid_name"]);
        $sql = "INSERT INTO `grids` ( `name`, `owner` ) VALUES ( '$grid_name', '$user_id' )";
        ExecuteSQL($sql);
        $last_inserted;
        GetSQL("SELECT LAST_INSERT_ID()", $last_inserted);
        $grid_id = $last_inserted[0][0];
        
        foreach (range(1, 30) as $x) {
            foreach (range(1, 30) as $y) {
                $sql_pixel = "INSERT INTO `pixels` ( `grid`, `x`, `y` ) VALUES ( '$grid_id', '$x', '$y' )";
                ExecuteSQL($sql_pixel);
            }
        }

        echo $grid_id;
        http_response_code(201);
    }
} else {
    echo "The POST method should be used.";
    http_response_code(405);
}

?>
