<?php

session_start();
if(isset($_SESSION['admin'])) {
  $sessi = explode('#', $_SESSION['admin']);
  $sessiId = $sessi[0];
  $sessiSt = $sessi[1];

  if($sessiSt == 1) {
    //header('location: ?url=adminantrian');
  } else {
    header('location: ?url=rekammedis');
  }

  include_once 'Model/AdminModel.php';
  $adminModel = new AdminModel();

  if(isset($_POST['submitRegDokter'])) {
    if(empty($_POST['namadokter']) || empty($_POST['username']) || empty($_POST['password'])
    || empty($_POST['hari']) || empty($_POST['jadwaljam1']) || empty($_POST['jadwaljam2'])) {
      $pesan = 'Kolom tidak boleh kosong';
    } else {
      $nama = $_POST['namadokter'];
      $username = $_POST['username'];
      $password = sha1($_POST['password'], TRUE);
      $sekarang = date('Y-m-d h:i:s');
      $jadwal = strtolower($_POST['hari']).' '.$_POST['jadwaljam1'].' Wib s/d '.$_POST['jadwaljam2'].' Wib';

      $cekAdminByUsername = $adminModel->cekAdminByUsername($username, 1);
      if($cekAdminByUsername > 0) {
        // Ada
        $pesan = 'Username sudah terdaftar';
      } else {
        // Belum Ada
        $tambahAdmin = $adminModel->tambahAdmin($nama, $username, $password, 2, $jadwal, 1, $sekarang);
        if($tambahAdmin > 0) {
          header('location: ?url=adminjadwaldokter&msg=Pendaftaran Dokter Baru Berhasil!');
        } else {
          $pesan = 'Error Query tambahAdmin';
        }
      }
    }
  }

  $getAllAdminData = $adminModel->getAllAdminData(2);

  include_once 'View/Adminjadwaldokter.php';
} else {
  header('location: ?url=admin');
}
?>
