<?php
  session_start();
  if (!isset($_SESSION["admin_id"])) { header("Location: ../admin_login/admin_login.php"); exit; }

  require_once "../../templates/admin_sidebar.php";
  require_once "../../sql/sql_bridge.php";
  admin_sidebar_css();

  $username = $_SESSION["admin_username"];
  $msg = ""; $msg_type = "";
  if (isset($_GET["sent"])) { $msg = "Pengumuman terkirim"; $msg_type = "success"; }
  if (isset($_GET["deleted"])) { $msg = "Pengumuman dihapus"; $msg_type = "success"; }

  // Filter tanggal
  $date_filter = trim($_GET["date"] ?? "today");
  $date_label = "Hari Ini";
  if ($date_filter === "all") {
    $date_label = "Semua";
    $date_where = "";
  } elseif ($date_filter === "today") {
    $date_where = " AND DATE(n.created_on) = CURDATE()";
  } else {
    $date_where = " AND DATE(n.created_on) = '" . mysqli_real_escape_string(connect_sql(), $date_filter) . "'";
    $date_label = date("d/m/Y", strtotime($date_filter));
  }

  try {
    $students = select_sql("SELECT s.nis, s.full_name, c.class_id FROM students_table s JOIN class_table c ON s.class = c.class_id ORDER BY c.class_id, s.att_number");
    $comps = select_sql("SELECT id, title FROM comps ORDER BY title");
    $recent = select_sql("SELECT n.*, s.full_name AS student_name FROM notifications n LEFT JOIN students_table s ON n.target_nis = s.nis WHERE n.type = 'announcement' $date_where ORDER BY n.created_on DESC LIMIT 100");
  } catch (Exception $e) { $students = []; $comps = []; $recent = []; }

  $student_options = '<option value="__all__">Semua Siswa</option>';
  foreach ($students as $s) {
    $student_options .= '<option value="' . $s['nis'] . '">' . htmlspecialchars($s['full_name']) . ' (' . $s['class_id'] . ')</option>';
  }
  $comp_options = '<option value="">-- Tanpa kaitan lomba --</option>';
  foreach ($comps as $c) {
    $comp_options .= '<option value="' . $c['id'] . '">' . htmlspecialchars($c['title']) . '</option>';
  }
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pengumuman | SCIS Admin</title>
</head>
<body>
  <?php admin_sidebar_html('announcements', $username); ?>
  <div id="admin_content">
    <h1 class="page-title">Pengumuman</h1>
    <p class="page-subtitle">Kirim notifikasi ke seluruh siswa, siswa tertentu, atau siswa dalam lomba tertentu.</p>

    <?php if ($msg): ?>
      <div class="msg msg-<?php echo $msg_type; ?>"><?php echo $msg; ?></div>
    <?php endif; ?>

    <div class="form-card" style="margin-bottom:28px;">
      <h3 style="font-family:'Playfair Display',serif;font-size:1rem;color:var(--navy);margin-bottom:16px;">Kirim Pengumuman</h3>
      <form action="admin_announcement_send.php" method="post">
        <div class="form-grid">
          <div class="form-group">
            <label for="target">Kirim Ke</label>
            <select name="target" id="target" onchange="updateTargetUI()">
              <option value="all">Semua Siswa</option>
              <option value="student">Siswa Tertentu</option>
              <option value="comp">Siswa di Lomba Tertentu</option>
            </select>
          </div>
          <div class="form-group" id="student_select_group">
            <label for="target_nis">Pilih Siswa</label>
            <select name="target_nis" id="target_nis"><?php echo $student_options; ?></select>
          </div>
          <div class="form-group" id="comp_select_group">
            <label for="target_comp">Pilih Lomba</label>
            <select name="target_comp" id="target_comp"><?php echo $comp_options; ?></select>
          </div>
          <div class="form-group full">
            <label for="message">Isi Pengumuman *</label>
            <textarea name="message" id="message" rows="4" required></textarea>
          </div>
          <div class="form-actions">
            <button type="submit" class="btn btn-gold">Kirim Pengumuman</button>
          </div>
        </div>
      </form>
      <script>
        // Sembunyikan saat load (pakai inline style agar bisa di-removeProperty nanti)
        document.getElementById('student_select_group').style.display = 'none';
        document.getElementById('comp_select_group').style.display = 'none';
      </script>
    </div>

    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;flex-wrap:wrap;gap:10px;">
      <h3 style="font-family:'Playfair Display',serif;font-size:1rem;color:var(--navy);margin:0;">Riwayat Pengumuman</h3>
      <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
        <label style="font-size:0.78rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;">Filter:</label>
        <a href="admin_announcements.php?date=today" class="btn btn-sm <?php echo $date_filter === 'today' ? 'btn-primary' : 'btn-danger'; ?>">Hari Ini</a>
        <a href="admin_announcements.php?date=<?php echo date('Y-m-d', strtotime('-1 day')); ?>" class="btn btn-sm <?php echo $date_filter === date('Y-m-d', strtotime('-1 day')) ? 'btn-primary' : 'btn-danger'; ?>">Kemarin</a>
        <a href="admin_announcements.php?date=<?php echo date('Y-m-d', strtotime('-7 days')); ?>" class="btn btn-sm <?php echo $date_filter === date('Y-m-d', strtotime('-7 days')) ? 'btn-primary' : 'btn-danger'; ?>">7 Hari Lalu</a>
        <a href="admin_announcements.php?date=all" class="btn btn-sm <?php echo $date_filter === 'all' ? 'btn-primary' : 'btn-danger'; ?>">Semua</a>
        <span style="font-size:0.78rem;color:var(--text-muted);margin-left:4px;">Menampilkan: <?php echo $date_label; ?></span>
      </div>
    </div>

    <?php if (!empty($recent)): ?>
      <div style="margin-bottom:12px;">
        <button type="button" class="btn btn-danger btn-sm" id="bulk_delete_btn" onclick="bulkDeleteSelected()">Hapus yang Dipilih (<span id="selected_count">0</span>)</button>
      </div>
        <table class="data-table">
          <thead>
            <tr>
              <th style="width:36px;"><input type="checkbox" id="select_all" onchange="toggleSelectAll(this)"></th>
              <th>Tanggal</th>
              <th>Penerima</th>
              <th>Pesan</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($recent as $r):
              $receiver = "Semua Siswa";
              if (!empty($r["target_nis"])) $receiver = htmlspecialchars($r["student_name"] ?? $r["target_nis"]);
              if (!empty($r["target_comp_id"])) $receiver .= " <small style='color:var(--text-muted);'>(lomba #" . $r["target_comp_id"] . ")</small>";
            ?>
              <tr>
                <td><input type="checkbox" name="ids[]" value="<?php echo $r['id']; ?>" class="row-check" onchange="updateSelectedCount()"></td>
                <td style="white-space:nowrap;font-size:0.8rem;"><?php echo date("d/m/Y H:i", strtotime($r["created_on"])); ?></td>
                <td style="font-size:0.82rem;"><?php echo $receiver; ?></td>
                <td><?php echo htmlspecialchars($r["message"]); ?></td>
                <td>
                  <a href="admin_announcement_delete.php?id=<?php echo $r['id']; ?>"
                     class="btn btn-danger btn-sm" data-confirm="Hapus pengumuman ini?">Hapus</a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
    <?php else: ?>
      <div class="empty-state"><p>Tidak ada pengumuman untuk periode ini.</p></div>
    <?php endif; ?>
  </div>
  <script>
    function updateTargetUI() {
      var v = document.getElementById('target').value;
      document.getElementById('student_select_group').style.display = v === 'student' ? '' : 'none';
      document.getElementById('comp_select_group').style.display = v === 'comp' ? '' : 'none';
    }

    function toggleSelectAll(el) { var c=document.querySelectorAll('.row-check'); for(var i=0;i<c.length;i++) c[i].checked=el.checked; updateSelectedCount(); }
    function updateSelectedCount() {
      var n=document.querySelectorAll('.row-check:checked').length;
      document.getElementById('selected_count').textContent=n;
      document.getElementById('bulk_delete_btn').style.opacity=n>0?'1':'0.4';
      document.getElementById('bulk_delete_btn').style.pointerEvents=n>0?'':'none';
    }
    function bulkDeleteSelected() {
      var checks=document.querySelectorAll('.row-check:checked');
      if(checks.length===0)return;
      confirmPopup('Hapus '+checks.length+' pengumuman yang dipilih?',function(){
        var form=document.createElement('form');
        form.method='POST';
        form.action='admin_announcement_bulk_delete.php';
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