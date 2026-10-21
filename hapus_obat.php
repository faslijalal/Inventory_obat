<?php
// Panggil file koneksi database
include "koneksi.php";

// Pastikan session sudah dimulai jika diperlukan untuk validasi login
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cek apakah parameter 'id' ada di URL
if (isset($_GET['id'])) {
    $id_obat = mysqli_real_escape_string($koneksi, $_GET['id']);

    // Query untuk menghapus data obat berdasarkan id_obat
    $query = mysqli_query($koneksi, "DELETE FROM obat WHERE id_obat = '$id_obat'");

    if ($query) {
        // Jika berhasil, berikan alert dan kembalikan ke halaman data obat
        echo "<script>
                alert('Data obat berhasil dihapus!');
                window.location='index.php?page=data_obat'; 
              </script>";
    } else {
        // Jika gagal (biasanya karena masih berelasi dengan tabel lain seperti penerimaan/pengeluaran)
        $error = mysqli_error($koneksi);
        echo "<script>
                alert('Gagal menghapus data obat: $error');
                window.location='index.php?page=obat';
              </script>";
    }
} else {
    // Jika mencoba akses file ini langsung tanpa membawa parameter ID
    echo "<script>
            alert('ID obat tidak ditemukan!');
            window.location='index.php?page=obat';
          </script>";
}
?>