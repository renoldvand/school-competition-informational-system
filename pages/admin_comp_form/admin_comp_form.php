<?php
  session_start();
  if (!isset($_SESSION["admin_id"])) { header("Location: ../admin_login/admin_login.php"); exit; }

  require_once "../../templates/admin_sidebar.php";
  require_once "../../sql/sql_bridge.php";
  admin_sidebar_css();

  $username = $_SESSION["admin_username"];

  try {
    $majors = select_sql("SELECT * FROM major_table ORDER BY major_id");
  } catch (Exception $e) {
    $majors = [];
  }

  $major_options = '<option value="">-- Semua Jurusan --</option>';
  foreach ($majors as $m) {
    $major_options .= '<option value="' . $m["major_id"] . '">' . htmlspecialchars($m["major_name"]) . '</option>';
  }
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tambah Lomba | SCIS Admin</title>
</head>
<body>
  <?php admin_sidebar_html('comp_add', $username); ?>
  <div id="admin_content">
    <h1 class="page-title">Tambah Lomba Baru</h1>
    <p class="page-subtitle">Isi data lomba yang akan ditambahkan.</p>

    <?php if (isset($_GET["error"])): ?>
      <div class="msg msg-error"><?php echo htmlspecialchars($_GET["error"]); ?></div>
    <?php endif; ?>

    <div class="form-card">
      <form action="admin_comp_form_handler.php" method="post" enctype="multipart/form-data">
        <div class="form-grid">
          <div class="form-group full">
            <label for="title">Judul Lomba *</label>
            <input type="text" name="title" id="title" required>
          </div>
          <div class="form-group">
            <label for="major">Jurusan</label>
            <select name="major" id="major"><?php echo $major_options; ?></select>
          </div>
          <div class="form-group">
            <label for="overseer">Guru Pendamping</label>
            <input type="text" name="overseer" id="overseer" placeholder="Opsional">
          </div>
          <div class="form-group">
            <label for="starts_on">Tanggal Mulai *</label>
            <input type="date" name="starts_on" id="starts_on" required>
          </div>
          <div class="form-group">
            <label for="ends_on">Tanggal Selesai *</label>
            <input type="date" name="ends_on" id="ends_on" required>
          </div>
          <div class="form-group">
            <label for="thumbnail">Thumbnail</label>
            <input type="file" accept="image/*" name="thumbnail" id="thumbnail">
          </div>
          <div class="form-group">
            <label for="icon">Ikon</label>
            <input type="file" accept="image/*" name="icon" id="icon">
          </div>
          <div class="form-group full">
            <label class="checkbox-label">
              <input type="checkbox" name="is_open" value="1" checked>
              Lomba terbuka untuk pendaftaran
            </label>
          </div>
          <div class="form-group full">
            <label for="description">Deskripsi *</label>
            <textarea name="description" id="description" rows="8" required></textarea>
          </div>

          <div class="form-group full">
            <div class="form-group full">
              <label>Tautan Eksternal (Opsional)</label>
              <div class="link-section" id="ext_links_section">
                <div class="ext-link-row">
                  <input type="text" name="ext_title[]" placeholder="Judul tautan">
                  <input type="text" name="ext_url[]" placeholder="https://...">
                  <button type="button" class="btn btn-danger btn-sm ext-remove-btn" onclick="this.parentElement.remove()" style="margin:0;">✕</button>
                </div>
              </div>
              <button type="button" class="btn btn-primary btn-sm" onclick="addExtLink()" style="margin-top:8px;width:fit-content;">+ Tambah Tautan</button>
            </div>
          </div>

          <div class="form-actions">
            <button type="submit" class="btn btn-gold">Simpan Lomba</button>
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
      row.innerHTML = '<input type="text" name="ext_title[]" placeholder="Judul tautan"><input type="text" name="ext_url[]" placeholder="https://..."><button type="button" class="btn btn-danger btn-sm ext-remove-btn" onclick="this.parentElement.remove()" style="margin:0;">✕</button>';
      section.appendChild(row);
    }
  </script>
</body>
</html>