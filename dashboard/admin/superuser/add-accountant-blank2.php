<?php
session_start();
include('../../../connect.php');
include('../../../functions.php');

if(!isset($_SESSION['name'])){
    header('location:../../../login.php');
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
        header('location: add-accountant-blank2.php');
    }else{
        if($num == 1 and $phone != $_SESSION['values2']){
        
            header('location: add-accountant-error2.php');
       
         }
         else{
            $update = "UPDATE accountant SET address = '$address', password='$password', fullname = '$fullname', phone = '$phone' where phone = '".$_SESSION['values2']."' ";
            mysqli_query($conn, $update);
            header('location: add-accountant-success2.php'); 
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.4.24/sweetalert2.all.js"></script>
    <script src="sweetalert2.all.min.js"></script>
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
                <div class="company-name" style="font-size:x-large">
                    GSJ Animal Health & Production
                </div>
            </div>
            <div class="links-container">
                <div class="link">
                     <a href="dashboard.php"><span id='link'> Dashboard <img src="images/dashboard.png" alt="" height="20px"></span> </a>
                </div>
                <div class="link">
                    <a href="profiles.php"><span id="link"> Profiles <img src="images/user-small.png" alt="" height="20px"></span></a>
                </div>
                <div class="link">
                    <a href="appointments.php"><span id='link'> Appointments <img src="images/appointments.png" alt="" height="20px"></span></a>
                </div>
                <div class="link">
                    <a href="settings.php"><span id='link'> Settings <img src="images/settings.png" alt="" height="20px"></span></a>
                </div>
                <div class="link">
                    <a href="../../logout.php" style="text-decoration: none; color: white">
                        <button class="logout" id="bttn">Logout</button>
                    </a>
                </div>
            </div>
        </div>

        <!-- this is the second column -->
        <div class="column2">
            <div class="greetings-container" style="padding-right: 20px">
               <a href="edit-accountant.php" style="text-decoration:underline"> <-- Previous Page </a>
            </div>
            <!-- the form that will allow the admin to add a doctor -->
            <div class="main-dashboard-container" id="main-dashboard-container">
                <div class="header">
                   <div class="pagetitle">  EDIT ACCOUNTANT.</div>
                </div>
                <div class="anothercontainer">
                    <div class="form-container">
                        <div class="form">
                            <form action="edit-accountant2.php" method="post">

                            <input type="text" name="fullname" id="input" value="<?php
                                $namevalue = "SELECT * from accountant where phone = '".$_SESSION['values2']."'";
                                $namelink = mysqli_query($conn, $namevalue);
                                $fetchname = mysqli_fetch_assoc($namelink);
                                echo $fetchname['fullname']
                                ?>">

                            <input type="text" name="address" id="input"value="<?php
                                $addressvalue = "SELECT * from accountant where phone = '".$_SESSION['values2']."'";
                                $addresslink = mysqli_query($conn, $addressvalue);
                                $fetchaddress = mysqli_fetch_assoc($addresslink);
                                echo $fetchaddress['address']
                                ?>">

                            <input type="text" name="phone" id="input" value="<?php
                                $phonevalue = "SELECT * from accountant where phone = '".$_SESSION['values2']."'";
                                $phonelink = mysqli_query($conn, $phonevalue);
                                $fetchphone = mysqli_fetch_assoc($phonelink);
                                echo $fetchphone['phone']
                                ?>">    

                            <input type="passoword" name="password" id="input" value="<?php
                                $passwordvalue = "SELECT * from accountant where phone = '".$_SESSION['values2']."'";
                                $passwordlink = mysqli_query($conn, $passwordvalue);
                                $fetchpassword = mysqli_fetch_assoc($passwordlink);
                                echo  str_replace('*', '', $fetchpassword['password']);
                                ?>">
                            <input type="submit" value="Edit Accountant" name="edit" id="bttn" class="submit">
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
     <script>
        Swal.fire({
        title: 'Error!',
        text: 'Input fields can not be blank',
        icon: 'error',
        confirmButtonText: 'Okay'
})
    </script>
</body>
</html>