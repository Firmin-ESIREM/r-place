<?php

include('../../include/config.inc.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $user_id = mysqli_real_escape_string($link, $data["user_id"]);
    $code = mysqli_real_escape_string($link, $data["code"]);
    $result_table;
    $sql = "SELECT `expiration_time` FROM `temporary_codes` WHERE `user`='$user_id' AND `code`='$code'";
    GetSQL($sql, $result_table);
    $expiration_time = $result_table[0][0];
    if ($expiration_time != "") {
        if (strcmp(date("Y-m-d H:i:s"), $expiration_time) < 0) {
            $token = bin2hex(openssl_random_pseudo_bytes(64));
            $hashed_token = hash('sha512', $token);
            $date=date_create(date("Y-m-d"));
            date_add($date, date_interval_create_from_date_string("7 days"));
            $expiration_date = date_format($date,"Y-m-d");
            $sql2 = "INSERT INTO `tokens` ( `token_hash`, `user`, `expiration_date` ) VALUES ( '$hashed_token', '$user_id', '$expiration_date' )";
            ExecuteSQL($sql2);
            $sql3 = "UPDATE `users` SET `active` = '1' WHERE `id`='$user_id'";
            ExecuteSQL($sql3);
            echo $token;
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
