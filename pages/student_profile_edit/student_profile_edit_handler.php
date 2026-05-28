<?php
  session_start();
  require_once "../../sql/sql_bridge.php";

  // Cek sudah login belum
  if (!isset($_SESSION["nis"])) {
    header("Location: ../student_login/student_login.php?error=" . urlencode("Anda harus login terlebih dahulu"));
    exit;
  }

  $nis = trim($_POST["nis"] ?? "");
  $description = trim($_POST["description"] ?? "");
  $delete_pfp = isset($_POST["delete_pfp"]) ? $_POST["delete_pfp"] : "0";

  // Keamanan: hanya bisa edit profil sendiri
  if ($nis !== $_SESSION["nis"]) {
    header("Location: ../student_profile/student_profile.php?nis=" . urlencode($_SESSION["nis"]));
    exit;
  }

  try {
    $conn = connect_sql();
    $nis_safe = mysqli_real_escape_string($conn, $nis);
    $desc_safe = mysqli_real_escape_string($conn, $description);

    // ── Handle hapus foto ──
    if ($delete_pfp === "1") {
      // Ambil path foto lama untuk hapus file fisiknya
      $old_rows = select_sql("SELECT profile_pic_path FROM students_table WHERE nis = '$nis_safe'");
      if (!empty($old_rows[0]["profile_pic_path"])) {
        $old_file = "../../" . $old_rows[0]["profile_pic_path"];
        if (file_exists($old_file)) {
          unlink($old_file);
        }
      }
      // Set kolom jadi NULL
      execute_sql("UPDATE students_table SET profile_pic_path = NULL, description = '$desc_safe' WHERE nis = '$nis_safe'");

      // Jika TIDAK upload foto baru, langsung redirect
      if (!isset($_FILES["new_pfp"]) || $_FILES["new_pfp"]["error"] === UPLOAD_ERR_NO_FILE) {
        header("Location: student_profile_edit.php?success=" . urlencode("Foto profil berhasil dihapus"));
        exit;
      }
      // Kalau ada file baru juga, lanjut ke proses upload di bawah
    }

    // ── Handle upload foto baru ──
    if (isset($_FILES["new_pfp"]) && $_FILES["new_pfp"]["error"] === UPLOAD_ERR_OK) {
      $file = $_FILES["new_pfp"];

      // Validasi tipe file
      $allowed_types = ["image/jpeg", "image/png", "image/gif", "image/webp"];
      if (!in_array($file["type"], $allowed_types)) {
        header("Location: student_profile_edit.php?error=" . urlencode("Format gambar harus JPG, PNG, GIF, atau WebP"));
        exit;
      }

      // Validasi ukuran (maks 2MB)
      if ($file["size"] > 2 * 1024 * 1024) {
        header("Location: student_profile_edit.php?error=" . urlencode("Ukuran foto maksimal 2MB"));
        exit;
      }

      // Hapus foto lama jika ada (karena mau diganti)
      $old_rows = select_sql("SELECT profile_pic_path FROM students_table WHERE nis = '$nis_safe'");
      if (!empty($old_rows[0]["profile_pic_path"])) {
        $old_file = "../../" . $old_rows[0]["profile_pic_path"];
        if (file_exists($old_file)) {
          unlink($old_file);
        }
      }

      // Buat nama file unik
      $ext = pathinfo($file["name"], PATHINFO_EXTENSION);
      $new_filename = "pfp_" . $nis . "_" . time() . "." . $ext;
      $upload_dir = "../../assets/images/profiles/";

      // Buat folder jika belum ada
      if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
      }

      $upload_path = $upload_dir . $new_filename;

      if (move_uploaded_file($file["tmp_name"], $upload_path)) {
        $pic_path = "assets/images/profiles/" . $new_filename;
        execute_sql(
          "UPDATE students_table SET description = '$desc_safe', profile_pic_path = '$pic_path' WHERE nis = '$nis_safe'"
        );
        header("Location: student_profile_edit.php?success=" . urlencode("Foto profil berhasil diperbarui"));
        exit;
      } else {
        header("Location: student_profile_edit.php?error=" . urlencode("Gagal mengupload foto"));
        exit;
      }
    } else {
      // Tidak hapus, tidak upload — hanya update deskripsi
      execute_sql(
        "UPDATE students_table SET description = '$desc_safe' WHERE nis = '$nis_safe'"
      );
      header("Location: student_profile_edit.php?success=" . urlencode("Profil berhasil diperbarui"));
      exit;
    }

  } catch (Exception $e) {
    header("Location: student_profile_edit.php?error=" . urlencode("Terjadi kesalahan sistem"));
    exit;
  }
?>