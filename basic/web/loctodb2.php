<?php

$connect = mysqli_connect("localhost","root","123", 'trav') or die ("Невозможно подключение к MySQL");

$json_array  = file_get_contents('/home/tutikh/Downloads/test_data/data/data/users_3.json');
$data = json_decode($json_array, true);
echo '<pre>';
print_r($data);