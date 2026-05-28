let loginForm = document.forms["admin_login_form"];
loginForm.submit_button.addEventListener("click", function() {
  setValidityText("");
  let username = loginForm.username.value.trim();
  let password = loginForm.password.value;
  if (!username || !password) {
    setValidityText("Semua field wajib diisi");
    return;
  }
  loginForm.submit();
});

function setValidityText(msg) {
  document.getElementById("validation_text").innerText = msg;
}