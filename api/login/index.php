<?php

include('../../include/config.inc.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $email = mysqli_real_escape_string($link, $data["email"]);
    $hashed_password = hash('sha512', $data["password"]);
    $result_table;
    $sql = "SELECT `id`, `active` FROM `users` WHERE `email` = '$email' AND `password_hash` = '$hashed_password'";
    GetSQL($sql, $result_table);
    if ($result_table[0][0] != "") {
        if ($result_table[0][1]) {
            $user_id = intval($result_table[0][0]);
            $token = bin2hex(openssl_random_pseudo_bytes(64));
            $hashed_token = hash('sha512', $token);
            $date=date_create(date("Y-m-d"));
            date_add($date, date_interval_create_from_date_string("7 days"));
            $expiration_date = date_format($date,"Y-m-d");
            $sql2 = "INSERT INTO `tokens` ( `token_hash`, `user`, `expiration_date` ) VALUES ( '$hashed_token', '$user_id', '$expiration_date' )";
            ExecuteSQL($sql2);
            echo $token;
        } else {
            echo "KO";
            http_response_code(403);
        }
    } else {
        echo "KO";
        http_response_code(401);
    }
} else {
    echo "The POST method should be used.";
    http_response_code(405);
}

?>
