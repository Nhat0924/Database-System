<?php
require_once 'db.php';
function get_query($sqlQuery) {

    // Establish connection
    $conn = connect();

    // Retrieve SQL query from textarea and sanitize it
    $sqlQuery = sanitize_mysql($conn, $_POST[$sqlQuery]);

    // DROP prevention
    $query = strtolower($sqlQuery);
    drop_prevention($query);

    // Execute actions
    $result = query_actions($conn, $query);
    // $action = array("create", "delete", "update", "insert");
    // foreach ($action as $act) {
    //     if (stripos($query, $act) !== false) {
    //         switch($act) {
    //             case "create":
    //                 $result = $conn->query($query);
    //                 echo "Table Created";
    //                 break;
    //             case "update":
    //                 $result = $conn->query($query);
    //                 echo "Table Updated";
    //                 break;
    //             case "delete":
    //                 $result = $conn->query($query);
    //                 echo "Row(s) Deleted";
    //                 break;
    //             case "insert":
    //                 $result = $conn->query($query);
    //                 echo "Row inserted";
    //                 break;
    //         }
    //     }
    // }

    // Execute select statement]
    $result = $conn->query($query);
    if (!$result) {
        echo "Query failed";
    } else {
        // Process the results
        echo "<h2>Query Results:</h2>";
        echo "<table border='1'>";
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            foreach ($row as $value) {
                echo "<td>$value</td>";
            }
            echo "</tr>";
        }
        echo "</table>";

    }

    //End connection
$conn = null;
}

function drop_prevention($query) {
    if (stripos($query,"drop") !== false) {
        echo "Error: Execution of SQL DROP statement is not allowed.";
        die();
    }
}

function query_actions($conn, $query) {
    // Execute actions
    $action = array("create", "delete", "update", "insert");
    foreach ($action as $act) {
        if (stripos($query, $act) !== false) {
            switch($act) {
                case "create":
                    $result = $conn->query($query);
                    echo "Table Created";
                    break;
                case "update":
                    $result = $conn->query($query);
                    echo "Table Updated";
                    break;
                case "delete":
                    $result = $conn->query($query);
                    echo "Row(s) Deleted";
                    break;
                case "insert":
                    $result = $conn->query($query);
                    echo "Row inserted";
                    break;
            }
        }
    }

    return $result;
}