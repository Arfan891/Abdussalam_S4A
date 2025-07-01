<?php
include '../koneksi.php';
session_start();

// Cek apakah user login dan role 'user'
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'user') {
    header("Location: ../login.php");
    exit;
}

$userId = $_SESSION['user']['id'];

// ❌ Batalkan pemesanan jika status masih "menunggu"
if (isset($_GET['batal'])) {
    $id = (int)$_GET['batal'];

    $stmt = $conn->prepare("DELETE FROM booking WHERE id = ? AND user_id = ? AND status = 'menunggu'");
    $stmt->bind_param("ii", $id, $userId);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo "<script>alert('✅ Pesanan berhasil dibatalkan.'); location.href='riwayat.php';</script>";
        } else {
            echo "<script>alert('⚠️ Gagal: Pesanan bukan milik Anda atau status sudah berubah.'); location.href='riwayat.php';</script>";
        }
    } else {
        echo "<script>alert('❌ Terjadi kesalahan SQL: " . $stmt->error . "');</script>";
    }
    exit;
}


// Ambil data riwayat user
$sql = "SELECT booking.*, lapangan.nama_lapangan 
        FROM booking 
        JOIN lapangan ON booking.lapangan_id = lapangan.id
        WHERE user_id=?
        ORDER BY tanggal DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$data = $stmt->get_result();
?>

<?php include '../header.php'; ?>

<h3>Riwayat Pemesanan</h3>

<table class="table table-bordered table-striped mt-3">
  <thead class="table-dark">
    <tr>
      <th>No</th>
      <th>Lapangan</th>
      <th>Tanggal</th>
      <th>Jam</th>
      <th>Status</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php $no = 1; while ($d = $data->fetch_assoc()): ?>
    <tr>
      <td><?= $no++ ?></td>
      <td><?= htmlspecialchars($d['nama_lapangan']) ?></td>
      <td><?= date('d/m/Y', strtotime($d['tanggal'])) ?></td>
      <td><?= substr($d['jam_mulai'], 0, 5) ?> - <?= substr($d['jam_selesai'], 0, 5) ?></td>
      <td>
        <span class="badge bg-<?= 
          $d['status'] == 'disetujui' ? 'success' : 
          ($d['status'] == 'ditolak' ? 'danger' : 'secondary') ?>">
          <?= ucfirst($d['status']) ?>
        </span>
      </td>
      <td>
        <?php if ($d['status'] == 'menunggu'): ?>
          <a href="?batal=<?= $d['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin membatalkan pesanan ini?')">Batal</a>
        <?php else: ?>
          <span class="text-muted">-</span>
        <?php endif; ?>
      </td>
    </tr>
    <?php endwhile; ?>
  </tbody>
</table>

<?php include '../footer.php'; ?>
