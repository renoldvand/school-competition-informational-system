<?php
  session_start();
  if (!isset($_SESSION["admin_id"])) { header("Location: ../admin_login/admin_login.php"); exit; }

  require_once "../../templates/admin_sidebar.php";
  require_once "../../sql/sql_bridge.php";
  admin_sidebar_css();

  $username = $_SESSION["admin_username"];

  try {
    $total_students = count(select_sql("SELECT nis FROM students_table"));
    $total_comps = count(select_sql("SELECT id FROM comps"));
    $pending_regs = count(select_sql("SELECT id FROM comp_registrations WHERE status = 'pending'"));
    $open_comps = count(select_sql("SELECT id FROM comps WHERE is_open = 1"));
  } catch (Exception $e) {
    $total_students = 0; $total_comps = 0; $pending_regs = 0; $open_comps = 0;
  }

  try {
    $recent_regs = select_sql(
      "SELECT cr.id, cr.student_nis, cr.status, cr.registered_on, s.full_name, c.title AS comp_title
       FROM comp_registrations cr
       JOIN students_table s ON cr.student_nis = s.nis
       JOIN comps c ON cr.comp_id = c.id
       ORDER BY cr.registered_on DESC LIMIT 8"
    );
  } catch (Exception $e) {
    $recent_regs = [];
  }
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard | SCIS Admin</title>
</head>
<body>
  <?php admin_sidebar_html('dashboard', $username); ?>
  <div id="admin_content">
    <h1 class="page-title">Dashboard</h1>
    <p class="page-subtitle">Ringkasan data Competition Informational System.</p>

    <div class="stat-grid">
      <div class="stat-card">
        <div class="stat-number"><?php echo $total_students; ?></div>
        <div class="stat-label">Total Siswa</div>
      </div>
      <div class="stat-card blue">
        <div class="stat-number"><?php echo $total_comps; ?></div>
        <div class="stat-label">Total Lomba</div>
      </div>
      <div class="stat-card gold">
        <div class="stat-number"><?php echo $pending_regs; ?></div>
        <div class="stat-label">Pendaftaran Pending</div>
      </div>
      <div class="stat-card green">
        <div class="stat-number"><?php echo $open_comps; ?></div>
        <div class="stat-label">Lomba Berlangsung</div>
      </div>
    </div>

    <h2 style="font-family:'Playfair Display',serif;font-size:1.1rem;color:var(--navy);margin-bottom:14px;">Pendaftaran Terbaru</h2>
    <?php if (empty($recent_regs)): ?>
      <div class="empty-state"><p>Belum ada pendaftaran.</p></div>
    <?php else: ?>
      <table class="data-table">
        <thead>
          <tr><th>Siswa</th><th>Lomba</th><th>Tanggal</th><th>Status</th></tr>
        </thead>
        <tbody>
          <?php foreach ($recent_regs as $r): ?>
            <tr>
              <td><?php echo htmlspecialchars($r["full_name"]); ?> <small style="color:var(--text-muted);">(<?php echo $r["student_nis"]; ?>)</small></td>
              <td><?php echo htmlspecialchars($r["comp_title"]); ?></td>
              <td><?php echo date("d/m/Y H:i", strtotime($r["registered_on"])); ?></td>
              <td><span class="badge badge-<?php echo $r['status']; ?>"><?php echo $r['status']; ?></span></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>
</body>
</html>