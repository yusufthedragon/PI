<?php
  include 'koneksi.php';
  $no = $_GET['no'];
  $query = $koneksi->prepare("SELECT nama_barang, harga FROM daftar_barang WHERE kode_barang='$no'");
  $query->execute();
  $inventori = $query->fetch();
  $data = array(
            'nama_barang' =>  $inventori['nama_barang'],
            'harga'       =>  $inventori['harga'],);
  echo json_encode($data);
?>
