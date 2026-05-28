<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
    <title>Masuk Akun | SCIS Skensa</title>
    <link rel="stylesheet" href="student_login.css">
  </head>

  <body>
    <main>
      <h1>Masuk Akun</h1>

      <?php 
        // Tampilkan pesan error dari URL (dari validator)
        if (isset($_GET["error"])) {
          echo '<p id="url_message" class="url-error">' . htmlspecialchars($_GET["error"]) . '</p>';
        }
        // Tampilkan pesan sukses dari URL (dari registry)
        if (isset($_GET["success"])) {
          echo '<p id="url_message" class="url-success">' . htmlspecialchars($_GET["success"]) . '</p>';
        }
      ?>

      <form id="login_form" action="student_login_validator.php" method="post">
        <fieldset>
          <figure>
            <img id="scis_logo" src="../../assets/images/logo_scis.png" alt="logo_scis.png">
          </figure>
          <legend><b>Data Siswa</b></legend>
          <div id="form_container">
            <label for="nis">NIS <span>*</span></label>
            <input type="number" name="nis" id="nis" min="1">
            
            <label for="password">Kata Sandi <span>*</span></label>
            <input type="password" name="password" id="password">
          </div>
          <p id="validation_text"></p>
          <input id="submit_button" type="button" value="Masuk">
          <p style="text-align:center;margin-top:4px;">
            <a href="../student_registry/student_registry.php" style="color:var(--accent);font-size:0.85rem;text-decoration:none;">Belum punya akun? Daftar di sini</a>
          </p>
        </fieldset>
      </form>  
    </main>

    <style>
      .url-error {
        text-align: center;
        padding: 10px 14px;
        border-radius: 8px;
        font-size: 0.8125rem;
        font-weight: 500;
        color: var(--error);
        background: var(--error-bg);
        border: 1px solid var(--error-border);
        margin-bottom: 8px;
        animation: shakeError 0.35s ease;
      }
      .url-success {
        text-align: center;
        padding: 10px 14px;
        border-radius: 8px;
        font-size: 0.8125rem;
        font-weight: 500;
        color: #1a6630;
        background: rgba(34,139,34,0.08);
        border: 1px solid rgba(34,139,34,0.2);
        margin-bottom: 8px;
      }
    </style>
  </body>

  <script src="student_login.js" defer></script>
</body>
</html>