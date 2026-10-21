<?php
include "koneksi.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$today = date('Y-m-d');
?>
<div class="container-fluid mt-4">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><i class="fa-solid fa-file-invoice-dollar"></i> Laporan Manajemen Obat UKS</h1>
        <button onclick="window.print()" class="btn btn-sm btn-primary shadow-sm">
            <i class="fa-solid fa-print fa-sm text-white-50"></i> Cetak Laporan
        </button>
    </div>


    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-primary text-white">
            <h6 class="m-0 font-weight-bold"><i class="fa-solid fa-arrow-right-from-bracket"></i> 1. Laporan Pengeluaran Obat</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered align-middle" width="100%" cellspacing="0">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Tanggal Keluar</th>
                            <th>Nama Obat</th>
                            <th>Jumlah Keluar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no1 = 1;
                        $q_keluar = mysqli_query($koneksi, "SELECT p.*, o.nama FROM pengeluaran p JOIN obat o ON p.id_obat = o.id_obat ORDER BY p.tanggal_pengeluaran DESC");

                        if ($q_keluar && mysqli_num_rows($q_keluar) > 0) {
                            while ($d_keluar = mysqli_fetch_array($q_keluar)) {
                                echo "<tr>
                                <td>" . $no1++ . "</td>
                                <td>" . date('d-m-Y', strtotime($d_keluar['tanggal_pengeluaran'])) . "</td>
                                <td><strong>" . htmlspecialchars($d_keluar['nama'] ?? '') . "</strong></td>
                                <td><span class='badge bg-danger'>" . ($d_keluar['jumlah'] ?? 0) . "</span></td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4' class='text-center text-muted'>Belum ada data pengeluaran obat.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- 2. LAPORAN PERMINTAAN OBAT PENDING -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-warning text-dark">
            <h6 class="m-0 font-weight-bold"><i class="fa-solid fa-clock"></i> 2. Laporan Permintaan Obat Masuk (Pending)</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered align-middle" width="100%" cellspacing="0">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Tanggal Permintaan</th>
                            <th>Nama Obat</th>
                            <th>Jumlah Diminta</th>
                            <th>Nama Petugas / Pembina</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no2 = 1;
                        // Melakukan JOIN ke tabel obat dan petugas berdasarkan struktur db_uks_sekolah
                        $q_pending = mysqli_query($koneksi, "SELECT p.*, o.nama AS nama_obat, pt.nama AS nama_pembina 
                            FROM permintaan p 
                            JOIN obat o ON p.id_obat = o.id_obat 
                            JOIN petugas pt ON p.id_petugas = pt.id_petugas 
                            WHERE p.status = 'pending' 
                            ORDER BY p.tanggal_permintaan ASC");

                        if ($q_pending && mysqli_num_rows($q_pending) > 0) {
                            while ($d_pending = mysqli_fetch_array($q_pending)) {
                                $tgl_minta   = !empty($d_pending['tanggal_permintaan']) ? date('d-m-Y', strtotime($d_pending['tanggal_permintaan'])) : '-';
                                $jumlah_obat = $d_pending['jumlah'] ?? 0;
                                $nama_obat   = $d_pending['nama_obat'] ?? 'Nama Obat Kosong';
                                $nama_pembina = $d_pending['nama_pembina'] ?? 'Petugas Tidak Tercatat';

                                echo "<tr>
                                    <td>" . $no2++ . "</td>
                                    <td>" . $tgl_minta . "</td>
                                    <td><strong>" . htmlspecialchars($nama_obat) . "</strong></td>
                                    <td><span class='badge bg-warning text-dark'>" . $jumlah_obat . "</span></td>
                                    <td>" . htmlspecialchars($nama_pembina) . "</td>
                                    <td><span class=''><i class='fa-solid fa-spinner fa-spin'></i> Menunggu Persetujuan</span></td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6' class='text-center text-muted fw-bold py-3'>✓ Tidak ada permintaan obat yang tertunda (Clear).</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- 3. LAPORAN OBAT KADALUARSA -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-danger text-white">
            <h6 class="m-0 font-weight-bold"><i class="fa-solid fa-skull-crossbones"></i> 3. Laporan Obat Kadaluarsa</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered align-middle" width="100%" cellspacing="0">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama Obat</th>
                            <th>Sisa Stok</th>
                            <th>Tanggal Expired</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no3 = 1;
                        // Mengambil obat yang tanggal_expired-nya kurang dari hari ini berdasarkan tabel obat
                        $q_expired = mysqli_query($koneksi, "SELECT nama, stok, tanggal_expired FROM obat WHERE tanggal_expired < '$today' ORDER BY tanggal_expired ASC");

                        if ($q_expired && mysqli_num_rows($q_expired) > 0) {
                            while ($d_expired = mysqli_fetch_array($q_expired)) {
                                echo "<tr>
                                    <td>" . $no3++ . "</td>
                                    <td><strong>" . htmlspecialchars($d_expired['nama']) . "</strong></td>
                                    <td>" . $d_expired['stok'] . "</td>
                                    <td class='text-danger fw-bold'>" . date('d-m-Y', strtotime($d_expired['tanggal_expired'])) . "</td>
                                    <td><span class=''>Wajib Dibuang / Retur</span></td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5' class='text-center text-success fw-bold py-3'>✓ Luar biasa! Tidak ada produk obat yang kadaluarsa saat ini.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        .btn,
        .sidebar,
        nav,
        footer,
        #kotak-peringatan-stok {
            display: none !important;
        }

        .card {
            border: none !important;
            box-shadow: none !important;
        }

        .card-header {
            background-color: #f8f9fa !important;
            color: #000 !important;
            border-bottom: 2px solid #000 !important;
        }
    }
</style>