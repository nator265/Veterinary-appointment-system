<?php
    session_start();
    include 'connect.php';

    if(isset($_POST['login'])){ 
        $fullname = $_POST['fullname'];
        $phone = $_POST['phone'];
        $password = $_POST['password'];       
        // to make sure that the input fields are not empty
    
        // authenticating the user input to authorize login
         // authenticating the user input to authorize login
         $s = "select * from users where fullname = '$fullname' && phone = '$phone' && password = '$password'";
         $d = "select * from doctors where fullname = '$fullname' && phone = '$phone' && password = '$password'";
         $c = "select * from admin where fullname = '$fullname' && phone = '$phone' && password = '$password'";
         $e = "select * from accountant where fullname = '$fullname' && phone = '$phone' && password = '$password'";
         // connecting the queery with the database
         $result = mysqli_query($conn, $s);
         $result2 = mysqli_query($conn, $d);
         $result3 = mysqli_query($conn, $c);
         $result4 = mysqli_query($conn, $e);
         // getting the required rows from the database
         $num = mysqli_num_rows($result);
         $num2 = mysqli_num_rows($result2);
         $num3 = mysqli_num_rows($result3);
         $num4 = mysqli_num_rows($result4);
         
     
         if($num > 0){
         
             $_SESSION['name'] = $fullname;
             $_SESSION['phone'] = $phone;
             header('location: dashboard/index.php');
             
         }elseif ($num2 > 0) {
             // Loging in
             $_SESSION['name'] = $fullname;
             $_SESSION['phone'] = $phone;
 
              // getting the doctors field of work
              $field = "SELECT field from doctors where phone = '".$_SESSION['phone']."'";
              $fieldlink = mysqli_query($conn, $field);
              $field2 = mysqli_fetch_assoc($fieldlink);                               
              $_SESSION['field3'] =  $field2["field"];
              
             header('location: dashboard/admin/dashboard.php');
 
         }elseif ($num3 > 0) {
             
                 $_SESSION['name'] = $fullname;
                 $_SESSION['phone'] = $phone;
                 header('location: dashboard/admin/superuser/dashboard.php');
        
         }
         elseif ($num4 > 0) {
             
                 $_SESSION['name'] = $fullname;
                 $_SESSION['phone'] = $phone;
                 header('location: dashboard/accountant/dashboard.php');
        
         }else{
        }
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
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="body">
    <h1>
        <span id="left-h1">Appointment Booking</span>
    </h1>

    <div class="form-container">
        <div class="form">
            <fieldset class="fieldset">
                <legend class="legend">
                    <h2>We are ready to help</h2> 
                </legend>
                <form method="POST" action="login-incorrect.php" name="form" onsubmit="return validated()">
                    <input type="text" name="fullname" id="email" placeholder="Fullname" style="font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif; font-weight: 100;"
                        onclick="document.getElementById('email').style.border = 'none'">
                    <br>
                    <input type="text" name="phone" id="phone" placeholder="Phone" style="font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif; font-weight: 100;"
                        onclick="document.getElementById('phone').style.border = 'none'">
                    <br>
                    <input type="password" name="password" id="password" placeholder="Password" style="font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif; font-weight: 100;"
                        onclick="document.getElementById('password').style.border = 'none'">
                    <br>
                    <div class="btn" style="margin-top: 10px;">
                    <input type="submit" name="login" value="Log In" id="bttn">
                    </div>
                </form>
                
                <div class="fieldset-container" style="text-align: center;">
                    <fieldset class="fieldset2">
                        <legend class="legend2">
                            <span style="color: white; font-size: large;">or</span>
                        </legend>
                    </fieldset>
                </div>
                <div class="btn">
                    <a href="sign-up.php"><button id="bttn">Sign Up</button></a>
                </div>
            </fieldset>
            
        </div>
    </div>
    </div>
    <!-- <script src="valid.js"></script> -->
    <script>
        Swal.fire({
        title: 'Error!',
        text: 'Please Fill In All Fields',
        icon: 'error',
        confirmButtonText: 'Okay',
})
    </script>
</body>

</html>