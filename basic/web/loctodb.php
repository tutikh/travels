<?php

$connect = mysqli_connect("localhost","root","123", 'trav') or die ("Невозможно подключение к MySQL");

$json_array  = file_get_contents('locations_1.json');
$data = json_decode($json_array, true);
echo '<pre>';
print_r($data);