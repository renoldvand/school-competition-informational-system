<?php
  session_start();
  if (!isset($_SESSION["admin_id"])) { header("Location: ../admin_login/admin_login.php"); exit; }

  require_once "../../templates/admin_sidebar.php";
  require_once "../../sql/sql_bridge.php";
  admin_sidebar_css();

  $username = $_SESSION["admin_username"];
  $search = trim($_GET["q"] ?? "");
  $msg = ""; $msg_type = "";
  if (isset($_GET["updated"])) { $msg = "Status pendaftaran diperbarui"; $msg_type = "success"; }

  try {
    if ($search !== "") {
      $conn = connect_sql();
      $s = mysqli_real_escape_string($conn, $search);
      $regs = select_sql(
        "SELECT cr.*, s.full_name, s.class AS student_class, c.title AS comp_title
         FROM comp_registrations cr JOIN students_table s ON cr.student_nis = s.nis
         JOIN comps c ON cr.comp_id = c.id
         WHERE s.full_name LIKE '%$s%' OR c.title LIKE '%$s%' OR cr.status LIKE '%$s%'
         ORDER BY cr.registered_on DESC"
      );
    } else {
      $regs = select_sql(
        "SELECT cr.*, s.full_name, s.class AS student_class, c.title AS comp_title
         FROM comp_registrations cr JOIN students_table s ON cr.student_nis = s.nis
         JOIN comps c ON cr.comp_id = c.id
         ORDER BY cr.registered_on DESC"
      );
    }
  } catch (Exception $e) {
    $regs = [];
  }
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pendaftaran | SCIS Admin</title>
</head>
<body>
  <?php admin_sidebar_html('regs', $username); ?>
  <div id="admin_content">
    <h1 class="page-title">Pendaftaran Lomba</h1>
    <p class="page-subtitle">Kelola status pendaftaran siswa ke lomba.</p>

    <?php if ($msg): ?>
      <div class="msg msg-<?php echo $msg_type; ?>"><?php echo $msg; ?></div>
    <?php endif; ?>

    <form method="get" style="margin-bottom:20px;display:flex;gap:8px;max-width:400px;">
      <input type="text" name="q" value="<?php echo htmlspecialchars($search); ?>" placeholder="Cari siswa, lomba, status..."
        style="flex:1;padding:9px 14px;border:1.5px solid rgba(42,82,160,0.15);border-radius:8px;font-family:'DM Sans',sans-serif;font-size:0.85rem;outline:none;">
      <button type="submit" class="btn btn-primary">Cari</button>
      <?php if ($search !== ""): ?>
        <a href="admin_registrations.php" class="btn btn-danger">Reset</a>
      <?php endif; ?>
    </form>
    
    <?php if (empty($regs)): ?>
      <div class="empty-state"><p>Belum ada pendaftaran.</p></div>
    <?php else: ?>
      <table class="data-table">
        <thead>
          <tr><th>Tanggal Daftar</th><th>Siswa</th><th>Kelas</th><th>Lomba</th><th>Status</th><th>Aksi</th></tr>
        </thead>
        <tbody>
          <?php foreach ($regs as $r):
            $class_row = select_sql("SELECT class_id FROM class_table WHERE class_id = '" . $r["student_class"] . "'");
            $class_display = !empty($class_row) ? $class_row[0]["class_id"] : $r["student_class"];
          ?>
            <tr>
              <td style="white-space:nowrap;font-size:0.8rem;"><?php echo date("d/m/Y H:i", strtotime($r["registered_on"])); ?></td>
              <td><strong><?php echo htmlspecialchars($r["full_name"]); ?></strong> <small style="color:var(--text-muted);">(<?php echo $r["student_nis"]; ?>)</small></td>
              <td><?php echo htmlspecialchars($class_display); ?></td>
              <td><?php echo htmlspecialchars($r["comp_title"]); ?></td>
              <td><span class="badge badge-<?php echo $r['status']; ?>"><?php echo $r['status']; ?></span></td>
              <td>
                <?php if ($r["status"] === "pending"): ?>
                  <div class="btn-row">
                    <a href="admin_registration_update.php?id=<?php echo $r['id']; ?>&status=accepted" class="btn btn-primary btn-sm">Terima</a>
                    <a href="admin_registration_update.php?id=<?php echo $r['id']; ?>&status=rejected" class="btn btn-danger btn-sm">Tolak</a>
                  </div>
                <?php elseif ($r["status"] === "accepted"): ?>
                  <a href="admin_registration_update.php?id=<?php echo $r['id']; ?>&status=pending" class="btn btn-danger btn-sm">Batalkan</a>
                <?php else: ?>
                  <a href="admin_registration_update.php?id=<?php echo $r['id']; ?>&status=pending" class="btn btn-danger btn-sm">Batalkan</a>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>
</body>
</html>