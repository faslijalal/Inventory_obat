<?php
include "koneksi.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Obat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <div class="container mt-4">
        <div class="table-responsive">

            <?php if (isset($_SESSION['petugas']['level']) &&$_SESSION['petugas']['level'] == 'admin') : ?>
                <div class="mb-3">
                    <a href="?page=tambah_obat" class="btn btn-success">+ Tambah Obat</a>
                </div>
            <?php endif; ?>

            <table class="table table-bordered align-middle" cellspacing="0">
                <thead>
                    <tr class="table-light">
                        <th>No.</th>
                        <th>Nama Obat</th>
                        <th>Stok Obat</th>
                        <th>Minimum</th>
                        <th>Expired</th>
                        <th>Status Expired</th>
                        <?php if (isset($_SESSION['petugas']['level']) &&$_SESSION['petugas']['level'] == 'admin'): ?>
                            <th>Aksi</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    // Disesuaikan dengan kolom asli tabel obat di database: id_obat, nama, stok, jumlah_minimum, tanggal_expired
                    $query = mysqli_query($koneksi, "SELECT id_obat, nama, stok, jumlah_minimum, tanggal_expired FROM obat");

                    while ($data = mysqli_fetch_array($query)):
                        $stok = (int)$data['stok'];
                        $min = (int)$data['jumlah_minimum'];
                    ?>
                        <tr class="baris-obat" data-nama="<?= htmlspecialchars($data['nama']); ?>" data-stok="<?= $stok; ?>" data-min="<?= $min; ?>">
                            <td><?= $i++; ?></td>
                            <td><strong><?= htmlspecialchars($data['nama']); ?></strong></td>
                            <td>
                                <span class="badge bg-secondary"><?= $stok; ?></span>
                            </td>
                            <td><?= $min; ?></td>
                            <td><?= $data['tanggal_expired']; ?></td>
                            <td>
                                <?php
                                $today = date('Y-m-d');
                                $expired =$data['tanggal_expired'];
                                if ($expired <$today) {
                                    echo "<span class='badge bg-danger'>Kadaluarsa</span>";
                                } else {
                                    echo "<span class='badge bg-success'>Masih Aman</span>";
                                }
                                ?>
                            </td>
                            
                            <?php if (isset($_SESSION['petugas']['level']) &&$_SESSION['petugas']['level'] == 'admin'): ?>
                                <td>
                                    <a href="?page=ubah_obat&id=<?= $data['id_obat']; ?>" class="text-primary me-3 text-decoration-none">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <a href="?page=hapus_obat&id=<?= $data['id_obat']; ?>" onclick="return confirm('Apakah Anda Yakin?')" class="text-danger text-decoration-none">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <div id="kotak-peringatan-stok" class="mt-3"></div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const barisObat = document.querySelectorAll('.baris-obat');
            const kotakPeringatan = document.getElementById('kotak-peringatan-stok');
            
            let obatKritis = [];

            barisObat.forEach(function(row) {
                const namaObat = row.dataset.nama;
                const stok = parseInt(row.dataset.stok);
                const min = parseInt(row.dataset.min);

                if (stok <= min) {
                    obatKritis.push(namaObat);
                }
            });

            if (obatKritis.length > 0) {
                kotakPeringatan.innerHTML = `
                    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                        <h4 class="alert-heading"><i class="fa-solid fa-triangle-exclamation"></i> PERINGATAN STOK MINIMUM!</h4>
                        <p class="mb-2">Terdapat <strong>${obatKritis.length} obat</strong> yang telah mencapai atau di bawah batas minimum stok. Mohon segera lakukan pengadaan ulang.</p>
                        <hr>
                        <ul class="mb-0">
                            ${obatKritis.map(nama => `<li><strong>${nama}</strong></li>`).join('')}
                        </ul>
                    </div>
                `;
            } else {
                kotakPeringatan.innerHTML = `
                    <div class="alert alert-success shadow-sm" role="alert">
                        <i class="fa-solid fa-circle-check"></i> <strong>Semua Stok Aman</strong>
                    </div>
                `;
            }
        });
    </script>
</body>

</html>