<?php
  error_reporting(E_ALL);
  ini_set('display_errors', 1);

  $host = "localhost";
  $username = "root";
  $db_password = "";
  $db_name = "data_db";

  $conn = new mysqli($host, $username, $db_password, $db_name);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM Data_User WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    echo json_encode($user);
  } else {
    echo json_encode(["error" => "ID not provided"]);
  }
?>