<?php
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
  include "connection.php";
  $host = "localhost";
  $Data_Username = "root";
  $db_password = "";
  $db_name = "data_db";

  $conn = new mysqli($host, $Data_Username, $db_password, $db_name);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  if(isset($_GET['search'])){
    $keyword = $_GET['search'];
    $sql = "SELECT * FROM Data_User JOIN profile ON profile.Data_User_id=Data_User.id WHERE (Data_User.fname LIKE '%$keyword%' OR Data_User.lname LIKE '%$keyword%' OR Data_User.email LIKE '%$keyword%' OR Data_User.phone LIKE '%$keyword%' OR Data_User.gender LIKE '%$keyword%'OR Data_User.country LIKE '%$keyword%' OR Data_User.state LIKE '%$keyword%' OR Data_User.city LIKE '%$keyword%' OR Data_User.status LIKE '%$keyword%') ORDER BY Data_User.id DESC";
  }else{
    $sql = "SELECT * FROM Data_User JOIN profile ON profile.Data_User_id=Data_User.id ORDER BY Data_User.id DESC";
  }
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <!-- <link rel="stylesheet" href="style.css"> -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      
    }
    html {
      font-family: Arial, Helvetica, sans-serif;
      width: 100%;
      height: 100%;
    }
    .main{
      display: flex;
      justify-content: flex-start;
      align-items: center;
      flex-direction: column;
      width: 100%;
      height: 100%;
      margin-top: 70px;
    }
    .card-header{
      display: flex;
      flex-direction: row;
      align-items: center;
      justify-content: flex-start;
      border-collapse: collapse;
      width: 80%;
      
    }
    .heading{
      font-size: 30px;
      display: inline-flex;
      justify-content: flex-start;
      align-items: center;
      width: 100%;
      
    }
    .user_data{
      display: flex;
      align-items: center;
      justify-content: flex-start;
      flex-direction: column;
      width: 80%;
    }
    .search-container {
      display: flex;
      justify-content: flex-start;
      align-items: center;
      width: 100%;
      margin-bottom: 15px;
    }
    input[type=text] {
      padding: 6px;
      margin-top: 8px;
      font-size: 17px;
      border: none;
      background: #ddd;
      color: #000;
      margin-right: -4px;
      outline: none;
    }
    input[type=text]:hover{
      background-color: rgba(233, 233, 233, 0.925);
    }
    ::placeholder{
      color: #000;
    }
    .search-container button {
      padding: 6px 10px;
      margin-top: 8px;
      margin-right: 16px;
      margin-left: 0;
      background: #ddd;
      font-size: 17px;
      border: none;
      cursor: pointer;
    }

    .search-container button:hover {
      background: #ccc;
    }
    .text-align{
      display: flex;
      justify-content: center;
      align-items: center;
      font-size: 40px;
      font-weight: bold;
    }
    .table{
      border-collapse: collapse;
      width: 100%;
    }
    .table td, .table th {
      border: 1px solid #ddd;
      padding: 8px;
    }
    .table tr:nth-child(even){background-color: #f2f2f2;}

    .table tr:hover {background-color: #c2c0c05b;}

    .table th {
      padding-top: 12px;
      padding-bottom: 12px;
      text-align: left;
      background-color: #f2f2f2;
      color: #000;
    }
    .btn-action{
      display: flex;
      flex-wrap: wrap;
    }
    .btn-add{
      background-color: #04AA6D;
      color: white;
      padding: 14px 20px;
      margin: 8px;
      border: none;
      cursor: pointer;
      text-decoration: none;
      width: 100%;
    }
    .btn-add:hover{
      opacity: 0.6;
    }
    .btn-up{
      background-color: #000;
      border-radius: 8px;
      padding: 7px;
    }
    .btn-del{
      background-color: #ff0000;
      border-radius: 8px;
      padding: 7px;
    }
  </style>
</head>

<body>

  <div class="main">
    <div class="card-header">
      <h1 class="heading">User Details!</h1>
      <a href="index.php" class="btn-add btn" style="width:auto;">Back</a>
    </div>
    <div class="user_data">
      <div class="search-container">
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="GET">
            <input type="text" value="<?=@$_GET['search']?>" placeholder="Search any details" name="search" required>
            <button type="submit"><i class="fa fa-search"></i>Search</button>
        </form>
      </div>
        <?php 
          $host = "localhost";
          $username = "root";
          $password = "";
          $db_name = "data_db";

          $conn = new mysqli($host, $username, $password, $db_name);

          if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
          }
          $sql = "SELECT * FROM Data_User";
          $result = $conn->query($sql);
          if(! $result){
            ?>
            <div class="text-align">
              No Result Found
            </div>
            <?php
          }
        ?>
      <table class="table">
        <thead>
          <tr>
            <th>ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Phone Number</th>
            <th>Gender</th>
            <th>Query</th>
            <th>Country</th>
            <th>State</th>
            <th>City</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $host = "localhost";
          $username = "root";
          $password = "";
          $db_name = "data_db";

          $conn = new mysqli($host, $username, $password, $db_name);

          if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
          }
          $sql = "SELECT * FROM Data_User";
          $result = $conn->query($sql);
          if ($result) {
            if ($result->num_rows > 0) {
              while ($row = $result->fetch_assoc()) {
                ?>
                <tr>
                  <td><?= $row['id']; ?></td>
                  <td><?= $row['fname']; ?></td>
                  <td><?= $row['lname']; ?></td>
                  <td><?= $row['email']; ?></td>
                  <td><?= $row['phone']; ?></td>
                  <td><?= $row['gender']; ?></td>
                  <td><?= $row['comment']; ?></td>
                  <td><?= $row['country']; ?></td>
                  <td><?= $row['state']; ?></td>
                  <td><?= $row['city']; ?></td>
                  <td><?= $row['status']; ?></td>
                  <td>
                    <div class="btn-action">
                      <a href="update_data.php?id=<?= $row['id']; ?>" class="btn-up btn-add" style="width:auto;">Edit</a>
                      <a href="delete.php?id=<?= $row['id']; ?>" class="btn-del btn-add" style="width:auto;">Delete</a>
                    </div>
                  </td>
                </tr>
                <?php
              }
            } else {
              echo "<tr><td colspan='12'>No records found</td></tr>";
            }
          } else {
            echo "<tr><td colspan='12'>Error: " . $conn->error . "</td></tr>";
          }
          $conn->close();
          ?>
        </tbody>
      </table>
    </div>
  </div>
</body>

</html>