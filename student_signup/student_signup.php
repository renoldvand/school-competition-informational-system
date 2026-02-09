<?php
  require_once "../sql/sql_bridge.php";
  
  create_account();

  function create_account() {
    $s_nis = $_POST["s_nis"];
    $s_name = $_POST["s_name"];
    $s_class = $_POST["s_class"];
    $s_att_number = $_POST["s_att_number"];
    $s_password = $_POST["s_password"];
    
    $success = execute_sql(
      "
        INSERT INTO students_table 
          (nis, full_name, att_number, class, acc_password)
        VALUES 
          ('$s_nis', '$s_name', 
          '$s_att_number', '$s_class', '$s_password');
      "
    );
  
    if ($success) {
      header("Location: ../profile.html"); 
      exit;
    }
  
    header("Location: ../failure.html"); 
    exit;
  }

?>
