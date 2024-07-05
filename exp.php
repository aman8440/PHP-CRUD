<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
$host = "localhost";
$Data_Username = "root";
$db_password = "";
$db_name = "data_db";

$conn = new mysqli($host, $Data_Username, $db_password, $db_name);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$searchKeyword = "";
if (isset($_GET['search'])) {
  $searchKeyword = $conn->real_escape_string($_GET['search']);
  $sql = "SELECT * FROM Data_User 
          WHERE fname LIKE '%$searchKeyword%' 
          OR lname LIKE '%$searchKeyword%' 
          OR email LIKE '%$searchKeyword%' 
          OR phone LIKE '%$searchKeyword%' 
          OR gender LIKE '%$searchKeyword%' 
          OR country LIKE '%$searchKeyword%' 
          OR state LIKE '%$searchKeyword%' 
          OR city LIKE '%$searchKeyword%' 
          OR status LIKE '%$searchKeyword%' 
          ORDER BY id ASC";
} else {
  $page=$_GET['page']??1;
  $limit=5;
  $offset= ($page-1)*$limit;

  $sql = "SELECT * FROM Data_User ORDER BY id ASC LIMIT $offset,$limit";
}

$result = $conn->query($sql);

$query3= "SELECT * FROM Data_User";
$run3= $conn->query($query3);
$totalResult= $run3->num_rows;
$totaPage= ceil($totalResult/$limit);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Project</title>
  <link rel="shortcut icon" href="https://upload.wikimedia.org/wikipedia/commons/1/10/MS_Project_Logo.png" type="image/x-icon">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
      margin-bottom: 20px;
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
    .no-records {
      text-align: center;
      font-size: 20px;
      font-weight: bold;
      padding: 10px;
    }
    .pagination {
      display: inline-block;
      margin-top: 20px;
      margin-bottom: 9em;
    }

    .pagination .pagesBtn {
      color: black;
      float: left;
      padding: 8px 16px;
      transition: background-color .3s;
      text-decoration: none;
    }

    .pagination a.active {
      background-color: #4CAF50;
      color: white;
    }
    .pagination .pagesBtn:hover:not(.active) {background-color: #ddd;}
    /* .pagination .pagesBtn:hover {background-color: #ddd;} */

    .dropdown {
      position: relative;
      display: inline-block;
    }
    .countryDropdown{
      padding: 5px;
      font-size: 14px;
    }
  </style>
</head>

<body>

<div class="main">
    <div class="card-header">
        <h1 class="heading">Users</h1>
        <a href="add_data.php" class="btn-add btn" style="width:auto;">Add</a>
    </div>
    <?php include "message.php"; ?>
    <?php include "update_message.php"; ?>
    <?php include "delete_message.php"; ?>
    <div class="user_data">
      <div class="search-container">
          <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="GET">
              <input type="text" value="<?= htmlspecialchars($searchKeyword, ENT_QUOTES, 'UTF-8') ?>" placeholder="Search any details" name="search" required>
              <button type="submit"><i class="fa fa-search"></i>Search</button>
          </form>
      </div>
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
          if ($result->num_rows > 0) {
              while ($row = $result->fetch_assoc()) {
                  ?>
                  <tr>
                      <td><?= htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8'); ?></td>
                      <td><?= htmlspecialchars($row['fname'], ENT_QUOTES, 'UTF-8'); ?></td>
                      <td><?= htmlspecialchars($row['lname'], ENT_QUOTES, 'UTF-8'); ?></td>
                      <td><?= htmlspecialchars($row['email'], ENT_QUOTES, 'UTF-8'); ?></td>
                      <td><?= htmlspecialchars($row['phone'], ENT_QUOTES, 'UTF-8'); ?></td>
                      <td><?= htmlspecialchars($row['gender'], ENT_QUOTES, 'UTF-8'); ?></td>
                      <td><?= htmlspecialchars($row['comment'], ENT_QUOTES, 'UTF-8'); ?></td>
                      <td><?= htmlspecialchars($row['country'], ENT_QUOTES, 'UTF-8'); ?></td>
                      <td><?= htmlspecialchars($row['state'], ENT_QUOTES, 'UTF-8'); ?></td>
                      <td><?= htmlspecialchars($row['city'], ENT_QUOTES, 'UTF-8'); ?></td>
                      <td><?= htmlspecialchars($row['status'], ENT_QUOTES, 'UTF-8'); ?></td>
                      <td>
                          <div class="btn-action">
                              <a href="add_data.php?id=<?= $row['id']; ?>" class="btn-up btn-add" style="width:auto;">Edit</a>
                              <a href="delete.php?id=<?= $row['id']; ?>" class="btn-del btn-add" style="width:auto;">Delete</a>
                          </div>
                      </td>
                  </tr>
                  <?php
              }
          } else {
            echo "<tr><td colspan='12' class='no-records'>No records found</td></tr>";
          }
          $conn->close();
          ?>
          </tbody>
          
      </table>
    </div>
    <div class="pagination">
      <!-- <a href="#">&laquo;</a> -->
      <?php
      $currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1; 
      $prevPage = $currentPage - 1;
      $nextPage = $currentPage + 1;

      // Start of pagination
      if ($currentPage > 1) {
        echo '<a class="pagesBtn" href="index.php?page=' . $prevPage . '">&laquo;</a> ';
      }
      for ($btn = 1; $btn <= $totaPage; $btn++) {
        if ($btn == $currentPage) {
          echo '<a class="pagesBtn active" href="index.php?page=' . $btn . '">' . $btn . '</a> ';
        } else {
          echo '<a class="pagesBtn" href="index.php?page=' . $btn . '">' . $btn . '</a> ';
        }
      }
      if ($currentPage < $totaPage) {
        echo '<a class="pagesBtn" href="index.php?page=' . $nextPage . '">&raquo;</a>';
      }
      ?>
      <!-- <a href="#">&raquo;</a> -->
      <div class="dropdown">
        <select class="countryDropdown" name="pages_1">
          <option value="">Select Page</option>
          <option value="Page5">5</option>
          <option value="Page10">10</option>
          <option value="Page15">15</option>
          <option value="Page20">20</option>
        </select>
      </div>
    </div>
</div>
</body>

</html>
