<?php
include "koneksi.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Login - UKS Inventory</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body class="bg-gradient-success">

    <div class="container">
        <!-- Outer Row -->
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12 col-md-9">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Login UKS Sekolah</h1>
                                    </div>

                                    <?php
                                    // Jika tombol login ditekan
                                    if (isset($_POST['login'])) {
                                        $username = mysqli_real_escape_string($koneksi, $_POST['username']);
                                        $password = md5($_POST['password']); // Menggunakan MD5 sesuai kode aslimu

                                        // Query mencocokkan username dan password pada tabel petugas
                                        $data = mysqli_query($koneksi, "SELECT * FROM petugas WHERE username='$username' AND password='$password'");
                                        $cek = mysqli_num_rows($data);

                                        if ($cek > 0) {
                                            $row = mysqli_fetch_assoc($data);
                                            
                                            // Simpan data session
                                            $_SESSION['petugas'] = $row;
                                            $_SESSION['level'] = $row['level']; // Menyimpan level ('admin' atau 'pembina')[cite: 2]

                                            echo '<script>alert("Login Berhasil!");</script>';

        
                                            if ($row['level'] == 'admin') {
                                                echo '<script>location.href="index.php";</script>'; // Ubah ke dashboard admin jika ada
                                            } else {
                                                echo '<script>location.href="index.php";</script>'; // Ubah ke dashboard pembina jika ada
                                            }
                                        } else {
                                            echo '<div class="alert alert-danger text-center" role="alert">Username atau Password salah!</div>';
                                        }
                                    }
                                    ?>

                                    <form method="POST" class="user">
                                        <div class="form-group">
                                            <input type="text" name="username" class="form-control form-control-user"
                                                id="exampleInputUsername" placeholder="Masukkan Username..." required>
                                        </div>
                                        <div class="form-group">
                                            <input type="password" name="password" class="form-control form-control-user"
                                                id="exampleInputPassword" placeholder="Password" required>
                                        </div>
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox small">
                                                <input type="checkbox" class="custom-control-input" id="customCheck">
                                                <label class="custom-control-label" for="customCheck">Remember Me</label>
                                            </div>
                                        </div>
                                        <button type="submit" name="login" class="btn btn-success btn-user btn-block">
                                            Login
                                        </button>
                                    </form>
                                    <hr>
                                    <div class="text-center">
                                        <a class="small" href="forgot-password.html">Forgot Password?</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

</body>

</html>