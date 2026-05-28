<?php
  function student_profile_edit_card_html(
    $nis = "?????", $name = "Anonim", $att_number = -1, $class = "? ??? ?",
    $current_description = "", $current_pfp = ""
  ) {
    $safe_description = htmlspecialchars($current_description);

    // Bangun blok foto saat ini + tombol hapus (hanya tampil jika ada foto)
    $current_photo_block = "";
    if (!empty($current_pfp)) {
      $current_pfp_src = "../../" . $current_pfp;
      $current_photo_block = <<<HTML
            <label>Foto Saat Ini</label>
            <div id="current_pfp_wrapper">
              <img src="$current_pfp_src" alt="foto_saat_ini" id="current_pfp_img">
              <button type="button" id="delete_pfp_btn" onclick="handleDeletePfp()">Hapus Foto</button>
            </div>
      HTML;
    }

    $html = <<<HTML
      <div id="student_profile_edit">
        <h1>$nis - $name</h1>
        <h2>$att_number / $class</h2>
        <form action="student_profile_edit_handler.php" method="post" enctype="multipart/form-data">
          <input type="hidden" name="nis" value="$nis">
          <input type="hidden" name="delete_pfp" id="delete_pfp_flag" value="0">
          <fieldset>
            $current_photo_block
            <label for="new_pfp">Foto Baru</label>
            <input type="file" accept="image/*" name="new_pfp" id="new_pfp">
            <label for="description">Deskripsi</label>
            <textarea rows="10" name="description" id="description">$safe_description</textarea>
            <label></label>
            <div id="button_row">
              <a class="return_link btn_batalkan" href="#">Batalkan</a>
              <input type="submit" value="Ubah">
            </div>
          </fieldset>
        </form>
      </div>  
    HTML;

    echo $html;
  }

  function student_profile_edit_card_css() {
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
        #student_profile_edit {
          margin: 0 auto;
          width: 90%;
          max-width: 620px;
          background-color: var(--white);
          padding: 0;
          border-radius: var(--radius);
          box-shadow: var(--shadow-md);
          border: 1px solid rgba(42,82,160,0.08);
          overflow: hidden;
          font-family: 'DM Sans', sans-serif;
        }

        /* ── Header card ── */
        #student_profile_edit h1 {
          margin: 0;
          font-family: 'Playfair Display', serif;
          font-size: 1.3rem;
          font-weight: 700;
          color: var(--white);
          background: linear-gradient(135deg, var(--navy-mid) 0%, var(--blue) 100%);
          padding: 22px 28px 8px;
          line-height: 1.2;
        }

        #student_profile_edit h2 {
          margin: 0;
          font-size: 0.85rem;
          font-weight: 400;
          color: rgba(255,255,255,0.62);
          background: linear-gradient(135deg, var(--navy-mid) 0%, var(--blue) 100%);
          padding: 0 28px 22px;
          font-family: 'DM Sans', sans-serif;
        }

        /* ── Form ── */
        #student_profile_edit form {
          padding: 24px 28px 28px;
        }

        #student_profile_edit fieldset {
          border: none;
          padding: 0;
          display: grid;
          gap: 14px 16px;
          grid-template-columns: 1fr 3fr;
          align-items: center;
        }

        /* Label (kolom kiri) */
        #student_profile_edit label {
          width: 100%;
          text-align: right;
          font-size: 0.8rem;
          font-weight: 600;
          color: var(--text-muted);
          text-transform: uppercase;
          letter-spacing: 0.4px;
        }

        /* Input file */
        #student_profile_edit input[type="file"] {
          font-size: 0.85rem;
          color: var(--text-muted);
          font-family: 'DM Sans', sans-serif;
          cursor: pointer;
        }

        /* Textarea */
        #student_profile_edit textarea {
          width: 100%;
          padding: 10px 13px;
          border: 1.5px solid rgba(42,82,160,0.16);
          border-radius: 9px;
          font-family: 'DM Sans', sans-serif;
          font-size: 0.9rem;
          color: var(--text);
          background: var(--bg);
          resize: vertical;
          outline: none;
          transition: border-color 0.18s, box-shadow 0.18s, background 0.18s;
          line-height: 1.6;
        }

        #student_profile_edit textarea:focus {
          border-color: var(--blue);
          background: var(--white);
          box-shadow: 0 0 0 3px rgba(42,82,160,0.10);
        }

        /* ── Foto saat ini (kolom kanan) ── */
        #student_profile_edit #current_pfp_wrapper {
          display: flex;
          align-items: center;
          gap: 14px;
        }

        #student_profile_edit #current_pfp_img {
          width: 72px;
          height: 72px;
          border-radius: 50%;
          object-fit: cover;
          border: 3px solid var(--gold-light);
          box-shadow: 0 2px 8px rgba(15,32,68,0.12);
          flex-shrink: 0;
        }

        /* Tombol Hapus Foto */
        #student_profile_edit #delete_pfp_btn {
          padding: 7px 16px;
          border-radius: 8px;
          border: 1.5px solid rgba(217,64,64,0.25);
          background: transparent;
          color: #d94040;
          font-family: 'DM Sans', sans-serif;
          font-size: 0.78rem;
          font-weight: 600;
          cursor: pointer;
          transition: background 0.18s, border-color 0.18s;
          white-space: nowrap;
        }

        #student_profile_edit #delete_pfp_btn:hover {
          background: rgba(217,64,64,0.08);
          border-color: rgba(217,64,64,0.45);
        }

        /* ── Baris tombol ── */
        #student_profile_edit #button_row {
          display: flex;
          gap: 12px;
          align-items: center;
        }

        #student_profile_edit #button_row .btn_batalkan,
        #student_profile_edit #button_row input[type="submit"] {
          padding: 9px 22px;
          border-radius: 9px;
          font-family: 'DM Sans', sans-serif;
          font-size: 0.875rem;
          font-weight: 600;
          cursor: pointer;
          border: none;
          transition: background 0.18s, transform 0.15s;
        }

        /* Tombol Batalkan */
        #student_profile_edit #button_row .btn_batalkan {
          background: transparent;
          border: 1.5px solid rgba(42,82,160,0.20);
          color: var(--text-muted);
          text-decoration: none;
        }

        #student_profile_edit #button_row .btn_batalkan:hover {
          border-color: var(--navy);
          color: var(--navy);
        }

        /* Tombol Submit */
        #student_profile_edit #button_row input[type="submit"] {
          background: var(--blue);
          color: var(--white);
          box-shadow: 0 3px 12px rgba(42,82,160,0.22);
        }

        #student_profile_edit #button_row input[type="submit"]:hover {
          background: var(--navy-mid);
          transform: translateY(-1px);
        }
      </style>
    HTML;

    echo $html;
  }
?>