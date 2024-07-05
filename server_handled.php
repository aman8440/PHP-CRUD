<?php
session_start();

$host = "localhost";
$username = "root";
$db_password = "";
$db_name = "data_db";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    $conn = new mysqli($host, $username, $db_password, $db_name);
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
} catch (Exception $e) {
    error_log($e->getMessage(), 3, 'error_log.txt');
    die("There was an error connecting to the database. Please try again later.");
}
function checkForDuplicates($conn, $email, $phone, $userId = null) {
  try {
      $query = "SELECT id FROM Data_User WHERE (email = ? OR phone = ?)";
      if ($userId) {
          $query .= " AND id != ?";
      }
      
      $stmt = $conn->prepare($query);
      if (!$stmt) {
          throw new Exception("Prepare statement failed: " . $conn->error);
      }
      
      if ($userId) {
          $stmt->bind_param("sii", $email, $phone, $userId);
      } else {
          $stmt->bind_param("si", $email, $phone);
      }
      
      if (!$stmt->execute()) {
          throw new Exception("Execute failed: " . $stmt->error);
      }

      $stmt->store_result();
      return $stmt->num_rows > 0;
  } catch (Exception $e) {
      error_log($e->getMessage(), 3, 'error_log.txt');
      die("There was an error processing your request. Please try again later.");
  }
}

$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';
$userId = $_SESSION['user_id'] ?? null;
$isDuplicate = checkForDuplicates($conn, $email, $phone, $userId);