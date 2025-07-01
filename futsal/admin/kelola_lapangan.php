<?php
include '../koneksi.php';
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

// Ambil data lapangan jika mode edit
$editData = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $edit = $conn->query("SELECT * FROM lapangan WHERE id=$id");
    if ($edit->num_rows > 0) {
        $editData = $edit->fetch_assoc();
    }
}

// Proses simpan edit
if (isset($_POST['simpan'])) {
    $id    = $_POST['id'];
    $nama  = $_POST['nama'];
    $jenis = $_POST['jenis'];
    $harga = $_POST['harga'];
    $gambar = $_POST['gambar_lama'];

    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        $gambar = uniqid() . '_' . $_FILES['gambar']['name'];
        move_uploaded_file($_FILES['gambar']['tmp_name'], "../uploads/$gambar");
    }

    $conn->query("UPDATE lapangan SET 
        nama_lapangan='$nama',
        jenis='$jenis',
        harga_per_jam='$harga',
        gambar='$gambar'
        WHERE id=$id");

    header("Location: kelola_lapangan.php");
    exit;
}

// Proses tambah lapangan baru
if (isset($_POST['tambah'])) {
    $nama  = $_POST['nama'];
    $jenis = $_POST['jenis'];
    $harga = $_POST['harga'];
    $gambar = '';

    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        $gambar = uniqid() . '_' . $_FILES['gambar']['name'];
        move_uploaded_file($_FILES['gambar']['tmp_name'], "../uploads/$gambar");
    }

    $conn->query("INSERT INTO lapangan (nama_lapangan, jenis, harga_per_jam, gambar)
                  VALUES ('$nama', '$jenis', '$harga', '$gambar')");
}

// Hapus lapangan
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $conn->query("DELETE FROM lapangan WHERE id=$id");
    header("Location: kelola_lapangan.php");
    exit;
}

// Ambil semua data lapangan
$data = $conn->query("SELECT * FROM lapangan");
?>

<?php include '../header.php'; ?>
<h3>Kelola Lapangan</h3>

<!-- Form Tambah/Edit -->
<form method="post" enctype="multipart/form-data" class="row g-3 mt-2 mb-4">
  <input type="hidden" name="id" value="<?= $editData['id'] ?? '' ?>">
  <input type="hidden" name="gambar_lama" value="<?= $editData['gambar'] ?? '' ?>">

  <div class="col-md-3">
    <input type="text" name="nama" class="form-control" placeholder="Nama Lapangan" required
      value="<?= $editData['nama_lapangan'] ?? '' ?>">
  </div>
  <div class="col-md-3">
    <input type="text" name="jenis" class="form-control" placeholder="Jenis" required
      value="<?= $editData['jenis'] ?? '' ?>">
  </div>
  <div class="col-md-2">
    <input type="number" name="harga" class="form-control" placeholder="Harga per jam" required
      value="<?= $editData['harga_per_jam'] ?? '' ?>">
  </div>
  <div class="col-md-2">
    <input type="file" name="gambar" class="form-control">
    <?php if (!empty($editData['gambar'])): ?>
      <small>Gambar saat ini: <?= $editData['gambar'] ?></small>
    <?php endif; ?>
  </div>
  <div class="col-md-2">
    <button type="submit" name="<?= $editData ? 'simpan' : 'tambah' ?>" class="btn btn-<?= $editData ? 'warning' : 'success' ?> w-100">
      <?= $editData ? 'Simpan' : 'Tambah' ?>
    </button>
  </div>
</form>

<!-- Tabel Lapangan -->
<table class="table table-bordered">
  <thead class="table-dark">
    <tr>
      <th>No</th>
      <th>Nama</th>
      <th>Jenis</th>
      <th>Harga</th>
      <th>Gambar</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php $no = 1; while ($d = $data->fetch_assoc()): ?>
    <tr>
      <td><?= $no++ ?></td>
      <td><?= $d['nama_lapangan'] ?></td>
      <td><?= $d['jenis'] ?></td>
      <td>Rp<?= number_format($d['harga_per_jam']) ?></td>
      <td>
        <?php if ($d['gambar']): ?>
          <img src="../uploads/<?= $d['gambar'] ?>" width="100">
        <?php endif; ?>
      </td>
      <td>
        <a href="?edit=<?= $d['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
        <a href="?hapus=<?= $d['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus lapangan ini?')">Hapus</a>
      </td>
    </tr>
    <?php endwhile; ?>
  </tbody>
</table>

<?php include '../footer.php'; ?>
