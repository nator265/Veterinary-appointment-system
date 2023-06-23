<?php
    session_start();
    include 'connect.php';
    header( "refresh:1.5; url = sign-up.php" ); 

    if(isset($_POST['login'])){ 
        $phone = $_POST['phone'];
        $password = $_POST['password'];       
        // to make sure that the input fields are not empty
        if(empty($phone) or empty($password)){
            header('location: login-error.php');
        }else{

        
        // authenticating the user input to authorize login
        $s = "select * from users where phone = '$phone' && password = '$password'";
        $d = "select * from doctors where phone = '$phone' && password = '$password'";
        $c = "select * from admin where phone = '$phone' && password = '$password'";
        $e = "select * from accountant where phone = '$phone' && password = '$password'";
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
        // getting the username for the users

        
    
        if($num > 0){
            $query2 = "select * from users where phone = '$phone' && password = '$password'";
            $link1 = mysqli_query($conn, $query2);
            $fetchname = mysqli_fetch_assoc($link1);
            $fullname = $fetchname['fullname'];
            $_SESSION['name'] = $fullname;
            $_SESSION['phone'] = $phone;
            header('location: dashboard/index.php');
            
        }elseif ($num2 > 0) {
            // Loging in
            $query2 = "select * from doctors where phone = '$phone' && password = '$password'";
            $link1 = mysqli_query($conn, $query2);
            $fetchname = mysqli_fetch_assoc($link1);
            $fullname = $fetchname['fullname'];
            $_SESSION['name'] = $fullname;
            $_SESSION['phone'] = $phone;

             // getting the doctors field of work
             $field = "SELECT field from doctors where phone = '".$_SESSION['phone']."'";
             $fieldlink = mysqli_query($conn, $field);
             $field2 = mysqli_fetch_assoc($fieldlink);                               
             $_SESSION['field3'] =  $field2["field"];
             
            header('location: dashboard/admin/dashboard.php');

        }elseif ($num3 > 0) {
            $query2 = "select * from admin where phone = '$phone' && password = '$password'";
            $link1 = mysqli_query($conn, $query2);
            $fetchname = mysqli_fetch_assoc($link1);
            $fullname = $fetchname['fullname'];
            $_SESSION['name'] = $fullname;
            $_SESSION['phone'] = $phone;
            header('location: dashboard/admin/superuser/dashboard.php');
       
        }
        elseif ($num4 > 0) {
            $query2 = "select * from accountant where phone = '$phone' && password = '$password'";
            $link1 = mysqli_query($conn, $query2);
            $fetchname = mysqli_fetch_assoc($link1);
            $fullname = $fetchname['fullname'];
            $_SESSION['name'] = $fullname;
            $_SESSION['phone'] = $phone;
            header('location: dashboard/accountant/dashboard.php');
       
        }
        else{header('location: login-incorrect.php');}
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
    <link rel="stylesheet" href="sign-up.css">
</head>
<body>
    <div class="form-container">
        <div class="form" style="display:block">
           <div style="text-align:center">
                <img src="images/gsj logo.png" alt="logo" height="200px" width="200px">
            </div>
            <fieldset class="fieldset">
                <legend class="legend" style="text-align: center">
                    <h2>GSJ Animal Health & Production Appointment System</h2> 
                </legend>
                <form method="POST" action="sign-up.php" style="text-align:center;">
                    <input type="text" name="fullname" id="email" placeholder="Fullname" required style="font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif; font-weight: 100;">
                    <br>
                    <input type="text" name="address" id="address" placeholder="District e.g. Lilongwe" required style="font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif; font-weight: 100;">
                    <br>
                    <input type="text" name="phone" id="phone" placeholder="Phone Number" required style="font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif; font-weight: 100;">
                    <br>
                    <input type="password" name="password" id="password" placeholder="Password" required style="font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif; font-weight: 100;">
                    <br>
                    <div class="btn">
                        <input type="submit" name="signup" value="Sign Up" id="bttn">
                    </div>
                </form>
                <div class="fieldset-container"  style="text-align: center;">
                    <fieldset class="fieldset2">
                        <legend class="legend2">
                            <span style="color: white; font-size: large;">Already have an account?</span>
                        </legend>
                    </fieldset>
                </div>
                <div class="btn">
                    <a href="login.php"><button id="bttn">Log In</button></a>
                </div>
            </fieldset>
        </div>
    </div>
    <!-- <script src="valid.js"></script> -->
    <script>
        Swal.fire({
        title: 'Error!',
        text: 'Phone number entered already exists',
        icon: 'error',
        showCancelButton: false,
        showConfirmButton: false,
})
    </script>
</body>

</html>