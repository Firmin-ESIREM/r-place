<?php

include('../../include/config.inc.php');

$result_table;

$sql="SELECT `grids`.`id`, `grids`.`name`, `users`.`username` FROM `grids`, `users`";
GetSQL($sql, $result_table);

$output = '[';

foreach ($result_table as &$result) {
    $result_array = array('id' => intval($result[0]), 'name' => $result[1], 'owner' => $result[2]);
    $output .= json_encode($result_array);
    $output .= ",";
}

$output = rtrim($output, ",");
$output .= ']';

echo $output;

?>
