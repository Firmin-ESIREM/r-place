<?php

include('../../include/config.inc.php');

function check_auth($token) {
    $result_table;
    $hashed_token = hash('sha512', $token);
    $current_date = date("Y-m-d");
    $sql = "SELECT `tokens`.`expiration_date`, `users`.`username`, `tokens`.`user` FROM `tokens`, `users` WHERE `tokens`.`token_hash` = '$hashed_token'";
    GetSQL($sql, $result_table);
    return $result_table;
}

?>
