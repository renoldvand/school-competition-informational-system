<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profil Siswa | CIS Skensa</title>
  <link rel="stylesheet" href="student_profile.css">
  
  <?php
    session_start();
    require_once "../../templates/top_bar.php";
    require_once "../../templates/bottom_bar.php";
    require_once "../../templates/student_profile_card.php";
    require_once "../../sql/sql_bridge.php";

    top_bar_css();
    bottom_bar_css();
    student_profile_card_css();

    $logged_in = isset($_SESSION["nis"]);
    $logged_nis = $logged_in ? $_SESSION["nis"] : null;
    $logged_name = $logged_in ? $_SESSION["name"] : null;

    $unread_count = 0;
    if ($logged_in) {
      try {
        $nis_safe = mysqli_real_escape_string(connect_sql(), $logged_nis);
        $ur = select_sql("SELECT COUNT(*) AS cnt FROM notifications WHERE target_nis = '$nis_safe' AND is_read = 0");
        $unread_count = intval($ur[0]["cnt"]);
      } catch (Exception $e) {}
    }

    // Ambil NIS dari URL
    $requested_nis = trim($_GET["nis"] ?? "");

    if ($requested_nis === "") {
      header("Location: ../students/students.php");
      exit;
    }

    // Ambil data siswa dari DB
    $student = null;
    $page_title = "Profil Siswa";
    try {
      $nis_safe = mysqli_real_escape_string(connect_sql(), $requested_nis);
      $rows = select_sql(
        "SELECT s.*, c.class_id AS class_display, m.major_name AS major_display
         FROM students_table s
         JOIN class_table c ON s.class = c.class_id
         LEFT JOIN major_table m ON c.major_id = m.major_id
         WHERE s.nis = '$nis_safe'"
      );
      if (!empty($rows)) {
        $student = $rows[0];
        $page_title = $student["full_name"] . " | CIS Skensa";
      }
    } catch (Exception $e) {
      $student = null;
    }

    // Tentukan apakah dilihat oleh pemilik profil
    $viewed_by_owner = ($logged_in && $logged_nis === $requested_nis);

    // Default pfp
    $default_pfp = "../../assets/images/default_pfp.jpg";
    $topbar_pfp = "../../assets/images/default_pfp.jpg";

    // Ambil lomba yang diikuti siswa ini
    // Ambil pencapaian siswa dari tabel student_achievements
    $joined_comps = [];
    $achievements = [];
    if ($student) {
      try {
        $nis_safe = mysqli_real_escape_string(connect_sql(), $requested_nis);
        $ach_rows = select_sql(
          "SELECT sa.*, c.title AS comp_title, c.icon_path AS comp_icon
           FROM student_achievements sa
           LEFT JOIN comps c ON sa.comp_id = c.id
           WHERE sa.student_nis = '$nis_safe'
           ORDER BY sa.created_on DESC"
        );
        $default_comp_icon = "../../assets/images/default_comp_icon.jpg";
        foreach ($ach_rows as $a) {
          $icon = !empty($a["comp_icon"]) ? "../../" . $a["comp_icon"] : $default_comp_icon;
          $result_label = "";
          if ($a["result"] === "juara_1") $result_label = "🏆 Juara 1";
          elseif ($a["result"] === "juara_2") $result_label = "🥈 Juara 2";
          elseif ($a["result"] === "juara_3") $result_label = "🥉 Juara 3";
          elseif ($a["result"] === "finalis") $result_label = "⭐ Finalis";
          else $result_label = "📋 Peserta";

          $display_name = $a["achievement_title"];
          if (!empty($a["comp_title"])) $display_name = $a["comp_title"];

          $joined_comps[] = [
            "name" => $display_name . " — " . $result_label,
            "icon" => $icon
          ];
        }
      } catch (Exception $e) {}
    }

    // Ambil lomba yang sedang diikuti (registrations)
    $registered_comps = [];
    if ($student) {
      try {
        $nis_safe = mysqli_real_escape_string(connect_sql(), $requested_nis);
        $reg_rows = select_sql(
          "SELECT cr.status, cr.comp_id, c.title AS comp_title
           FROM comp_registrations cr
           JOIN comps c ON cr.comp_id = c.id
           WHERE cr.student_nis = '$nis_safe'
           ORDER BY cr.registered_on DESC"
        );
        foreach ($reg_rows as $rr) {
          $registered_comps[] = $rr;
        }
      } catch (Exception $e) {}
    }

    // Siapkan parameter untuk template
    if ($student) {
      $pfp = !empty($student["profile_pic_path"]) ? "../../" . $student["profile_pic_path"] : $default_pfp;
      $desc = !empty($student["description"]) ? $student["description"] : "[Tidak ada deskripsi]";
      $created = !empty($student["created_on"]) ? date("d/m/Y", strtotime($student["created_on"])) : "??/??/??";
      $major = !empty($student["major_display"]) ? $student["major_display"] : "???";
      $class = !empty($student["class_display"]) ? $student["class_display"] : "???";

      // Selalu pakai foto profil user yang login di top bar
      if ($logged_in) {
        try {
          $nis_safe = mysqli_real_escape_string(connect_sql(), $logged_nis);
          $pfp_rows = select_sql("SELECT profile_pic_path FROM students_table WHERE nis = '$nis_safe'");
          if (!empty($pfp_rows[0]["profile_pic_path"])) {
            $topbar_pfp = "../../" . $pfp_rows[0]["profile_pic_path"];
          }
        } catch (Exception $e) {}
      }
    }
  ?>
</head>
<body>
  <?php top_bar_html($topbar_pfp, $logged_in, $logged_nis, $unread_count); ?>
  <main>
    <?php 
      if ($student) {
        student_profile_card_html(
          $student["full_name"],
          $student["nis"],
          $student["att_number"],
          $class,
          $major,
          $pfp,
          $desc,
          $registered_comps,
          $joined_comps,
          $achievements,
          $created,
          $viewed_by_owner
        );
      } else {
        echo '<p style="text-align:center;color:var(--text-muted);padding:40px;font-size:1rem;">Siswa dengan NIS "' . htmlspecialchars($requested_nis) . '" tidak ditemukan.</p>';
        echo '<p style="text-align:center;"><a href="../students/students.php" style="color:var(--blue);text-decoration:none;">Kembali ke daftar siswa</a></p>';
      }
    ?>
  </main>
  <?php bottom_bar_html($logged_name); ?>
</body>
</html>