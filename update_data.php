<?php
  session_start();
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  $host = "localhost";
  $Data_Username = "root";
  $db_password = "";
  $db_name = "data_db";

  $conn = new mysqli($host, $Data_Username, $db_password, $db_name);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  $id = $_GET['id'];
  $query = "SELECT * FROM Data_User WHERE id = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $result = $stmt->get_result();
  $Data_User = $result->fetch_assoc();

  $stmt->close();
  $conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Project</title>
  <link rel="shortcut icon" href="https://upload.wikimedia.org/wikipedia/commons/1/10/MS_Project_Logo.png"
    type="image/x-icon">
  <link rel="stylesheet" href="style.css">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      height: 100%;
    }
    .container {
      padding: 16px;
      display: flex;
      justify-content: center;
      align-items: flex-start;
      width: 50%;
      height: auto;
      flex-direction: column;
      text-align: left;
      margin: auto;
      box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
      background-color: white;
    }
  
    #stateDropdown{
      margin-right: 12px;
      padding: 10px;
      width: 20%;
      margin-top: 0;
      border-color: light-dark(rgb(118, 118, 118), rgb(133, 133, 133));
    }
    #cityDropdown{
      margin-right: 12px;
      padding: 10px;
      width: 20%;
      margin-top: 0;
      border-color: light-dark(rgb(118, 118, 118), rgb(133, 133, 133));
    }
    .address_field,
    select {
      margin-right: 12px;
      padding: 6px;
      width: auto;
    }
  </style>
</head>

<body>
  <div id="id02" class="update">
    <form id="updateForm" action="updates.php" method="POST">
      <div class="container">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($Data_User['id']); ?>">
        <label for="fname" style="margin-top:10px"><b>First Name</b></label>
        <input type="text" id="fname" name="fname" placeholder="Enter First Name" value="<?php echo htmlspecialchars($Data_User['fname']); ?>">

        <label for="lname"><b>Last Name</b></label>
        <input type="text" id="lname" name="lname" placeholder="Enter Last Name" value="<?php echo htmlspecialchars($Data_User['lname']); ?>">

        <label for="email"><b>Email</b></label>
        <input type="email" id="email" name="email" placeholder="Enter Email" value="<?php echo htmlspecialchars($Data_User['email']); ?>">

        <label for="phone"><b>Phone Number:</b></label>
        <input type="tel" id="phone" name="phone" placeholder="Enter Phone Number" value="<?php echo htmlspecialchars($Data_User['phone']); ?>">

        <label for="gender"><b>Gender</b></label>
        <div class="gender_style">
          <input type="radio" id="female" name="gender" value="female" <?php if ($Data_User['gender'] == 'female') echo 'checked'; ?>>Female
          <input type="radio" id="male" name="gender" value="male" <?php if ($Data_User['gender'] == 'male') echo 'checked'; ?>>Male
          <input type="radio" id="other" name="gender" value="other" <?php if ($Data_User['gender'] == 'other') echo 'checked'; ?>>Other
        </div>

        <label for="comment" style="margin-top:10px"><b>Query</b></label>
        <textarea id="comment" name="comment" class="text_query" placeholder="Enter your query here ..."
          cols="40"><?php echo htmlspecialchars($Data_User['comment']); ?></textarea>

        <div class="address_field">
          <label for="country" style="margin-right:12px"><b>Country:</b></label>
          <select id="country" class="countryDropdown" name="country">
            <option value="">Select Country</option>
            <option value="United States" <?php if ($Data_User['country'] == 'United States') echo 'selected'; ?>>United States</option>
            <option value="Canada" <?php if ($Data_User['country'] == 'Canada') echo 'selected'; ?>>Canada</option>
            <option value="United Kingdom" <?php if ($Data_User['country'] == 'United Kingdom') echo 'selected'; ?>>United Kingdom</option>
            <option value="Australia" <?php if ($Data_User['country'] == 'Australia') echo 'selected'; ?>>Australia</option>
            <option value="Germany" <?php if ($Data_User['country'] == 'Germany') echo 'selected'; ?>>Germany</option>
            <option value="France" <?php if ($Data_User['country'] == 'France') echo 'selected'; ?>>France</option>
            <option value="Japan" <?php if ($Data_User['country'] == 'Japan') echo 'selected'; ?>>Japan</option>
            <option value="China" <?php if ($Data_User['country'] == 'China') echo 'selected'; ?>>China</option>
            <option value="India" <?php if ($Data_User['country'] == 'India') echo 'selected'; ?>>India</option>
            <option value="Brazil" <?php if ($Data_User['country'] == 'Brazil') echo 'selected'; ?>>Brazil</option>
            <option value="Russia" <?php if ($Data_User['country'] == 'Russia') echo 'selected'; ?>>Russia</option>
            <option value="South Korea" <?php if ($Data_User['country'] == 'South Korea') echo 'selected'; ?>>South Korea</option>
            <option value="Italy" <?php if ($Data_User['country'] == 'Italy') echo 'selected'; ?>>Italy</option>
            <option value="Mexico" <?php if ($Data_User['country'] == 'Mexico') echo 'selected'; ?>>Mexico</option>
            <option value="South Africa" <?php if ($Data_User['country'] == 'South Africa') echo 'selected'; ?>>South Africa</option>
          </select>

          <label for="state" style="margin-left:20px; margin-right:12px"><b>State:</b></label>
          <input type="text" id="stateDropdown" placeholder="Enter State" name="state" value="<?php echo htmlspecialchars($Data_User['state']); ?>">

          <label for="city" style="margin-left:20px; margin-right:12px"><b>City:</b></label>
          <input type="text" id="cityDropdown" placeholder="Enter City" name="city" value="<?php echo htmlspecialchars($Data_User['city']); ?>">
        </div>

        <label for="status"><b>Status</b></label>
        <div class="gender_style">
          <input type="radio" id="status_yes" name="status" value="yes" <?php if ($Data_User['status'] == 'yes') echo 'checked'; ?>>Yes
          <input type="radio" id="status_no" name="status" value="no" <?php if ($Data_User['status'] == 'no') echo 'checked'; ?>>No
        </div>
        <button type="submit">Update</button>
        <button type="button" onclick="window.location.href='index.php';" class="cancelbtn">Cancel</button>
      </div>
    </form>
  </div>
</body>
</html>
