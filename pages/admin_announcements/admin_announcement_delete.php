<?php
  session_start();
  if (!isset($_SESSION["admin_id"])) { header("Location: ../admin_login/admin_login.php"); exit; }
  require_once "../../sql/sql_bridge.php";

  $id = intval($_GET["id"] ?? 0);
  if ($id > 0) {
    try { execute_sql("DELETE FROM notifications WHERE id = $id"); } catch (Exception $e) {}
  }
  header("Location: admin_announcements.php?deleted=1");
  exit;
?>