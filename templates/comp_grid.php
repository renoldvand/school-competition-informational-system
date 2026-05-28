
<?php

  // Competition Card / Postingan
  function comp_card_html(
    $name = "Perlombaan Tak Ternama",
    $thumbnail = "../../assets/images/default_comp_card.png",
    $major = "???",
    $opened = true,
    $id = null
  ) {
    $status_message = "Terbuka";
    $status_class = "opened";
    if ($opened == false) {
      $status_message = "Tertutup";
      $status_class = "closed";
    }
    
    $href = "../comp_post/comp_post.php";
    if ($id) {
      $href .= "?id=" . urlencode($id);
    }

    $html = <<<HTML
      <a class="comp_card" href="$href">
        <figure>
          <img src="$thumbnail" alt="thumbnail.png">
          <figcaption id="name"><b>$name</b></figcaption>
          <p class="major">$major</p>
          <p class="status $status_class">$status_message</p>
        </figure>
      </a>
    HTML;

    return $html; 
  }
  
  // Competition Grid
  // Penataan kompetisi
  function comp_grid_html($comps = []) {
    $cards = "";
    
    foreach ($comps as $comp) {
      $cards .= comp_card_html(
        $comp["name"], $comp["thumbnail"], $comp["major"], $comp["opened"], 
        $comp["id"] ?? null
      );
    }
    // Tes Kartu
    // $cards .= comp_card_html();
    // $cards .= comp_card_html();
    // $cards .= comp_card_html();

    // Lihat skrip Top Bar atau Bottom Bar untuk penjelasan Heredoc String.
    $html = <<<HTML
      <div id="comp_grid">
        $cards
      </div>
    HTML;
    echo $html;
  }

  // Elemen <style> harus dicetak di dalam elemen <head> untuk bisa bekerja.
  function comp_grid_css() {
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
          --shadow-md:  0 6px 24px rgba(15,32,68,0.15);
          --radius:     12px;
        }

        /* ── Grid Wrapper ── */
        #comp_grid {
          margin: 0 auto;
          width: 90%;
          max-width: 1100px;
          padding: 16px 0 32px;
          min-height: 60vh;
          background-color: transparent;       /* Ganti dari merah */
          display: grid;
          gap: 20px;
          grid-template-columns: repeat(auto-fill, 300px);
          justify-content: center;
          align-content: baseline;
        }

        #comp_grid * {
          margin: 0;
          font-family: 'DM Sans', sans-serif;
        }

        /* ── Card Kompetisi ── */
        .comp_card {
          background-color: var(--white);
          border-radius: var(--radius);
          box-shadow: var(--shadow-sm);
          width: 100%;
          text-decoration: none;
          color: var(--text);
          border: 1px solid rgba(42,82,160,0.08);
          overflow: hidden;
          display: block;
          transition: transform 0.22s ease, box-shadow 0.22s ease;
        }

        .comp_card:hover {
          transform: translateY(-5px);
          box-shadow: var(--shadow-md);
        }

        .comp_card * {
          text-align: left;
        }

        .comp_card figure {
          display: grid;
          padding: 0;
          grid-template-columns: 70% 30%;
          grid-template-areas:
            "card_thumb card_thumb"
            "card_name  card_name"
            "card_major card_status";
          gap: 0;
        }

        /* Thumbnail */
        .comp_card img {
          grid-area: card_thumb;
          width: 100%;
          height: 160px;
          object-fit: cover;
          display: block;
        }

        /* Nama kompetisi */
        .comp_card figcaption {
          grid-area: card_name;
          font-family: 'Playfair Display', serif;
          font-size: 1rem;
          font-weight: 600;
          color: var(--navy);
          padding: 14px 16px 6px;
          line-height: 1.3;
          overflow-wrap: break-word;
          width: 100%;
        }

        .comp_card figcaption b {
          font-weight: 700;
        }

        /* Jurusan */
        .comp_card .major {
          grid-area: card_major;
          font-size: 0.78rem;
          color: var(--text-muted);
          font-weight: 400;
          padding: 0 16px 14px;
          width: 100%;
          align-self: center;
        }

        /* Status buka/tutup */
        .comp_card .status {
          grid-area: card_status;
          font-size: 0.72rem;
          font-weight: 700;
          letter-spacing: 0.8px;
          text-transform: uppercase;
          padding: 4px 12px;
          border-radius: 20px;
          width: fit-content;
          margin: 0 10px 14px auto;
          align-self: end;
          justify-self: end;
        }

        /* Status: Terbuka */
        .comp_card .opened {
          color: #1a6630;
          background-color: rgba(34,139,34,0.12);
        }

        /* Status: Tertutup */
        .comp_card .closed {
          color: #5a5a5a;
          background-color: rgba(120,120,120,0.12);
        }
      </style>
    HTML;

    echo $html;
  }
?>
