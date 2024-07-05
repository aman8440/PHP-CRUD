<?php
  $host = "localhost";
  $username = "root";
  $db_password = "";
  $db_name = "data_db";

  $conn = new mysqli($host, $username, $db_password, $db_name);

  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  }

  $email = $_POST['email'];
  $phone = $_POST['phone'];

  $emailQuery = "SELECT id FROM Data_User WHERE email = ?";
  $emailStmt = $conn->prepare($emailQuery);
  $emailStmt->bind_param("s", $email);
  $emailStmt->execute();
  $emailResult = $emailStmt->get_result();
  $emailExists = $emailResult->num_rows > 0;

  $phoneQuery = "SELECT id FROM Data_User WHERE phone = ?";
  $phoneStmt = $conn->prepare($phoneQuery);
  $phoneStmt->bind_param("i", $phone);
  $phoneStmt->execute();
  $phoneResult = $phoneStmt->get_result();
  $phoneExists = $phoneResult->num_rows > 0;

  $conn->close();

  $response = array(
      'emailExists' => $emailExists,
      'phoneExists' => $phoneExists
  );

  header('Content-Type: application/json');
  echo json_encode($response);
?>
