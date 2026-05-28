<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daftar Siswa | CIS Skensa</title>
  <link rel="stylesheet" href="students.css">
  
  <?php
    session_start();
    require_once "../../templates/top_bar.php";
    require_once "../../templates/bottom_bar.php";
    require_once "../../templates/student_grid.php";
    require_once "../../sql/sql_bridge.php";

    top_bar_css();
    bottom_bar_css();
    student_grid_css();

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

    // Ambil parameter pencarian
    $search = trim($_GET["q"] ?? "");

    // Ambil data siswa dari database
    $students = [];
    try {
      if ($search !== "") {
        $search_safe = mysqli_real_escape_string(connect_sql(), $search);
        $students = select_sql(
          "SELECT s.nis, s.full_name AS name, s.att_number, c.class_id AS class, s.profile_pic_path AS profile_pic
           FROM students_table s
           JOIN class_table c ON s.class = c.class_id
           WHERE s.full_name LIKE '%$search_safe%' 
              OR s.nis LIKE '%$search_safe%'
              OR c.class_id LIKE '%$search_safe%'
           ORDER BY c.class_id, s.att_number"
        );
      } else {
        $students = select_sql(
          "SELECT s.nis, s.full_name AS name, s.att_number, c.class_id AS class, s.profile_pic_path AS profile_pic
           FROM students_table s
           JOIN class_table c ON s.class = c.class_id
           ORDER BY c.class_id, s.att_number"
        );
      }
    } catch (Exception $e) {
      // Jika DB error, tetap tampilkan halaman dengan array kosong
      $students = [];
    }

    // Tentukan default pfp path
    $default_pfp = "../../assets/images/default_pfp.jpg";
    foreach ($students as &$s) {
      if (empty($s["profile_pic"])) {
        $s["profile_pic"] = $default_pfp;
      } else {
        $s["profile_pic"] = "../../" . $s["profile_pic"];
      }
    }
    unset($s);
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
      margin-bottom: 20px;
    }
    .search-row {
      width: 100%;
      max-width: 1100px;
      margin: 0 auto;
      padding: 0 20px 8px;
      display: flex;
      align-items: center;
      gap: 12px;
      flex-wrap: wrap;
      justify-content: center;
    }
    .search-row form {
      width: 100%;
      justify-content: center;
    }
    .search-row input[type="text"] {
      width: 75%;
      padding: 10px 16px;
      border: 1.5px solid rgba(42,82,160,0.18);
      border-radius: 9px;
      font-family: 'DM Sans', sans-serif;
      font-size: 0.9rem;
      background: var(--white);
      color: var(--text);
      outline: none;
      transition: border-color 0.18s, box-shadow 0.18s;
    }
    .search-row input[type="text"]:focus {
      border-color: var(--blue);
      box-shadow: 0 0 0 3px rgba(42,82,160,0.10);
    }
    .search-row .result-count {
      font-size: 0.82rem;
      color: var(--text-muted);
    }

    .result-count {
      width: 100%;
      text-align: center;
    }

    .search-row a.clear-search {
      font-size: 0.82rem;
      color: var(--blue);
      text-decoration: none;
      font-weight: 500;
    }
    .search-row a.clear-search:hover {
      color: var(--navy);
    }
  </style>
</head>
<body>
  <?php top_bar_html($topbar_pfp, $logged_in, $logged_nis, $unread_count); ?>  
  <div class="page-header">
    <h1>Daftar Siswa</h1>
    <p>Seluruh siswa yang terdaftar di SCIS Skensa.</p>
  </div>

  <div class="search-row">
    <form method="get" style="display:flex;gap:8px;width:100%;max-width:100%;">
      <input type="text" name="q" placeholder="Cari nama, NIS, atau kelas..." value="<?php echo htmlspecialchars($search); ?>">
      <input type="submit" value="Cari" style="padding:10px 18px;border:none;border-radius:9px;background:var(--blue);color:white;font-family:'DM Sans',sans-serif;font-size:0.85rem;font-weight:600;cursor:pointer;white-space:nowrap;">
    </form>
    <span class="result-count"><?php echo count($students); ?> siswa ditemukan</span>
    <?php if ($search !== ""): ?>
      <a href="students.php" class="clear-search">Reset</a>
    <?php endif; ?>
  </div>

  <main style="padding-top:8px;">
    <?php student_grid_html($students); ?>
  </main>
  <?php bottom_bar_html($logged_name); ?>
</body>
</html>