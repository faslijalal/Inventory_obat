<?php
include "koneksi.php";

if (isset($_POST['submit_permintaan'])) {
    $id_obat = $_POST['namaObat'];
    $jumlah  = $_POST['jumlah'];
    $status  = 'pending';
    $tanggal = date('Y-m-d');
    $query = "INSERT INTO permintaan (id_obat, jumlah, status, tanggal) VALUES ('$id_obat', '$jumlah', '$status', '$tanggal')";

    if ($query){
        echo "<script>alert('Permintaan berhasil dikirim!'); window.location='index.php?page=permintaan';</script>";
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
}
?>