<?php

$host = "localhost"; // Veritabanı sunucusu
$username = "root"; // Veritabanı kullanıcı adı
$password = ""; // Veritabanı şifresi
$dbName = "noteapp_data"; // Veritabanı adı

$conn = new mysqli($host, $username, $password, $dbName);

if ($conn->connect_error) {
    die("Veritabanı bağlantısı başarısız: " . $conn->connect_error);
}

?>
