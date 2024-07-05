​<?php
  session_start();
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);
  $host = "localhost";
  $username = "root";
  $db_password = "";
  $db_name = "data_db";

  $conn = new mysqli($host, $username, $db_password, $db_name);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  if (isset($_GET['id'])) {
  $stu_id = $_GET['id'];
  $sql = "DELETE FROM Data_User WHERE id ='$stu_id'";
  $result = $conn->query($sql);
  if ($result == TRUE) {
    $_SESSION['message']='Data Deleted Successfully';
    header("Location: index.php");
    exit;
  }else{
    echo "Error:" . $sql . "<br>" . $conn->error;
  }
  }
  $conn->close();
?>