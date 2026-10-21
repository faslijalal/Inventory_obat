<?php
include "koneksi.php";
// Memastikan session dimulai agar $_SESSION['petugas'] tidak error
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permintaan Obat</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
</head>

<body>
    <div class="container-fluid mt-4">
        <h1 class="h3 mb-2 text-gray-800">Permintaan Obat</h1>
        <div>
            <?php if (isset($_SESSION['petugas']['level']) && $_SESSION['petugas']['level'] == 'pembina') : ?>
                <button type="button" class="btn btn-success mb-2" data-bs-toggle="modal" data-bs-target="#modalMintaObat">+ Permintaan Obat</button>
            <?php endif; ?>

            <?php
            if (isset($_POST["submit_permintaan"])) {
                $obat     = mysqli_real_escape_string($koneksi, $_POST['obat']);
                $jumlah   = mysqli_real_escape_string($koneksi, $_POST['jumlah']);
                $tanggal  = mysqli_real_escape_string($koneksi, $_POST['tanggal']);
                
                // Mengambil id_petugas dari session yang sedang login, atau dari select form jika disediakan
                $petugas  = $_SESSION['petugas']['id_petugas']; 
                $status   = 'pending'; // Status awal saat mengajukan permintaan sesuai enum database

                if (empty($obat)) {
                    echo "<script>alert('Gagal: Silakan pilih obat terlebih dahulu!'); window.location='?page=Permintaan';</script>";
                    exit;
                }

                // Query INSERT disesuaikan dengan kolom pada tabel permintaan (id_obat, jumlah, tanggal_permintaan, status, id_petugas)
                $query = mysqli_query($koneksi, "INSERT INTO permintaan (id_obat, jumlah, tanggal_permintaan, status, id_petugas) VALUES ('$obat', '$jumlah', '$tanggal', '$status', '$petugas')");

                if ($query) {
                    echo "<script>alert('Tambah data permintaan berhasil'); window.location='?page=Permintaan';</script>";
                } else {
                    $error = mysqli_error($koneksi);
                    echo "<script>alert('Tambah data gagal: $error');</script>";
                }
            }
            ?>

            <div class="modal fade" id="modalMintaObat" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Form Permintaan Obat</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="" method="POST">
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Pilih Obat</label>
                                    <select name="obat" class="form-select" required>
                                        <option value="">-- Pilih Obat --</option>
                                        <?php
                                        $data_obat = mysqli_query($koneksi, "SELECT * FROM obat");
                                        while ($o = mysqli_fetch_array($data_obat)):
                                        ?>
                                            <option value="<?= $o['id_obat']; ?>"><?= $o['nama']; ?> (Stok: <?= $o['stok']; ?>)</option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Jumlah Permintaan</label>
                                    <input type="number" name="jumlah" class="form-control" placeholder="Masukkan jumlah" min="1" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Tanggal Permintaan</label>
                                    <input type="date" name="tanggal" class="form-control" value="<?= date('Y-m-d'); ?>" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" name="submit_permintaan" class="btn btn-success">Kirim Permintaan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr class="bg-gray-200">
                                <th>No</th>
                                <th>Nama Obat</th>
                                <th>Jumlah</th>
                                <th>Tanggal</th>
                                <th>Nama Petugas</th>
                                <th>Status</th>
                                <?php if (isset($_SESSION['petugas']['level']) && $_SESSION['petugas']['level'] == 'admin') : ?>
                                    <th>Aksi</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            // Query menggunakan JOIN yang tepat berdasarkan struktur foreign key database db_uks_sekolah
                            $query = mysqli_query($koneksi, "SELECT per.id_permintaan, o.nama as nama_obat, per.jumlah, per.tanggal_permintaan, per.status, pet.nama as nama_petugas 
                                FROM permintaan as per 
                                LEFT JOIN obat as o ON o.id_obat = per.id_obat 
                                LEFT JOIN petugas as pet ON pet.id_petugas = per.id_petugas");
                            
                            while ($data = mysqli_fetch_array($query)):
                            ?>
                                <tr>
                                    <td><?= $i++; ?></td>
                                    <td><?= $data['nama_obat']; ?></td>
                                    <td><?= $data['jumlah']; ?></td>
                                    <td><?= $data['tanggal_permintaan']; ?></td>
                                    <td><?= $data['nama_petugas']; ?></td>
                                    <td>
                                        <?php
                                        if ($data['status'] == "pending") {
                                            echo "<span class='badge bg-warning text-dark'>Pending</span>";
                                        } elseif ($data['status'] == "setuju") {
                                            echo "<span class='badge bg-success text-white'>Setuju</span>";
                                        } else {
                                            echo "<span class='badge bg-danger text-white'>Ditolak</span>";
                                        }
                                        ?>
                                    </td>
                                    <?php if (isset($_SESSION['petugas']['level']) && $_SESSION['petugas']['level'] == 'admin') : ?>
                                        <td>
                                            <a href="?page=setuju&id=<?= $data['id_permintaan']; ?>" class="btn btn-success btn-sm" title="Setujui"><i class="fa-solid fa-check"></i></a>
                                            <a href="?page=tolak&id=<?= $data['id_permintaan']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda Yakin Ingin Menolak Permintaan Ini?')" title="Tolak"><i class="fa-solid fa-xmark"></i></a>
                                        </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>

</html>