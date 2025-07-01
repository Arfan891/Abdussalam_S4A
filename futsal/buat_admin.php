<?php
include 'koneksi.php';

$username = 'admin';
$password = password_hash('admin123', PASSWORD_DEFAULT);
$nama     = 'Admin';
$email    = 'admin@futsal.com';
$role     = 'admin';

// Cek apakah admin sudah ada
$cek = $conn->query("SELECT * FROM users WHERE username='$username'");
if ($cek->num_rows == 0) {
    $conn->query("INSERT INTO users (username, password, nama, email, role)
                  VALUES ('$username', '$password', '$nama', '$email', '$role')");
    echo "✅ Admin berhasil dibuat.";
} else {
    echo "⚠️ Admin sudah ada.";
}
?>