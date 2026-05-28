<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Perlombaan | CIS Skensa</title>
  <link rel="stylesheet" href="comp_post.css">
  
  <?php
    session_start();
    require_once "../../templates/top_bar.php";
    require_once "../../templates/bottom_bar.php";
    require_once "../../templates/comp_post_card.php";
    require_once "../../templates/return_link.php";
    require_once "../../sql/sql_bridge.php";

    top_bar_css();
    bottom_bar_css();
    comp_post_card_css();

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

    // Ambil ID dari URL
    $comp_id = trim($_GET["id"] ?? "");
    $page_title = "Perlombaan | CIS Skensa";

    if ($comp_id === "") {
      header("Location: ../comps/comps.php");
      exit;
    }

    // Ambil data kompetisi dari DB
    $comp = null;
    $external_links = [];
    try {
      $id_safe = mysqli_real_escape_string(connect_sql(), $comp_id);
      $rows = select_sql(
        "SELECT c.*, m.major_name AS major_display
         FROM comps c
         LEFT JOIN major_table m ON c.major = m.major_id
         WHERE c.id = '$id_safe'"
      );
      if (!empty($rows)) {
        $comp = $rows[0];
        $page_title = $comp["title"] . " | CIS Skensa";

        // Ambil link eksternal
        $ext_rows = select_sql(
          "SELECT title, address AS link FROM comp_external_links WHERE linked_comp = '$id_safe'"
        );
        $external_links = $ext_rows;
      }
    } catch (Exception $e) {
      $comp = null;
    }

    $default_thumb = "../../assets/images/default_comp_thumbnail.jpg";
    $default_pfp = "../../assets/images/default_pfp.jpg";
  ?>
</head>
<body>
  <?php top_bar_html($topbar_pfp, $logged_in, $logged_nis, $unread_count); ?>  <main>
    <?php 
      if ($comp) {
        $thumb = !empty($comp["thumbnail_path"]) ? "../../" . $comp["thumbnail_path"] : $default_thumb;
        $starts = date("d/m/Y", strtotime($comp["starts_on"]));
        $ends = date("d/m/Y", strtotime($comp["ends_on"]));
        $overseer = !empty($comp["overseer"]) ? $comp["overseer"] : "Belum ditentukan";
        $major = !empty($comp["major_display"]) ? $comp["major_display"] : "Umum";
        $desc = !empty($comp["description"]) ? $comp["description"] : "[Tidak ada deskripsi]";

        // Tentukan status pendaftaran
        $reg_status = null;
        if ($logged_in) {
          try {
            $nis_safe = mysqli_real_escape_string(connect_sql(), $logged_nis);
            $check = select_sql("SELECT status FROM comp_registrations WHERE student_nis = '$nis_safe' AND comp_id = " . intval($comp_id));
            if (!empty($check)) {
              $reg_status = $check[0]["status"];
            } elseif ($comp["is_open"] == 1) {
              $reg_status = "not_registered";
            }
          } catch (Exception $e) {}
        }

        // Pesan dari URL
        $reg_msg = ""; $reg_msg_type = "";
        if (isset($_GET["success"])) { $reg_msg = $_GET["success"]; $reg_msg_type = "success"; }
        if (isset($_GET["error"])) { $reg_msg = $_GET["error"]; $reg_msg_type = "error"; }

        comp_post_card_html(
          $comp["title"], $major, $desc, $thumb, $external_links,
          $starts, $ends, $overseer, false,
          $reg_status, $reg_msg, $reg_msg_type
        );
      } else {
        echo '<p style="text-align:center;color:var(--text-muted);padding:40px;font-size:1rem;">Perlombaan tidak ditemukan.</p>';
        echo '<p style="text-align:center;"><a href="../comps/comps.php" style="color:var(--blue);text-decoration:none;">Kembali ke daftar perlombaan</a></p>';
      }
    ?>
  </main>
  <?php bottom_bar_html($logged_name); ?>
  <?php return_link_js(); ?>
</body>
</html>