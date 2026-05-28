<?php
  session_start();
  if (!isset($_SESSION["admin_id"])) { header("Location: ../admin_login/admin_login.php"); exit; }

  require_once "../../templates/admin_sidebar.php";
  require_once "../../sql/sql_bridge.php";
  admin_sidebar_css();

  $username = $_SESSION["admin_username"];

  try {
    $majors = select_sql("SELECT * FROM major_table ORDER BY major_id");
  } catch (Exception $e) { $majors = []; }

  $major_options = '<option value="">-- Pilih Jurusan --</option>';
  foreach ($majors as $m) {
    $major_options .= '<option value="' . $m["major_id"] . '">' . htmlspecialchars($m["major_name"]) . '</option>';
  }

  try {
    $classes = select_sql("SELECT c.class_id, m.major_id FROM class_table c JOIN major_table m ON c.major_id = m.major_id ORDER BY c.class_id");
  } catch (Exception $e) { $classes = []; }
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tambah Siswa | SCIS Admin</title>
</head>
<body>
  <?php admin_sidebar_html('students', $username); ?>
  <div id="admin_content">
    <h1 class="page-title">Tambah Siswa Baru</h1>
    <p class="page-subtitle">Buat akun siswa langsung dari panel admin.</p>

    <?php if (isset($_GET["error"])): ?>
      <div class="msg msg-error"><?php echo htmlspecialchars($_GET["error"]); ?></div>
    <?php endif; ?>

    <div class="form-card">
      <form action="admin_student_add_handler.php" method="post">
        <div class="form-grid">
          <div class="form-group">
            <label for="nis">NIS *</label>
            <input type="number" name="nis" id="nis" min="1" required>
          </div>
          <div class="form-group">
            <label for="full_name">Nama Lengkap *</label>
            <input type="text" name="full_name" id="full_name" required>
          </div>
          <div class="form-group">
            <label for="major">Jurusan *</label>
            <select name="major" id="major" onchange="filterClasses()"><?php echo $major_options; ?></select>
          </div>
          <div class="form-group">
            <label for="class">Kelas *</label>
            <select name="class" id="class">
              <option value="">-- Pilih jurusan dulu --</option>
              <?php foreach ($classes as $c): ?>
                <option class="class-opt" data-major="<?php echo $c['major_id']; ?>" value="<?php echo $c['class_id']; ?>" style="display:none;"><?php echo $c['class_id']; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group">
            <label for="att_number">No. Absen *</label>
            <input type="number" name="att_number" id="att_number" min="1" required>
          </div>
          <div class="form-group">
            <label for="password">Kata Sandi *</label>
            <input type="text" name="password" id="password" value="12345678" required>
          </div>
          <div class="form-actions">
            <button type="submit" class="btn btn-gold">Tambah Siswa</button>
            <a href="admin_students.php" class="btn btn-danger">Batalkan</a>
          </div>
        </div>
      </form>
    </div>
  </div>
  <script>
    function filterClasses() {
      var major = document.getElementById('major').value;
      var opts = document.querySelectorAll('#class .class-opt');
      var first = true;
      opts.forEach(function(o) {
        if (o.getAttribute('data-major') === major) { o.style.display = ''; if (first) { o.selected = true; first = false; } }
        else { o.style.display = 'none'; o.selected = false; }
      });
    }
  </script>
</body>
</html>