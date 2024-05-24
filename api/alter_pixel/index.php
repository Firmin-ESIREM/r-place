<?php

include('../check_auth/check_auth.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_COOKIE["r_place_login_token"];

    $result_table = check_auth($token);

    if ($result_table[0][0] == "") {
        echo "Forbidden. You do not have access to this resource.";
        http_response_code(403);
    } else {
        $data = json_decode(file_get_contents('php://input'), true);
        $grid_id = mysqli_real_escape_string($link, $data["grid_id"]);
        $x = mysqli_real_escape_string($link, $data["x"]);
        $y = mysqli_real_escape_string($link, $data["y"]);
        $color = mysqli_real_escape_string($link, $data["color"]);
        $return_table;
        $sql = "SELECT `last_modified` FROM `pixels` WHERE `grid`='$grid_id' AND `x`='$x' AND `y`='$y'";
        GetSQL($sql, $return_table);
        $modified_at = $return_table[0][0];
        $date=date_create(date("Y-m-d H:i:s"));
        $interval = date_interval_create_from_date_string("15 seconds");
        $interval->invert = 1;
        date_add($date, $interval);
        $current_minus_timeout = date_format($date,"Y-m-d H:i:s");

        if (strcmp($modified_at, $current_minus_timeout) < 0) {
            $user_id = $result_table[0][2];
            $username = $result_table[0][1];
            $current_datetime=date("Y-m-d H:i:s");
            $sql2 = "UPDATE `pixels` SET `color` = '$color', `owner` = '$user_id', `last_modified` = '$current_datetime' WHERE `grid`='$grid_id' AND `x`='$x' AND `y`='$y'";
            ExecuteSQL($sql2);
            $table_to_return = array('state' => 'OK', 'owner' => $username);
            echo json_encode($table_to_return);
        } else {
            echo "{\"state\": \"TIMEOUT\"}";
        }
    }
} else {
    echo "The POST method should be used.";
    http_response_code(405);
}

?>
