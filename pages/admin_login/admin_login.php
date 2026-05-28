<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Admin | SCIS Skensa</title>
  <link rel="stylesheet" href="admin_login.css">
</head>
<body>
  <main>
    <h1>Login <span>Admin</span></h1>
    <?php
      if (isset($_GET["error"])) {
        echo '<p class="url-msg error">' . htmlspecialchars($_GET["error"]) . '</p>';
      }
    ?>
    <form id="admin_login_form" action="admin_login_validator.php" method="post">
      <fieldset>
        <legend>Data Admin</legend>
        <div id="form_container">
          <label for="username">Username <span>*</span></label>
          <input type="text" name="username" id="username" autocomplete="username">
          <label for="password">Kata Sandi <span>*</span></label>
          <input type="password" name="password" id="password" autocomplete="current-password">
        </div>
        <p id="validation_text"></p>
        <input id="submit_button" type="button" value="Masuk">
      </fieldset>
    </form>
  </main>
  <script src="admin_login.js" defer></script>
</body>
</html>