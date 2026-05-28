<?php
  session_start();
  if (!isset($_SESSION["admin_id"])) { header("Location: ../admin_login/admin_login.php"); exit; }

  require_once "../../templates/admin_sidebar.php";
  require_once "../../sql/sql_bridge.php";
  admin_sidebar_css();

  $username = $_SESSION["admin_username"];
  $msg = ""; $msg_type = "";
  if (isset($_GET["deleted"])) { $msg = "Siswa berhasil dihapus"; $msg_type = "success"; }
  if (isset($_GET["added"])) { $msg = "Siswa berhasil ditambahkan"; $msg_type = "success"; }

  $search = trim($_GET["q"] ?? "");

  try {
    $conn = connect_sql();
    if ($search !== "") {
      $s = mysqli_real_escape_string($conn, $search);
      $students = select_sql(
        "SELECT s.*, c.class_id AS class_display, m.major_name
         FROM students_table s JOIN class_table c ON s.class = c.class_id
         LEFT JOIN major_table m ON c.major_id = m.major_id
         WHERE s.full_name LIKE '%$s%' OR s.nis LIKE '%$s%' OR c.class_id LIKE '%$s%'
         ORDER BY c.class_id, s.att_number"
      );
    } else {
      $students = select_sql(
        "SELECT s.*, c.class_id AS class_display, m.major_name
         FROM students_table s JOIN class_table c ON s.class = c.class_id
         LEFT JOIN major_table m ON c.major_id = m.major_id
         ORDER BY c.class_id, s.att_number"
      );
    }
  } catch (Exception $e) { $students = []; }
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kelola Siswa | SCIS Admin</title>
</head>
<body>
  <?php admin_sidebar_html('students', $username); ?>
  <div id="admin_content">
    <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:6px;">
      <div>
        <h1 class="page-title">Kelola Siswa</h1>
        <p class="page-subtitle" style="margin-bottom:0;">Tambah, lihat detail, kelola pencapaian, atau hapus siswa.</p>
      </div>
      <a href="admin_student_add.php" class="btn btn-gold" style="margin-top:8px;">+ Tambah Siswa</a>
    </div>

    <?php if ($msg): ?>
      <div class="msg msg-<?php echo $msg_type; ?>"><?php echo $msg; ?></div>
    <?php endif; ?>

    <form method="get" style="margin-bottom:20px;display:flex;gap:8px;max-width:400px;">
      <input type="text" name="q" value="<?php echo htmlspecialchars($search); ?>" placeholder="Cari nama, NIS, kelas..."
        style="flex:1;padding:9px 14px;border:1.5px solid rgba(42,82,160,0.15);border-radius:8px;font-family:'DM Sans',sans-serif;font-size:0.85rem;outline:none;">
      <button type="submit" class="btn btn-primary">Cari</button>
      <?php if ($search !== ""): ?>
        <a href="admin_students.php" class="btn btn-danger">Reset</a>
      <?php endif; ?>
    </form>

    <?php if (empty($students)): ?>
      <div class="empty-state"><p>Tidak ada data siswa ditemukan.</p></div>
    <?php else: ?>
      <table class="data-table">
        <thead>
          <tr><th>NIS</th><th>Nama</th><th>Kelas</th><th>Jurusan</th><th>Pencapaian</th><th>Aksi</th></tr>
        </thead>
        <tbody>
          <?php foreach ($students as $s):
            $ach_count = 0;
            try { $ach_count = count(select_sql("SELECT id FROM student_achievements WHERE student_nis = '" . $s["nis"] . "'")); } catch(Exception $e) {}
          ?>
            <tr>
              <td><?php echo $s["nis"]; ?></td>
              <td><strong><?php echo htmlspecialchars($s["full_name"]); ?></strong></td>
              <td><?php echo htmlspecialchars($s["class_display"]); ?></td>
              <td><?php echo htmlspecialchars($s["major_name"] ?? "-"); ?></td>
              <td><?php echo $ach_count; ?></td>
              <td>
                <div class="btn-row">
                  <a href="admin_student_achievements.php?nis=<?php echo $s["nis"]; ?>" class="btn btn-primary btn-sm">Pencapaian</a>
                  <a href="../admin_student_delete/admin_student_delete.php?nis=<?php echo $s["nis"]; ?>"
                     class="btn btn-danger btn-sm"
                     data-confirm="Hapus siswa <?php echo addslashes($s['full_name']); ?>?">Hapus</a>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>
    <script>
    function toggleSelectAll(el) { var c=document.querySelectorAll('.row-check'); for(var i=0;i<c.length;i++) c[i].checked=el.checked; updateSelectedCount(); }
    function updateSelectedCount() {
      var n=document.querySelectorAll('.row-check:checked').length;
      document.getElementById('selected_count').textContent=n;
      document.getElementById('bulk_del_btn').style.opacity=n>0?'1':'0.4';
      document.getElementById('bulk_del_btn').style.pointerEvents=n>0?'':'none';
    }
    function bulkDelete() {
      var checks=document.querySelectorAll('.row-check:checked');
      var n=checks.length;
      if(n===0)return;
      confirmPopup('Hapus '+n+' siswa yang dipilih?',function(){
        var form=document.createElement('form');
        form.method='POST';
        form.action='admin_students_bulk_delete.php';
        for(var i=0;i<checks.length;i++){
          var input=document.createElement('input');
          input.type='hidden';input.name='ids[]';input.value=checks[i].value;
          form.appendChild(input);
        }
        document.body.appendChild(form);
        form.submit();
      });
    }
    updateSelectedCount();
  </script>
</body>
</html>