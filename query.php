<?php
require_once 'db.php';

if($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['submitted'])) {
        if(isset($_POST['sqlQuery']) && !empty($_POST['sqlQuery'])) {
            $pdo = connect();
            $SQLquery = sanitize_string($_POST['sqlQuery']);
            $query = strtolower($SQLquery);

            if(strpos($query, 'drop') !== false) {
                die('<br><span class="error-message bold">SQL DROP statement are prohibited.</span><br>');
            }

            try{
                //Need to check if this's gonna work lol
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

                $result = $pdo->query($SQLquery);
                if (!$result) {
                    die("Failed to execute SQL query.");
                }
                
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

                echo '<br><span class="bold">Number of rows retrieved: </span>' . $count;

            } catch (PDOException $e) {
                echo '<br><span class="error-message bold">Error: Invalid query statement.</span><br>';
                echo 'Error Message: ' . htmlspecialchars($e->getMessage());
            }
        
            $pdo = null;
        }

        else {
            return;
            }
    }
}