<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Perlombaan | CIS Skensa</title>
  <link rel="stylesheet" href="comps.css">
  
  <?php
    session_start();
    require_once "../../templates/top_bar.php";
    require_once "../../templates/bottom_bar.php";
    require_once "../../templates/comp_grid.php";
    require_once "../../sql/sql_bridge.php";

    top_bar_css();
    bottom_bar_css();
    comp_grid_css();

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

    // Ambil data kompetisi dari DB
    $comps = [];
    $default_thumb = "../../assets/images/default_comp_card.png";
    try {
      $rows = select_sql(
        "SELECT c.id, c.title AS name, c.thumbnail_path AS thumbnail, m.major_name AS major, c.is_open AS opened
         FROM comps c
         LEFT JOIN major_table m ON c.major = m.major_id
         ORDER BY c.is_open DESC, c.starts_on DESC"
      );
      foreach ($rows as $row) {
        if (empty($row["thumbnail"])) {
          $row["thumbnail"] = $default_thumb;
        } else {
          $row["thumbnail"] = "../../" . $row["thumbnail"];
        }
        $row["opened"] = ($row["opened"] == 1);
        $comps[] = $row;
      }
    } catch (Exception $e) {
      $comps = [];
    }

    $default_pfp = "../../assets/images/default_pfp.jpg";
  ?>
  <style>
    .page-header {
      max-width: 1100px;
      margin: 0 auto;
      padding: 32px 20px 0;
    }
    .page-header h1 {
      font-family: 'Playfair Display', serif;
      font-size: clamp(1.5rem, 3vw, 2.2rem);
      color: var(--navy);
      margin-bottom: 4px;
      font-weight: 700;
    }
    .page-header p {
      color: var(--text-muted);
      font-size: 0.95rem;
      font-weight: 300;
      margin-bottom: 4px;
    }
  </style>
</head>
<body>
  <?php top_bar_html($topbar_pfp, $logged_in, $logged_nis, $unread_count); ?>  
  <div class="page-header">
    <h1>Perlombaan</h1>
    <p>Daftar perlombaan akademik dan non-akademik untuk siswa SMKN 1 Denpasar.</p>
  </div>

  <main style="padding-top:8px;">
    <?php comp_grid_html($comps); ?>
  </main>
  <?php bottom_bar_html($logged_name); ?>
</body>
</html>