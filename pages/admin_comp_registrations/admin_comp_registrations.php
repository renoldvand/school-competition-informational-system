<?php
  session_start();
  if (!isset($_SESSION["admin_id"])) { header("Location: ../admin_login/admin_login.php"); exit; }

  require_once "../../templates/admin_sidebar.php";
  require_once "../../sql/sql_bridge.php";
  admin_sidebar_css();

  $username = $_SESSION["admin_username"];
  $comp_id = intval($_GET["comp_id"] ?? 0);
  if ($comp_id <= 0) { header("Location: ../admin_comps/admin_comps.php"); exit; }

  $msg = ""; $msg_type = "";
  if (isset($_GET["updated"])) { $msg = "Status diperbarui"; $msg_type = "success"; }
  if (isset($_GET["removed"])) { $msg = "Siswa dihapus dari lomba"; $msg_type = "success"; }

  try {
    $comp = select_sql("SELECT * FROM comps WHERE id = $comp_id");
    if (empty($comp)) { header("Location: ../admin_comps/admin_comps.php"); exit; }

    $regs = select_sql(
      "SELECT cr.*, s.full_name, s.class AS student_class
       FROM comp_registrations cr JOIN students_table s ON cr.student_nis = s.nis
       WHERE cr.comp_id = $comp_id ORDER BY cr.registered_on DESC"
    );
  } catch (Exception $e) { $regs = []; }
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pendaftar Lomba | SCIS Admin</title>
</head>
<body>
  <?php admin_sidebar_html('comps', $username); ?>
  <div id="admin_content">
    <h1 class="page-title">Pendaftar: <?php echo htmlspecialchars($comp[0]["title"]); ?></h1>
    <p class="page-subtitle"><a href="../admin_comps/admin_comps.php" style="color:var(--blue);text-decoration:none;">← Kembali ke Kelola Lomba</a></p>

    <?php if ($msg): ?>
      <div class="msg msg-<?php echo $msg_type; ?>"><?php echo $msg; ?></div>
    <?php endif; ?>

    <?php if (empty($regs)): ?>
      <div class="empty-state"><p>Belum ada siswa mendaftar.</p></div>
    <?php else: ?>
      <table class="data-table">
        <thead><tr><th>Tanggal</th><th>Siswa</th><th>Kelas</th><th>Status</th><th>Aksi</th></tr></thead>
        <tbody>
          <?php foreach ($regs as $r):
            $class_row = select_sql("SELECT class_id FROM class_table WHERE class_id = '" . $r["student_class"] . "'");
            $class_disp = !empty($class_row) ? $class_row[0]["class_id"] : $r["student_class"];
          ?>
            <tr>
              <td style="font-size:0.8rem;white-space:nowrap;"><?php echo date("d/m/Y H:i", strtotime($r["registered_on"])); ?></td>
              <td>
                <strong><?php echo htmlspecialchars($r["full_name"]); ?></strong>
                <small style="color:var(--text-muted);"> (<?php echo $r["student_nis"]; ?>)</small>
                <br><a href="../admin_students/admin_student_achievements.php?nis=<?php echo $r["student_nis"]; ?>" style="font-size:0.72rem;color:var(--blue);">Kelola Pencapaian</a>
              </td>
              <td><?php echo htmlspecialchars($class_disp); ?></td>
              <td><span class="badge badge-<?php echo $r['status']; ?>"><?php echo $r['status']; ?></span></td>
              <td>
                <?php if ($r["status"] === "pending"): ?>
                  <div class="btn-row">
                    <a href="../admin_registrations/admin_registration_update.php?id=<?php echo $r['id']; ?>&status=accepted" class="btn btn-primary btn-sm">Terima</a>
                    <a href="../admin_registrations/admin_registration_update.php?id=<?php echo $r['id']; ?>&status=rejected" class="btn btn-danger btn-sm">Tolak</a>
                  </div>
                <?php else: ?>
                  <div class="btn-row">
                    <a href="../admin_registrations/admin_registration_update.php?id=<?php echo $r['id']; ?>&status=pending" class="btn btn-danger btn-sm">Batalkan</a>
                    <a href="admin_comp_registration_remove.php?id=<?php echo $r['id']; ?>&comp_id=<?php echo $comp_id; ?>" class="btn btn-danger btn-sm" data-confirm="Hapus siswa ini dari daftar lomba?">Hapus</a>
                  </div>
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