<?php
  session_start();
  require_once "../../sql/sql_bridge.php";

  $username = trim($_POST["username"] ?? "");
  $password = $_POST["password"] ?? "";

  if ($username === "" || $password === "") {
    header("Location: admin_login.php?error=" . urlencode("Semua field wajib diisi"));
    exit;
  }

  try {
    $conn = connect_sql();
    $user_safe = mysqli_real_escape_string($conn, $username);
    $rows = select_sql("SELECT * FROM admins_table WHERE username = '$user_safe'");

    if (empty($rows)) {
      header("Location: admin_login.php?error=" . urlencode("Username tidak ditemukan"));
      exit;
    }

    $admin = $rows[0];
    if ($password !== $admin["password"]) {
      header("Location: admin_login.php?error=" . urlencode("Kata sandi salah"));
      exit;
    }

    $_SESSION["admin_id"] = $admin["id"];
    $_SESSION["admin_username"] = $admin["username"];

    header("Location: ../admin_dashboard/admin_dashboard.php");
    exit;

  } catch (Exception $e) {
    header("Location: admin_login.php?error=" . urlencode("Terjadi kesalahan sistem"));
    exit;
  }
?>