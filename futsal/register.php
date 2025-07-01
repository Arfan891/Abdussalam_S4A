<?php
include 'koneksi.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $nama     = $_POST['nama'];
  $username = $_POST['username'];
  $email    = $_POST['email'];
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

  $cek = $conn->query("SELECT * FROM users WHERE username='$username'");
  if ($cek->num_rows > 0) {
    $error = "Username sudah digunakan";
  } else {
    $conn->query("INSERT INTO users (nama, username, email, password, role) VALUES ('$nama','$username','$email','$password','user')");
    header("Location: login.php");
    exit;
  }
}
?>

<?php include 'header.php'; ?>
<h3>Daftar Akun</h3>
<?php if (isset($error)): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
<form method="post" style="max-width: 400px;">
  <label>Nama</label>
  <input type="text" name="nama" class="form-control" required>
  <label>Username</label>
  <input type="text" name="username" class="form-control" required>
  <label>Email</label>
  <input type="email" name="email" class="form-control" required>
  <label>Password</label>
  <input type="password" name="password" class="form-control" required>
  <button class="btn btn-primary mt-2">Daftar</button>
</form>
<?php include 'footer.php'; ?>
