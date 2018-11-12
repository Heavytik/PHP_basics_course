<?php
    session_start();
    if ( ! isset($_SESSION['name']) ) {
        die('Not logged in');
    }
    // Create "open" database access
    $pdo = new PDO('mysql:host=localhost; port=8889; dbname=misc',
        'fred', 'zap');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>

<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Jere Tofferi Autos Database</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--link rel="stylesheet" type="text/css" media="screen" href="main.css" />
    <script src="main.js"></script-->
</head>
<body>
    <?php
        echo("<h1>Tracking Autos for " . htmlentities($_SESSION['name']) . "</h1>");

        // Prints success method, if SQL success
        if ( isset($_SESSION['success']) ) {
            echo('<p style="color: green;">'.htmlentities($_SESSION['success'])."</p>\n");
            unset($_SESSION['success']);
        }
    ?>

    <h2>Automobiles</h2>

    <?php
        // Prints all entities from table
        $stmt2 = $pdo->query("SELECT * FROM autos");
        
        echo("<ul>");
        while ( $row2 = $stmt2->fetch(PDO::FETCH_ASSOC) ) {
            echo( "<li>" . htmlentities($row2['year']) . " " . htmlentities($row2['make'])
            . " " . htmlentities($row2['mileage']) );
            echo( "</li>");
        }
        echo("</ul>");
    ?>

    <p><a href="add.php">Add New</a> | <a href="logout.php">Logout</a></p>
</body>
</html>
