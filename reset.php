<?php
    session_start();
    include 'connect.php';

    if(isset($_POST['reset'])){ 
        $email = $_POST['email'];
        // to make sure that the input fields are not empty
        if(empty($email)){
            header('location: login-error2.php');
        }
        // authenticating the user input to authorize login
        $s = "select * from allusers where email = '$email'";
        $result = mysqli_query($conn, $s);
        if(mysqli_num_rows($result) > 0){
             // getting the required rows from the database
            $num = mysqli_num_rows($result);
            if($num > 0){
                
                header('Location: reset_password.php?reset=' . urlencode($email));
exit();
            }       
       }else{header('location: login-incorrect2.php');}
       
        // else{header('location: login-incorrect.php');}
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.4.24/sweetalert2.all.js"></script>
    <script src="sweetalert2.all.min.js"></script>
    <title>Vet Appointment Booking</title>
    <link rel="stylesheet" href="reset.css">
</head>
<body>
    <div class="body">
    <div class="form-container">
        <div class="form" style="display:block">
            <div style="text-align:center">
                <img src="images/gsj logo.png" alt="logo" height="200px" width="200px">
            </div>
            <fieldset class="fieldset">
                <legend class="legend" style="text-align: center">
                    <h2>Password Reset</h2> 
                </legend>
                <form method="POST" action="reset_password.php" name="form" onsubmit="return validated()" style="text-align:center;">
                    <input type="email" name="email" id="phone" placeholder="Enter Your Email" style="font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif; font-weight: 100;">
                    <br>
                    <div class="btn" style="margin-top: 10px;">
                    <input type="submit" name="reset" value="Send reset code" id="bttn">
                    </div>
                    
                </form>   
                <div class="btn" style="margin-top: 20px;">
                    <a href="login.php"><button id="bttn">Cancel</button></a>
                </div>            
            </fieldset>
            
        </div>
    </div>
    </div>
    <!-- <script src="valid.js"></script> -->
</body>

</html>