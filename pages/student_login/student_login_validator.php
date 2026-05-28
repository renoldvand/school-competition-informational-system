<?php
  session_start();
  require_once "../../sql/sql_bridge.php";

  $nis = trim($_POST["nis"] ?? "");
  $password = $_POST["password"] ?? "";

  // Validasi dasar
  if ($nis === "" || $password === "") {
    header("Location: student_login.php?error=" . urlencode("Semua field wajib diisi"));
    exit;
  }

  if ($nis <= 0) {
    header("Location: student_login.php?error=" . urlencode("NIS harus lebih dari 0"));
    exit;
  }

  try {
    // Cari siswa di database
    $nis_safe = mysqli_real_escape_string(connect_sql(), $nis);
    $rows = select_sql("SELECT * FROM students_table WHERE nis = '$nis_safe'");

    if (empty($rows)) {
      header("Location: student_login.php?error=" . urlencode("NIS tidak terdaftar"));
      exit;
    }

    $student = $rows[0];

    // Bandingkan password (plain text, sesuai data seed)
    if ($password !== $student["acc_password"]) {
      header("Location: student_login.php?error=" . urlencode("Kata sandi salah"));
      exit;
    }

    // Login berhasil — simpan session
    $_SESSION["nis"] = $student["nis"];
    $_SESSION["name"] = $student["full_name"];
    $_SESSION["class"] = $student["class"];

    // Update last_logged_on
    execute_sql("UPDATE students_table SET last_logged_on = NOW() WHERE nis = '$nis_safe'");

    // Redirect ke profil
    header("Location: ../student_profile/student_profile.php?nis=" . urlencode($nis));
    exit;

  } catch (Exception $e) {
    header("Location: student_login.php?error=" . urlencode("Terjadi kesalahan sistem"));
    exit;
  }
?>