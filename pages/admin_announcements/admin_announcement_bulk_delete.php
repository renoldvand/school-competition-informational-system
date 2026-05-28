<?php
  session_start();
  if (!isset($_SESSION["admin_id"])) { header("Location: ../admin_login/admin_login.php"); exit; }
  require_once "../../sql/sql_bridge.php";

  $ids = $_POST["ids"] ?? [];
  $deleted = 0;
  if (!empty($ids) && is_array($ids)) {
    $conn = connect_sql();
    foreach ($ids as $id) {
      $id_safe = mysqli_real_escape_string($conn, intval($id));
      try { execute_sql("DELETE FROM notifications WHERE id = $id_safe"); $deleted++; } catch (Exception $e) {}
    }
  }
  header("Location: admin_announcements.php?deleted=1");
  exit;
?>