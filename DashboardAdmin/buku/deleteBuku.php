<?php 
require "../../config/config.php";
$id_buku = $_GET["id_buku"];
//var_dump($bukuId); die;

if(delete($id_buku) > 0) {
  echo "
  <script>
  alert('Data buku berhasil dihapus');
  document.location.href = 'daftarBuku.php';
  </script>";
}else {
  echo "
  <script>
  alert('Data buku gagal dihapus');
  document.location.href = 'daftarBuku.php';
  </script>";
}
?>