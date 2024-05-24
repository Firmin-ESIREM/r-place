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
        $grid_id = mysqli_real_escape_string($link, $data["id"]);
        $sql = "SELECT `name` FROM `grids` WHERE `id`='$grid_id'";
        $result_table;
        GetSQL($sql, $result_table);
        if ($result_table[0][0] != "") {
            $grid_name = $result_table[0][0];
            $pixels=array();
            foreach (range(1, 30) as $x) {
                foreach (range(1, 30) as $y) {
                    $result_table_pixel;
                    $sql_pixel = "SELECT `color`, `owner`, `last_modified` FROM `pixels` WHERE `grid`='$grid_id' AND `x`='$x' AND `y`='$y'";
                    GetSQL($sql_pixel, $result_table_pixel);
                    $username = "";
                    $pixel_user_id = $result_table_pixel[0][1];
                    if ($pixel_user_id != NULL) {
                        $result_table_username;
                        $sql_username = "SELECT `username` FROM `users` WHERE `id` = '$pixel_user_id'";
                        GetSQL($sql_username, $result_table_username);
                        $username = $result_table_username[0][0];
                    }
                    $pixel = array('x' => $x, 'y' => $y, 'color' => $result_table_pixel[0][0], 'owner' => $username, 'last_modified' => $result_table_pixel[0][2]);
                    array_push($pixels, $pixel);
                }
            }
            $grid = array('name' => $grid_name, 'pixels' => $pixels);
            echo json_encode($grid);
        } else {
            echo "Not found.";
            http_response_code(404);
        }
    }
} else {
    echo "The POST method should be used.";
    http_response_code(405);
}


?>
