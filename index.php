<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="styles.css">
    <title>COMP 5120 - Term Project</title>
</head>
<body>
    <h1>COMP 5120 - Term Project</h1>
    <h2>Nhat Nguyen - nhn0001@auburn.edu</h2>
    <hr class="separator">
    <p>Query Tables </p>
    <form method="post">
    <textarea name="sqlQuery" placeholder="Enter your query here" class="query-box"></textarea>
    <input type="submit" name="submitted" value="Submit">
    <input type="reset" name = "clear" value="Clear">
</form>

    <?php
        include_once 'query.php';
    ?>
</body>
</html>
