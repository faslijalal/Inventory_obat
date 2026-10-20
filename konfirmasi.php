<?php
include "koneksi.php";

$id = $_GET["id"]; // id_permintaan
$aksi = $_GET["aksi"];

// Ambil data permintaan untuk tahu obat apa dan berapa jumlahnya
$query = mysqli_query($koneksi, "SELECT * FROM permintaan WHERE id_permintaan='$id'");
$data = mysqli_fetch_assoc($query);

if ($data['status'] == "pending") {
    $id_obat = $data['id_obat'];
    $jumlah = $data['jumlah'];

    if ($aksi == 'setuju') {
        // 1. Update status permintaan
        mysqli_query($koneksi, "UPDATE permintaan SET status='disetujui' WHERE id_permintaan='$id'");
        // 2. Tambah stok di tabel obat
        mysqli_query($koneksi, "UPDATE obat SET stok = stok + $jumlah WHERE id_obat='$id_obat'");
        
        echo "<script>alert('Permintaan disetujui, stok bertambah!'); window.location='index.php?page=permintaan';</script>";
    } 
    elseif ($aksi == 'tolak') {
        mysqli_query($koneksi, "UPDATE permintaan SET status='ditolak' WHERE id_permintaan='$id'");
        echo "<script>alert('Permintaan ditolak!'); window.location='index.php?page=permintaan';</script>";
    }
} else {
    echo "<script>alert('Data sudah diproses sebelumnya'); window.location='index.php?page=permintaan';</script>";
}
?>