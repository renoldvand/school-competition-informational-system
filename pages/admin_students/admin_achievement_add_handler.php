<?php
  session_start();
  if (!isset($_SESSION["admin_id"])) { header("Location: ../admin_login/admin_login.php"); exit; }

  require_once "../../sql/sql_bridge.php";

  $nis = trim($_POST["nis"] ?? "");
  $comp_id = intval($_POST["comp_id"] ?? 0);
  $result = trim($_POST["result"] ?? "peserta");
  $achievement_title = trim($_POST["achievement_title"] ?? "");
  $notes = trim($_POST["notes"] ?? "");

  if ($nis === "") { header("Location: admin_students.php"); exit; }

  try {
    $conn = connect_sql();
    $nis_s = mysqli_real_escape_string($conn, $nis);

    // Jika judul kosong dan ada comp_id, pakai nama lomba
    if ($achievement_title === "" && $comp_id > 0) {
      $comp = select_sql("SELECT title FROM comps WHERE id = $comp_id");
      $achievement_title = !empty($comp) ? $comp[0]["title"] : "Pencapaian";
    }
    if ($achievement_title === "") $achievement_title = "Pencapaian";

    $title_s = mysqli_real_escape_string($conn, $achievement_title);
    $result_s = mysqli_real_escape_string($conn, $result);
    $notes_s = mysqli_real_escape_string($conn, $notes) ?: "NULL";
    $comp_sql = $comp_id > 0 ? $comp_id : "NULL";

    $notes_sql = $notes === "" ? "NULL" : "'$notes_s'";
    execute_sql(
      "INSERT INTO student_achievements (student_nis, comp_id, achievement_title, result, notes)
       VALUES ('$nis_s', $comp_sql, '$title_s', '$result_s', $notes_sql)"
    );

    header("Location: admin_student_achievements.php?nis=$nis&added=1");
    exit;
  } catch (Exception $e) {
    header("Location: admin_student_achievements.php?nis=$nis&error=" . urlencode($e->getMessage()));
    exit;
  }
?>