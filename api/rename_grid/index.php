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
        $grid_name = mysqli_real_escape_string($link, $data["new_name"]);
        $grid_id = mysqli_real_escape_string($link, $data["id"]);
        $sql = "UPDATE `grids` SET `name` = '$grid_name' WHERE `grids`.`id`='$grid_id'";
        ExecuteSQL($sql);
        echo "OK";
    }
} else {
    echo "The POST method should be used.";
    http_response_code(405);
}

?>
