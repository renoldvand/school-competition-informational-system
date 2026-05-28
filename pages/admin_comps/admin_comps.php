<?php
  session_start();
  if (!isset($_SESSION["admin_id"])) { header("Location: ../admin_login/admin_login.php"); exit; }

  require_once "../../templates/admin_sidebar.php";
  require_once "../../sql/sql_bridge.php";
  admin_sidebar_css();

  $username = $_SESSION["admin_username"];
  $msg = ""; $msg_type = "";

  if (isset($_GET["deleted"])) { $msg = "Lomba berhasil dihapus"; $msg_type = "success"; }
  if (isset($_GET["added"])) { $msg = "Lomba berhasil ditambahkan"; $msg_type = "success"; }
  if (isset($_GET["updated"])) { $msg = "Lomba berhasil diperbarui"; $msg_type = "success"; }
  
  $search = trim($_GET["q"] ?? "");
  try {
    if ($search !== "") {
      $conn = connect_sql();
      $s = mysqli_real_escape_string($conn, $search);

      $comps = select_sql(
        "SELECT c.*, m.major_name,
         (SELECT COUNT(*) FROM comp_registrations WHERE comp_id = c.id) AS reg_count
         FROM comps c
         LEFT JOIN major_table m ON c.major = m.major_id
         WHERE c.title LIKE '%$s%' OR m.major_name LIKE '%$s%'
         ORDER BY c.created_on DESC"
      );
    } else {
      $comps = select_sql(
        "SELECT c.*, m.major_name,
         (SELECT COUNT(*) FROM comp_registrations WHERE comp_id = c.id) AS reg_count
         FROM comps c
         LEFT JOIN major_table m ON c.major = m.major_id
         ORDER BY c.created_on DESC"
      );
    }
  } catch (Exception $e) {
    $comps = [];
  }
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kelola Lomba | SCIS Admin</title>
</head>
<body>
  <?php admin_sidebar_html('comps', $username); ?>
  <div id="admin_content">
    <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:6px;">
      <div>
        <h1 class="page-title">Kelola Lomba</h1>
        <p class="page-subtitle" style="margin-bottom:0;">Tambah, edit, atau hapus lomba.</p>
      </div>
      <a href="../admin_comp_form/admin_comp_form.php" class="btn btn-gold" style="margin-top:8px;">+ Tambah Lomba</a>
    </div>

    <?php if ($msg): ?>
      <div class="msg msg-<?php echo $msg_type; ?>"><?php echo $msg; ?></div>
    <?php endif; ?>

    <?php if (empty($comps)): ?>
      <div class="empty-state"><p>Belum ada lomba.</p></div>
    <?php else: ?>
    <form method="get" style="margin-bottom:20px;display:flex;gap:8px;max-width:400px;">
      <input type="text" name="q" value="<?php echo htmlspecialchars($search); ?>" placeholder="Cari lomba..."
        style="flex:1;padding:9px 14px;border:1.5px solid rgba(42,82,160,0.15);border-radius:8px;font-family:'DM Sans',sans-serif;font-size:0.85rem;outline:none;">
      <button type="submit" class="btn btn-primary">Cari</button>
      <?php if ($search !== ""): ?>
        <a href="admin_comps.php" class="btn btn-danger">Reset</a>
      <?php endif; ?>
    </form>
      <table class="data-table">
        <thead>
          <tr><th>Judul</th><th>Jurusan</th><th>Status</th><th>Pendaftar</th><th>Tanggal</th><th>Aksi</th></tr>
        </thead>
        <tbody>
          <?php foreach ($comps as $c): ?>
            <tr>
              <td><strong><?php echo htmlspecialchars($c["title"]); ?></strong></td>
              <td><?php echo htmlspecialchars($c["major_name"] ?? "Umum"); ?></td>
              <td><span class="badge badge-<?php echo $c['is_open'] == 1 ? 'open' : 'closed'; ?>"><?php echo $c['is_open'] == 1 ? 'Terbuka' : 'Tertutup'; ?></span></td>
              <td><?php
                if ($c["reg_count"] > 0) {
                  echo '<a href="../admin_comp_registrations/admin_comp_registrations.php?comp_id=' . $c["id"] . '" class="btn btn-primary btn-sm">' . $c["reg_count"] . ' orang</a>';
                } else {
                  echo '0';
                }
              ?></td>
              <td style="white-space:nowrap;font-size:0.8rem;"><?php echo date("d/m/Y", strtotime($c["starts_on"])); ?> — <?php echo date("d/m/Y", strtotime($c["ends_on"])); ?></td>
              <td>
                <div class="btn-row">
                  <a href="../admin_comp_edit/admin_comp_edit.php?id=<?php echo $c['id']; ?>" class="btn btn-primary btn-sm">Edit</a>
                  <a href="../admin_comp_delete/admin_comp_delete.php?id=<?php echo $c['id']; ?>" class="btn btn-danger btn-sm" data-confirm="Hapus lomba ini? Semua pendaftaran terkait juga akan dihapus.">Hapus</a>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>
</body>
</html>