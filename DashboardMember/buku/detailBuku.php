<?php
require "../../config/config.php";
$kodebuku = $_GET["id"];
$query = queryReadData("SELECT * FROM tb_buku WHERE kodebuku = '$kodebuku'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/de8de52639.js" crossorigin="anonymous"></script>
    <title>Detail Buku || Member</title>
</head>
<body>
<nav class="navbar fixed-top bg-body-tertiary shadow-sm">
    <div class="container-fluid p-3">
        <a class="navbar-brand" href="#">
            
        </a>
        <a class="btn btn-tertiary" href="../dashboardMember.php">Dashboard</a>
    </div>
</nav>

<div class="p-4 mt-5">
    <h2 class="mt-5">Detail Buku</h2>
    <div class="d-flex justify-content-center">
       
            <?php foreach ($query as $item) : ?>
                
                    
                </div>
               
                    <!-- Display success message here with font-size 25 -->
                    <p style="font-size: 45px;">Selamat! Peminjaman Berhasil.</p>
                    <a href="daftarBuku.php" class="btn btn-danger">Kembali</a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>
