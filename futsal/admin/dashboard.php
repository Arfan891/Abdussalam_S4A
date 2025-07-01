<?php
include '../koneksi.php';
session_start();

// Proteksi admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

// Ambil data jumlah
$jumlah_user     = $conn->query("SELECT COUNT(*) FROM users WHERE role = 'user'")->fetch_row()[0];
$jumlah_lapangan = $conn->query("SELECT COUNT(*) FROM lapangan")->fetch_row()[0];
$jumlah_booking  = $conn->query("SELECT COUNT(*) FROM booking")->fetch_row()[0];
?>

<?php include '../header.php'; ?>
<h3>Dashboard Admin</h3>

<div class="row mt-4">
  <div class="col-md-4">
    <div class="card text-bg-primary mb-3">
      <div class="card-body">
        <h5 class="card-title">Total User</h5>
        <p class="card-text fs-4"><?= $jumlah_user ?></p>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card text-bg-success mb-3">
      <div class="card-body">
        <h5 class="card-title">Lapangan Tersedia</h5>
        <p class="card-text fs-4"><?= $jumlah_lapangan ?></p>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card text-bg-warning mb-3">
      <div class="card-body">
        <h5 class="card-title">Total Pemesanan</h5>
        <p class="card-text fs-4"><?= $jumlah_booking ?></p>
      </div>
    </div>
  </div>
</div>
<?php include '../footer.php'; ?>
