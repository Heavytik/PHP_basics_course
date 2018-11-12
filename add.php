<?php
    // if site is loaded without name parameter, dont show anything
    session_start();
    if ( ! isset($_SESSION['name']) ) {
        die('Not logged in');
    }

    if ( isset($_POST['cancel'] ) ) {
        header("Location: view.php");
        return;
    }

    // Create "open" database access
    $pdo = new PDO('mysql:host=localhost; port=8889; dbname=misc',
        'fred', 'zap');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    function addingError($errormessage) {
        $_SESSION['error'] = $errormessage;
        header("Location: add.php");
    }
    
    // insert form data to server
    if( isset($_POST['make']) ) {
        if ( strlen($_POST['make']) > 1 ) {
            if ( is_numeric($_POST['year']) && is_numeric($_POST['mileage']) ) {
                try {           
                    $stmt = $pdo->prepare(
                        'INSERT INTO autos (make, year, mileage) VALUES ( :mk, :yr, :mi)'
                    );        
                    $stmt->execute(array(
                        ':mk' => $_POST['make'],
                        ':yr' => $_POST['year'],
                        ':mi' => $_POST['mileage'])
                    );
                    $_SESSION['success'] = "Record inserted";
                    header("Location: view.php");
                    return;

                } catch (Exception $ex) {
                    error_log("error1.php, SQL error =" . $ex->getMessage());
                    addingError("Something went wrong");
                    return;
                }
                $success = true; 
            } else {
                addingError("Mileage and year must be numeric");
                return;
            }
        } else {
            addingError("Make is required");
            return;
        }
    }

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
    ?>

    <form method="POST">
        <label for="make">Make:</label>
        <input type="text" name="make" id="make">
        <br/>
        <label for="year">Year:</label>
        <input type="text" name="year" id="year">
        <br/>
        <label for="milage">Milage:</label>
        <input type="text" name="mileage" id="milage">
        <br/>
        <button type="submit" name="add">Add</button>
        <button type="submit" name="cancel">Cancel</button>
    </form>

    <?php
        
        // Prints error message, if available
        if ( isset($_SESSION['error']) ) {
            echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
            unset($_SESSION['success']);
        }
        
    ?>


</body>
</html>