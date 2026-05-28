<?php
  session_start();
  if (!isset($_SESSION["admin_id"])) { header("Location: ../admin_login/admin_login.php"); exit; }

  require_once "../../sql/sql_bridge.php";

  $id = intval($_GET["id"] ?? 0);
  $nis = trim($_GET["nis"] ?? "");

  if ($id > 0) {
    try { execute_sql("DELETE FROM student_achievements WHERE id = $id"); } catch (Exception $e) {}
  }

  header("Location: admin_student_achievements.php?nis=" . urlencode($nis) . "&deleted=1");
  exit;
?>