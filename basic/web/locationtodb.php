<?php

$connect = mysqli_connect("localhost","root","123", 'trav') or die ("Невозможно подключение к MySQL");

mysqli_set_charset($connect, "utf8");

$filename = "/home/tutikh/Downloads/test_data/data/data/locations_8.json";
if ($file = fopen($filename, "r")) {
    while (!feof($file)) {
        $line = fgets($file);
        $data = json_decode($line, true);
        $query = "insert into Location (id, place, country, city, distance) values ";
        foreach ($data['locations'] as $key => $location) {
            if ($key != 0) {
                $query .= ",";
            }
            $query .= "('" . $location['id'] . "', '" . $location['place'] . "', '" . $location['country'] . "', '" . $location['city'] . "',  '". $location['distance'] . "')";
        }
        $query .= ";";
        $result = mysqli_query($connect, $query) or die("Ошибка ");
    }
}
if($result)
{
    echo "Запись успешно добавлена";
} else {
    echo 'ошиюка';
}
