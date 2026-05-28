<?php
  session_start();
  if (!isset($_SESSION["admin_id"])) { header("Location: ../admin_login/admin_login.php"); exit; }

  require_once "../../sql/sql_bridge.php";

  $nis = trim($_POST["nis"] ?? "");
  $full_name = trim($_POST["full_name"] ?? "");
  $major = trim($_POST["major"] ?? "");
  $class = trim($_POST["class"] ?? "");
  $att_number = trim($_POST["att_number"] ?? "");
  $password = $_POST["password"] ?? "";

  if ($nis === "" || $full_name === "" || $major === "" || $class === "" || $att_number === "" || $password === "") {
    header("Location: admin_student_add.php?error=" . urlencode("Semua field wajib diisi"));
    exit;
  }
  if ($nis <= 0 || $att_number <= 0) {
    header("Location: admin_student_add.php?error=" . urlencode("NIS dan No. Absen harus lebih dari 0"));
    exit;
  }
  if (strlen($password) < 8) {
    header("Location: admin_student_add.php?error=" . urlencode("Password minimal 8 karakter"));
    exit;
  }

  try {
    $conn = connect_sql();
    $nis_s = mysqli_real_escape_string($conn, $nis);
    $existing = select_sql("SELECT nis FROM students_table WHERE nis = '$nis_s'");
    if (!empty($existing)) {
      header("Location: admin_student_add.php?error=" . urlencode("NIS $nis sudah terdaftar"));
      exit;
    }
    $att_s = mysqli_real_escape_string($conn, $att_number);
    $class_s = mysqli_real_escape_string($conn, $class);
    $existing_att = select_sql("SELECT nis FROM students_table WHERE class = '$class_s' AND att_number = '$att_s'");
    if (!empty($existing_att)) {
      header("Location: admin_student_add.php?error=" . urlencode("No. Absen $att_number di kelas $class sudah digunakan"));
      exit;
    }
    $name_s = mysqli_real_escape_string($conn, $full_name);
    $pw_s = mysqli_real_escape_string($conn, $password);
    execute_sql("INSERT INTO students_table (nis, full_name, att_number, class, acc_password) VALUES ('$nis_s','$name_s','$att_s','$class_s','$pw_s')");
    header("Location: admin_students.php?added=1");
    exit;
  } catch (Exception $e) {
    header("Location: admin_student_add.php?error=" . urlencode("Terjadi kesalahan: " . $e->getMessage()));
    exit;
  }
?>