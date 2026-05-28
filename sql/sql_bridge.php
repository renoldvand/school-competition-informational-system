<?php
  function connect_sql() {
    $host = "127.0.0.1";
    $username = "root";
    $password = "";
    $database = "db_scis";
    $conn = mysqli_connect($host, $username, $password, $database);

    if (!$conn) {
      throw new Exception
        ("Koneksi database gagal, mohon periksa spesifikasi koneksi.");
    }
    return $conn;
  }

  function execute_sql($command) {
    $conn = null;
    try {
      $conn = connect_sql();
      if ($conn->query($command) === TRUE) {
        return TRUE;
      } else {
        throw new Exception($command . "<br>" . $conn->error);
      }
    } catch (Exception $ex) {
      throw new Exception($ex->getMessage());
    } finally {
      if ($conn) { $conn->close(); }
    }
  }

  function select_sql($command) {
    $conn = null;
    try {
      $conn = connect_sql();
      $result = $conn->query($command);
      if (!$result) {
        throw new Exception($command . "<br>" . $conn->error);
      }

      $data_rows = [];
      while($row = $result->fetch_assoc()) {
        array_push($data_rows, $row);
      }
      return $data_rows;
    } catch (Exception $ex) {
      throw new Exception($ex->getMessage());
    } finally {
      if ($conn) { $conn->close(); }
    }
  }
?>