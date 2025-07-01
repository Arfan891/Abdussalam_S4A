<?php
session_start();
if (isset($_SESSION['user'])) {
  if ($_SESSION['user']['role'] == 'admin') {
    header("Location: admin/dashboard.php");
  } else {
    header("Location: user/beranda.php");
  }
  exit;
} else {
  header("Location: login.php");
  exit;
}
