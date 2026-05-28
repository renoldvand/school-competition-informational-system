<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Profil | CIS Skensa</title>
  <link rel="stylesheet" href="student_profile_edit.css">
  
  <?php
    session_start();
    require_once "../../templates/top_bar.php";
    require_once "../../templates/bottom_bar.php";
    require_once "../../templates/student_profile_edit_card.php";
    require_once "../../templates/return_link.php";
    require_once "../../sql/sql_bridge.php";

    top_bar_css();
    bottom_bar_css();
    student_profile_edit_card_css();

    // WAJIB login — jika belum, redirect
    if (!isset($_SESSION["nis"])) {
      header("Location: ../student_login/student_login.php?error=" . urlencode("Anda harus login untuk mengedit profil"));
      exit;
    }

    $logged_nis = $_SESSION["nis"];
    $logged_name = $_SESSION["name"];
    $page_title = "Edit Profil | CIS Skensa";

    // Ambil data siswa dari DB
    $student = null;
    try {
      $nis_safe = mysqli_real_escape_string(connect_sql(), $logged_nis);
      $rows = select_sql(
        "SELECT s.*, c.class_id AS class_display
         FROM students_table s
         JOIN class_table c ON s.class = c.class_id
         WHERE s.nis = '$nis_safe'"
      );
      if (!empty($rows)) {
        $student = $rows[0];
        $page_title = "Edit " . $student["full_name"] . " | CIS Skensa";
      }
    } catch (Exception $e) {
      $student = null;
    }

    $default_pfp = "../../assets/images/default_pfp.jpg";

    // Ambil foto profil user yang login untuk top bar
    $logged_in = isset($_SESSION["nis"]);
    $logged_nis = $_SESSION["nis"];
    $logged_name = $_SESSION["name"];

    $unread_count = 0;
    if ($logged_in) {
      try {
        $nis_safe = mysqli_real_escape_string(connect_sql(), $logged_nis);
        $ur = select_sql("SELECT COUNT(*) AS cnt FROM notifications WHERE target_nis = '$nis_safe' AND is_read = 0");
        $unread_count = intval($ur[0]["cnt"]);
      } catch (Exception $e) {}
    }
    
    if ($logged_in) {
      try {
        $nis_safe = mysqli_real_escape_string(connect_sql(), $logged_nis);
        $pfp_rows = select_sql("SELECT profile_pic_path FROM students_table WHERE nis = '$nis_safe'");
        if (!empty($pfp_rows[0]["profile_pic_path"])) {
          $default_pfp = "../../" . $pfp_rows[0]["profile_pic_path"];
        }
      } catch (Exception $e) {}
    }

    // Ambil pesan success/error dari URL
    $message = "";
    $message_type = "";
    if (isset($_GET["success"])) {
      $message = $_GET["success"];
      $message_type = "success";
    } elseif (isset($_GET["error"])) {
      $message = $_GET["error"];
      $message_type = "error";
    }
  ?>
</head>
<body>
  <?php top_bar_html($default_pfp, $logged_in, $logged_nis, $unread_count); ?>
  <main>
    <?php if ($message): ?>
      <div class="edit-message <?php echo $message_type; ?>" style="
        max-width:620px;margin:0 auto 16px;padding:12px 20px;border-radius:9px;font-size:0.85rem;font-weight:500;
        <?php if ($message_type === 'success'): ?>
          background:rgba(34,139,34,0.1);color:#1a6630;border:1px solid rgba(34,139,34,0.2);
        <?php else: ?>
          background:rgba(217,48,37,0.1);color:#d93025;border:1px solid rgba(217,48,37,0.2);
        <?php endif; ?>
      ">
        <?php echo htmlspecialchars($message); ?>
      </div>
    <?php endif; ?>

    <?php 
      if ($student) {
        student_profile_edit_card_html(
          $student["nis"],
          $student["full_name"],
          $student["att_number"],
          $student["class_display"],
          $student["description"] ?? "",
          $student["profile_pic_path"] ?? ""
        );
      } else {
        echo '<p style="text-align:center;color:var(--text-muted);padding:40px;">Data siswa tidak ditemukan.</p>';
      }
    ?>
  </main>
  <?php bottom_bar_html($logged_name); ?>
  <script>
    function handleDeletePfp() {
      // Set flag hapus
      document.getElementById('delete_pfp_flag').value = '1';
      // Sembunyikan foto saat ini dan tombol hapus
      var img = document.getElementById('current_pfp_img');
      var btn = document.getElementById('delete_pfp_btn');
      if (img) img.style.display = 'none';
      if (btn) btn.style.display = 'none';
      // Reset file input
      document.getElementById('new_pfp').value = '';
    }
  </script>
  <?php return_link_js(); ?>
</body>
</html>