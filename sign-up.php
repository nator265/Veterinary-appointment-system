<?php
session_start();
include 'connect.php';

if(isset($_POST['signup'])){
    
    $fullname = $_POST['fullname'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $s = "select * from users where phone = '$phone'";
    $result = mysqli_query($conn, $s);
    $num = mysqli_num_rows($result);

    if($num == 1){

        ?>
        <script>
            swal({
                title: "error",
                text: "The Details Entered Already Exist, Please Log In",
                icon: "error",
            });
        </script>
        <?php
    }
    else{
        ?>
        <script>
            swal.fire({
                title: "success",
                text: "registration successful",
                icon: "success",
            });
        </script>
        <?php
        
        $reg = "insert into users(fullname, address, phone, password) values ('$fullname', '$address', '$phone', '$password')";
        mysqli_query($conn, $reg);
        $_SESSION['name'] = $fullname;
        $_SESSION['phone'] = $phone;
        header('location: dashboard/index.php');
    }
}
?>
<!-- html code -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="sweetalert2.all.min.js"></script>
    <title>Sign Up</title>
    <link rel="stylesheet" href="sign-up.css">
</head>
<body>
    <h1>
        <span id="left-h1">Appointment Booking</span>
    </h1>

    <div class="form-container">
        <div class="form">
            <fieldset class="fieldset">
                <legend class="legend">
                    <h2> Welcome</h2> 
                </legend>
                <form method="POST" action="sign-up.php">
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
                            <span style="color: white; font-size: large;">or</span>
                        </legend>
                    </fieldset>
                </div>
                <div class="btn">
                    <a href="login.php"><button id="bttn">Log In</button></a>
                </div>
            </fieldset>
        </div>
    </div>
</body>
</html>