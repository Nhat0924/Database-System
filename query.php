<?php
require_once 'db.php';
function get_query($sqlQuery) {

    // Establish connection
    $conn = connect();

    // Retrieve SQL query from textarea and sanitize it
    $sqlQuery = sanitize_mysql($conn, $_POST[$sqlQuery]);

    // Get query
    $query = strtolower($sqlQuery);
    $action = array("create", "delete", "update", "insert");
    $action_executed = false;
    // DROP prevention
    if (stripos($query,"drop") !== false) {
        drop_prevention($query);
    }
    else {
    // Execute ACTION statement
        foreach ($action as $act) {
            if (stripos($query, $act) !== false) {
                $result = query_actions($conn, $query);
                $action_executed = true;
                break;
            }
        }
    }

    // Execute SELECT statement
    if (!$action_executed) {
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
    }

    //End connection
$conn = null;
}

function drop_prevention($query) {
    if (stripos($query,"drop") !== false) {
        echo "<h2>Error: Execution of SQL DROP statement is not allowed.</h2>";
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
                    echo "<h2>Table Created</h2>";
                    break;
                case "update":
                    $result = $conn->query($query);
                    echo "<h2>Table Updated</h2>";
                    break;
                case "delete":
                    $result = $conn->query($query);
                    echo "<h2>Row(s) Deleted</h2>";
                    break;
                case "insert":
                    $result = $conn->query($query);
                    echo "<h2>Row inserted</h2>";
                    break;
            }
        }
    }

    return $result;
}
