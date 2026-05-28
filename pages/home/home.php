<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Beranda | CIS Skensa</title>
  <link rel="stylesheet" href="home.css">
  
  <?php
    session_start();
    require_once "../../templates/top_bar.php";
    require_once "../../templates/bottom_bar.php";
    require_once "../../sql/sql_bridge.php";

    top_bar_css();
    bottom_bar_css();

    $logged_in = isset($_SESSION["nis"]);
    $logged_nis = $logged_in ? $_SESSION["nis"] : null;
    $logged_name = $logged_in ? $_SESSION["name"] : null;

    // Hitung notifikasi unread
    $unread_count = 0;
    if ($logged_in) {
      try {
        $nis_safe = mysqli_real_escape_string(connect_sql(), $logged_nis);
        $ur = select_sql("SELECT COUNT(*) AS cnt FROM notifications WHERE target_nis = '$nis_safe' AND is_read = 0");
        $unread_count = intval($ur[0]["cnt"]);
      } catch (Exception $e) {}
    }

    // Ambil foto profil user yang login
    $topbar_pfp = "../../assets/images/default_pfp.jpg";
    if ($logged_in) {
      try {
        $nis_safe = mysqli_real_escape_string(connect_sql(), $logged_nis);
        $pfp_rows = select_sql("SELECT profile_pic_path FROM students_table WHERE nis = '$nis_safe'");
        if (!empty($pfp_rows[0]["profile_pic_path"])) {
          $topbar_pfp = "../../" . $pfp_rows[0]["profile_pic_path"];
        }
      } catch (Exception $e) {}
    }
  ?>
</head>
<body>
  <?php top_bar_html($topbar_pfp, $logged_in, $logged_nis, $unread_count); ?>
  <main>
    <img id="skensa_logo" src="../../assets/images/logo_skensa.png" alt="logo_skensa.png">
    <h2>SMK Negeri 1 Denpasar</h2>
    <h3>Competition Informational System</h3>
    <p>Menginformasikan perlombaan akademik dan non-akademik untuk siswa.</p>
  </main>
      <p style="text-align:center;margin-top:24px;">
      <a href="../admin_login/admin_login.php" style="color:var(--text-muted);font-size:0.75rem;text-decoration:none;opacity:0.5;transition:opacity 0.18s;" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.5'">Panel Admin</a>
    </p>
  <?php bottom_bar_html($logged_name); ?>
</body>
</html>