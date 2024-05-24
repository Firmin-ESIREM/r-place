<?php

include('../../include/config.inc.php');
include('../mailer/send.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $email = mysqli_real_escape_string($link, $data["email"]);
    $username = mysqli_real_escape_string($link, $data["username"]);
    $hashed_password = hash('sha512', $data["password"]);
    $sql = "INSERT INTO `users` ( `email`, `username`, `password_hash` ) VALUES ( '$email', '$username', '$hashed_password' )";
    try {
        ExecuteSQL($sql);
        $last_inserted;
        GetSQL("SELECT LAST_INSERT_ID()", $last_inserted);
        $user_id = $last_inserted[0][0];
        $random = rand(0, 999999);
        $code = str_pad("$random", 6, '0', STR_PAD_LEFT);
        $date=date_create(date("Y-m-d H:i:s"));
        date_add($date, date_interval_create_from_date_string("15 minutes"));
        $expiration_time = date_format($date,"Y-m-d H:i:s");
        $sql_code = "INSERT INTO `temporary_codes` ( `code`, `user`, `expiration_time` ) VALUES ( '$code', '$user_id', '$expiration_time' )";
        ExecuteSQL($sql_code);
        send_mail($username, $email, $code);
        echo $user_id;
        http_response_code(201);
    } catch(mysqli_sql_exception) {
        echo "ALREADY_EXISTS";
        http_response_code(401);
    }
}

?>
