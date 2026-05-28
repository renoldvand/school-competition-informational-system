<?php
  session_start();
  if (!isset($_SESSION["admin_id"])) { header("Location: ../admin_login/admin_login.php"); exit; }

  require_once "../../templates/admin_sidebar.php";
  require_once "../../sql/sql_bridge.php";
  admin_sidebar_css();

  $username = $_SESSION["admin_username"];
  $comp_id = intval($_GET["id"] ?? 0);
  if ($comp_id <= 0) { header("Location: ../admin_comps/admin_comps.php"); exit; }

  try {
    $conn = connect_sql();
    $rows = select_sql("SELECT * FROM comps WHERE id = $comp_id");
    if (empty($rows)) { header("Location: ../admin_comps/admin_comps.php"); exit; }
    $comp = $rows[0];

    $majors = select_sql("SELECT * FROM major_table ORDER BY major_id");
    $ext_links = select_sql("SELECT * FROM comp_external_links WHERE linked_comp = $comp_id");
  } catch (Exception $e) {
    header("Location: ../admin_comps/admin_comps.php"); exit;
  }

  $major_options = '<option value="">-- Semua Jurusan --</option>';
  foreach ($majors as $m) {
    $sel = ($comp["major"] === $m["major_id"]) ? " selected" : "";
    $major_options .= '<option value="' . $m["major_id"] . '"' . $sel . '>' . htmlspecialchars($m["major_name"]) . '</option>';
  }

  // Bangun HTML untuk link rows yang sudah ada
  $ext_links_html = "";
  if (!empty($ext_links)) {
    foreach ($ext_links as $el) {
      $ext_links_html .= '<div class="ext-link-row">'
        . '<input type="text" name="ext_title[]" placeholder="Judul tautan" value="' . htmlspecialchars($el["title"]) . '">'
        . '<input type="text" name="ext_url[]" placeholder="https://..." value="' . htmlspecialchars($el["address"]) . '">'
        . '<button type="button" class="btn btn-danger btn-sm" onclick="this.parentElement.remove()" style="margin:0;">✕</button>'
        . '</div>';
    }
  }
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Lomba | SCIS Admin</title>
</head>
<body>
  <?php admin_sidebar_html('comps', $username); ?>
  <div id="admin_content">
    <h1 class="page-title">Edit Lomba</h1>
    <p class="page-subtitle"><?php echo htmlspecialchars($comp["title"]); ?></p>

    <?php if (isset($_GET["error"])): ?>
      <div class="msg msg-error"><?php echo htmlspecialchars($_GET["error"]); ?></div>
    <?php endif; ?>

    <div class="form-card">
      <form action="admin_comp_edit_handler.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $comp_id; ?>">
        <div class="form-grid">
          <div class="form-group full">
            <label for="title">Judul Lomba *</label>
            <input type="text" name="title" id="title" value="<?php echo htmlspecialchars($comp["title"]); ?>" required>
          </div>
          <div class="form-group">
            <label for="major">Jurusan</label>
            <select name="major" id="major"><?php echo $major_options; ?></select>
          </div>
          <div class="form-group">
            <label for="overseer">Guru Pendamping</label>
            <input type="text" name="overseer" id="overseer" value="<?php echo htmlspecialchars($comp["overseer"] ?? ""); ?>">
          </div>
          <div class="form-group">
            <label for="starts_on">Tanggal Mulai *</label>
            <input type="date" name="starts_on" id="starts_on" value="<?php echo $comp["starts_on"]; ?>" required>
          </div>
          <div class="form-group">
            <label for="ends_on">Tanggal Selesai *</label>
            <input type="date" name="ends_on" id="ends_on" value="<?php echo $comp["ends_on"]; ?>" required>
          </div>
          <div class="form-group">
            <label>Thumbnail</label>
            <input type="file" accept="image/*" name="thumbnail" id="thumbnail">
            <?php if (!empty($comp["thumbnail_path"])): ?>
              <small style="color:var(--text-muted);margin-top:4px;font-size:0.75rem;">Saat ini: <?php echo basename($comp["thumbnail_path"]); ?></small>
              <label class="checkbox-label" style="margin-top:4px;"><input type="checkbox" name="delete_thumbnail" value="1"> Hapus thumbnail</label>
            <?php endif; ?>
          </div>
          <div class="form-group">
            <label>Ikon</label>
            <input type="file" accept="image/*" name="icon" id="icon">
            <?php if (!empty($comp["icon_path"])): ?>
              <small style="color:var(--text-muted);margin-top:4px;font-size:0.75rem;">Saat ini: <?php echo basename($comp["icon_path"]); ?></small>
              <label class="checkbox-label" style="margin-top:4px;"><input type="checkbox" name="delete_icon" value="1"> Hapus ikon</label>
            <?php endif; ?>
          </div>
          <div class="form-group full">
            <label class="checkbox-label"><input type="checkbox" name="is_open" value="1" <?php echo $comp["is_open"] == 1 ? "checked" : ""; ?>> Lomba terbuka untuk pendaftaran</label>
          </div>
          <div class="form-group full">
            <label for="description">Deskripsi *</label>
            <textarea name="description" id="description" rows="8" required><?php echo htmlspecialchars($comp["description"] ?? ""); ?></textarea>
          </div>
          <div class="form-group full">
            <label>Tautan Eksternal</label>
            <div class="link-section" id="ext_links_section">
              <?php echo $ext_links_html; ?>
            </div>
            <button type="button" class="btn btn-primary btn-sm" onclick="addExtLink()" style="margin-top:8px;width:fit-content;">+ Tambah Tautan</button>
          </div>
          <div class="form-actions">
            <button type="submit" class="btn btn-gold">Simpan Perubahan</button>
            <a href="../admin_comps/admin_comps.php" class="btn btn-danger">Batalkan</a>
          </div>
        </div>
      </form>
    </div>
  </div>
  <script>
    function addExtLink() {
      var section = document.getElementById('ext_links_section');
      var row = document.createElement('div');
      row.className = 'ext-link-row';
      row.innerHTML = '<input type="text" name="ext_title[]" placeholder="Judul tautan">'
        + '<input type="text" name="ext_url[]" placeholder="https://...">'
        + '<button type="button" class="btn btn-danger btn-sm" onclick="this.parentElement.remove()" style="margin:0;">✕</button>';
      section.appendChild(row);
    }
  </script>
</body>
</html>