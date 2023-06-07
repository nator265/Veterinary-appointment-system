<?php
    session_start();
    include 'connect.php';

    if(isset($_POST['reset'])){ 
        $phone = $_POST['phone'];
        $password = $_POST['password']; 
        $id = $_POST['id'];
        // to make sure that the input fields are not empty
        if(empty($phone) or empty($password) or empty($id)){
            header('location: login-error2.php');
        }
        // authenticating the user input to authorize login
        $s = "select * from allusers where phone = '$phone' && userid = '$id'";
        $result = mysqli_query($conn, $s);
        if(mysqli_num_rows($result) > 0){
             // getting the required rows from the database
            $num = mysqli_num_rows($result);
            if($num > 0){
                $query2 = "UPDATE allusers
                            SET password='$password' where phone = '$phone' and userid = '$id'";
                $link1 = mysqli_query($conn, $query2);
                // for the user
                $query2 = "UPDATE users 
                            SET password='$password' where phone = '$phone'";
                $link1 = mysqli_query($conn, $query2);
                // for the doctor
                $query2 = "UPDATE doctors
                            SET password='$password' where phone = '$phone'";
                 $link1 = mysqli_query($conn, $query2);
                //  for the accountant
                $query2 = "UPDATE accountant
                            SET password='$password' where phone = '$phone'";
                 $link1 = mysqli_query($conn, $query2);
                
                header('location: login.php');
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
                <form method="POST" action="reset.php" name="form" onsubmit="return validated()" style="text-align:center;">
                    <input type="text" name="id" id="phone" placeholder="ID" style="font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif; font-weight: 100;"
                        onclick="document.getElementById('phone').style.border = 'none'">
                    <br>
                    <input type="text" name="phone" id="phone" placeholder="Phone" style="font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif; font-weight: 100;"
                        onclick="document.getElementById('phone').style.border = 'none'">
                    <br>
                    <input type="password" name="password" id="password" placeholder="Password" style="font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif; font-weight: 100;"
                        onclick="document.getElementById('password').style.border = 'none'">
                    <br>
                    <div class="btn" style="margin-top: 10px;">
                    <input type="submit" name="reset" value="Reset" id="bttn">
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