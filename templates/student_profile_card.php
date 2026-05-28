
<?php
  // Entri Kompetisi yang diikuti
  function joined_comps_html(
    $name = "???",
    $icon = "../../assets/images/default_comp_icon.jpg",
  ) {
    // Lihat skrip Top Bar atau Bottom Bar untuk penjelasan Heredoc String.
    $html = <<<HTML
      <a class="comp_entry">
        <img src="$icon" alt="comp_icon.jpg">
        <p>$name</p>
      </a>
    HTML;
    return $html;
  }

  // Student Profile Card / Kartu Profile Siswa
  function student_profile_card_html(
    $name = "Anonim",
    $nis = "?",
    $att_number = "???",
    $class = "???",
    $major = "???",
    $profile_pic = "../../assets/images/default_pfp.jpg",
    $profile_description = "[Tidak ada deskripsi]",
    $registered_comps = [],
    $joined_comps = [],
    $achievements = [],
    $created_on = "??/??/??",
    $viewed_by_owner = true
  ) {
    $entries = "";
    
    foreach ($joined_comps as $comp) {
      $entries .= joined_comps_html($comp["name"], $comp["icon"]);
    }

    
    $edit_profile_button = "";
    if ($viewed_by_owner) {
      $edit_profile_button = <<<HTML
        <a id="edit_profile" href="../student_profile_edit/student_profile_edit.php">Edit Profil</a> 
      HTML;
    }

    $empty_achievement = "";
    if (empty($entries)) {
      $empty_achievement = '<p class="empty-text">Belum ada pencapaian.</p>';
    }

    $reg_section = "";
    if (!empty($registered_comps)) {
      $reg_items = "";
      foreach ($registered_comps as $rc) {
        $status_cls = "";
        $status_label = "";
        if ($rc["status"] === "pending") { $status_cls = "reg-pending"; $status_label = "Pending"; }
        elseif ($rc["status"] === "accepted") { $status_cls = "reg-accepted"; $status_label = "Diterima"; }
        elseif ($rc["status"] === "rejected") { $status_cls = "reg-rejected"; $status_label = "Ditolak"; }
        $reg_items .= '<a class="comp_entry" href="../comp_post/comp_post.php?id=' . $rc["comp_id"] . '">'
          . '<p class="reg-comp-name">' . htmlspecialchars($rc["comp_title"]) . '</p>'
          . '<span class="reg-badge-small ' . $status_cls . '">' . $status_label . '</span>'
          . '</a>';
      }
      $reg_section = '<h3>Lomba yang Diikuti</h3><div id="comp_list">' . $reg_items . '</div>';
    }

    $html = <<<HTML
      <div id="student_profile">
        <section>
          <figure>
            <img src="$profile_pic" alt="pfp.png">    
            <figcaption><h1>$name</h1></figcaption>
            $edit_profile_button
          </figure>
          <div id="student_datas">
            <div id="student_creds">
              <p>NIS</p>
              <p>: $nis</p>
              <p>Nomor Absen</p>
              <p>: $att_number</p>
              <p>Kelas</p>
              <p>: $class</p>
              <p>Jurusan</p>
              <p>: $major</p>
              <p>Tanggal Daftar</p>
              <p>: $created_on</p>
            </div>
            <p id="description">$profile_description</p>
          </div>
        </section>
        <h3>Pencapaian</h3>
        <div id="achievement_list">
          $entries
          $empty_achievement
        </div>
        $reg_section
      </div>
    HTML;

    echo $html;
  }

  // Elemen <style> harus dicetak di dalam elemen <head> untuk bisa bekerja.
  function student_profile_card_css() {
    $html = <<<HTML
      <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500;600&display=swap');

        :root {
          --navy:       #0f2044;
          --navy-mid:   #1a3260;
          --blue:       #2a52a0;
          --gold:       #e6b94a;
          --gold-light: #f5d98a;
          --bg:         #eceef4;
          --white:      #ffffff;
          --text:       #1a1f2e;
          --text-muted: #5a6278;
          --shadow-sm:  0 2px 8px rgba(15,32,68,0.10);
          --shadow-md:  0 8px 28px rgba(15,32,68,0.15);
          --radius:     14px;
        }

        /* ── Card Wrapper ── */
        #student_profile {
          background-color: var(--white);      /* Ganti dari merah */
          width: 90%;
          max-width: 760px;
          min-height: auto;
          margin: 16px auto 24px;
          padding: 0;
          border-radius: var(--radius);
          box-shadow: var(--shadow-md);
          border: 1px solid rgba(42,82,160,0.08);
          overflow: hidden;
          font-family: 'DM Sans', sans-serif;
        }

        #student_profile * {
          margin: 0;
          font-family: 'DM Sans', sans-serif;
        }

        /* ── Section Atas (foto + data) ── */
        #student_profile section {
          display: flex;
          gap: 0;
          flex-direction: row;
          background: linear-gradient(135deg, var(--navy-mid) 0%, var(--blue) 100%);
          padding: 32px 36px;
          align-items: flex-start;
        }

        /* ── Kolom Foto + Nama ── */
        #student_profile figure {
          width: 38%;
          flex-shrink: 0;
          display: flex;
          flex-direction: column;
          align-items: center;
          gap: 12px;
          text-align: center;
        }

        #student_profile figure img {
          height: 110px;
          width: 110px;
          object-fit: cover;
          object-position: top;
          border-radius: 50%;
          border: 4px solid var(--gold);
          box-shadow: 0 4px 16px rgba(0,0,0,0.25);
        }

        #student_profile figure figcaption h1 {
          font-family: 'Playfair Display', serif;
          font-size: 1.2rem;
          font-weight: 700;
          color: var(--white);
          line-height: 1.25;
          text-align: center;
        }

        /* Tombol Edit Profil */
        #student_profile #edit_profile {
          display: inline-block;
          background-color: var(--gold);      /* Ganti dari oranye */
          color: var(--navy);
          padding: 7px 20px;
          border-radius: 8px;
          font-size: 0.8rem;
          font-weight: 700;
          text-decoration: none;
          text-align: center;
          transition: background 0.18s, transform 0.15s;
          box-shadow: 0 3px 10px rgba(230,185,74,0.30);
        }

        #student_profile #edit_profile:hover {
          background-color: var(--gold-light);
          transform: translateY(-1px);
        }

        /* ── Kolom Data Siswa ── */
        #student_profile #student_datas {
          width: 62%;
          display: flex;
          gap: 12px;
          flex-direction: column;
          padding-left: 24px;
        }

        /* Grid label : nilai */
        #student_profile #student_creds {
          display: grid;
          grid-template-columns: 42% 58%;
          row-gap: 6px;
        }

        #student_profile #student_creds > * {
          text-align: left;
          font-size: 0.82rem;
          color: rgba(255,255,255,0.80);
          line-height: 1.55;
        }

        /* Kolom label (kiri) */
        #student_profile #student_creds > p:nth-child(odd) {
          font-weight: 600;
          color: rgba(255,255,255,0.55);
          font-size: 0.78rem;
          text-transform: uppercase;
          letter-spacing: 0.3px;
        }

        /* Kolom nilai (kanan) */
        #student_profile #student_creds > p:nth-child(even) {
          color: var(--white);
          font-weight: 400;
        }

        /* Deskripsi profil */
        #student_profile #description {
          max-width: 100%;
          text-align: left;
          margin-top: 4px;
          text-indent: 1em;
          word-wrap: break-word;
          font-size: 0.85rem;
          color: rgba(255,255,255,0.70);
          line-height: 1.65;
          font-weight: 300;
        }

        /* ── Section Bawah (daftar lomba) ── */
        #student_profile h3 {
          padding: 18px 28px 10px;
          font-family: 'Playfair Display', serif;
          font-size: 1rem;
          font-weight: 600;
          color: var(--navy);
          border-top: 1px solid rgba(42,82,160,0.09);
        }

        #student_profile #comp_list {
          display: flex;
          flex-direction: column;
          padding: 0 16px 20px;
          gap: 4px;
        }

        /* Entry kompetisi yang diikuti */
        #student_profile .comp_entry {
          display: flex;
          align-items: center;
          justify-content: flex-start;
          padding: 9px 12px;
          gap: 12px;
          border-radius: 9px;
          text-decoration: none;
          transition: background 0.17s;
        }

        #student_profile .comp_entry:hover {
          background: rgba(42,82,160,0.06);
        }

        #student_profile .comp_entry img {
          width: 38px;
          height: 38px;
          border-radius: 8px;
          object-fit: cover;
          flex-shrink: 0;
          box-shadow: var(--shadow-sm);
        }

        #student_profile .comp_entry p {
          font-size: 0.88rem;
          font-weight: 500;
          color: var(--text);
        }

        #student_profile .empty-text {
          padding:8px 28px;font-size:0.82rem;color:var(--text-muted);font-style:italic;
        }
        .reg-comp-name { font-size:0.88rem;font-weight:500;color:var(--text);flex:1; }
        .reg-badge-small {
          display:inline-block;padding:3px 8px;border-radius:12px;font-size:0.65rem;
          font-weight:700;letter-spacing:0.3px;text-transform:uppercase;white-space:nowrap;
        }
        .reg-badge-small.reg-pending { background:rgba(230,185,74,0.15);color:#8a6000; }
        .reg-badge-small.reg-accepted { background:rgba(34,139,34,0.1);color:var(--success); }
        .reg-badge-small.reg-rejected { background:rgba(217,64,64,0.1);color:var(--danger); }
        #student_profile #comp_list .comp_entry {
          flex-wrap:wrap;gap:6px;
        }
      </style>
    HTML;

    echo $html;
  }
?>
