<?php
  include 'koneksi.php';

  session_start(); //Memulai session
  if (!isset($_SESSION['login'])) { //Jika session belum diset/user belum login
    header("location: login.php"); //Maka akan dialihkan ke halaman login
  }

  //Mengambil No.Transaksi yang dijadikan parameter
  $no_transaksi = $_GET['no_transaksi'];

  //Mengambil detail transaksi berdasarkan No. Transaksi yang dijadikan parameter
  $query = $koneksi->prepare("SELECT * FROM penjualan WHERE no_transaksi = :no_transaksi");
  $query->bindParam(':no_transaksi', $no_transaksi);
  $query->execute();
  $row = $query->fetch();
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Penjualan - Toko Zati Parts</title>
    <link rel="shortcut icon" href="images/logo.png" />
    <link rel="stylesheet" href="css/materialize.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css" />
    <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  </head>
  <body>
    <nav>
      <div class="nav-wrapper grey darken-3">
        <a href="index.php" class="brand-logo center">TOKO ZATI PARTS</a>
      </div>
    </nav>
    <div id="keterangan"></div>
    <div class="container">
      <h3 class="center">DETAIL PENJUALAN</h3>
      <div class="row">
        <div class="col s12">
          No. Transaksi :
          <div class="input-field inline">
            <input type="text" class="validate" id="no_transaksi" value="<?php echo $row['no_transaksi']; ?>" readonly />
          </div>
        </div>
        <div class="col s12">
          Tanggal :
          <div class="input-field inline">
            <input type="text" id="datepicker" name="tanggal" value="<?php echo $row['tanggal']; ?>" readonly />
          </div>
        </div>
        <div class="col s12">
          Nama Konsumen :
          <div class="input-field inline">
            <input type="text" class="validate" name="faktur" value="<?php echo $row['nama']; ?>" readonly />
          </div>
        </div>
        <?php
        //Jika ada data Alamat di record
        if($row['alamat'] != "") {
          echo "<div class='col s12'>
                  Alamat Konsumen :
                  <div class='input-field inline'>
                    <input type='text' class='validate' value='".$row['alamat']."' style='width:320px;' readonly />
                  </div>
                </div>";
        }

        //Jika ada data Kurir di record
        if ($row['kurir'] != "") {
        echo "<div class='col s12'>
                Kurir Pengiriman :
                <div class='input-field inline'>
                  <input type='text' class='validate' value='".$row['kurir']."' readonly />
                </div>
              </div>";
        }

        //Jika ada data Ongkir di record
        if($row['ongkir'] != 0) {
          echo "<div class='col s12'>
                  Ongkos Kirim : Rp.
                  <div class='input-field inline'>
                    <input type='text' class='validate' value='".number_format($row['ongkir'], 0, '', '.')."' readonly />
                  </div>
                </div>";
        }

        //Jika ada data No. Resi di record
        if($row['no_resi'] != "") {
          echo "<div class='col s12'>
                  No. Resi :
                  <div class='input-field inline'>
                    <input type='text' class='validate' value='".$row['no_resi']."' readonly />
                  </div>
                </div>";
        }
        ?>
      </div>
      <div class="row">
        <div class="col s12">
          Daftar Pembelian :
          <table class="centered bordered">
            <thead>
              <tr>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Jumlah</th>
              </tr>
            </thead>
            <tbody>
              <?php
                //Mengambil data barang yang dijual
                $query2 = $koneksi->prepare("SELECT * FROM pengaruh WHERE no_transaksi = :no_transaksi");
                $query2->bindParam(':no_transaksi', $no_transaksi);
                $query2->execute();

                while($row2 = $query2->fetch()) {
                  echo "<tr>
                  <td>".$row2['kode_barang']."</td>
                  <td>".$row2['nama_barang']."</td>
                  <td>".$row2['jumlah']."</td>
                  </tr>";
                }
              ?>
            </tbody>
          </table>
        </div>
        <div class="row"></div>
        <div class="col s12">
          Total Pembelian : Rp.
          <div class="input-field inline">
            <input type="text" class="validate" value="<?php echo number_format($row['total'], 0, '', '.'); ?>" readonly />
          </div>
        </div>
      </div>
      <div class="row"></div>
      <div class="row"></div>
      <div class="row">
        <div class="col s12 m4 l4 center">
          <a class="waves-effect waves-light btn red" onclick="hapus()"><i class="material-icons left">delete</i>Hapus Transaksi</a>
          <div class="row"></div>
        </div>
        <div class="col s12 m4 l4 center">
          <a class="waves-effect waves-light btn green accent-4" <?php echo "href='edit_penjualan.php?no_transaksi=".$row['no_transaksi']."'"; ?>><i class="material-icons left">edit</i>Edit Transaksi</a>
          <div class="row"></div>
        </div>
        <div class="col s12 m4 l4 center">
          <a class="waves-effect waves-light btn blue darken-1" href="daftar_penjualan.php"><i class="material-icons left">arrow_forward</i>Kembali</a>
          <div class="row"></div>
        </div>
      </div>
    </div>
    <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
    <script type="text/javascript" src="js/materialize.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
    <script>
      function hapus() {
        swal({
          title: "Anda yakin?",
          text: "Data Transaksi tersebut akan dihapus dari database! <br />Data Barang yang telah dibeli akan dibatalkan!",
          html: true,
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#DD6B55",
          confirmButtonText: "Ya, saya yakin!",
          cancelButtonText: "Batal",
          closeOnConfirm: true
        }, function(isConfirm) {
          if (isConfirm) {
            var no_transaksi = $("#no_transaksi").val();
            $.ajax({
              url: 'ajax_hapus_penjualan.php',
              dataType: "html",
              data: 'no_transaksi=' + no_transaksi,
            }).success(function(data) {
              $('#keterangan').html(data);
            });
          }
        });
      }
    </script>
  </body>
</html>
