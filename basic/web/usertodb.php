<?php

$connect = mysqli_connect("localhost","root","123", 'trav') or die ("Невозможно подключение к MySQL");

mysqli_set_charset($connect, "utf8");

$filename = "/home/tutikh/Downloads/test_data/data/data/users_11.json";
if ($file = fopen($filename, "r")) {
    while (!feof($file)) {
        $line = fgets($file);
        $data = json_decode($line, true);
        $query = "insert into User (id, email, first_name, last_name, gender, birth_date) values ";
        foreach ($data['users'] as $key => $user) {
            if ($key != 0) {
                $query .= ",";
            }

            $query .= "('" . $user['id'] . "', '" . $user['email'] . "', '" . $user['first_name'] . "', '" . $user['last_name'] . "',  '". $user['gender'] . "', '". $user['birth_date'] . "')";
        }
        $query .= ";";

//   echo $query;
        $result = mysqli_query($connect, $query) or die("Ошибка ");
    }
}
if($result)
{
    echo "Запись успешно добавлена";
} else {
    echo 'ошиюка';
}
