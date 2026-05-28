<?php
  session_start();
  if (!isset($_SESSION["admin_id"])) { header("Location: ../admin_login/admin_login.php"); exit; }

  require_once "../../sql/sql_bridge.php";

  $target = trim($_POST["target"] ?? "all");
  $target_nis = trim($_POST["target_nis"] ?? "");
  $target_comp = intval($_POST["target_comp"] ?? 0);
  $message = trim($_POST["message"] ?? "");

  if ($message === "") {
    header("Location: admin_announcements.php?error=" . urlencode("Pesan wajib diisi"));
    exit;
  }

  try {
    $conn = connect_sql();
    $msg_s = mysqli_real_escape_string($conn, $message);

    if ($target === "all") {
      // Kirim ke semua siswa
      $students = select_sql("SELECT nis FROM students_table");
      foreach ($students as $s) {
        $n = mysqli_real_escape_string($conn, $s["nis"]);
        execute_sql("INSERT INTO notifications (target_nis, message, type) VALUES ('$n', '$msg_s', 'announcement')");
      }
    } elseif ($target === "student") {
      $n = mysqli_real_escape_string($conn, $target_nis);
      if ($target_nis !== "" && $target_nis !== "__all__") {
        execute_sql("INSERT INTO notifications (target_nis, message, type) VALUES ('$n', '$msg_s', 'announcement')");
      }
    } elseif ($target === "comp" && $target_comp > 0) {
      // Kirim ke siswa yang terdaftar di lomba tersebut
      $regs = select_sql("SELECT student_nis FROM comp_registrations WHERE comp_id = $target_comp AND status = 'accepted'");
      foreach ($regs as $r) {
        $n = mysqli_real_escape_string($conn, $r["student_nis"]);
        $comp_sql = $target_comp;
        execute_sql("INSERT INTO notifications (target_nis, target_comp_id, message, type) VALUES ('$n', $comp_sql, '$msg_s', 'announcement')");
      }
    }

    header("Location: admin_announcements.php?sent=1");
    exit;
  } catch (Exception $e) {
    header("Location: admin_announcements.php?error=" . urlencode($e->getMessage()));
    exit;
  }
?>