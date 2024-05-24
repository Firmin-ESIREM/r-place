<?php

include('../../include/config.inc.php');

$token = $_COOKIE["r_place_login_token"];

if ($token == "") {
    echo "{\"state\": \"KO\"}";
} else {
    $result_table;
    $hashed_token = hash('sha512', $token);
    $current_date = date("Y-m-d");
    $sql = "SELECT `tokens`.`expiration_date`, `users`.`username` FROM `tokens`, `users` WHERE `tokens`.`token_hash` = '$hashed_token'";
    GetSQL($sql, $result_table);
    if ($result_table[0][0] == "" || strcmp($result_table[0][0], date("Y-m-d")) < 0) {
        echo "{\"state\": \"KO\"}";
    } else {
        $result_array = array('state' => 'OK', 'expirationDate' => $result_table[0][0], 'username' => $result_table[0][1]);
        echo json_encode($result_array);
    }
}

?>
