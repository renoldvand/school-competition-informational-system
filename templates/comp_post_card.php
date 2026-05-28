<?php
  function external_links_html($title = "???", $link = NULL) {
    $html = "";
    if ($link) {
      $html = '<a href="' . $link . '"><h3 class="external_links">' . $title . '</h3></a>';
    }
    return $html;
  }

  function comp_post_card_html(
    $title = "???",
    $major = "???",
    $post_description = "[Tidak ada deskripsi]",
    $thumbnail_path = "../../assets/images/default_comp_thumbnail.jpg",
    $external_links = [],
    $starts_on = "??/??/??",
    $ends_on = "??/??/??",
    $overseer = "Anonim",
    $viewed_by_owner = false,
    $reg_status = null,
    $reg_msg = "",
    $reg_msg_type = ""
  ) {
    $link_elements = "";
    foreach ($external_links as $el) {
      $link_elements .= external_links_html($el["title"], $el["link"]);
    }

    $edit_post_button = "";
    if ($viewed_by_owner) {
      $edit_post_button = '<a id="edit_post" href="">Edit Postingan</a>';
    }

    // Tombol/badge berdasarkan status pendaftaran
    $register_section = "";
    if ($reg_status === null) {
      // Belum login atau lomba tertutup → tidak tampil apa-apa
    } elseif ($reg_status === "not_registered") {
      $register_section = '<div class="reg-section"><a href="comp_register_handler.php" class="reg-btn reg-btn-daftar">Daftar Lomba</a></div>';
    } elseif ($reg_status === "pending") {
      $register_section = '<div class="reg-section"><span class="reg-badge reg-pending">⏳ Menunggu Konfirmasi</span></div>';
    } elseif ($reg_status === "accepted") {
      $register_section = '<div class="reg-section"><span class="reg-badge reg-accepted">✓ Diterima — Anda Terdaftar</span></div>';
    } elseif ($reg_status === "rejected") {
      $register_section = '<div class="reg-section"><span class="reg-badge reg-rejected">✗ Ditolak</span><a href="comp_register_handler.php" class="reg-btn reg-btn-ulang">Daftar Ulang</a></div>';
    }

    // Pesan sukses/error
    $msg_html = "";
    if ($reg_msg !== "") {
      $cls = ($reg_msg_type === "success") ? "reg-success" : "reg-error";
      $msg_html = '<div class="reg-msg ' . $cls . '">' . htmlspecialchars($reg_msg) . '</div>';
    }

    $html = <<<HTML
      <div id="comp_post_card">
        <div id="thumb_wrap">
          <img id="thumbnail" src="$thumbnail_path" alt="thumbnail">
          <a class="return_link">
            <img src="../../assets/images/return_icon.png" alt="return">
          </a>
        </div>
        $msg_html
        <h1>$title</h1>
        <h3>$major</h3>
        $link_elements
        <h3>Guru Pendamping: $overseer</h3>
        <h3>Dilaksanakan: $starts_on - $ends_on</h3>
        $edit_post_button
        $register_section
        <p>$post_description</p>
      </div>
    HTML;
    echo $html;
  }

  function comp_post_card_css() {
    echo <<<HTML
      <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500;600&display=swap');
        :root {
          --navy:#0f2044;--navy-mid:#1a3260;--blue:#2a52a0;--gold:#e6b94a;--gold-light:#f5d98a;
          --bg:#eceef4;--white:#ffffff;--text:#1a1f2e;--text-muted:#5a6278;
          --shadow-sm:0 2px 8px rgba(15,32,68,0.10);--shadow-md:0 8px 28px rgba(15,32,68,0.15);
          --radius:14px;--success:#1a6630;--danger:#d94040;
        }
        #comp_post_card {
          margin:0 auto;background:var(--white);width:90%;max-width:720px;
          padding:0 0 36px;border-radius:var(--radius);
          box-shadow:var(--shadow-md);border:1px solid rgba(42,82,160,0.08);
          overflow:hidden;font-family:'DM Sans',sans-serif;
        }
        #comp_post_card * { margin-top:0;margin-bottom:0;font-family:'DM Sans',sans-serif; }

        /* Thumbnail wrapper */
        #thumb_wrap { position:relative;width:100%; }
        #comp_post_card #thumbnail { width:100%;height:280px;object-fit:cover;display:block; }
        #comp_post_card .return_link {
          position:absolute;bottom:16px;left:20px;
          width:46px;height:46px;background:var(--white);border-radius:50%;
          display:flex;justify-content:center;align-items:center;
          box-shadow:0 2px 10px rgba(15,32,68,0.20);border:1px solid rgba(42,82,160,0.12);
          transition:box-shadow 0.18s,transform 0.18s;text-decoration:none;z-index:2;
        }
        #comp_post_card .return_link:hover { box-shadow:0 4px 16px rgba(15,32,68,0.25);transform:scale(1.07); }
        #comp_post_card .return_link img { max-width:55%;max-height:55%; }

        /* Konten */
        #comp_post_card h1,#comp_post_card h3,#comp_post_card p,
        #comp_post_card a:not(.return_link):not(.reg-btn):not(#edit_post) {
          padding-left:28px;padding-right:28px;
        }
        #comp_post_card h1 {
          font-family:'Playfair Display',serif;font-size:clamp(1.4rem,3vw,1.9rem);
          font-weight:700;color:var(--navy);margin-top:22px;margin-bottom:6px;line-height:1.25;
        }
        #comp_post_card h3 { font-size:0.875rem;font-weight:500;color:var(--text-muted);margin-top:6px; }
        #comp_post_card a.external_links,#comp_post_card h3.external_links {
          color:var(--blue);text-decoration:none;font-size:0.875rem;font-weight:500;
          display:block;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;
          padding-left:28px;padding-right:28px;margin-top:6px;transition:color 0.15s;
        }
        #comp_post_card a.external_links:hover { color:var(--navy); }
        #comp_post_card p {
          margin-top:18px;text-indent:1.2em;word-wrap:break-word;max-width:100%;
          font-size:0.95rem;font-weight:300;line-height:1.8;color:#2e3550;
        }

        /* Pesan di dalam card */
        #comp_post_card .reg-msg { margin:16px 28px 0;padding:12px 18px;border-radius:9px;font-size:0.85rem;font-weight:500; }
        #comp_post_card .reg-success { background:rgba(34,139,34,0.1);color:var(--success);border:1px solid rgba(34,139,34,0.2); }
        #comp_post_card .reg-error { background:rgba(217,64,64,0.1);color:var(--danger);border:1px solid rgba(217,64,64,0.2); }

        /* Tombol edit */
        #comp_post_card #edit_post {
          display:block;background:var(--gold);color:var(--navy);padding:9px 0;
          margin:18px 28px 0;text-align:center;border-radius:9px;font-weight:700;
          font-size:0.875rem;text-decoration:none;box-shadow:0 3px 10px rgba(230,185,74,0.30);
        }
        #comp_post_card #edit_post:hover { background:var(--gold-light); }

        /* Section pendaftaran */
        .reg-section {
          margin:20px 28px 0;
          padding:16px 20px;
          background:var(--bg);
          border-radius:10px;
          border:1px solid rgba(42,82,160,0.08);
          display:flex;
          align-items:center;
          gap:14px;
          flex-wrap:wrap;
        }
        .reg-btn {
          display:inline-block;padding:11px 28px;border-radius:9px;font-size:0.88rem;
          font-weight:700;text-decoration:none;transition:all 0.18s;
        }
        .reg-btn-daftar { background:var(--gold);color:var(--navy);box-shadow:0 3px 12px rgba(230,185,74,0.30); }
        .reg-btn-daftar:hover { background:var(--gold-light);transform:translateY(-1px); }
        .reg-btn-ulang { padding:9px 22px;background:var(--blue);color:var(--white);font-size:0.82rem;box-shadow:0 3px 10px rgba(42,82,160,0.20);border-radius:8px; }
        .reg-btn-ulang:hover { background:var(--navy-mid); }
        .reg-badge {
          display:inline-flex;align-items:center;gap:6px;
          padding:10px 20px;border-radius:9px;font-size:0.85rem;font-weight:600;
        }
        .reg-pending { background:rgba(230,185,74,0.15);color:#8a6000; }
        .reg-accepted { background:rgba(34,139,34,0.1);color:var(--success); }
        .reg-rejected { background:rgba(217,64,64,0.1);color:var(--danger); }
      </style>
    HTML;
  }
?>