<?php

include('../../include/config.inc.php');
include('../mailer/send.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $email = mysqli_real_escape_string($link, $data["email"]);
    $result_table;
    $sql = "SELECT `id`, `email`, `username` FROM `users` WHERE `email` = '$email'";
    GetSQL($sql, $result_table);
    if ($result_table[0][0] == "") {
        http_response_code(404);
        echo "KO";
    } else {
        $user_id = $result_table[0][0];
        $email = $result_table[0][1];
        $username = $result_table[0][2];
        $random = rand(0, 999999);
        $code = str_pad("$random", 6, '0', STR_PAD_LEFT);
        $date=date_create(date("Y-m-d H:i:s"));
        date_add($date, date_interval_create_from_date_string("15 minutes"));
        $expiration_time = date_format($date,"Y-m-d H:i:s");
        $sql_code = "INSERT INTO `temporary_codes` ( `code`, `user`, `expiration_time` ) VALUES ( '$code', '$user_id', '$expiration_time' )";
        ExecuteSQL($sql_code);
        send_mail($username, $email, $code);
        echo $user_id;
    }
} else {
    echo "The POST method should be used.";
    http_response_code(405);
}

?>
