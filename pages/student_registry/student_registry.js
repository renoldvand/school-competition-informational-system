let registryForm = document.forms["registry_form"];

// Mengupdate opsi-opsi kelas setiap pergantian kelas.
registryForm.major.addEventListener("change", updateClassOptions);

function updateClassOptions() {
  let selectedMajor = registryForm.major.value;
  
  for (const classOption of registryForm.class.options) {
    classOption.style.display = "none";
  } 

  registryForm.class.options[0].style.display = "block";
  registryForm.class.value = "";

  for (const classOption of document.getElementsByClassName(selectedMajor)) {
    classOption.style.display = "block";
  }
}


// Prosedur validasi data siswa secara klien sebelum divalidasi di server.
registryForm.submit_button.addEventListener("click", clientValidation);

function clientValidation() {
  setValidityText("");

  student_data = [
    registryForm.nis.value, 
    registryForm.full_name.value,
    registryForm.major.value,
    registryForm.class.value,
    registryForm.att_number.value,
    registryForm.password.value,
    registryForm.password_confirm.value
  ];

  if (!allFieldInputted(student_data)) {
    setValidityText("Semua field wajib diisi");
    return;
  }

  if (!moreThanZeroNumber(student_data[0])) {
    setValidityText("NIS harus lebih dari 0");
    return;
  } 

  if (!moreThanZeroNumber(student_data[4])) {
    setValidityText("Nomor absen harus lebih dari 0");
    return;
  } 
  
  if (!lengthyPassword(student_data[5])) {
    setValidityText("Password harus memiliki minimal 8 karakter");
    return;
  } 

  if (!matchingPasswordConfirmation(student_data[5], student_data[6])) {
    setValidityText("Password harus sesuai dengan konfirmasi password");
    return;
  }


  registryForm.submit();
}

// Memeriksa apakah semua input formulir sudah terisi.
function allFieldInputted(inputs) {
  let validity = true;

  inputs.forEach(input => {
    if (input == "") {
      validity = false;
    }
  });
  
  return validity;
}

// Memeriksa apakah password itu sesuai dengan konfirmasi password.
function matchingPasswordConfirmation(password, confirmation) {
  return password == confirmation;
}

// Memeriksa apakah password minimum 8 karakter.
function lengthyPassword(password) {
  return password.length >= 8;
}

// Memeriksa apakah input bilangan lebih dari 0.
function moreThanZeroNumber(number) {
  return number > 0;
}

function setValidityText(message) {
  document.getElementById("validation_text").innerText = message;
}

