<?php
  session_start();
  if (!isset($_SESSION["admin_id"])) { header("Location: ../admin_login/admin_login.php"); exit; }

  require_once "../../sql/sql_bridge.php";

  $id = intval($_GET["id"] ?? 0);
  $status = trim($_GET["status"] ?? "");

  $allowed = ["pending", "accepted", "rejected"];
  if ($id <= 0 || !in_array($status, $allowed)) {
    header("Location: admin_registrations.php");
    exit;
  }

  try {
    $conn = connect_sql();
    $id_safe = mysqli_real_escape_string($conn, $id);
    $status_safe = mysqli_real_escape_string($conn, $status);
    execute_sql("UPDATE comp_registrations SET status = '$status_safe' WHERE id = $id_safe");

    // Kirim notifikasi ke siswa
    $reg = select_sql("SELECT student_nis, comp_id FROM comp_registrations WHERE id = $id_safe");
    if (!empty($reg)) {
      $nis = mysqli_real_escape_string($conn, $reg[0]["student_nis"]);
      $comp = select_sql("SELECT title FROM comps WHERE id = " . intval($reg[0]["comp_id"]));
      $comp_title = !empty($comp) ? $comp[0]["title"] : "Lomba";

      if ($status === "accepted") {
        $msg = "Pendaftaran Anda di \"$comp_title\" telah diterima.";
      } elseif ($status === "rejected") {
        $msg = "Pendaftaran Anda di \"$comp_title\" telah ditolak.";
      } else {
        $msg = "Status pendaftaran Anda di \"$comp_title\" diubah menjadi pending.";
      }
      $msg_safe = mysqli_real_escape_string($conn, $msg);
      execute_sql("INSERT INTO notifications (target_nis, comp_id, message, type) VALUES ('$nis', " . intval($reg[0]["comp_id"]) . ", '$msg_safe', 'registration')");
    }
  } catch (Exception $e) {}

  header("Location: admin_registrations.php?updated=1");
  exit;
?>