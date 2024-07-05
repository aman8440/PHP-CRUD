<?php session_start(); ?>
<?php
  if (isset($_SESSION['id'])) {
    header('Location: index.php');
    exit();
  }
  include "connection.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Project</title>
  <link rel="stylesheet" href="style.css">
  <link rel="shortcut icon" href="https://upload.wikimedia.org/wikipedia/commons/1/10/MS_Project_Logo.png"
  type="image/x-icon">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css"           integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>

<body>
  <div id="id01" class="modal">
    <form id="addForm" action="connection.php" method="POST" onsubmit="return validateForm()">
      <div class="container">
        <h2 style="margin-bottom:20px;">Sign Up</h2>
        <p><span class="error">*Required field</span></p>
        <label for="fname" style="margin-top:10px"><b>First Name</b></label>
        <input type="text" name="fname" placeholder="Enter First Name" value="<?php echo htmlspecialchars($fname); ?>">
        <span class="error">* <?php echo $fnameErr; ?></span>
        <span id="fnameErr" class="error"></span>

        <label for="lname"><b>Last Name</b></label>
        <input type="text" name="lname" placeholder="Enter Last Name" value="<?php echo htmlspecialchars($lname); ?>">
        <span class="error">* <?php echo $lnameErr; ?></span>
        <span id="lnameErr" class="error"></span>

        <label for="email"><b>Email</b></label>
        <input type="email" id="email" name="email" placeholder="Enter Email" value="<?php echo htmlspecialchars($email); ?>">
        <span class="error">* <?php echo $emailErr; ?></span>
        <span id="emailErr" class="error"></span>

        <label for="phone"><b>Phone Number</b></label>
        <input type="tel" id="phone" name="phone" placeholder="Enter Phone Number"
          value="<?php echo htmlspecialchars($phone); ?>">
        <span class="error">* <?php echo $phoneErr; ?></span>
        <span id="phoneErr" class="error"></span>

        <label for="gender"><b>Gender</b></label>
        <div class="gender_style">
          <input type="radio" name="gender" <?php if (isset($gender) && $gender == "female")
            echo "checked"; ?>
            value="female">Female
          <input type="radio" name="gender" <?php if (isset($gender) && $gender == "male")
            echo "checked"; ?>
            value="male">Male
          <input type="radio" name="gender" <?php if (isset($gender) && $gender == "other")
            echo "checked"; ?>
            value="other">Other
          <span class="error" style="margin-left:10px">* <?php echo $genderErr; ?></span>
          <span id="genderErr" class="error"></span>
        </div>

        <label for="comment" style="margin-top:10px"><b>Query</b></label>
        <textarea name="comment" class="text_query" placeholder="Enter your query here ..."
          cols="40"><?php echo htmlspecialchars($comment); ?></textarea>

        <label for="password"><b>Password</b></label>
        <div class="password-field" style="display:flex; width:100%;">
          <input type="password" id="password" placeholder="Enter Password" name="password"
            value="<?php echo $password; ?>">
          <img src="https://static.thenounproject.com/png/4334035-200.png" width="1%" height="1%" style="display: inline; margin:auto; margin-left: -5%; vertical-align: middle; width: 20px; cursor:pointer;" id="togglePassword">
        </div>
        <span class="error">* <?php echo $passwordErr; ?></span>
        <span id="passwordErr" class="error"></span>

        <label for="cpassword"><b>Confirm Password</b></label>
        <input type="password" id="cpassword" placeholder="Enter Confirm Password" name="cpassword"
          value="<?php echo $cpassword; ?>">
        <span class="error">* <?php echo $cpasswordErr; ?></span>
        <span id="cpasswordErr" class="error"></span>

        <div class="address_field">
          <label for="country" style="width:auto;"><b>Country:</b></label>
          <select class="countryDropdown" name="country">
            <option value="">Select Country</option>
            <option value="United States">United States</option>
            <option value="Canada">Canada</option>
            <option value="United Kingdom">United Kingdom</option>
            <option value="Australia">Australia</option>
            <option value="Germany">Germany</option>
            <option value="France">France</option>
            <option value="Japan">Japan</option>
            <option value="China">China</option>
            <option value="India">India</option>
            <option value="Brazil">Brazil</option>
            <option value="Russia">Russia</option>
            <option value="South Korea">South Korea</option>
            <option value="Italy">Italy</option>
            <option value="Mexico">Mexico</option>
            <option value="South Africa">South Africa</option>
          </select>

          <label for="state" style="width:auto; margin-left:20px;"><b>State:</b></label>
          <input type="text" id="stateDropdown" placeholder="Enter State" name="state" value="<?php echo $state; ?>">

          <label for="city" style="width:auto; margin-left:20px;"><b>City:</b></label>
          <input type="text" id="cityDropdown" placeholder="Enter City" name="city" value="<?php echo $city; ?>">
        </div>
        <span class="error">* <?php echo $addressErr; ?></span>
        <span id="addressErr" class="error"></span>

        <button type="submit">Sign up</button>
        <button type="button" onclick="window.location.href='login.php';" class="cancelbtn">Login</button>
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
    function validateForm() {
      document.querySelectorAll('.error').forEach(function (element) {
        element.textContent = '';
      });

      let fname = document.querySelector('input[name="fname"]').value;
      if (fname === '') {
        document.getElementById('fnameErr').textContent = 'Please enter a first name';
        return false;
      }
      else if (!/^[a-zA-Z-' ]*$/.test(fname)) {
        document.getElementById('fnameErr').textContent = 'Only letters and white space allowed';
        return false;
      }

      let lname = document.querySelector('input[name="lname"]').value;
      if (lname === '') {
        document.getElementById('lnameErr').textContent = 'Please enter a last name';
        return false;
      }
      else if (!/^[a-zA-Z-' ]*$/.test(lname)) {
        document.getElementById('lnameErr').textContent = 'Only letters and white space allowed';
        return false;
      }

      let email = document.querySelector('input[name="email"]').value;
      if (email === '') {
        document.getElementById('emailErr').textContent = 'Please enter an email';
        return false;
      }
      else if (!/\S+@\S+\.\S+/.test(email)) {
        document.getElementById('emailErr').textContent = 'Invalid email format';
        return false;
      }

      let phone = document.querySelector('input[name="phone"]').value;
      if (phone === '') {
        document.getElementById('phoneErr').textContent = 'Please enter a phone number';
        return false;
      }
      else if (!/^[0-9]{10}$/.test(phone)) {
        document.getElementById('phoneErr').textContent = 'Invalid phone number format';
        return false;
      }

      let gender = document.querySelector('input[name="gender"]:checked');
      if (!gender) {
        document.getElementById('genderErr').textContent = 'Please select a gender';
        return false;
      }

      let password = document.querySelector('input[name="password"]').value;
      let cpassword = document.querySelector('input[name="cpassword"]').value;
      if (password === '') {
        document.getElementById('passwordErr').textContent = 'Please enter a password';
        return false;
      }
      else if (password.length < 8 || !/\d/.test(password) || !/[A-Z]/.test(password) || !/[a-z]/.test(password)) {
        document.getElementById('passwordErr').textContent = 'Password must be at least 8 characters, contain a number, a capital letter, and a lowercase letter';
        return false;
      }
      if (cpassword === '') {
        document.getElementById('cpasswordErr').textContent = 'Please enter a confirm password';
        return false;
      }
      else if (password !== cpassword) {
        document.getElementById('cpasswordErr').textContent = 'Passwords do not match';
        return false;
      }
      
      let country = document.querySelector('select[name="country"]').value;
      let state = document.getElementById('stateDropdown').value;
      let city = document.getElementById('cityDropdown').value;
      if (country === '' || state === '' || city === '') {
        document.getElementById('addressErr').textContent = 'Please complete the address';
        return false;
      }
      var emailError = document.getElementById('emailErr');
      var phoneError = document.getElementById('phoneErr');
      var emailExists = false;
      var phoneExists = false;
      var xhr = new XMLHttpRequest();
      xhr.open('POST', 'check_email.php', true);
      xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
      xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
          if (xhr.status === 200) {
            var response = JSON.parse(xhr.responseText);
            if (response.emailExists) {
              emailError.textContent = 'Email already exists';
              emailExists = true;
            } else {
              emailError.textContent = '';
            }
            if (response.phoneExists) {
              phoneError.textContent = 'Phone number already exists';
              phoneExists = true;
            } else {
              phoneError.textContent = '';
            }
            if (!emailExists && !phoneExists) {
              document.getElementById('addForm').submit();
            }
          } else {
            console.error('Error fetching data:', xhr.status);
          }
        }
      };
      var formData = 'email=' + encodeURIComponent(email) + '&phone=' + encodeURIComponent(phone);
      xhr.send(formData);
      return false;
    }

  </script>
</body>
</html>