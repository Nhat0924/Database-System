<?php
require_once 'db.php';
//Form handling

if($_SERVER["REQUEST_METHOD"] == "POST") {
    //Retrieve the SQL statement from the query box, sanitized and create connection
    if (isset($_POST['submitted'])) {
        if(isset($_POST['sqlQuery']) && !empty($_POST['sqlQuery'])) {
            $pdo = connect();
            $SQLquery = sanitize_string($_POST['sqlQuery']); //PHP 5.3
            //lowercase the string to check for keywords
            $query = strtolower($SQLquery);

            //Prohibit SQL DROP statement
            if(strpos($query, 'drop') !== false) {
                die('<br><span class="error-message bold">SQL DROP statement are prohibited.</span><br>');
            }
            // try catch to display error message for invalid query
            try{
                //Detect SQL action statement other than SELECT statement and display message
                $action = array('create', 'update', 'delete', 'insert');
                foreach($action as $keyword) {
                    if(strpos($query, $keyword) !== false) {
                        if ($keyword == 'create') {
                            $result = $pdo->query($SQLquery);
                            echo '<br><span class="action bold">TABLE CREATED</span>';
                            $pdo = null;
                            return;
                        }

                        else if ($keyword == 'update') {
                            $result = $pdo->query($SQLquery);
                            echo '<br><span class="action bold">TABLE UPDATED</span>';
                            $pdo = null;
                            return;
                        }

                        else if ($keyword == "delete") {
                            $result = $pdo->query($SQLquery);
                            echo '<br><span class="action bold">ROW(S) DELETED</span>';
                            $pdo = null;
                            return;
                        }
                        else if ($keyword == "insert") {
                            $result = $pdo->query($SQLquery);
                            echo '<br><span class="action bold">ROW(S) INSERTED</span>';
                            $pdo = null;
                            return;
                        }    
                    }
                }

                //Execute SELECT statement and display error message if necessary
                $result = $pdo->query($SQLquery);
                if (!$result) {
                    die('<span class="error-message bold">Failed to execute SQL query.</span>');
                }
                
                //Output query results in table form
                $count = 0;

                $fields_num = $result->columnCount();
                echo "<br><table><tr>";
                for ($i = 0; $i < $fields_num; $i++) {
                    $columnMeta = $result->getColumnMeta($i);
                    echo "<th>{$columnMeta['name']}</th>";
                }
                echo "</tr>\n";

                while ($row = $result->fetch(PDO::FETCH_NUM)) {
                    echo "<tr>";
                    foreach($row as $cell) {
                        echo "<td>$cell</td>";
                    }
                    echo "</tr>\n";
                    $count++;
                }
                echo "</table>";

                //Display the number of rows retrieved
                echo '<br><span class="bold">Number of rows retrieved: </span>' . $count;

            } catch (PDOException $e) {
                //Display the error message
                echo '<br><span class="error-message bold">Error: Invalid query statement.</span><br>';
                echo 'Error Message: ' . htmlspecialchars($e->getMessage());
            }
            
            //Disconnect after querying
            $pdo = null;
        }

        else {
            //Return if there's no input in the query box
            return;
            }
    }
}