<?php
include "koneksi.php";
session_destroy();
header("location: login.php");
exit;
