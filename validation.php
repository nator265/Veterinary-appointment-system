<?php
   session_start(); 

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
    ?>