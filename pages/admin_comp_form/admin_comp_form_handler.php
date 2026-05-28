<?php
  session_start();
  if (!isset($_SESSION["admin_id"])) { header("Location: ../admin_login/admin_login.php"); exit; }

  require_once "../../sql/sql_bridge.php";

  $title = trim($_POST["title"] ?? "");
  $major = trim($_POST["major"] ?? "");
  $overseer = trim($_POST["overseer"] ?? "");
  $starts_on = $_POST["starts_on"] ?? "";
  $ends_on = $_POST["ends_on"] ?? "";
  $is_open = isset($_POST["is_open"]) ? 1 : 0;
  $description = trim($_POST["description"] ?? "");

  if ($title === "" || $starts_on === "" || $ends_on === "" || $description === "") {
    header("Location: admin_comp_form.php?error=" . urlencode("Judul, tanggal, dan deskripsi wajib diisi"));
    exit;
  }

  $conn = null;
  try {
    $conn = connect_sql();
    $title_safe = mysqli_real_escape_string($conn, $title);
    $desc_safe = mysqli_real_escape_string($conn, $description);
    $major_sql = $major === "" ? "NULL" : "'" . mysqli_real_escape_string($conn, $major) . "'";
    $overseer_sql = $overseer === "" ? "NULL" : "'" . mysqli_real_escape_string($conn, $overseer) . "'";

    $thumb_sql = "NULL";
    if (isset($_FILES["thumbnail"]) && $_FILES["thumbnail"]["error"] === UPLOAD_ERR_OK) {
      $thumb_sql = "'" . upload_image($conn, $_FILES["thumbnail"], "comp_thumb_" . time()) . "'";
    }

    $icon_sql = "NULL";
    if (isset($_FILES["icon"]) && $_FILES["icon"]["error"] === UPLOAD_ERR_OK) {
      $icon_sql = "'" . upload_image($conn, $_FILES["icon"], "comp_icon_" . time()) . "'";
    }

    // Insert langsung di koneksi yang sama
    $conn->query(
      "INSERT INTO comps (title, major, description, overseer, thumbnail_path, icon_path, starts_on, ends_on, is_open)
       VALUES ('$title_safe', $major_sql, '$desc_safe', $overseer_sql, $thumb_sql, $icon_sql, '$starts_on', '$ends_on', $is_open)"
    );

    if (!$conn) throw new Exception("Gagal menyimpan lomba");
    $new_id = $conn->insert_id;

    // Insert external links (dinamis)
    if (isset($_POST["ext_title"]) && is_array($_POST["ext_title"])) {
      for ($i = 0; $i < count($_POST["ext_title"]); $i++) {
        $lt = trim($_POST["ext_title"][$i] ?? "");
        $lu = trim($_POST["ext_url"][$i] ?? "");
        if ($lt !== "" && $lu !== "") {
          $lt_s = mysqli_real_escape_string($conn, $lt);
          $lu_s = mysqli_real_escape_string($conn, $lu);
          $conn->query("INSERT INTO comp_external_links (title, address, linked_comp) VALUES ('$lt_s', '$lu_s', $new_id)");
        }
      }
    }

    $conn->close();
    header("Location: ../admin_comps/admin_comps.php?added=1");
    exit;

  } catch (Exception $e) {
    if ($conn) $conn->close();
    header("Location: admin_comp_form.php?error=" . urlencode("Terjadi kesalahan: " . $e->getMessage()));
    exit;
  }

  function upload_image($conn, $file, $prefix) {
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