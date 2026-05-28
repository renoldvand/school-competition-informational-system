<?php
  session_start();
  if (!isset($_SESSION["admin_id"])) { header("Location: ../admin_login/admin_login.php"); exit; }

  require_once "../../sql/sql_bridge.php";

  $nis = trim($_GET["nis"] ?? "");
  if ($nis === "") { header("Location: ../admin_students/admin_students.php"); exit; }

  try {
    $nis_safe = mysqli_real_escape_string(connect_sql(), $nis);
    // Hapus pendaftaran
    execute_sql("DELETE FROM comp_registrations WHERE student_nis = '$nis_safe'");
    // Ambil path foto untuk dihapus
    $rows = select_sql("SELECT profile_pic_path FROM students_table WHERE nis = '$nis_safe'");
    if (!empty($rows[0]["profile_pic_path"])) {
      $f = "../../" . $rows[0]["profile_pic_path"];
      if (file_exists($f)) unlink($f);
    }
    // Hapus siswa
    execute_sql("DELETE FROM students_table WHERE nis = '$nis_safe'");
  } catch (Exception $e) {}

  header("Location: ../admin_students/admin_students.php?deleted=1");
  exit;
?>