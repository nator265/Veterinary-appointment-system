<?php
if(isset($_POST['submit'])){
    $_SESSION['selectedYear'] = $_POST['selectedYear'];
    header('location:check-appointments.php');
}

// Use the selectedYear value as needed

// Perform any necessary server-side operations or database queries
// Return a response if needed
?>
