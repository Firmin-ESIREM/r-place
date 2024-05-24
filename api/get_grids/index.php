<?php

include('../check_auth/check_auth.php');

$token = $_COOKIE["r_place_login_token"];

$result_table = check_auth($token);

if ($result_table[0][0] == "") {
    echo "Forbidden. You do not have access to this resource.";
    http_response_code(403);
} else {
    $grids_result_table;

    $sql="SELECT `grids`.`id`, `grids`.`name`, `users`.`username` FROM `grids`, `users`";
    GetSQL($sql, $grids_result_table);

    $output = '[';

    foreach ($grids_result_table as &$result) {
        $result_array = array('id' => intval($result[0]), 'name' => $result[1], 'owner' => $result[2]);
        $output .= json_encode($result_array);
        $output .= ",";
    }

    $output = rtrim($output, ",");
    $output .= ']';

    echo $output;
}

?>
