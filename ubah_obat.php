<?php 
include "koneksi.php"; 
?>
<div class="container-fluid mt-4">
    <h1 class="h3 mb-2 text-gray-800">Update Stok Obat</h1>
    <div class="card shadow mb-3 col-lg-6">
        <div class="card-body">
            <form method="POST">
                <?php
                $id = mysqli_real_escape_string($koneksi, $_GET["id"]);
                if (isset($_POST["submit"])) {
                    $stok_baru = (int)$_POST['stok'];
                    if ($stok_baru < 0) {
                        echo "<div class='alert alert-danger'>Stok tidak boleh bernilai negatif!</div>";
                    } else {
                        $query_update = mysqli_query($koneksi, "UPDATE obat SET stok = $stok_baru WHERE id_obat = '$id'");
                        
                        if ($query_update) {
                            echo "<script>alert('Stok berhasil diperbarui!'); window.location='?page=obat';</script>";
                        } else {
                            echo "<div class='alert alert-danger'>Gagal memperbarui database.</div>";
                        }
                    }
                }
                $query_select = mysqli_query($koneksi, "SELECT nama, stok, jumlah_minimum FROM obat WHERE id_obat = '$id'");
                $data = mysqli_fetch_assoc($query_select);

                if (!$data) {
                    echo "<div class='alert alert-danger'>Data obat tidak ditemukan!</div>";
                    exit;
                }
                ?>

                <div class="mb-3">
                    <label class="form-label font-weight-bold">Nama Obat</label>
                    <input type="text" class="form-control" value="<?= htmlspecialchars($data['nama']); ?>" readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label font-weight-bold text-muted">Batas Minimum Stok Obat Ini</label>
                    <input type="text" class="form-control bg-light text-muted" value="<?= $data['jumlah_minimum']; ?>" readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label font-weight-bold text-primary">Jumlah Stok Sekarang</label>
                    <input type="number" name="stok" class="form-control" value="<?= $data['stok']; ?>" min="0" required>
                </div>

                <hr>

                <div class="d-flex justify-content-between align-items-center">
                    <a href="?page=data_obat" class="btn btn-danger">Kembali</a>
                    <div>
                        <button type="reset" class="btn btn-secondary">Reset</button>
                        <a href="?page=data_obat" type="submit" name="submit" class="btn btn-primary">Simpan Stok Baru</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>