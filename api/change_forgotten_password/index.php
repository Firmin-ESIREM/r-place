<?php

include('../../include/config.inc.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $user_id = mysqli_real_escape_string($link, $data["user_id"]);
    $code = mysqli_real_escape_string($link, $data["code"]);
    $hashed_password = hash('sha512', $data["password"]);
    $result_table;
    $sql = "SELECT `expiration_time` FROM `temporary_codes` WHERE `user`='$user_id' AND `code`='$code'";
    GetSQL($sql, $result_table);
    $expiration_time = $result_table[0][0];
    if ($expiration_time != "") {
        if (strcmp(date("Y-m-d H:i:s"), $expiration_time) < 0) {
            $sql = "UPDATE `users` SET `password_hash`='$hashed_password' WHERE `id`='$user_id'";
            ExecuteSQL($sql);
            echo "OK";
        }
    } else {
        echo "KO";
        http_response_code(404);
    }
} else {
    echo "The POST method should be used.";
    http_response_code(405);
}

?>
