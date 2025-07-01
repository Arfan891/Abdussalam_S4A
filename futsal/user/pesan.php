<?php
include '../koneksi.php';
session_start();

// Cek apakah user login dan role user
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'user') {
    header("Location: ../login.php");
    exit;
}

$uid = $_SESSION['user']['id'];

// Proses pemesanan
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $lapangan_id = $_POST['lapangan_id'];
    $tanggal     = $_POST['tanggal'];
    $jam_mulai   = $_POST['jam_mulai'];
    $jam_selesai = $_POST['jam_selesai'];

    // Simpan ke database
    $conn->query("INSERT INTO booking (user_id, lapangan_id, tanggal, jam_mulai, jam_selesai, status)
                  VALUES ('$uid', '$lapangan_id', '$tanggal', '$jam_mulai', '$jam_selesai', 'menunggu')");
    $sukses = true;
}

// Ambil data lapangan untuk pilihan
$lapangan = $conn->query("SELECT * FROM lapangan");
?>

<?php include '../header.php'; ?>

<h3>Pesan Lapangan</h3>

<?php if (isset($sukses)): ?>
  <div class="alert alert-success">Pemesanan berhasil dikirim. Menunggu konfirmasi admin.</div>
<?php endif; ?>

<form method="post" style="max-width: 600px;">
  <div class="mb-3">
    <label for="lapangan" class="form-label">Pilih Lapangan</label>
    <select name="lapangan_id" id="lapangan" class="form-select" required>
      <option value="">-- Pilih --</option>
      <?php while ($l = $lapangan->fetch_assoc()): ?>
        <option value="<?= $l['id'] ?>"><?= $l['nama_lapangan'] ?> - <?= $l['jenis'] ?> (Rp<?= number_format($l['harga_per_jam']) ?>/jam)</option>
      <?php endwhile; ?>
    </select>
  </div>

  <div class="mb-3">
    <label for="tanggal" class="form-label">Tanggal</label>
    <input type="date" name="tanggal" class="form-control" required>
  </div>

  <div class="row mb-3">
    <div class="col">
      <label class="form-label">Jam Mulai</label>
      <input type="time" name="jam_mulai" class="form-control" required>
    </div>
    <div class="col">
      <label class="form-label">Jam Selesai</label>
      <input type="time" name="jam_selesai" class="form-control" required>
    </div>
  </div>

  <button type="submit" class="btn btn-success">Pesan Sekarang</button>
</form>

<?php include '../footer.php'; ?>
