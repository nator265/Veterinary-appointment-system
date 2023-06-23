<?php
session_start();
include('../connect.php');
include('../functions.php');

if(!isset($_SESSION['name'])){
    header('location:../login.php');
}

if(isset($_POST['edit'])){
    
    $fullname = $_POST['fullname'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    
    $s = "SELECT phone FROM doctors WHERE phone = '$phone'
    UNION
    SELECT phone FROM users WHERE phone = '$phone'
    UNION
    SELECT phone FROM admin WHERE phone = '$phone'
    UNION
    SELECT phone FROM accountant WHERE phone = '$phone'";
    $result = mysqli_query($conn, $s);
    $num = mysqli_num_rows($result);

    if (empty($fullname) || empty($address) || empty($phone) || empty($password)) {
        header('location: add-doctor-blank.php');
    }else{
        if($num == 1  and $phone != $_SESSION['phone']){
        
            header('location: add-doctor-error.php');
       
         }
         else{
            $reg = "UPDATE users
                SET fullname = '$fullname', address = '$address', phone = '$phone', password = '$password'
                WHERE phone = '".$_SESSION['phone']."'";
            $link = mysqli_query($conn, $reg);
            $update2 = "UPDATE allusers SET password='$password', fullname = '$fullname', phone = '$phone' where phone = '".$_SESSION['phone']."' ";
            mysqli_query($conn, $update2);
            // for appointments
            $update3 = "UPDATE appointments SET phone = '$phone' where phone = '".$_SESSION['phone']."' ";
            mysqli_query($conn, $update3);
            // for notifications sender
            $update4 = "UPDATE notifications SET phone = '$phone' where phone = '".$_SESSION['phone']."' ";
            mysqli_query($conn, $update4);
            $update4 = "UPDATE notifications SET sender = '$fullname' where phone = '".$_SESSION['phone']."' ";
            mysqli_query($conn, $update4);
            // for notifications reciever
            $update5 = "UPDATE notifications SET reciever = '$phone' where reciever = '".$_SESSION['phone']."' ";
            mysqli_query($conn, $update5);

            $_SESSION['phone'] = $phone;
            $_SESSION['forlater'] = $fullname;
            header('location: add-doctor-success.php');
         }
    }   
}

?>

<DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="add-accountant.css">
    <title>Dashboard</title>
</head>
<body>
   
    <!-- these are columns -->
    <div class="flex-container">
        
    <!-- this is a shadow that make the first column come out -->
    <div class="shadow"></div>

        <div class="column1">
            <div class="company-name-container">
                <div class="company-name" style="font-size: x-large; font-weight:100">
                GSJ Animal Health & Production
                </div>
            </div>
            <div class="links-container">
                <div class="link">
                    <a href="index.php"><span class="link1"> Dashboard <img src="images/dashboard.png" alt="" height="20px"></a>
                </div>
                <div class="link">
                    <a href="appointments.php"><span id='link'> Appointments <img src="images/appointments.png" alt="" height="20px"></span></a>
                </div>
                <div class="link">
                    <a href="notifications.php"><span id='link'> Notifications <img src="images/notifications.png" alt="" height="20px"></span> </a>
                </div>
                <div class="link">
                    <a href="settings.php"><span id='link'> Settings <img src="images/settings.png" alt="" height="20px"></span> </a>
                </div>
                <div class="logout">
                    <a href="logout.php" style="text-decoration: none; color: white">
                        <button id="bttn">Logout</button>
                    </a>
                </div>
            </div>
        </div>

        <!-- this is the second column -->
        <div class="column2">
            <div class="greetings-container" style="padding-right: 20px">
               <a href="settings.php" style="text-decoration:underline"> <-- Previous Page </a>
            </div>
            <!-- the form that will allow the admin to add a doctor -->
            <div class="main-dashboard-container" id="main-dashboard-container">
                <div class="header">
                   <div class="pagetitle">  EDIT MY PROFILE.</div>
                </div>
                <div class="anothercontainer">
                    <div class="form-container">
                        <div class="form">
                            <form action="edit-profile.php" method="POST">
                                <?php
                                $query = "SELECT fullname, address, phone, password FROM users WHERE phone = ?";
                                $stmt = mysqli_prepare($conn, $query);
                                mysqli_stmt_bind_param($stmt, "s", $_SESSION['phone']);
                                mysqli_stmt_execute($stmt);
                                mysqli_stmt_bind_result($stmt, $fullname, $address, $phone, $password);
                                mysqli_stmt_fetch($stmt);
                                mysqli_stmt_close($stmt);
                                ?>
                                <input type="text" name="fullname" id="input" value="<?php echo htmlspecialchars($fullname); ?>">
                                <input type="text" name="address" id="input" value="<?php echo htmlspecialchars($address); ?>">
                                <input type="text" name="phone" id="input" value="<?php echo htmlspecialchars($phone); ?>" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                <input type="password" name="password" id="input" value="<?php echo htmlspecialchars(str_replace('*', '', $password)); ?>">
                                <input type="submit" value="Edit" name="edit" id="bttn" class="submit">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <script>

        //  greeting the user on top of the dashboad page

        const greeting = document.getElementById('greetings');
        const hour = new Date().getHours();
        const welcomeTypes = ["Good Morning,", "Good Afternoon,", "Good Evening,", "Good Night,"];
        let welcomeText = "";

        if (hour < 12){
            welcomeText = welcomeTypes[0];
        }
        else if (hour < 17){
            welcomeText = welcomeTypes[1];    
        }
        else if (hour < 20){
            welcomeText = welcomeTypes[2];
        }
        else {
            welcomeText = welcomeTypes[3];
        }

        greeting.innerHTML = welcomeText;

        // this is to close the modal
        
    </script>
</body>
</html>