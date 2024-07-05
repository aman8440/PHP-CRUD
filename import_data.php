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
    function importSQLFile($conn, $sqlFile) {
        $queries = file_get_contents($sqlFile);
        if ($conn->multi_query($queries)) {
            do {
                /* store first result set */
                if ($result = $conn->store_result()) {
                    $result->free();
                }
                /* print divider */
                if ($conn->more_results()) {
                    printf("-----------------\n");
                }
            } while ($conn->next_result());
        } else {
            echo "Error importing file: " . $conn->error;
        }
    }

    importSQLFile($conn, 'countries.sql');
    importSQLFile($conn, 'states.sql');
    importSQLFile($conn, 'cities.sql');

    // $conn->close();
    echo "Data imported successfully.";
?>