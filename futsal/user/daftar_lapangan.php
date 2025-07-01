<?php
include '../koneksi.php';
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'user') {
    header("Location: ../login.php");
    exit;
}

$lapangan = $conn->query("SELECT * FROM lapangan");
?>

<?php include '../header.php'; ?>

<h3>Daftar Lapangan</h3>

<div class="row">
  <?php while ($l = $lapangan->fetch_assoc()): ?>
    <div class="col-md-4">
      <div class="card mb-4">
        <?php if (!empty($l['gambar'])): ?>
          <img src="../uploads/<?= $l['gambar'] ?>" class="card-img-top" alt="<?= $l['nama_lapangan'] ?>">
        <?php else: ?>
          <img src="https://via.placeholder.com/400x200?text=Lapangan" class="card-img-top" alt="Placeholder">
        <?php endif; ?>
        <div class="card-body">
          <h5 class="card-title"><?= $l['nama_lapangan'] ?></h5>
          <p class="card-text">
            Jenis: <?= $l['jenis'] ?><br>
            Harga: Rp<?= number_format($l['harga_per_jam']) ?>/jam
          </p>
          <a href="pesan.php" class="btn btn-primary">Pesan Sekarang</a>
        </div>
      </div>
    </div>
  <?php endwhile; ?>
</div>

<?php include '../footer.php'; ?>
