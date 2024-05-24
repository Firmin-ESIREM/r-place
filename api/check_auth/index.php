<?php

include('./check_auth.php');

$token = $_COOKIE["r_place_login_token"];

if ($token == "") {
    echo "{\"state\": \"KO\"}";
} else {
    $result_table = check_auth($token);
    if ($result_table[0][0] == "" || strcmp($result_table[0][0], date("Y-m-d")) < 0) {
        echo "{\"state\": \"KO\"}";
    } else {
        $result_array = array('state' => 'OK', 'expirationDate' => $result_table[0][0], 'username' => $result_table[0][1]);
        echo json_encode($result_array);
    }
}

?>
