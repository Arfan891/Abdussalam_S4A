<?php
include '../koneksi.php';
session_start();

// Hanya admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

// Update status manual via POST
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ubah_status'])) {
    $id = (int)$_POST['id_booking'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE booking SET status=? WHERE id=?");
    $stmt->bind_param("si", $status, $id);
    if (!$stmt->execute()) {
        die("Gagal ubah status: " . $stmt->error);
    }
}

// Filter
$statusFilter = $_GET['status'] ?? '';
$tanggalFilter = $_GET['tanggal'] ?? '';

$query = "SELECT booking.*, users.nama, lapangan.nama_lapangan 
          FROM booking
          JOIN users ON booking.user_id = users.id
          JOIN lapangan ON booking.lapangan_id = lapangan.id
          WHERE 1";

$params = [];
$types = "";

if ($statusFilter !== '') {
    $query .= " AND booking.status = ?";
    $params[] = $statusFilter;
    $types .= "s";
}
if ($tanggalFilter !== '') {
    $query .= " AND booking.tanggal = ?";
    $params[] = $tanggalFilter;
    $types .= "s";
}

$query .= " ORDER BY booking.tanggal DESC";
$stmt = $conn->prepare($query);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$data = $stmt->get_result();
?>

<?php include '../header.php'; ?>
<h3>Kelola Pemesanan</h3>

<!-- Filter -->
<form method="get" class="row g-2 mb-3">
  <div class="col-md-3">
    <input type="date" name="tanggal" value="<?= htmlspecialchars($tanggalFilter) ?>" class="form-control">
  </div>
  <div class="col-md-3">
    <select name="status" class="form-select">
      <option value="">Semua Status</option>
      <option value="menunggu" <?= $statusFilter == 'menunggu' ? 'selected' : '' ?>>Menunggu</option>
      <option value="disetujui" <?= $statusFilter == 'disetujui' ? 'selected' : '' ?>>Disetujui</option>
      <option value="ditolak" <?= $statusFilter == 'ditolak' ? 'selected' : '' ?>>Ditolak</option>
    </select>
  </div>
  <div class="col-md-2">
    <button class="btn btn-primary w-100">Filter</button>
  </div>
  <div class="col-md-2">
    <a href="kelola_pesanan.php" class="btn btn-secondary w-100">Reset</a>
  </div>
</form>

<table class="table table-bordered">
  <thead class="table-dark">
    <tr>
      <th>No</th>
      <th>Nama Pemesan</th>
      <th>Lapangan</th>
      <th>Tanggal</th>
      <th>Jam</th>
      <th>Status</th>
      <th>Ubah Status</th>
    </tr>
  </thead>
  <tbody>
    <?php $no = 1; while ($d = $data->fetch_assoc()): ?>
    <tr>
      <td><?= $no++ ?></td>
      <td><?= htmlspecialchars($d['nama']) ?></td>
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
        <form method="post" class="d-flex">
          <input type="hidden" name="id_booking" value="<?= $d['id'] ?>">
          <select name="status" class="form-select form-select-sm me-1">
            <option value="menunggu" <?= $d['status'] == 'menunggu' ? 'selected' : '' ?>>Menunggu</option>
            <option value="disetujui" <?= $d['status'] == 'disetujui' ? 'selected' : '' ?>>Disetujui</option>
            <option value="ditolak" <?= $d['status'] == 'ditolak' ? 'selected' : '' ?>>Ditolak</option>
          </select>
          <button type="submit" name="ubah_status" class="btn btn-sm btn-primary">Ubah</button>
        </form>
      </td>
    </tr>
    <?php endwhile; ?>
  </tbody>
</table>

<?php include '../footer.php'; ?>
