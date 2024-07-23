<?php
session_start();

if (!isset($_SESSION["signIn"])) {
    header("Location: ../../sign/admin/sign_in.php");
    exit;
}
require "../peminjaman/conf.php";

// Function to fetch members data
function fetchMembers() {
    global $conn;
    $query = "SELECT * FROM tb_anggota";
    $result = mysqli_query($conn, $query);
    $members = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $members[] = $row;
    }
    return $members;
}

// Function to search members
function searchMember($keyword) {
    global $conn;
    $query = "SELECT * FROM tb_anggota WHERE nama_anggota LIKE '%$keyword%'";
    $result = mysqli_query($conn, $query);
    $members = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $members[] = $row;
    }
    return $members;
}

// Function to delete a member
function deleteMember($id) {
    global $conn;
    $query = "DELETE FROM tb_anggota WHERE nisn = '$id'";
    $result = mysqli_query($conn, $query);
    if ($result) {
        return true; // Deletion successful
    } else {
        return false; // Deletion failed
    }
}

// Check if delete action is triggered
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $deleteId = $_GET['id'];
    $deleteResult = deleteMember($deleteId);
    if ($deleteResult) {
        // Redirect to avoid resubmission on refresh
        header("Location: ".$_SERVER['PHP_SELF']);
        exit;
    } else {
        // Handle deletion failure
        echo "Failed to delete member.";
    }
}

// Check if search form is submitted
if (isset($_POST["search"])) {
    $member = searchMember($_POST["keyword"]);
} else {
    // Fetch all members initially
    $member = fetchMembers();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/de8de52639.js" crossorigin="anonymous"></script>
    <title>Member Terdaftar</title>
</head>
<body>
<nav class="navbar fixed-top bg-body-tertiary shadow-sm">
    <div class="container-fluid p-3">
        <a class="navbar-brand" href="#">
            <img src="../../assets/logoNav.png" alt="logo" width="120px">
        </a>
        <a class="btn btn-tertiary" href="../dashboardAdmin.php">Dashboard</a>
    </div>
</nav>

<div class="p-4 mt-5">
    <!-- Search form -->
    <form action="" method="post" class="mt-5">
        <div class="input-group d-flex justify-content-end mb-3">
            <input class="border p-2 rounded rounded-end-0 bg-tertiary" type="text" name="keyword" id="keyword" placeholder="Cari data member...">
            <button class="border border-start-0 bg-light rounded rounded-start-0" type="submit" name="search"><i class="fa-solid fa-magnifying-glass"></i></button>
        </div>
    </form>

    <!-- Table of members -->
    <caption>List of Members</caption>
    <div class="table-responsive mt-3">
        <table class="table table-striped table-hover">
            <thead class="text-center">
            <tr>
                <th class="bg-primary text-light">kode anggota</th>
                <th class="bg-primary text-light">nama anggota</th>
                <th class="bg-primary text-light">password</th>
                <th class="bg-primary text-light">Jenis Kelamin</th>
                <th class="bg-primary text-light">alamat anggota</th>
                <th class="bg-primary text-light">tlp anggota</th>
                <th class="bg-primary text-light">tempat lahir</th>
                <th class="bg-primary text-light">tanggal lahir</th>
                <th class="bg-primary text-light">Edit</th>
                <th class="bg-primary text-light">Delete</th>
            </tr>
            </thead>
            <?php foreach ($member as $item) : ?>
                <tr>
                    <td><?= $item["kode_anggota"]; ?></td>
                    <td><?= $item["nama_anggota"]; ?></td>
                    <td><?= $item["password"]; ?></td>
                    <td><?= $item["jenis_kelamin"]; ?></td>
                    <td><?= $item["alamat_anggota"]; ?></td>
                    <td><?= $item["tlp_anggota"]; ?></td>
                    <td><?= $item["temapat_lahir"]; ?></td>
                    <td><?= $item["tanggal_lahir"]; ?></td>
                    <td>
                        <div class="action">
                            <a href="editMember.php?id=<?= $item["kode_anggota"]; ?>" class="btn btn-primary"><i class="fa-solid fa-pencil"></i></a>
                        </div>
                    </td>
                    <td>
                        <div class="action">
                            <a href="?action=delete&id=<?= $item["kode_anggota"]; ?>" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus data member ?');"><i class="fa-solid fa-trash"></i></a>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>

<footer class="fixed-bottom shadow-lg bg-subtle p-3">
    <div class="container-fluid d-flex justify-content-between">
        <p class="mt-2">Created by <span class="text-primary">Mangandaralam Sakti</span> © 2023</p>
        <p class="mt-2">versi 1.0</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>
