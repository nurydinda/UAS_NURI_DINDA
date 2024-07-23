<?php
session_start();

if (!isset($_SESSION["signIn"])) {
    header("Location: ../../sign/admin/sign_in.php");
    exit;
}

require "../peminjaman/conf.php";

if (isset($_GET['id'])) {
    $nisn = $_GET['id'];

    // Query untuk menghapus data dari tabel tb_anggota berdasarkan NISN
    $query = "DELETE FROM tb_anggota WHERE nisn = '$nisn'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        // Redirect kembali ke halaman sebelumnya setelah menghapus data
        header("Location:anggota.php");
        exit;
    } else {
        echo "Gagal menghapus data: " . mysqli_error($conn);
    }
} else {
    echo "NISN tidak ditemukan.";
}
?>
