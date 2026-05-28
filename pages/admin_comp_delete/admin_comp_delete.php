<?php
  session_start();
  if (!isset($_SESSION["admin_id"])) { header("Location: ../admin_login/admin_login.php"); exit; }
  require_once "../../sql/sql_bridge.php";

  $comp_id = intval($_GET["id"] ?? 0);
  if ($comp_id <= 0) { header("Location: ../admin_comps/admin_comps.php"); exit; }

  try {
    // Urutan hapus sesuai foreign key dependencies
    execute_sql("DELETE FROM comp_registrations WHERE comp_id = $comp_id");
    execute_sql("DELETE FROM comp_external_links WHERE linked_comp = $comp_id");
    execute_sql("DELETE FROM student_achievements WHERE comp_id = $comp_id");
    execute_sql("DELETE FROM notifications WHERE target_comp_id = $comp_id");

    // Hapus file gambar
    $rows = select_sql("SELECT thumbnail_path, icon_path FROM comps WHERE id = $comp_id");
    if (!empty($rows)) {
      foreach (["thumbnail_path", "icon_path"] as $col) {
        if (!empty($rows[0][$col])) {
          $f = "../../" . $rows[0][$col];
          if (file_exists($f)) unlink($f);
        }
      }
    }
    execute_sql("DELETE FROM comps WHERE id = $comp_id");
  } catch (Exception $e) {}

  header("Location: ../admin_comps/admin_comps.php?deleted=1");
  exit;
?>