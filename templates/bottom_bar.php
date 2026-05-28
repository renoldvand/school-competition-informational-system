
<?php

  // Bottom Bar / Footer
  // Bilah navigasi di bagian paling bawah halaman web.
  function bottom_bar_html($account_name = NULL) {
    $account_message = "Anda belum login (masuk)";
    if ($account_name != NULL) {
      $account_name = strtoupper($account_name);
      $account_message = "Anda login sebagai $account_name";
    }
    
    // Heredoc String merupakan sejenis sintaks string yang menetapkan warna kode html di VS Code.
    // Ia diketik dengan tiga tanda panah kiri dan nama bebas.
    // Seperti string double quoted (""), ia dapat disisipkan variabel untuk fleksibilitas.
    $html = <<<HTML
      <footer>
        <a id="globe_icon" href="https://smkn1denpasar.sch.id/">
          <img src="../../assets/images/social_icons/globe.png" alt="globe_icon.png">
        </a>

        <a id="fb_icon" href="http://facebook.com/smknegeri1denpasar">
          <img src="../../assets/images/social_icons/fb.png" alt="facebook_icon.png">
        </a>
        <!-- <a id="admin_link" href="../admin_login/admin_login.php" title="Panel Admin">
          <img src="../../assets/images/social_icons/globe.png" alt="admin.png" style="filter:grayscale(1) brightness(0.5);">
        </a> -->
        <p>$account_message</p>

        <a id="yt_icon" href="https://youtube.com/@smkn1denpasar">
          <img src="../../assets/images/social_icons/yt.png" alt="youtube_icon.png">
        </a>

        <a id="ig_icon" href="https://instagram.com/smkn1denpasar/">
          <img src="../../assets/images/social_icons/ig.png" alt="instagrampng">
        </a>
      </footer>
    HTML;

    echo $html;
  }

  // Elemen <style> harus dicetak di dalam elemen <head> untuk bisa bekerja.  
  function bottom_bar_css() {
    $html = <<<HTML
      <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500&display=swap');

        :root {
          --navy:     #0f2044;
          --gold:     #e6b94a;
          --white:    #ffffff;
          --text-dim: rgba(255,255,255,0.60);
        }

        footer * {
          margin: auto 0;
          font-family: 'DM Sans', sans-serif;
        }

        footer {
          margin: 0;
          background: linear-gradient(90deg, #0d1c3a 0%, #1a3260 100%);
          display: flex;
          justify-content: space-evenly;
          align-items: center;
          height: 72px;
          border-top: 2px solid rgba(230,185,74,0.25);
        }

        /* Pesan login */
        footer p {
          color: var(--text-dim);
          font-size: 0.8rem;
          font-weight: 300;
          letter-spacing: 0.3px;
          text-align: center;
        }

        /* Ikon sosial media - tombol bulat */
        footer a {
          height: 44px;
          width: 44px;
          border-radius: 50%;
          display: flex;
          justify-content: center;
          align-items: center;
          transition: transform 0.18s, opacity 0.18s, box-shadow 0.18s;
          flex-shrink: 0;
        }

        footer a:hover {
          transform: translateY(-3px) scale(1.08);
          opacity: 0.90;
          box-shadow: 0 4px 14px rgba(0,0,0,0.30);
        }

        footer img {
          height: 22px;
          width: auto;
          margin: auto;
        }

        footer #globe_icon {
          background-color: #1F466F;
        }

        footer #fb_icon {
          background-color: #1877F2;
        }

        footer #yt_icon {
          background-color: #B2071D;
        }

        footer #ig_icon {
          background: radial-gradient(circle at 30% 107%, #fdf497 0%, #fd5949 45%, #d6249f 60%, #285AEB 90%);
        }
        footer #admin_link {
          background-color: rgba(255,255,255,0.08);
        }
        footer #admin_link:hover {
          background-color: rgba(255,255,255,0.15);
        }
      </style>
    HTML;

    echo $html;
  }
?>
