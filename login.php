<?php session_start(); ?>

<?php
  if (isset($_SESSION['id'])) {
    header('Location: index.php');
    exit();
  }
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);

  $host = "localhost";
  $username = "root";
  $db_password = "password";
  $db_name = "data_db";

  $conn = new mysqli($host, $username, $db_password, $db_name);
  if ($conn->connect_error) {
    header("HTTP/1.1 500 Internal Server Error");
    die("Connection failed: " . $conn->connect_error);
  }
  $email_error = $password_error = "";
  $email = $password = $wrong = "";
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $valid = true;
    if (empty($_POST["email"])) {
      $email_error = "Please enter an email";
      $valid = false;
    } else {
      $email = test_input($_POST["email"]);
      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email_error = "Invalid email format";
        $valid = false;
      }
    }
    if (!empty($_POST["password"])) {
      $password = test_input($_POST["password"]);
      if (strlen($password) < 8) {
        $password_error = "Your Password Must Contain At Least 8 Characters!";
        $valid = false;
      } elseif (!preg_match("#[0-9]+#", $password)) {
        $password_error = "Your Password Must Contain At Least 1 Number!";
        $valid = false;
      } elseif (!preg_match("#[A-Z]+#", $password)) {
        $password_error = "Your Password Must Contain At Least 1 Capital Letter!";
        $valid = false;
      } elseif (!preg_match("#[a-z]+#", $password)) {
        $password_error = "Your Password Must Contain At Least 1 Lowercase Letter!";
        $valid = false;
      }
    } else {
      $password_error = "Please enter a password";
      $valid = false;
    }
    if ($valid) {
      $email = $_POST['email'];
      $password = $_POST['password'];
      $query = "SELECT * from Data_User WHERE email = '$email'";
      $user = $conn->query($query);
      if (!$user) {
        header("HTTP/1.1 500 Internal Server Error");
        die('Query Failed');
      }
      $row = $user->fetch_assoc();
      if ($row && password_verify($password, $row['password'])) {
        $_SESSION['id'] = $row['id'];
        $_SESSION['email'] = $row['email'];
        $_SESSION['fname'] = $row['fname'];
        $_SESSION['lname'] = $row['lname'];
        header('Location: index.php');
        exit();
      } else {
        header("HTTP/1.1 401 Unauthorized");
        $wrong= "Your email and password are incorrect.";
      }
    } 
  }

  function test_input($data)
  {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Project</title>
  <link rel="stylesheet" href="style.css">
  <link rel="shortcut icon" href="https://upload.wikimedia.org/wikipedia/commons/1/10/MS_Project_Logo.png" type="image/x-icon">
</head>

<body>
  <div id="id01" class="login-form">
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" onsubmit="return validateLoginForm()">
    <div class="container_1">
      <p><span class="error"><?php echo $wrong; ?></span></p>
      <h2 style="margin-bottom:20px;">Login</h2>
      <label for="email"><b>Email</b></label>
      <input type="email" name="email" placeholder="Enter Email" value="<?php echo htmlspecialchars($email); ?>">
      <span id="email_error" class="error"></span>
      <span class="error"><?php echo $email_error; ?></span>
      
      <label for="password"><b>Password</b></label>
      <div class="password-field" style="display:flex; width:100%;">
        <input type="password" id="password" name="password" placeholder="Enter Password" name="password"
          value="<?php echo $password; ?>">
        <img src="https://static.thenounproject.com/png/4334035-200.png" width="1%" height="1%" style="display: inline; margin:auto; margin-left: -5%; vertical-align: middle; width: 20px; cursor:pointer;" id="togglePassword">
      </div>
      <span id="password_error" class="error"></span>
      <span class="error"><?php echo $password_error; ?></span>
      
      <button type="submit">Login</button>
      <button type="button" onclick="window.location.href='sign_up.php';" class="cancelbtn">Sign up</button>
    </div>
    </form>
  </div>
  <script>
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');
    togglePassword.addEventListener('click', function (e) {
      const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
      password.setAttribute('type', type);
      if (togglePassword.src.match("https://icons.veryicon.com/png/o/miscellaneous/hekr/action-hide-password.png")) {
        togglePassword.src ="https://static.thenounproject.com/png/4334035-200.png";
      } else {
        togglePassword.src ="https://icons.veryicon.com/png/o/miscellaneous/hekr/action-hide-password.png";
      }
    }); 
    function validateLoginForm() {
      document.querySelectorAll('.error').forEach(function (element) {
        element.textContent = '';
      });
      let email = document.querySelector('input[name="email"]').value;
      if (email === '') {
        document.getElementById('email_error').textContent = 'Please enter an email';
        return false;
      }
      else if (!/\S+@\S+\.\S+/.test(email)) {
        document.getElementById('email_error').textContent = 'Invalid email format';
        return false;
      }

      let password = document.querySelector('input[name="password"]').value;
      if (password === '') {
        document.getElementById('password_error').textContent = 'Please enter a password';
        return false;
      }
      else if (password.length < 8 || !/\d/.test(password) || !/[A-Z]/.test(password) || !/[a-z]/.test(password)) {
        document.getElementById('password_error').textContent = 'Password must be at least 8 characters, contain a number, a capital letter, and a lowercase letter';
        return false;
      }
      return true;
    }
  </script>
</body>

</html>