<?php
 session_start(); //Memulai session
 if (!isset($_SESSION['login'])) { //Jika session belum diset/user belum login
   header("location: login.php"); //Maka akan dialihkan ke halaman login
 }
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Barang - Toko Zati Parts</title>
    <link rel="shortcut icon" href="images/logo.png" />
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css" />
    <link rel="stylesheet" href="css/materialize.css" />
    <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  </head>
  <body>
    <nav>
      <div class="nav-wrapper grey darken-3">
        <a href="index.php" class="brand-logo center">TOKO ZATI PARTS</a>
      </div>
    </nav>
    <div class="container">
      <h3 class="center">EDIT BARANG</h3>
      <div class="row">
        <form name="myform">
          <div class="col s12">
            Masukkan Kode Barang Lama:
            <div class="input-field inline">
              <input type="text" name="kodelama" id="kodelama" class="validate" onkeyup="upperCaseF(this), autofill()" autocomplete="off" />
            </div>
          </div>
          <div class="col s12">
            Masukkan Kode Barang Baru:
            <div class="input-field inline">
              <input type="text" name="kodebaru" id="kodebaru" class="validate" onkeydown="upperCaseF(this)" />
            </div>
          </div>
          <div class="col s12">
            Masukkan Nama Barang:
            <div class="input-field inline">
              <input type="text" name="nama" id="nama" class="validate" onkeydown="upperCaseF(this)" autocomplete="off" />
            </div>
          </div>
          <div class="col s12">
            Masukkan Harga Barang: Rp.
            <div class="input-field inline">
              <input type="text" name="harga" id="harga" class="validate" />
            </div>
          </div>
          <div class="col s12">
            Masukkan Jumlah Barang:
            <div class="input-field inline">
              <input type="text" name="jumlah" id="jumlah" class="validate" />
            </div>
          </div>
        </form>
      </div>
      <div class="row"></div>
      <div class="row">
        <div class="col s6 l6 center">
          <a class="waves-effect waves-light btn green accent-4" onclick="edit()"><i class="material-icons left">edit</i>PERBARUI</a>
        </div>
        <div class="col s6 l6 center">
          <a class="waves-effect waves-light btn blue darken-1" onclick="batal()"><i class="material-icons left">cancel</i>BATAL</a>
        </div>
      </div>
      <div class="row"></div>
    </div>
    <div id="keterangan"></div>
    <script type="text/javascript" src="js/materialize.js"></script>
    <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
    <script type="text/javascript" src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
    <script type="text/javascript">
      function upperCaseF(a) { //Fungsi untuk membuat input kapital secara otomatis
        setTimeout(function() {
          a.value = a.value.toUpperCase();
        }, 1);
      }

      $(function() { //Fungsi untuk mengambil daftar barang dari database
        $("#kodelama").autocomplete({ //dan mempopulasikannya di input kode barang secara otomatis
          source: 'search_barang_pembelian.php'
        });
      });

      function autofill() { //Fungsi untuk mengisi form Nama, Harga, dan Jumlah secara otomatis
        var no = $("#kodelama").val();
        $.ajax({
          url: 'ajax_barang.php',
          dataType: "html",
          data: "no=" + no,
        }).success(function(data) {
          var json = data,
            obj = JSON.parse(json);
          $('#nama').val(obj.nama_barang);
          $('#harga').val(obj.harga);
          $('#jumlah').val(obj.stok);
        });
      }

      function edit() {
        //Memeriksa apakah semua form terisi
        if ((myform.kodelama.value == "") || (myform.nama.value == "") || (myform.harga.value == "") || (myform.jumlah.value == "")) {
          swal({
             title: "Error!",
             text: "Harap mengisi seluruh data!",
             timer: 2000,
             type: "error"
           });
        } else {
          swal({
            title: "Anda yakin?",
            text: "Semua data yang telah dimasukkan akan masuk ke database!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Ya, saya yakin!",
            cancelButtonText: "Batal",
            closeOnConfirm: true
          }, function(isConfirm) {
            if (isConfirm) {
              var kodelama = $("#kodelama").val();
              var kodebaru = $("#kodebaru").val();
              if (kodebaru == "") {
                kodebaru = kodelama;
              }
              var nama = $("#nama").val();
              var harga = $("#harga").val();
              var jumlah = $("#jumlah").val();
              $.ajax({
                url: 'ajax_edit_barang.php',
                dataType: "html",
                data: {'kodelama': kodelama, 'kodebaru': kodebaru, 'nama': nama, 'harga': harga, 'jumlah': jumlah},
              }).success(function(data) {
                $('#keterangan').html(data);
              });
            }
          });
        }
      }

      function batal() {
        //Memeriksa apakah semua form kosong
        if ((myform.kodelama.value != "") || (myform.kodebaru.value != "") || (myform.nama.value != "") || (myform.harga.value != "") || (myform.jumlah.value != "")) {
          swal({
            title: "Anda yakin?",
            text: "Semua data yang telah dimasukkan akan hilang!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Ya, saya yakin!",
            cancelButtonText: "Batal",
            closeOnConfirm: false
          }, function(isConfirm) {
            if (isConfirm) {
              window.location = "daftar_barang.php";
            }
          });
        } else {
          window.location = "daftar_barang.php";
        }
      }
    </script>
  </body>
</html>
