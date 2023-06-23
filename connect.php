<?php
   
   // database connection
   $server_name = 'localhost';
   $user_name = 'root';
   $password = '';
   $db = 'schoolsystem';

   $conn = mysqli_connect($server_name, $user_name, $password, $db);
    // check connection
    if(!$conn){
        die("ERROR: Could not connect. " . mysqli_connect_error());
    }

    $expiration = time() + (60 * 60);

    // Store the expiration time in the session
    $_SESSION['expiration'] = $expiration;
    // Check if the expiration time has passed
    if (isset($_SESSION['expiration']) && time() > $_SESSION['expiration']) {
        // Session has expired, destroy it
        session_unset();
        session_destroy();
    }
    // Update the expiration time to 30 minutes from now
    $expiration = time() + (60 * 60);

    // Store the updated expiration time in the session
    $_SESSION['expiration'] = $expiration;
    ?>