<?php session_start(); ?>

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 600)) {
  session_unset();    
  session_destroy(); 
  header('location: login.php');
  exit();
}
$_SESSION['LAST_ACTIVITY'] = time();
if (!isset($_SESSION['id'])) { 
  header('location: login.php'); 
}
$host = "localhost";
$Data_Username = "root";
$db_password = "";
$db_name = "data_db";

$conn = new mysqli($host, $Data_Username, $db_password, $db_name);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$searchKeyword = "";
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 5;
$offset = ($page - 1) * $limit;
$sortColumn = isset($_GET['sortColumn']) ? $_GET['sortColumn'] : 'id';
$sortOrder = isset($_GET['sortOrder']) ? $_GET['sortOrder'] : 'ASC';

if (isset($_GET['search'])) {
    $searchKeyword = trim($conn->real_escape_string($_GET['search']));
    $sql = "SELECT * FROM Data_User WHERE fname LIKE '%$searchKeyword%' OR lname LIKE '%$searchKeyword%' OR email LIKE '%$searchKeyword%' OR phone LIKE '%$searchKeyword%' OR gender LIKE '%$searchKeyword%' OR country LIKE '%$searchKeyword%' OR state LIKE '%$searchKeyword%' OR city LIKE '%$searchKeyword%' OR status LIKE '%$searchKeyword%' ORDER BY $sortColumn $sortOrder LIMIT $limit OFFSET $offset";
    $countSql = "SELECT COUNT(*) AS total FROM Data_User WHERE fname LIKE '%$searchKeyword%' OR lname LIKE '%$searchKeyword%' OR email LIKE '%$searchKeyword%' OR phone LIKE '%$searchKeyword%' OR gender LIKE '%$searchKeyword%' OR country LIKE '%$searchKeyword%' OR state LIKE '%$searchKeyword%' OR city LIKE '%$searchKeyword%' OR status LIKE '%$searchKeyword%'";
} else {
    $sql = "SELECT * FROM Data_User ORDER BY $sortColumn $sortOrder LIMIT $limit OFFSET $offset";
    $countSql = "SELECT COUNT(*) AS total FROM Data_User";
}

$result = $conn->query($sql); 
$countResult = $conn->query($countSql);
$totalResult = $countResult->fetch_assoc()['total'];
$totaPage = ceil($totalResult / $limit);
function getSortLink($column) {
  $sortColumn = isset($_GET['sortColumn']) ? $_GET['sortColumn'] : 'id';
  $sortOrder = isset($_GET['sortOrder']) && $_GET['sortOrder'] == 'ASC' ? 'DESC' : 'ASC';
  $queryParams = array_merge($_GET, ['sortColumn' => $column, 'sortOrder' => $sortOrder]);
  return '?' . http_build_query($queryParams);
}
if (isset($_POST['signout'])) {
  session_destroy();
  header('location: login.php');
}
$conn->close();
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
    ul {
      list-style-type: none;
      margin: 0;
      padding: 0;
      overflow: hidden;
      background-color: #333;
    }

    li {
      float: left;
    }

    li a {
      display: block;
      color: white;
      text-align: center;
      padding: 14px 16px;
      text-decoration: none;
    }

    li a:hover:not(.active) {
      background-color: #111;
    }
    .users_span{
      color: white;
      margin: 12px;
    }
    .active {
      background-color: #04AA6D;
    }
    .main {
      display: flex;
      justify-content: flex-start;
      align-items: center;
      flex-direction: column;
      width: 100%;
      height: 100%;
      margin-top: 70px;
      margin-bottom: 12.87%;
    }
    .card-header {
      display: flex;
      flex-direction: row;
      align-items: center;
      justify-content: flex-start;
      border-collapse: collapse;
      width: 80%;
    }
    .heading {
      font-size: 30px;
      display: inline-flex;
      justify-content: flex-start;
      align-items: center;
      margin-right: 12px;
    }
    .user_data {
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
    }
    input[type=text] {
      padding: 6px;
      margin-top: 8px;
      font-size: 17px;
      border: none;
      background: #edebeb;
      color: #000;
      margin-right: -5px;
      outline: none;
    }
    input[type=text]:hover {
      background-color: rgba(233, 233, 233, 0.781);
    }
    ::placeholder {
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
    .table {
      display: block;
      overflow: auto;
      border-collapse: collapse;
      width: 100%;
    }
    .table td, .table th {
      border: 1px solid #ddd;
      padding: 8px;
    }
    .asc-icon:before {
      content: '\25B2';
    }
    .desc-icon:before {
      content: '\25BC';
    }
    .table tr:nth-child(even) { background-color: #f2f2f2; }
    .table tr:hover { background-color: #c2c0c05b; }
    .table th {
      padding-top: 12px;
      padding-bottom: 12px;
      text-align: left;
      background-color: #f2f2f2;
      color: #000;
    }
    .table th a{
      text-decoration: none;
      color: black;
    }
    /* .table th a i {
      color: rgba(0,0,0,0.4);
    } */
    .btn-action {
      display: flex;
      flex-wrap: wrap;
    }
    .btn-add {
      background-color: #04AA6D;
      color: white;
      padding: 14px 20px;
      margin: 8px;
      border: none;
      cursor: pointer;
      text-decoration: none;
      width: 100%;
    }
    .btn-add:hover { opacity: 0.6; }
    .btn-up {
      background-color: #000;
      border-radius: 8px;
      padding: 7px;
    }
    .btn-del {
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
      margin-bottom: 1em;
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
    .pagination .pagesBtn:hover:not(.active) { background-color: #ddd; }
    .dropdown {
      position: relative;
      display: inline-block;
      margin-left: 13px;
    }
    .countryDropdown {
      padding: 5px;
      font-size: 14px;
      outline: none;
    }
    .btn-logout{
      padding: 13px;
      background-color: #04AA6D;
      color: white;
      cursor: pointer;
    }
    .btn-logout:hover{
      opacity: 0.8;
    }
    footer {
      /* text-align: center; */
      padding: 3px;
      background-color: #000;
      color: white;
      display: flex;
      height: 100%;
      width: 100%;
      justify-content: center;
      align-items: flex-end;
    }
    footer p{
      margin: 17px;
    }
  </style>
</head>
<body>
<ul>
  <li><a  href="index.php">Home</a></li>
  
  <li style="float:right; display:flex">
    <?php if (isset($_SESSION['fname']) && isset($_SESSION['lname'])): ?>
      <span class="users_span">Welcome, <?= htmlspecialchars($_SESSION['fname'], ENT_QUOTES, 'UTF-8') . ' ' . htmlspecialchars($_SESSION['lname'], ENT_QUOTES, 'UTF-8'); ?></span>
    <?php endif; ?>
  <form action="" method="post">
    <button type="submit" name='signout' class="btn-logout btn">Logout</button>
  </form>
  </li>
</ul>
  <div class="main">
    <div class="card-header">
      <h1 class="heading">Users</h1>
      <div class="search-container">
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="GET">
          <input type="text" value="<?= htmlspecialchars($searchKeyword, ENT_QUOTES, 'UTF-8') ?>" placeholder="Search any details" name="search"> 
          <button type="submit"><i class="fa fa-search"></i>Search</button>
          <input type="hidden" name="limit" value="<?= $limit ?>">
        </form>
      </div>
      <a href="add_data.php" class="btn-add btn" style="width:auto;">Add</a>
  </div>
    <?php include "message.php"; ?>
    <?php include "update_message.php"; ?>
    <?php include "delete_message.php"; ?>
  <div class="user_data">
    <table class="table">
        <thead>
        <tr>
          <th><a href="<?= getSortLink('id') ?>">ID<span class="<?= $sortColumn == 'id' ? ($sortOrder == 'ASC' ? 'asc-icon' : 'desc-icon') : '' ?> sort-icon"></span></a></th>
          <th><a href="<?= getSortLink('fname') ?>">First Name<span class="<?= $sortColumn == 'fname' ? ($sortOrder == 'ASC' ? 'asc-icon' : 'desc-icon') : '' ?> sort-icon"></span></a></th>
          <th><a href="<?= getSortLink('lname') ?>">Last Name<span class="<?= $sortColumn == 'lname' ? ($sortOrder == 'ASC' ? 'asc-icon' : 'desc-icon') : '' ?> sort-icon"></span></a></th>
          <th><a href="<?= getSortLink('email') ?>">Email</a><span class="<?= $sortColumn == 'email' ? ($sortOrder == 'ASC' ? 'asc-icon' : 'desc-icon') : '' ?> sort-icon"></span></th>
          <th><a href="<?= getSortLink('phone') ?>">Phone Number<span class="<?= $sortColumn == 'phone' ? ($sortOrder == 'ASC' ? 'asc-icon' : 'desc-icon') : '' ?> sort-icon"></span></a></th>
          <th><a href="<?= getSortLink('gender') ?>">Gender<span class="<?= $sortColumn == 'gender' ? ($sortOrder == 'ASC' ? 'asc-icon' : 'desc-icon') : '' ?> sort-icon"></span></a></th>
          <th>Query</th>
          <th><a href="<?= getSortLink('country') ?>">Country<span class="<?= $sortColumn == 'country' ? ($sortOrder == 'ASC' ? 'asc-icon' : 'desc-icon') : '' ?> sort-icon"></span></a></th>
          <th><a href="<?= getSortLink('state') ?>">State<span class="<?= $sortColumn == 'state' ? ($sortOrder == 'ASC' ? 'asc-icon' : 'desc-icon') : '' ?> sort-icon"></span></a></th>
          <th><a href="<?= getSortLink('city') ?>">City<span class="<?= $sortColumn == 'city' ? ($sortOrder == 'ASC' ? 'asc-icon' : 'desc-icon') : '' ?> sort-icon"></span></a></th>
          <th><a href="<?= getSortLink('status') ?>">Status<span class="<?= $sortColumn == 'status' ? ($sortOrder == 'ASC' ? 'asc-icon' : 'desc-icon') : '' ?> sort-icon"></span></a></th>
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
        ?>
        </tbody>
    </table>
  </div>
  <div class="pagination">
    <?php
    $prevPage = $page - 1;
    $nextPage = $page + 1;
    $queryParams = http_build_query(array_merge($_GET, ['page' => $prevPage]));
    
    if ($page > 1) {
        echo '<a class="pagesBtn" href="index.php?' . $queryParams . '">&laquo;</a> ';
    }
    for ($btn = 1; $btn <= $totaPage; $btn++) {
      $queryParams = http_build_query(array_merge($_GET, ['page' => $btn]));
      if ($btn == $page) {
        echo '<a class="pagesBtn active">' . $btn . '</a> ';
      } else {
        echo '<a class="pagesBtn" href="index.php?' . $queryParams . '">' . $btn . '</a> ';
      }
    }
    $queryParams = http_build_query(array_merge($_GET, ['page' => $nextPage]));
    if ($page < $totaPage) {
      echo '<a class="pagesBtn" href="index.php?' . $queryParams . '">&raquo;</a>';
    }
    ?>
    <div class="dropdown">
      <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="GET">
        <input type="hidden" name="search" value="<?= htmlspecialchars($searchKeyword, ENT_QUOTES, 'UTF-8') ?>">
        <input type="hidden" name="page" value="<?= $page ?>">
        <input type="hidden" name="sortColumn" value="<?= htmlspecialchars($sortColumn, ENT_QUOTES, 'UTF-8') ?>">
        <input type="hidden" name="sortOrder" value="<?= htmlspecialchars($sortOrder, ENT_QUOTES, 'UTF-8') ?>">
        <select class="countryDropdown" name="limit" onchange="this.form.submit()">
          <option value="5" <?= $limit == 5 ? 'selected' : '' ?>>5</option>
          <option value="10" <?= $limit == 10 ? 'selected' : '' ?>>10</option>
          <option value="15" <?= $limit == 15 ? 'selected' : '' ?>>15</option>
          <option value="20" <?= $limit == 20 ? 'selected' : '' ?>>20</option>
        </select>
      </form>
    </div>
    <?php echo "Total Records: $totalResult"; ?>  
  </div>
  </div>
  <footer>
    <p>All ©Copyrights revsered by ARCS Infotech</p>
  </footer>
</body>
</html>
