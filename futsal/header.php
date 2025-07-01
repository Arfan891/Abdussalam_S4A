<?php ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Penyewaan Futsal</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="/futsal/css/style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">FUTSAL</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <?php if (isset($_SESSION['user'])): ?>
          <?php if ($_SESSION['user']['role'] == 'admin'): ?>
            <li class="nav-item"><a class="nav-link" href="/futsal/admin/dashboard.php">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="/futsal/admin/kelola_lapangan.php">Lapangan</a></li>
            <li class="nav-item"><a class="nav-link" href="/futsal/admin/kelola_pesanan.php">Pesanan</a></li>
          <?php else: ?>
            <li class="nav-item"><a class="nav-link" href="/futsal/user/beranda.php">Beranda</a></li>
            <li class="nav-item"><a class="nav-link" href="/futsal/user/daftar_lapangan.php">Lapangan</a></li>
            <li class="nav-item"><a class="nav-link" href="/futsal/user/riwayat.php">Riwayat</a></li>
          <?php endif; ?>
          <li class="nav-item"><a class="nav-link text-danger" href="/futsal/logout.php">Logout</a></li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link" href="/futsal/login.php">Login</a></li>
          <li class="nav-item"><a class="nav-link" href="/futsal/register.php">Daftar</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
<div class="container mt-4">
