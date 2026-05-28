<?php
  session_start();
  if (!isset($_SESSION["admin_id"])) { header("Location: ../admin_login/admin_login.php"); exit; }

  require_once "../../sql/sql_bridge.php";

  $comp_id = intval($_POST["id"] ?? 0);
  if ($comp_id <= 0) { header("Location: ../admin_comps/admin_comps.php"); exit; }

  $title = trim($_POST["title"] ?? "");
  $major = trim($_POST["major"] ?? "");
  $overseer = trim($_POST["overseer"] ?? "");
  $starts_on = $_POST["starts_on"] ?? "";
  $ends_on = $_POST["ends_on"] ?? "";
  $is_open = isset($_POST["is_open"]) ? 1 : 0;
  $description = trim($_POST["description"] ?? "");

  if ($title === "" || $starts_on === "" || $ends_on === "" || $description === "") {
    header("Location: admin_comp_edit.php?id=$comp_id&error=" . urlencode("Judul, tanggal, dan deskripsi wajib diisi"));
    exit;
  }

  try {
    $conn = connect_sql();
    $title_safe = mysqli_real_escape_string($conn, $title);
    $desc_safe = mysqli_real_escape_string($conn, $description);
    $major_sql = $major === "" ? "NULL" : "'" . mysqli_real_escape_string($conn, $major) . "'";
    $overseer_sql = $overseer === "" ? "NULL" : "'" . mysqli_real_escape_string($conn, $overseer) . "'";

    // Handle thumbnail
    $delete_thumb = isset($_POST["delete_thumbnail"]) ? true : false;
    if ($delete_thumb) {
      $old = select_sql("SELECT thumbnail_path FROM comps WHERE id = $comp_id");
      if (!empty($old[0]["thumbnail_path"])) { $f = "../../" . $old[0]["thumbnail_path"]; if (file_exists($f)) unlink($f); }
      $thumb_sql = "NULL";
    } elseif (isset($_FILES["thumbnail"]) && $_FILES["thumbnail"]["error"] === UPLOAD_ERR_OK) {
      $old = select_sql("SELECT thumbnail_path FROM comps WHERE id = $comp_id");
      if (!empty($old[0]["thumbnail_path"])) { $f = "../../" . $old[0]["thumbnail_path"]; if (file_exists($f)) unlink($f); }
      $thumb_sql = "'" . upload_image($_FILES["thumbnail"], "comp_thumb_" . $comp_id . "_" . time()) . "'";
    } else {
      $thumb_sql = "thumbnail_path";
    }

    // Handle icon
    $delete_icon = isset($_POST["delete_icon"]) ? true : false;
    if ($delete_icon) {
      $old = select_sql("SELECT icon_path FROM comps WHERE id = $comp_id");
      if (!empty($old[0]["icon_path"])) { $f = "../../" . $old[0]["icon_path"]; if (file_exists($f)) unlink($f); }
      $icon_sql = "NULL";
    } elseif (isset($_FILES["icon"]) && $_FILES["icon"]["error"] === UPLOAD_ERR_OK) {
      $old = select_sql("SELECT icon_path FROM comps WHERE id = $comp_id");
      if (!empty($old[0]["icon_path"])) { $f = "../../" . $old[0]["icon_path"]; if (file_exists($f)) unlink($f); }
      $icon_sql = "'" . upload_image($_FILES["icon"], "comp_icon_" . $comp_id . "_" . time()) . "'";
    } else {
      $icon_sql = "icon_path";
    }

    execute_sql(
      "UPDATE comps SET title='$title_safe', major=$major_sql, description='$desc_safe',
       overseer=$overseer_sql, thumbnail_path=$thumb_sql, icon_path=$icon_sql,
       starts_on='$starts_on', ends_on='$ends_on', is_open=$is_open
       WHERE id = $comp_id"
    );

    // Insert external links (dinamis)
    execute_sql("DELETE FROM comp_external_links WHERE linked_comp = $comp_id");
    if (isset($_POST["ext_title"]) && is_array($_POST["ext_title"])) {
      for ($i = 0; $i < count($_POST["ext_title"]); $i++) {
        $lt = trim($_POST["ext_title"][$i] ?? "");
        $lu = trim($_POST["ext_url"][$i] ?? "");
        if ($lt !== "" && $lu !== "") {
          $lt_s = mysqli_real_escape_string($conn, $lt);
          $lu_s = mysqli_real_escape_string($conn, $lu);
          $conn->query("INSERT INTO comp_external_links (title, address, linked_comp) VALUES ('$lt_s', '$lu_s', $comp_id)");
        }
      }
    }

    header("Location: ../admin_comps/admin_comps.php?updated=1");
    exit;

  } catch (Exception $e) {
    header("Location: admin_comp_edit.php?id=$comp_id&error=" . urlencode($e->getMessage()));
    exit;
  }

  function upload_image($file, $prefix) {
    $allowed = ["image/jpeg","image/png","image/gif","image/webp"];
    if (!in_array($file["type"], $allowed)) throw new Exception("Format gambar tidak didukung");
    if ($file["size"] > 3 * 1024 * 1024) throw new Exception("Ukuran gambar maksimal 3MB");
    $ext = pathinfo($file["name"], PATHINFO_EXTENSION);
    $filename = $prefix . "." . $ext;
    $dir = "../../assets/images/comps/";
    if (!is_dir($dir)) mkdir($dir, 0755, true);
    if (move_uploaded_file($file["tmp_name"], $dir . $filename)) return "assets/images/comps/" . $filename;
    throw new Exception("Gagal mengupload gambar");
  }
?>