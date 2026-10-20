<?php include "koneksi.php"?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

</head>

<body>
    <div class="container-fluid">

        <!-- Page Heading -->


        <!-- Content Row -->
        <div class="row">

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card bg-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-light text-uppercase mb-1">Jumlah Obat Masuk</div>
                                <div class="h5 mb-0 font-weight-bold text-light"><?= mysqli_num_rows(mysqli_query($koneksi, 'SELECT*FROM obat')); ?></div>
                            </div>
                            <div class="col-auto rotate-n-15">
                                <i class="fa-solid fa-pills fa-2x text-light"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card bg-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-light text-uppercase mb-1">
                                    Stok Menipis</div>
                                <div class="h5 mb-0 font-weight-bold text-light"><?= mysqli_num_rows(mysqli_query($koneksi, 'SELECT COUNT(id_obat) 
                                    FROM obat 
                                    WHERE stok < 10;')); ?></div>
                            </div>
                            <div class="col-auto ">
                                <i class="fa-solid fa-arrow-trend-down fa-2x text-light"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card bg-danger shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-light text-uppercase mb-1">Hampir Kadaluarsa
                                </div>
                                <div class="row no-gutters align-items-center">
                                    <div class="col-auto">    
                                                                <!-- disini adalah bagian menampilkan apakan obat sudah kadaluarsa atau belum -->
                                        <div class="h5 mb-0 mr-3 font-weight-bold text-light"><?= mysqli_num_rows(mysqli_query($koneksi, 'SELECT COUNT(*) 
                                            FROM obat 
                                            WHERE tanggal_expired <= CURDATE() + INTERVAL 7 DAY')); ?></div>
                                    </div>
                                    <div class="col">
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto">
                            <i class="fa-solid fa-clock-rotate-left fa-2x text-light"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Requests Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card bg-dark shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-light text-uppercase mb-1">
                                    Total Pengguna</div>
                                <div class="h5 mb-0 font-weight-bold text-light"><?= mysqli_num_rows(mysqli_query($koneksi, 'SELECT*FROM petugas')); ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fa-solid fa-users fa-2x text-light"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Row -->

        <div class="row">

            <!-- Area Chart -->
            <div class="col-xl-8 col-lg-7">
                <div class="mb-4">
                    <!-- Content Row -->
                    <div class="row">

                        <!-- Content Column -->
                        <div class="col-lg-6 mb-4">
                        </div>
                    </div>

                </div>

                <div class="card mb-4 border-warning">
                    <div class="card-body">
                        <table class="table table-border">
                            <tr>
                                <td width="150"><strong>Nama</strong></td>
                                <td width="1">:</td>
                                <td width="200"><?= $_SESSION['petugas']['nama']; ?></td>
                            </tr>
                            <tr>
                                <td width="150"><strong>Level User</strong></td>
                                <td width="1">:</td>
                                <td width="200"><?= $_SESSION['petugas']['level']; ?></td>
                            </tr>
                            <tr>
                                <td width="150"><strong>Tanggal Login</strong></td>
                                <td width="1">:</td>
                                <td width="200"><?= date('d-m-y'); ?></td>
                            </tr>


                        </table>
                    </div>

                </div>
</body>

</html>