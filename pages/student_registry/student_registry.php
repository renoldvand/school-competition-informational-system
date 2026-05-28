<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
    <title>Tambahkan Siswa Baru | SCIS Skensa</title>
    <link rel="stylesheet" href="student_registry.css">
  </head>
  <body>
    <main>
      <h1>Tambahkan Siswa</h1>
      <?php 
        if (isset($_GET["error"])) {
          echo '<p class="url-error">' . htmlspecialchars($_GET["error"]) . '</p>';
        }
      ?>
      <form id="registry_form" action="student_registry_validator.php" method="post">
        <fieldset>
          <legend><b>Data Siswa</b></legend>
          <div id="form_container">
            <label for="nis">NIS <span>*</span></label>
            <input type="number" name="nis" id="nis" min="1">

            <label for="full_name">Nama <span>*</span></label>
            <input type="text" name="full_name" id="full_name">
            
            <label for="major">Jurusan <span>*</span></label>
            <select name="major" id="major">
              <option value="">-- Pilih Jurusan --</option>
              <option value="RPL">Rekayasa Perangkat Lunak</option>
              <option value="DKV">Desain Komunikasi Visual</option>
              <option value="TKJ">Teknik Komputer dan Jaringan</option>
            </select>
            
            <label for="class">Kelas <span>*</span></label>
            <select name="class" id="class">
              <option value="">-- Pilih Kelas --</option>
              <option class="class_options RPL" value="X RPL 1">X RPL 1</option>
              <option class="class_options RPL" value="X RPL 2">X RPL 2</option>
              <option class="class_options DKV" value="X DKV 1">X DKV 1</option>
              <option class="class_options DKV" value="X DKV 2">X DKV 2</option>
              <option class="class_options TKJ" value="X TKJ 1">X TKJ 1</option>
              <option class="class_options TKJ" value="X TKJ 2">X TKJ 2</option>
            </select>
            
            <label for="att_number">No. Absen <span>*</span></label>
            <input type="number" name="att_number" id="att_number" min="1">

            <label for="password">Kata Sandi <span>*</span></label>
            <input 
              type="text" placeholder="12345678" name="password"
              id="password" value="12345678"
            >

            <label for="password_confirm">Konfirmasi Sandi <span>*</span></label>
            <input
              type="text" placeholder="12345678" name="password_confirm"
              id="password_confirm" value="12345678"
            >
          </div>
          <p id="validation_text"></p>
          <input id="submit_button" type="button" value="Tambahkan">
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
    </style>
  </body>

  <script src="student_registry.js" defer></script>
</html>
