<?php include "koneksi.php"; ?>

<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Tambah Obat</h1>
    
    <div class="card shadow mb-4">
        <div class="card-body">
            <?php 
            if (isset($_POST["submit"])) {
                $obat    = mysqli_real_escape_string($koneksi, $_POST['obat']);
                $stok    = $_POST['stok'];
                $min     = $_POST['minimum'];
                $expired = $_POST['expired'];

                // Query INSERT disesuaikan dengan kolom database (nama, jumlah_minimum, stok, tanggal_expired) tanpa kategori
                $query = mysqli_query($koneksi, "INSERT INTO obat (nama, jumlah_minimum, stok, tanggal_expired) VALUES ('$obat', '$min', '$stok', '$expired')");
                
                if ($query) {
                    echo "<script>alert('Tambah data obat berhasil'); window.location='?page=data_obat';</script>";
                } else {
                    echo "<script>alert('Tambah data gagal: " . mysqli_error($koneksi) . "');</script>";
                }
            }
            ?>

            <form method="POST">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold">Nama Obat</label>
                            <input type="text" name="obat" class="form-control" placeholder="Masukkan nama obat..." required>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold">Stok Obat</label>
                            <input type="number" name="stok" class="form-control" placeholder="Masukkan jumlah stok..." required>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold">Minimum Stok (Peringatan)</label>
                            <input type="number" name="minimum" class="form-control" placeholder="Batas minimum stok..." required>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold">Tanggal Expired</label>
                            <input type="date" name="expired" class="form-control" required>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary" name="submit" value="submit">
                        <i class="fa-solid fa-save"></i> Simpan
                    </button>
                    <button type="reset" class="btn btn-secondary">
                        <i class="fa-solid fa-rotate-left"></i> Reset
                    </button>
                    <a href="?page=data_obat" class="btn btn-danger">
                        <i class="fa-solid fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>