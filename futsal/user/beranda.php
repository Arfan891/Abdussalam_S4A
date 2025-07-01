<?php
include '../koneksi.php';
session_start();

// Cek apakah sudah login dan role-nya user
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'user') {
    header("Location: ../login.php");
    exit;
}

$nama = $_SESSION['user']['nama'];
?>

<?php include '../header.php'; ?>

<div class="text-center">
  <h2>Selamat datang, <?= htmlspecialchars($nama) ?>!</h2>
  <p class="lead">Silakan pilih menu yang tersedia di bawah ini.</p>

  <div class="row justify-content-center mt-4">
    <div class="col-md-3">
      <a href="daftar_lapangan.php" class="btn btn-outline-primary w-100 mb-2">Daftar Lapangan</a>
    </div>
    <div class="col-md-3">
      <a href="pesan.php" class="btn btn-outline-success w-100 mb-2">Pesan Lapangan</a>
    </div>
    <div class="col-md-3">
      <a href="riwayat.php" class="btn btn-outline-info w-100 mb-2">Riwayat Pemesanan</a>
    </div>
  </div>
</div>

<?php include '../footer.php'; ?>
