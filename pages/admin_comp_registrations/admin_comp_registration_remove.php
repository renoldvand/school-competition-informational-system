<?php
  session_start();
  if (!isset($_SESSION["admin_id"])) { header("Location: ../admin_login/admin_login.php"); exit; }
  require_once "../../sql/sql_bridge.php";

  $id = intval($_GET["id"] ?? 0);
  $comp_id = intval($_GET["comp_id"] ?? 0);
  if ($id > 0) {
    try { execute_sql("DELETE FROM comp_registrations WHERE id = $id"); } catch (Exception $e) {}
  }
  header("Location: admin_comp_registrations.php?comp_id=$comp_id&removed=1");
  exit;
?>