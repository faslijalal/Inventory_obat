<?php
include "koneksi.php";

if(isset($_GET['id'])){

$id = $_GET['id'];

$data = mysqli_query($koneksi,"SELECT * FROM permintaan
WHERE id_permintaan='$id'
AND status='pending'
");

$d = mysqli_fetch_assoc($data);

if($d){
$id_obat = $d['id_obat'];
$jumlah = $d['jumlah'];

mysqli_query($koneksi,"UPDATE permintaan
SET status='tolak'
WHERE id_permintaan='$id'
");

echo "<script>alert('Permintaan ditolak'); window.location='?page=permintaan';</script>";
}else{
echo "<script>alert('Data tidak ditemukan atau sudah diproses'); window.location='?page=permintaan';</script>";
}
}
?>