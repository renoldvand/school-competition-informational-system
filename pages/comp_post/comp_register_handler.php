<?php
  session_start();
  if (!isset($_SESSION["nis"])) {
    header("Location: ../student_login/student_login.php?error=" . urlencode("Anda harus login"));
    exit;
  }
  require_once "../../sql/sql_bridge.php";

  $referer = $_SERVER["HTTP_REFERER"] ?? "";
  $comp_id = 0;
  if (preg_match('/[?&]id=(\d+)/', $referer, $m)) $comp_id = intval($m[1]);
  if ($comp_id <= 0) { header("Location: ../comps/comps.php"); exit; }

  $nis = $_SESSION["nis"];
  try {
    $conn = connect_sql();
    $nis_safe = mysqli_real_escape_string($conn, $nis);

    $comps = select_sql("SELECT id, is_open, title FROM comps WHERE id = $comp_id");
    if (empty($comps)) { header("Location: ../comps/comps.php"); exit; }
    if ($comps[0]["is_open"] != 1) {
      header("Location: comp_post.php?id=$comp_id&error=" . urlencode("Lomba ini sudah ditutup"));
      exit;
    }

    $existing = select_sql("SELECT id, status FROM comp_registrations WHERE student_nis = '$nis_safe' AND comp_id = $comp_id");
    if (!empty($existing)) {
      $st = $existing[0]["status"];
      if ($st === "accepted") {
        header("Location: comp_post.php?id=$comp_id&error=" . urlencode("Anda sudah diterima di lomba ini"));
        exit;
      } elseif ($st === "pending") {
        header("Location: comp_post.php?id=$comp_id&error=" . urlencode("Pendaftaran Anda masih menunggu konfirmasi"));
        exit;
      } elseif ($st === "rejected") {
        // Hapus record lama, lalu daftar ulang
        execute_sql("DELETE FROM comp_registrations WHERE student_nis = '$nis_safe' AND comp_id = $comp_id");
      }
    }

    execute_sql("INSERT INTO comp_registrations (student_nis, comp_id) VALUES ('$nis_safe', $comp_id)");
    header("Location: comp_post.php?id=$comp_id&success=" . urlencode("Berhasil mendaftar lomba!"));
    exit;
  } catch (Exception $e) {
    header("Location: comp_post.php?id=$comp_id&error=" . urlencode("Terjadi kesalahan"));
    exit;
  }
?>