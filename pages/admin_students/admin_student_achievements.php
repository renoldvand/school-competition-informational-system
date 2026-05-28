<?php
  session_start();
  if (!isset($_SESSION["admin_id"])) { header("Location: ../admin_login/admin_login.php"); exit; }

  require_once "../../templates/admin_sidebar.php";
  require_once "../../sql/sql_bridge.php";
  admin_sidebar_css();

  $username = $_SESSION["admin_username"];
  $nis = trim($_GET["nis"] ?? "");
  if ($nis === "") { header("Location: admin_students.php"); exit; }

  $msg = ""; $msg_type = "";
  if (isset($_GET["added"])) { $msg = "Pencapaian ditambahkan"; $msg_type = "success"; }
  if (isset($_GET["deleted"])) { $msg = "Pencapaian dihapus"; $msg_type = "success"; }

  try {
    $conn = connect_sql();
    $nis_s = mysqli_real_escape_string($conn, $nis);
    $students = select_sql("SELECT * FROM students_table WHERE nis = '$nis_s'");
    if (empty($students)) { header("Location: admin_students.php"); exit; }
    $student = $students[0];

    $achievements = select_sql(
      "SELECT sa.*, c.title AS comp_title FROM student_achievements sa
       LEFT JOIN comps c ON sa.comp_id = c.id
       WHERE sa.student_nis = '$nis_s' ORDER BY sa.created_on DESC"
    );

    $comps = select_sql("SELECT id, title FROM comps ORDER BY title");
  } catch (Exception $e) { header("Location: admin_students.php"); exit; }

  $result_options = [
    'peserta' => 'Peserta',
    'finalis' => 'Finalis',
    'juara_3' => 'Juara 3',
    'juara_2' => 'Juara 2',
    'juara_1' => 'Juara 1',
  ];

  $comp_options = '<option value="">-- Tidak terkait lomba --</option>';
  foreach ($comps as $c) {
    $comp_options .= '<option value="' . $c['id'] . '">' . htmlspecialchars($c['title']) . '</option>';
  }

  $result_opts = '';
  foreach ($result_options as $k => $v) {
    $result_opts .= '<option value="' . $k . '">' . $v . '</option>';
  }
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pencapaian Siswa | SCIS Admin</title>
</head>
<body>
  <?php admin_sidebar_html('students', $username); ?>
  <div id="admin_content">
    <h1 class="page-title">Pencapaian: <?php echo htmlspecialchars($student["full_name"]); ?></h1>
    <p class="page-subtitle">NIS <?php echo $nis; ?> &middot; Kelola pencapaian lomba siswa.</p>

    <?php if ($msg): ?>
      <div class="msg msg-<?php echo $msg_type; ?>"><?php echo $msg; ?></div>
    <?php endif; ?>

    <div class="form-card" style="margin-bottom:28px;">
      <h3 style="font-family:'Playfair Display',serif;font-size:1rem;color:var(--navy);margin-bottom:16px;">Tambah Pencapaian</h3>
      <form action="admin_achievement_add_handler.php" method="post">
        <input type="hidden" name="nis" value="<?php echo $nis; ?>">
        <div class="form-grid">
          <div class="form-group">
            <label for="comp_id">Lomba</label>
            <select name="comp_id" id="comp_id"><?php echo $comp_options; ?></select>
          </div>
          <div class="form-group">
            <label for="result">Hasil</label>
            <select name="result" id="result"><?php echo $result_opts; ?></select>
          </div>
          <div class="form-group full">
            <label for="achievement_title">Judul Pencapaian</label>
            <input type="text" name="achievement_title" id="achievement_title" placeholder="Opsional, otomatis pakai nama lomba jika kosong">
          </div>
          <div class="form-group full">
            <label for="notes">Catatan</label>
            <input type="text" name="notes" id="notes" placeholder="Opsional, misal: tingkat provinsi, penyelenggara, dll">
          </div>
          <div class="form-actions">
            <button type="submit" class="btn btn-gold">Tambah</button>
            <a href="admin_students.php" class="btn btn-danger">Kembali</a>
          </div>
        </div>
      </form>
    </div>

    <?php if (empty($achievements)): ?>
      <div class="empty-state"><p>Belum ada pencapaian.</p></div>
    <?php else: ?>
      <table class="data-table">
        <thead>
          <tr><th>Lomba</th><th>Hasil</th><th>Catatan</th><th>Tanggal</th><th>Aksi</th></tr>
        </thead>
        <tbody>
          <?php foreach ($achievements as $a):
            $label = $result_options[$a["result"]] ?? $a["result"];
            $title = !empty($a["achievement_title"]) ? $a["achievement_title"] : ($a["comp_title"] ?? "-");
          ?>
            <tr>
              <td><?php echo htmlspecialchars($title); ?></td>
              <td><span class="badge badge-<?php echo $a['result'] === 'peserta' ? 'info' : 'accepted'; ?>"><?php echo $label; ?></span></td>
              <td><?php echo htmlspecialchars($a["notes"] ?? "-"); ?></td>
              <td style="font-size:0.8rem;"><?php echo date("d/m/Y", strtotime($a["created_on"])); ?></td>
              <td>
                <a href="admin_achievement_delete.php?id=<?php echo $a['id']; ?>&nis=<?php echo $nis; ?>"
                   class="btn btn-danger btn-sm" data-confirm="Hapus pencapaian ini?">Hapus</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>
</body>
</html>