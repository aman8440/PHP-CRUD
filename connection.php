<?php session_start(); ?>

<?php
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

  $fnameErr = $lnameErr = $emailErr = $phoneErr = $genderErr = $passwordErr = $cpasswordErr = $addressErr= "";
  $fname = $lname = $email = $phone = $gender = $comment = $password = $cpassword = $country = $state = $city = $status = "";

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $valid = true;

    if (empty($_POST["fname"])) {
      $fnameErr = "Please enter a first name";
      $valid = false;
    } else {
      $fname = test_input($_POST["fname"]);
      if (!preg_match("/^[a-zA-Z-' ]*$/", $fname)) {
        $fnameErr = "Only letters and white space allowed";
        $valid = false;
      }
    }

    if (empty($_POST["lname"])) {
      $lnameErr = "Please enter a last name";
      $valid = false;
    } else {
      $lname = test_input($_POST["lname"]);
      if (!preg_match("/^[a-zA-Z-' ]*$/", $lname)) {
        $lnameErr = "Only letters and white space allowed";
        $valid = false;
      }
    }

    if (empty($_POST["email"])) {
      $emailErr = "Please enter a email";
      $valid = false;
    } else {
      $email = test_input($_POST["email"]);
      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailErr = "Invalid email format";
        $valid = false;
      }
      else {
        $stmt = $conn->prepare("SELECT id FROM Data_User WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $emailErr = "Email already exists";
            $valid = false;
        }
        $stmt->close();
      }
    }

    if (empty($_POST["phone"])) {
      $phoneErr = "Please enter a phone number";
      $valid = false;
    } else {
      $phone = test_input($_POST["phone"]);
      if (!preg_match('/^[0-9]{10}$/', $phone)) {
        $phoneErr = "Invalid phone number format";
        $valid = false;
      } else {
        $stmt = $conn->prepare("SELECT id FROM Data_User WHERE phone = ?");
        $stmt->bind_param("s", $phone);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $phoneErr = "Phone number already exists";
            $valid = false;
        }
        $stmt->close();
      }
    }

    if (empty($_POST["gender"])) {
      $genderErr = "Please select a gender";
      $valid = false;
    } else {
      $gender = test_input($_POST["gender"]);
    }

    $comment = !empty($_POST["comment"]) ? test_input($_POST["comment"]) : "";

    if (!empty($_POST["password"]) && !empty($_POST["cpassword"])) {
      $password = test_input($_POST["password"]);
      $cpassword = test_input($_POST["cpassword"]);
      if (strlen($password) < 8) {
        $passwordErr = "Your Password Must Contain At Least 8 Characters!";
        $valid = false;
      } elseif (!preg_match("#[0-9]+#", $password)) {
        $passwordErr = "Your Password Must Contain At Least 1 Number!";
        $valid = false;
      } elseif (!preg_match("#[A-Z]+#", $password)) {
        $passwordErr = "Your Password Must Contain At Least 1 Capital Letter!";
        $valid = false;
      } elseif (!preg_match("#[a-z]+#", $password)) {
        $passwordErr = "Your Password Must Contain At Least 1 Lowercase Letter!";
        $valid = false;
      } elseif ($password !== $cpassword) {
        $cpasswordErr = "Passwords do not match!";
        $valid = false;
      }
    } else {
      $passwordErr = "Please enter a password";
      $cpasswordErr = "Please confirm your password";
      $valid = false;
    }

    if (empty($_POST["country"])) {
      $addressErr = "Please select a country.";
      $valid = false;
    } else {
      $country = test_input($_POST["country"]);
    }

    if (empty($_POST["state"])) {
      $addressErr = "Please enter a state.";
      $valid = false;
    } else {
      $state = test_input($_POST["state"]);
    }

    if (empty($_POST["city"])) {
      $addressErr = "Please enter a city.";
      $valid = false;
    } else {
      $city = test_input($_POST["city"]);
    }

    $status = !empty($_POST["status"]) ? test_input($_POST["status"]) : "";

    if ($valid) {
      $hashed_password = password_hash($password, PASSWORD_DEFAULT);
      $stmt = $conn->prepare("INSERT INTO Data_User (fname, lname, email, phone, gender, comment, password, country, state, city) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
      $stmt->bind_param("sssissssss", $fname, $lname, $email, $phone, $gender, $comment, $hashed_password, $country, $state, $city);
      if ($stmt->execute()) {
        $_SESSION['message'] = 'Data Inserted Successfully';
        header("Location: index.php");
        exit;
      } else {
        echo "Error Inserting a database: " . $stmt->error;          
      }
      $stmt->close();   
    }
  }

  $conn->close();

  function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }

?>
