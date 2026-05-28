<?php
  session_start();
  if (!isset($_SESSION["nis"])) { header("Location: ../student_login/student_login.php"); exit; }

  require_once "../../templates/top_bar.php";
  require_once "../../templates/bottom_bar.php";
  require_once "../../sql/sql_bridge.php";

  top_bar_css();
  bottom_bar_css();

  $logged_nis = $_SESSION["nis"];
  $logged_name = $_SESSION["name"];

  // Ambil foto profil
  $topbar_pfp = "../../assets/images/default_pfp.jpg";
  try {
    $nis_safe = mysqli_real_escape_string(connect_sql(), $logged_nis);
    $pfp_rows = select_sql("SELECT profile_pic_path FROM students_table WHERE nis = '$nis_safe'");
    if (!empty($pfp_rows[0]["profile_pic_path"])) $topbar_pfp = "../../" . $pfp_rows[0]["profile_pic_path"];
  } catch (Exception $e) {}

  // Hitung unread
  $unread = 0;
  try {
    $unread_rows = select_sql("SELECT COUNT(*) AS cnt FROM notifications WHERE target_nis = '$nis_safe' AND is_read = 0");
    $unread = intval($unread_rows[0]["cnt"]);
  } catch (Exception $e) {}

  // Tandai semua sebagai dibaca
  if ($unread > 0) {
    try { execute_sql("UPDATE notifications SET is_read = 1 WHERE target_nis = '$nis_safe'"); } catch (Exception $e) {}
  }

  // Ambil notifikasi
  $notifs = [];
  try {
    $notifs = select_sql("SELECT * FROM notifications WHERE target_nis = '$nis_safe' ORDER BY created_on DESC LIMIT 30");
  } catch (Exception $e) {}

  $type_labels = [
    'registration' => 'Pendaftaran Lomba',
    'announcement' => 'Pengumuman',
    'info' => 'Informasi',
  ];
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Notifikasi | CIS Skensa</title>
  <link rel="stylesheet" href="student_notifications.css">
</head>
<body>
  <?php top_bar_html($topbar_pfp, true, $logged_nis, 0); ?>
  <main>
    <h1>Notifikasi</h1>
    <p class="page-sub">Pemberitahuan dari admin dan status pendaftaran lomba.</p>

    <?php if (empty($notifs)): ?>
      <div class="empty-state"><p>Tidak ada notifikasi.</p></div>
    <?php else: ?>
      <div class="notif-list">
        <?php foreach ($notifs as $n):
          $type_class = $n["type"];
          $label = $type_labels[$n["type"]] ?? "Informasi";
          $is_unread = ($n["is_read"] == 0) ? " unread" : "";
          $time = date("d/m/Y H:i", strtotime($n["created_on"]));
        ?>
          <div class="notif-card <?php echo $type_class . $is_unread; ?>">
            <div class="notif-type"><?php echo $label; ?></div>
            <div class="notif-msg"><?php echo htmlspecialchars($n["message"]); ?></div>
            <div class="notif-time"><?php echo $time; ?></div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </main>
  <?php bottom_bar_html($logged_name); ?>
</body>
</html>