<?php
  include "connection.php";

  $countries_query = "SELECT id, name FROM countries";
  $countries_result = $conn->query($countries_query);
  if (isset($_POST['type'])) {
    $type = $_POST['type'];

    $countries = [];
  if ($countries_result) {
    while ($row = $countries_result->fetch_assoc()) {
      $countries[] = $row;
    }
  } else {
    // Handle error if needed
  }

  echo $countries;

    if ($type == 'state') {
      $id = $_POST['id'];
      $stmt = $conn->prepare("SELECT id, name FROM states WHERE country_id = ?");
      $stmt->bind_param("i", $id);
      $stmt->execute();
      $result = $stmt->get_result();
      $data = [];
      while ($row = $result->fetch_assoc()) {
        $data[] = $row;
      }
      echo json_encode($data);
      $stmt->close();
      exit;
    } elseif ($type == 'city') {
      $id = $_POST['id'];
      $stmt = $conn->prepare("SELECT id, name FROM cities WHERE state_id = ?");
      $stmt->bind_param("i", $id);
      $stmt->execute();
      $result = $stmt->get_result();
      $data = [];
      while ($row = $result->fetch_assoc()) {
        $data[] = $row;
      }
      echo json_encode($data);
      $stmt->close();
      exit;
    } elseif ($type == 'get_country_id') {
      $name = $_POST['name'];
      $stmt = $conn->prepare("SELECT id FROM countries WHERE name = ? LIMIT 1");
      $stmt->bind_param("s", $name);
      $stmt->execute();
      $result = $stmt->get_result();
      $row = $result->fetch_assoc();
      echo json_encode($row['id']);
      $stmt->close();
      exit;
    } elseif ($type == 'get_state_id') {
      $name = $_POST['name'];
      $country_id = $_POST['country_id'];
      $stmt = $conn->prepare("SELECT id FROM states WHERE name = ? AND country_id = ? LIMIT 1");
      $stmt->bind_param("si", $name, $country_id);
      $stmt->execute();
      $result = $stmt->get_result();
      $row = $result->fetch_assoc();
      echo json_encode($row['id']);
      $stmt->close();
      exit;
    }
  }
?>
