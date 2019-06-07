<?php

$connect = mysqli_connect("localhost","root","123", 'trav') or die ("Невозможно подключение к MySQL");

mysqli_set_charset($connect, "utf8");

$filename = "/home/tutikh/Downloads/test_data/data/data/visits_11.json";
if ($file = fopen($filename, "r")) {
    while (!feof($file)) {
        $line = fgets($file);
        $data = json_decode($line, true);
        $query = "insert into Visit (id, location, user, visited_at, mark) values ";
        foreach ($data['visits'] as $key => $visit) {
            if ($key != 0) {
                $query .= ",";
            }
            $query .= "('" . $visit['id'] . "', '" . $visit['location'] . "', '" . $visit['user'] . "', '" . $visit['visited_at'] . "',  '". $visit['mark'] . "')";
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