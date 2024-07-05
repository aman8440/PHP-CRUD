<?php
  $host = "localhost";
  $username = "root";
  $db_password = "";
  $db_name = "data_db";

  $conn = new mysqli($host, $username, $db_password, $db_name);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  if (isset($_POST['phone'])) {
    $phone = $_POST['phone'];
    $stmt = $conn->prepare("SELECT id FROM Data_User WHERE phone = ?");
    $stmt->bind_param("i", $phone);
    $stmt->execute();
    $stmt->store_result();

    $response = array();
    $response['exists'] = $stmt->num_rows > 0;

    echo json_encode($response);

    $stmt->close();
  }

  $conn->close();
?>