<?php
$host = "127.0.0.1";
$username = "root";
$password = "";
$database_name = "perpustakaan";
$connection = mysqli_connect($host, $username, $password, $database_name);

// MENAMPILKAN DATA KATEGORI BUKU
function queryReadData($dataKategori) {
    global $connection;
    $result = mysqli_query($connection, $dataKategori);
    $items = [];            
    while($item = mysqli_fetch_assoc($result)) {
        $items[] = $item;
    }
    return $items;
}

// Menambahkan data buku 
function tambahBuku($dataBuku) {
    global $connection;
    
    $cover = upload();
    if (!$cover) {
        return 0;
    }

    $idBuku = htmlspecialchars($dataBuku["id_buku"]);
    $kategoriBuku = htmlspecialchars($dataBuku["kategori"]);
    $judulBuku = htmlspecialchars($dataBuku["judul"]);
    $pengarangBuku = htmlspecialchars($dataBuku["pengarang"]);
    $penerbitBuku = htmlspecialchars($dataBuku["penerbit"]);
    $tahunTerbit = (int) $dataBuku["tahun_terbit"];
    $jumlahHalaman = (int) $dataBuku["jumlah_halaman"];
    $deskripsiBuku = htmlspecialchars($dataBuku["buku_deskripsi"]);
    
    $queryInsertDataBuku = "INSERT INTO buku (cover, id_buku, kategori, judul, pengarang, penerbit, tahun_terbit, jumlah_halaman, buku_deskripsi)
                            VALUES ('$cover', '$idBuku', '$kategoriBuku', '$judulBuku', '$pengarangBuku', '$penerbitBuku', $tahunTerbit, $jumlahHalaman, '$deskripsiBuku')";
    
    mysqli_query($connection, $queryInsertDataBuku);
    return mysqli_affected_rows($connection);
}

// Function upload gambar 
function upload() {
    $namaFile = $_FILES["cover"]["name"];
    $ukuranFile = $_FILES["cover"]["size"];
    $error = $_FILES["cover"]["error"];
    $tmpName = $_FILES["cover"]["tmp_name"];
    
    // cek apakah ada gambar yg diupload
    if ($error === 4) {
        echo "<script>
        alert('Silahkan upload cover buku terlebih dahulu!');
        </script>";
        return 0;
    }
    
    // cek kesesuaian format gambar
    $formatGambarValid = ["jpg", "jpeg", "png", "svg", "bmp", "psd", "tiff"];
    $ekstensiGambar = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));
    
    if (!in_array($ekstensiGambar, $formatGambarValid)) {
        echo "<script>
        alert('Format file tidak sesuai');
        </script>";
        return 0;
    }
    
    // batas ukuran file
    if ($ukuranFile > 2000000) {
        echo "<script>
        alert('Ukuran file terlalu besar!');
        </script>";
        return 0;
    }
    
    //generate nama file baru, agar nama file tdk ada yg sama
    $namaFileBaru = uniqid() . '.' . $ekstensiGambar;
    move_uploaded_file($tmpName, '../../imgDB/' . $namaFileBaru);
    return $namaFileBaru;
}

// MENAMPILKAN SESUATU SESUAI DENGAN INPUTAN USER PADA * SEARCH ENGINE *
function search($keyword) {
    $querySearch = "SELECT * FROM buku 
                    WHERE judul LIKE '%$keyword%' OR
                          kategori LIKE '%$keyword%'";
    return queryReadData($querySearch);
}

function searchMember($keyword) {
    $searchMember = "SELECT * FROM member 
                     WHERE nisn LIKE '%$keyword%' OR 
                           kode_member LIKE '%$keyword%' OR
                           nama LIKE '%$keyword%' OR 
                           jurusan LIKE '%$keyword%'";
    return queryReadData($searchMember);
}

// DELETE DATA Buku
function delete($bukuId) {
    global $connection;
    $queryDeleteBuku = "DELETE FROM buku WHERE id_buku = '$bukuId'";
    mysqli_query($connection, $queryDeleteBuku);
    return mysqli_affected_rows($connection);
}

// UPDATE || EDIT DATA BUKU 
function updateBuku($dataBuku) {
    global $connection;

    $gambarLama = htmlspecialchars($dataBuku["coverLama"]);
    $idBuku = htmlspecialchars($dataBuku["id_buku"]);
    $kategoriBuku = htmlspecialchars($dataBuku["kategori"]);
    $judulBuku = htmlspecialchars($dataBuku["judul"]);
    $pengarangBuku = htmlspecialchars($dataBuku["pengarang"]);
    $penerbitBuku = htmlspecialchars($dataBuku["penerbit"]);
    $tahunTerbit = (int) $dataBuku["tahun_terbit"];
    $jumlahHalaman = (int) $dataBuku["jumlah_halaman"];
    $deskripsiBuku = htmlspecialchars($dataBuku["buku_deskripsi"]);
    
    // pengecekan mengganti gambar || tidak
    if ($_FILES["cover"]["error"] === 4) {
        $cover = $gambarLama;
    } else {
        $cover = upload();
    }
    
    $queryUpdate = "UPDATE buku SET 
                    cover = '$cover',
                    kategori = '$kategoriBuku',
                    judul = '$judulBuku',
                    pengarang = '$pengarangBuku',
                    penerbit = '$penerbitBuku',
                    tahun_terbit = $tahunTerbit,
                    jumlah_halaman = $jumlahHalaman,
                    buku_deskripsi = '$deskripsiBuku'
                    WHERE id_buku = '$idBuku'";
    
    mysqli_query($connection, $queryUpdate);
    return mysqli_affected_rows($connection);
}

// Hapus member yang terdaftar
function deleteMember($nisnMember) {
    global $connection;
    $deleteMember = "DELETE FROM member WHERE nisn = $nisnMember";
    mysqli_query($connection, $deleteMember);
    return mysqli_affected_rows($connection);
}

// Hapus history pengembalian data BUKU
function deleteDataPengembalian($idPengembalian) {
    global $connection;
    $deleteDataPengembalianBuku = "DELETE FROM pengembalian WHERE id_pengembalian = $idPengembalian";
    mysqli_query($connection, $deleteDataPengembalianBuku);
    return mysqli_affected_rows($connection);
}

// Peminjaman BUKU
function pinjamBuku($dataBuku) {
    global $connection;
    
    $idBuku = htmlspecialchars($dataBuku["id_buku"]);
    $nisn = htmlspecialchars($dataBuku["nisn"]);
    $idAdmin = htmlspecialchars($dataBuku["id"]);
    $tglPinjam = htmlspecialchars($dataBuku["tgl_peminjaman"]);
    $tglKembali = htmlspecialchars($dataBuku["tgl_pengembalian"]);
    
    // cek apakah user memiliki denda 
    $cekDenda = mysqli_query($connection, "SELECT denda FROM pengembalian WHERE nisn = $nisn AND denda > 0");
    if (mysqli_num_rows($cekDenda) > 0) {
        $item = mysqli_fetch_assoc($cekDenda);
        $jumlahDenda = $item["denda"];
        if ($jumlahDenda > 0) {
            echo "<script>
            alert('Anda belum melunasi denda, silahkan lakukan pembayaran terlebih dahulu!');
            </script>";
            return 0;
        }
    }
    
    // cek batas user meminjam buku berdasarkan nisn
    $nisnResult = mysqli_query($connection, "SELECT nisn FROM peminjaman WHERE nisn = $nisn");
    if (mysqli_fetch_assoc($nisnResult)) {
        echo "<script>
        alert('Anda sudah meminjam buku, Harap kembalikan dahulu buku yg anda pinjam!');
        </script>";
        return 0;
    }
    
    $queryPinjam = "INSERT INTO peminjaman VALUES(null, '$idBuku', $nisn, $idAdmin, '$tglPinjam', '$tglKembali')";
    mysqli_query($connection, $queryPinjam);
    return mysqli_affected_rows($connection);
}

// Pengembalian BUKU
function pengembalian($dataBuku) {
    global $connection;
    
    // Variabel pengembalian
    $idPeminjaman = htmlspecialchars($dataBuku["id_peminjaman"]);
    $idBuku = htmlspecialchars($dataBuku["id_buku"]);
    $nisn = htmlspecialchars($dataBuku["nisn"]);
    $idAdmin = htmlspecialchars($dataBuku["id_admin"]);
    $tenggatPengembalian = htmlspecialchars($dataBuku["tgl_pengembalian"]);
    $bukuKembali = htmlspecialchars($dataBuku["buku_kembali"]);
    $keterlambatan = htmlspecialchars($dataBuku["keterlambatan"]);
    $denda = (int) $dataBuku["denda"];
    
    if ($bukuKembali > $tenggatPengembalian) {
        echo "<script>
        alert('Anda terlambat mengembalikan buku, harap bayar denda sesuai dengan jumlah yang ditentukan!');
        </script>";
    }
    
    // Menghapus data siswa yang sudah mengembalikan buku
    $hapusDataPeminjam = "DELETE FROM peminjaman WHERE id_peminjaman = $idPeminjaman";

    // Memasukkan data ke dalam tabel pengembalian
    $queryPengembalian = "INSERT INTO pengembalian VALUES(null, $idPeminjaman, '$idBuku', $nisn, $idAdmin, '$bukuKembali', '$keterlambatan', $denda)";
    
    mysqli_query($connection, $hapusDataPeminjam);
    mysqli_query($connection, $queryPengembalian);
    return mysqli_affected_rows($connection);
}

function bayarDenda($data) {
    global $connection;
    $idPengembalian = htmlspecialchars($data["id_pengembalian"]);
    $jmlDenda = (int) $data["denda"];
    $jmlDibayar = (int) $data["bayarDenda"];
    $calculate = $jmlDenda - $jmlDibayar;
    
    $bayarDenda = "UPDATE pengembalian SET denda = $calculate WHERE id_pengembalian = $idPengembalian";
    mysqli_query($connection, $bayarDenda);
    return mysqli_affected_rows($connection);
}
?>
