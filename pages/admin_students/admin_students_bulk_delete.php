<?php
  session_start();
  if (!isset($_SESSION["admin_id"])) { header("Location: ../admin_login/admin_login.php"); exit; }
  require_once "../../sql/sql_bridge.php";

  $ids = $_POST["ids"] ?? [];
  if (!empty($ids) && is_array($ids)) {
    $conn = connect_sql();
    foreach ($ids as $nis) {
      $nis_safe = mysqli_real_escape_string($conn, trim($nis));
      try {
        execute_sql("DELETE FROM comp_registrations WHERE student_nis = '$nis_safe'");
        execute_sql("DELETE FROM student_achievements WHERE student_nis = '$nis_safe'");
        $rows = select_sql("SELECT profile_pic_path FROM students_table WHERE nis = '$nis_safe'");
        if (!empty($rows[0]["profile_pic_path"])) { $f = "../../" . $rows[0]["profile_pic_path"]; if (file_exists($f)) unlink($f); }
        execute_sql("DELETE FROM students_table WHERE nis = '$nis_safe'");
      } catch (Exception $e) {}
    }
  }
  header("Location: admin_students.php?deleted=1");
  exit;
?>