
<?php
  // Student Card / Kartu Tautan Siswa
  function student_card_html(
    $name = "Anonim",
    $att_number = "???",
    $class = "???",
    $profile_pic = "../../assets/images/default_pfp.jpg",
    $nis = null
  ) {
    $href = "../student_profile/student_profile.php";
    if ($nis) {
      $href .= "?nis=" . urlencode($nis);
    }

    $html = <<<HTML
      <a class="student_card" href="$href">
        <figure>
          <img src="$profile_pic" alt="pfp.png">
          <figcaption>
            <b>$name</b>
            <p class="att_num">$att_number</p>
            <p class="class">$class</p>
          </figcaption>
        </figure>
      </a>
    HTML;

    return $html;
  }
  
  // Student Grid
  // Penataan siswa
  function student_grid_html($students = []) {
    $cards = "";
    
    foreach ($students as $student) {
      $cards .= student_card_html(
        $student["name"], 
        $student["att_number"], 
        $student["class"], 
        $student["profile_pic"],
        $student["nis"] ?? null
      ); 
    }
    
    // Tes Kartu
    // $cards .= student_card_html("I Wayan Aditya Pramata", 10, "X RPL 1");
    // $cards .= student_card_html("Anak Agung Rahyanadi", 1, "X RPL 1");
    // $cards .= student_card_html("Yomaharu Wariyui", 30, "X RPL 1");

    // Lihat skrip Top Bar atau Bottom Bar untuk penjelasan Heredoc String.
    $html = <<<HTML
      <div id="student_grid">
        $cards
      </div>
    HTML;
    echo $html;
  }

  // Elemen <style> harus dicetak di dalam elemen <head> untuk bisa bekerja.
  function student_grid_css() {
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
          --shadow-md:  0 6px 22px rgba(15,32,68,0.15);
          --radius:     12px;
        }

        /* ── Grid Wrapper ── */
        #student_grid {
          margin: 0 auto;
          width: 90%;
          max-width: 1100px;
          padding: 16px 0 32px;
          min-height: 60vh;
          background-color: transparent;       /* Ganti dari hijau */
          display: grid;
          gap: 16px;
          grid-template-columns: repeat(auto-fill, 300px);
          justify-content: center;
          align-content: baseline;
        }

        #student_grid > * {
          margin: 0;
        }

        /* ── Card Siswa ── */
        .student_card {
          width: 100%;
          text-decoration: none;
          display: block;
          border-radius: var(--radius);
          overflow: hidden;
          transition: transform 0.22s ease, box-shadow 0.22s ease;
        }

        .student_card:hover {
          transform: translateY(-4px);
          box-shadow: var(--shadow-md);
        }

        .student_card * {
          text-align: left;
          color: var(--text);
          font-family: 'DM Sans', sans-serif;
        }

        .student_card figure {
          margin: 0;
          background-color: var(--white);      /* Ganti dari lime */
          border: 1px solid rgba(42,82,160,0.08);
          border-radius: var(--radius);
          box-shadow: var(--shadow-sm);
          display: grid;
          grid-template-columns: 72px 1fr;
          gap: 14px;
          padding: 16px;
          align-items: center;
          transition: background 0.18s;
        }

        .student_card:hover figure {
          background-color: #f7f8fc;
        }

        /* Foto profil */
        .student_card img {
          width: 72px;
          height: 72px;
          aspect-ratio: 1/1;
          object-fit: cover;
          object-position: top;
          border-radius: 50%;
          border: 3px solid var(--gold-light);
          box-shadow: 0 2px 8px rgba(15,32,68,0.12);
          flex-shrink: 0;
        }

        /* Teks info siswa */
        .student_card figcaption {
          padding: 0;
          display: flex;
          flex-direction: column;
          gap: 3px;
        }

        .student_card figcaption > * {
          margin: 0;
          width: 100%;
        }

        /* Nama siswa */
        .student_card figcaption b {
          font-size: 0.95rem;
          font-weight: 600;
          color: var(--navy);
          line-height: 1.3;
          font-family: 'Playfair Display', serif;
        }

        /* Nomor absen */
        .student_card .att_num {
          font-size: 0.78rem;
          color: var(--blue);
          font-weight: 600;
        }

        /* Kelas */
        .student_card .class {
          font-size: 0.78rem;
          color: var(--text-muted);
          font-weight: 400;
        }
      </style>
    HTML;

    echo $html;
  }
?>
