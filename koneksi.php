<?php 
if (session_status() == PHP_SESSION_NONE){
    session_start();
}

$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_uks_sekolah"; 

$koneksi = mysqli_connect($host, $user, $pass, $db);

// Cek koneksi
if (mysqli_connect_errno()) {
    echo "Gagal terhubung ke MySQL: " . mysqli_connect_error();
}
?>