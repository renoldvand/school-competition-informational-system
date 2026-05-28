<?php
  session_start();
  require_once "../../sql/sql_bridge.php";

  $nis = trim($_POST["nis"] ?? "");
  $full_name = trim($_POST["full_name"] ?? "");
  $major = trim($_POST["major"] ?? "");
  $class = trim($_POST["class"] ?? "");
  $att_number = trim($_POST["att_number"] ?? "");
  $password = $_POST["password"] ?? "";
  $password_confirm = $_POST["password_confirm"] ?? "";

  // Validasi
  if ($nis === "" || $full_name === "" || $major === "" || $class === "" 
      || $att_number === "" || $password === "" || $password_confirm === "") {
    header("Location: student_registry.php?error=" . urlencode("Semua field wajib diisi"));
    exit;
  }

  if ($nis <= 0) {
    header("Location: student_registry.php?error=" . urlencode("NIS harus lebih dari 0"));
    exit;
  }

  if ($att_number <= 0) {
    header("Location: student_registry.php?error=" . urlencode("Nomor absen harus lebih dari 0"));
    exit;
  }

  if (strlen($password) < 8) {
    header("Location: student_registry.php?error=" . urlencode("Password minimal 8 karakter"));
    exit;
  }

  if ($password !== $password_confirm) {
    header("Location: student_registry.php?error=" . urlencode("Password dan konfirmasi tidak sesuai"));
    exit;
  }

  try {
    $conn = connect_sql();

    // Cek NIS sudah ada belum
    $nis_safe = mysqli_real_escape_string($conn, $nis);
    $existing = select_sql("SELECT nis FROM students_table WHERE nis = '$nis_safe'");
    
    if (!empty($existing)) {
      header("Location: student_registry.php?error=" . urlencode("NIS $nis sudah terdaftar"));
      exit;
    }

    // Cek nomor absen di kelas yang sama
    $class_safe = mysqli_real_escape_string($conn, $class);
    $att_safe = mysqli_real_escape_string($conn, $att_number);
    $existing_att = select_sql(
      "SELECT nis FROM students_table WHERE class = '$class_safe' AND att_number = '$att_safe'"
    );
    
    if (!empty($existing_att)) {
      header("Location: student_registry.php?error=" . urlencode("Nomor absen $att_number di kelas $class sudah digunakan"));
      exit;
    }

    // Insert siswa baru
    $name_safe = mysqli_real_escape_string($conn, $full_name);
    $pw_safe = mysqli_real_escape_string($conn, $password);
    
    execute_sql(
      "INSERT INTO students_table (nis, full_name, att_number, class, acc_password) 
       VALUES ('$nis_safe', '$name_safe', '$att_safe', '$class_safe', '$pw_safe')"
    );

    // Redirect ke login dengan pesan sukses
    header("Location: ../student_login/student_login.php?success=" . urlencode("Akun berhasil dibuat! Silakan login."));
    exit;

  } catch (Exception $e) {
    header("Location: student_registry.php?error=" . urlencode("Terjadi kesalahan: " . $e->getMessage()));
    exit;
  }
?>