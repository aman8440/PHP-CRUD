<?php session_start(); ?>

<?php
  $host = "localhost";
  $username = "root";
  $db_password = "";
  $db_name = "data_db";
  ini_set('display_errors', '1');
  ini_set('display_startup_errors', '1');
  error_reporting(E_ALL);

  $conn = new mysqli($host, $username, $db_password, $db_name);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $gender = $_POST['gender'];
    $comment = $_POST['comment'];
    $country = $_POST['country'];
    $state = $_POST['state'];
    $city = $_POST['city'];
    $status = $_POST['status'];
    
    $check_email_sql = "SELECT id FROM Data_User WHERE email = ? AND id != ?";
    $stmt = $conn->prepare($check_email_sql);
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("si", $email, $id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $_SESSION['emailErr1'] = 'Email already exists for another user.';
        header("Location: add_data.php?id=$id");
        exit;
    }
    $stmt->close();

    $check_phone_sql = "SELECT id FROM Data_User WHERE phone = ? AND id != ?";
    $stmt = $conn->prepare($check_phone_sql);
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("ii", $phone, $id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $_SESSION['phoneErr1'] = 'Phone number already exists for another user.';
        header("Location: add_data.php?id=$id");
        exit;
    }
    $stmt->close();


    $sql = "UPDATE Data_User SET fname=?, lname=?, email=?, phone=?, gender=?, comment=?, country=?, state=?, city=?, status=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
      die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("sssissssssi", $fname, $lname, $email, $phone, $gender, $comment, $country, $state, $city, $status, $id);

    if ($stmt->execute()) {
      $_SESSION['message']='Data Updated Successfully';
      header("Location: index.php");
      exit;
    } else {
      echo "Error updating record: " . $stmt->error;
    }

    $stmt->close();
  }
  $conn->close();
?>