<?php
include "koneksi.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Proses Simpan Data Pengeluaran
if (isset($_POST["submit_pengeluaran"])) {
    $id_siswa     = mysqli_real_escape_string($koneksi, $_POST['id_siswa']);
    $id_obat      = mysqli_real_escape_string($koneksi, $_POST['id_obat']);
    $jumlah       = (int)$_POST['jumlah'];
    $tanggal      = mysqli_real_escape_string($koneksi, $_POST['tanggal_keluar']);
    
    // Mengambil id_petugas dari session yang sedang login, atau dari select option jika admin bebas memilih
    $id_petugas   = isset($_POST['id_petugas']) ? $_POST['id_petugas'] : $_SESSION['petugas']['id_petugas']; 

    // 1. Cek stok obat
    $cek_stok = mysqli_query($koneksi, "SELECT nama, stok FROM obat WHERE id_obat = '$id_obat'");
    $data_obat = mysqli_fetch_array($cek_stok);

    if (!$data_obat) {
        echo "<script>alert('Obat tidak ditemukan!');</script>";
    } elseif ($data_obat['stok'] < $jumlah) {
        echo "<script>alert('Gagal! Stok " . $data_obat['nama'] . " tidak mencukupi. Sisa: " . $data_obat['stok'] . "');</script>";
    } else {
        // 2. Transaksi Database
        mysqli_begin_transaction($koneksi);
        try {
            $query_insert = mysqli_query($koneksi, "INSERT INTO pengeluaran (id_siswa, id_obat, jumlah, tanggal_pengeluaran, id_petugas) VALUES ('$id_siswa', '$id_obat', '$jumlah', '$tanggal', '$id_petugas')");

            $query_update = mysqli_query($koneksi, "UPDATE obat SET stok = stok - $jumlah WHERE id_obat = '$id_obat'");

            if ($query_insert && $query_update) {
                mysqli_commit($koneksi);
                echo "<script>alert('Data pengeluaran berhasil disimpan!'); window.location='?page=pengeluaran_obat';</script>";
            } else {
                throw new Exception("Query Gagal");
            }
        } catch (Exception $e) {
            mysqli_rollback($koneksi);
            echo "<script>alert('Terjadi kesalahan sistem. Gagal menyimpan data.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengeluaran Obat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container-fluid mt-4">
    <h1 class="h3 mb-4 text-gray-800">Kelola Pengeluaran Obat</h1>
    
    <div class="row">
        <!-- Form Input Pengeluaran -->
        <div class="col-xl-4 col-lg-5 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 bg-success">
                    <h6 class="m-0 font-weight-bold text-white">Input Pengeluaran</h6>
                </div>
                <div class="card-body">
                    <form action="" method="POST">
                        <div class="mb-3">
                            <label class="form-label font-weight-bold">Pilih Siswa & Kamar</label>
                            <select name="id_siswa" class="form-select" required>
                                <option value="">-- Pilih Siswa (Kamar) --</option>
                                <?php 
                                $siswa_query = mysqli_query($koneksi, "SELECT id_siswa, nama, kamar FROM siswa ORDER BY nama ASC");
                                while($s = mysqli_fetch_array($siswa_query)): 
                                ?>
                                    <option value="<?= $s['id_siswa']; ?>"><?= $s['nama']; ?> - Kamar: <?= $s['kamar']; ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label font-weight-bold">Pilih Obat</label>
                            <select name="id_obat" class="form-select" required>
                                <option value="">-- Pilih Obat --</option>
                                <?php 
                                $obat_query = mysqli_query($koneksi, "SELECT id_obat, nama, stok FROM obat WHERE stok > 0 ORDER BY nama ASC");
                                while($o = mysqli_fetch_array($obat_query)): 
                                ?>
                                    <option value="<?= $o['id_obat']; ?>"><?= $o['nama']; ?> (Stok: <?= $o['stok']; ?>)</option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label font-weight-bold">Jumlah Keluar</label>
                            <input type="number" name="jumlah" class="form-control" min="1" placeholder="Masukkan Jumlah" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label font-weight-bold">Tanggal Pengeluaran</label>
                            <input type="date" name="tanggal_keluar" class="form-control" value="<?= date('Y-m-d'); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label font-weight-bold">Petugas / Pembina</label>
                            <select name="id_petugas" class="form-select" required>
                                <option value="">-- Pilih Petugas --</option>
                                <?php 
                                $petugas_query = mysqli_query($koneksi, "SELECT * FROM petugas");
                                while($p = mysqli_fetch_array($petugas_query)): 
                                ?>
                                    <option value="<?= $p['id_petugas']; ?>"><?= $p['nama']; ?> (<?= $p['level']; ?>)</option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <hr>
                        <button type="submit" name="submit_pengeluaran" class="btn btn-success w-100">Simpan Pengeluaran</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Tabel Riwayat Pengeluaran -->
        <div class="col-xl-8 col-lg-7 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 bg-success">
                    <h6 class="m-0 font-weight-bold text-white">Riwayat Pengeluaran Obat</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr class="table-light">
                                    <th>No</th>
                                    <th>Nama Siswa</th>
                                    <th>Kamar</th>
                                    <th>Obat</th>
                                    <th>Jumlah</th>
                                    <th>Petugas</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                // Query JOIN disesuaikan dengan kolom tanggal_pengeluaran di database
                                $query_tabel = mysqli_query($koneksi, "SELECT p.tanggal_pengeluaran, p.jumlah, s.nama AS nama_siswa, s.kamar AS kamar_siswa, o.nama AS nama_obat, pet.nama AS nama_petugas
                                    FROM pengeluaran AS p
                                    LEFT JOIN siswa AS s ON p.id_siswa = s.id_siswa
                                    LEFT JOIN obat AS o ON p.id_obat = o.id_obat
                                    LEFT JOIN petugas AS pet ON p.id_petugas = pet.id_petugas
                                    ORDER BY p.id_pengeluaran DESC
                                ");

                                while ($row = mysqli_fetch_array($query_tabel)):
                                ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td><?= $row['nama_siswa']; ?></td>
                                        <td><?= $row['kamar_siswa']; ?></td>
                                        <td><span class="badge bg-info text-dark"><?= $row['nama_obat']; ?></span></td>
                                        <td><?= $row['jumlah']; ?></td>
                                        <td><?= $row['nama_petugas']; ?></td>
                                        <td><?= $row['tanggal_pengeluaran']; ?></td>
                                    </tr>
                                <?php endwhile; ?>
                                
                                <?php if (mysqli_num_rows($query_tabel) == 0): ?>
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">Belum ada data pengeluaran obat.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>