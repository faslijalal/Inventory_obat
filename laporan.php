<?php
include "koneksi.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$today = date('Y-m-d');
?>
<div class="container-fluid mt-4">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><i class="fa-solid fa-file-invoice-dollar"></i> Laporan Manajemen Obat</h1>
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
                            <th>Kategori</th>
                            <th>Jumlah Keluar</th>
                            <th>Keterangan / Peruntukan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no1 = 1;
                        // Diasumsikan kamu memiliki tabel 'pengeluaran_obat' yang berelasi dengan tabel 'obat'
                        $q_keluar = mysqli_query($koneksi, "SELECT p.*, o.nama, o.kategori FROM pengeluaran p JOIN obat o ON p.id_obat = o.id_obat ORDER BY p.tanggal_keluar DESC");

                        if (mysqli_num_rows($q_keluar) > 0) {
                            while ($d_keluar = mysqli_fetch_array($q_keluar)) {
                                // FIX 1 & 2: Amankan kolom kategori dan keterangan dari nilai NULL / Undefined Key
                                $kategori_obat   = $d_keluar['kategori'] ?? '-';
                                $keterangan_obat = $d_keluar['keterangan'] ?? 'Tidak ada keterangan';
                                echo "<tr>
                                <td>" . $no1++ . "</td>
                                <td>" . date('d-m-Y', strtotime($d_keluar['tanggal_keluar'])) . "</td>
                                <td><strong>" . htmlspecialchars($d_keluar['nama'] ?? '') . "</strong></td>
                                <td><span class='badge bg-info text-white border'>" . htmlspecialchars($kategori_obat) . "</span></td>
                                <td><span class='badge bg-danger'>" . ($d_keluar['jumlah'] ?? 0) . "</span></td>
                                <td>" . htmlspecialchars($keterangan_obat) . "</td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6' class='text-center text-muted'>Belum ada data pengeluaran obat.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-warning text-dark">
            <h6 class="m-0 font-weight-bold"><i class="fa-solid fa-clock"></i> 2. Laporan Permintaan Obat Masuk (Pending Pembina)</h6>
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
                            <th>nama Pembina</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no2 = 1;
                        $q_pending = mysqli_query($koneksi, "SELECT * FROM permintaan WHERE status = 'pending' ORDER BY tanggal_permintaan ASC");

                        if ($q_pending && mysqli_num_rows($q_pending) > 0) {
                            while ($d_pending = mysqli_fetch_array($q_pending)) {

                                // 1. Ambil data Nama Obat (Mencoba mendeteksi key yang paling mungkin dari tabel permintaan)
                                if (isset($d_pending['nama_obat']) && $d_pending['nama_obat'] !== null) {
                                    $nama_obat = $d_pending['nama_obat'];
                                } elseif (isset($d_pending['nama']) && $d_pending['nama'] !== null) {
                                    $nama_obat = $d_pending['nama'];
                                } elseif (isset($d_pending['obat']) && $d_pending['obat'] !== null) {
                                    $nama_obat = $d_pending['obat'];
                                } else {
                                    $nama_obat = 'Nama Obat Kosong';
                                }

                                // 2. Ambil data Nama Pembina (Mencoba mendeteksi variasi nama kolom pembina)
                                if (isset($d_pending['nama_pembina']) && $d_pending['nama_pembina'] !== null) {
                                    $nama_pembina = $d_pending['nama_pembina'];
                                } elseif (isset($d_pending['pembina']) && $d_pending['pembina'] !== null) {
                                    $nama_pembina = $d_pending['pembina'];
                                } else {
                                    $nama_pembina = 'Pembina Tidak Tercatat';
                                }

                                // 3. Amankan data penunjang lainnya dari nilai NULL atau kosong
                                $tgl_minta   = !empty($d_pending['tanggal_permintaan']) ? date('d-m-Y', strtotime($d_pending['tanggal_permintaan'])) : '-';
                                $jumlah_obat = $d_pending['jumlah'] ?? 0;

                                // 4. Render ke dalam baris tabel HTML
                                echo "<tr>
                                    <td>" . $no2++ . "</td>
                                    <td>" . $tgl_minta . "</td>
                                    <td><strong>" . htmlspecialchars($nama_obat) . "</strong></td>
                                    <td><span class='badge bg-warning text-dark'>" . $jumlah_obat . "</span></td>
                                    <td>" . htmlspecialchars($nama_pembina) . "</td>
                                    <td><span class='badge bg-secondary'><i class='fa-solid fa-spinner fa-spin'></i> Menunggu Persetujuan</span></td>
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
                            <th>Kategori</th>
                            <th>Sisa Stok</th>
                            <th>Tanggal Expired</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no3 = 1;
                        // Mengambil obat yang tanggal_expired-nya kurang dari hari ini
                        $q_expired = mysqli_query($koneksi, "SELECT nama, kategori, stok, tanggal_expired FROM obat WHERE tanggal_expired < '$today' ORDER BY tanggal_expired ASC");

                        if (mysqli_num_rows($q_expired) > 0) {
                            while ($d_expired = mysqli_fetch_array($q_expired)) {
                                echo "<tr>
                                    <td>" . $no3++ . "</td>
                                    <td><strong>" . htmlspecialchars($d_expired['nama']) . "</strong></td>
                                    <td><span class='badge bg-light text-dark border'>" . htmlspecialchars($d_expired['kategori']) . "</span></td>
                                    <td>" . $d_expired['stok'] . "</td>
                                    <td class='text-danger fw-bold'>" . date('d-m-Y', strtotime($d_expired['tanggal_expired'])) . "</td>
                                    <td><span class='badge bg-danger'>Wajib Dibuang / Retur</span></td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6' class='text-center text-success fw-bold py-3'>✓ Luar biasa! Tidak ada produk obat yang kadaluarsa saat ini.</td></tr>";
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
            shadow: none !important;
        }

        .card-header {
            background-color: #f8f9fa !important;
            color: #000 !important;
            border-bottom: 2px solid #000 !important;
        }
    }
</style>