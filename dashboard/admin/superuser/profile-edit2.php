<?php
session_start();
include('../../../connect.php');
include('../../../functions.php');

if(!isset($_SESSION['name'])){
    header('location:../../../login.php');
}
if(isset($_POST['submit'])){
    
    $fullname = $_POST['fullname'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $field = $_POST['field'];
    $password = $_POST['password'];
    
    $s = "select * from doctors where phone = '$phone'";
    $result = mysqli_query($conn, $s);
    $num = mysqli_num_rows($result);

    if (empty($fullname) || empty($address) || empty($phone) || empty($field) || empty($password)) {
        header('location: add-doctor-blank.php');
    }else{
        if($num == 1){
        
            header('location: add-doctor-error.php');
       
         }
         else{
             $reg = "insert into doctors(fullname, address, phone, field, password) values ('$fullname', '$address', '$phone', '$field', '$password')";
             mysqli_query($conn, $reg);
         }
    }
    header('location: add-doctor-success.php');
}
if(isset($_GET['edit'])){
    $_SESSION['values3'] = $_GET['edit'];
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
                <div class="company-name" style="font-size:x-large">
                    GSJ Animal Health & Production
                </div>
            </div>
            <div class="links-container">
                <div class="link">
                     <a href="dashboard.php"><span id='link'> Dashboard </span> </a>
                </div>
                <div class="link">
                    <a href="profiles.php"><span id="link"> Profiles </span></a>
                </div>
                <div class="link">
                    <a href="appointments.php"><span id='link'> Appointments </span></a>
                </div>
                <div class="link">
                    <a href="settings.php"><span id='link'> Settings </span></a>
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
               <a href="my-profile2.php" style="text-decoration:underline"> <-- Previous Page </a>
            </div>
            <!-- the form that will allow the admin to add a doctor -->
            <div class="main-dashboard-container" id="main-dashboard-container">
                <div class="header">
                   <div class="pagetitle">  EDIT ADMIN.</div>
                </div>
                <div class="anothercontainer">
                    <div class="form-container">
                        <div class="form">
                            <form action="my-profile2.php" method="post">

                            <input type="text" name="fullname" id="input" value="<?php
                                $namevalue = "SELECT * from admin where phone = '".$_SESSION['values3']."'";
                                $namelink = mysqli_query($conn, $namevalue);
                                $fetchname = mysqli_fetch_assoc($namelink);
                                echo $fetchname['fullname']
                                ?>">

                            <input type="text" name="address" id="input"value="<?php
                                $addressvalue = "SELECT * from admin where phone = '".$_SESSION['values3']."'";
                                $addresslink = mysqli_query($conn, $addressvalue);
                                $fetchaddress = mysqli_fetch_assoc($addresslink);
                                echo $fetchaddress['address']
                                ?>">

                            <input type="text" name="phone" id="input" value="<?php
                                $phonevalue = "SELECT * from admin where phone = '".$_SESSION['values3']."'";
                                $phonelink = mysqli_query($conn, $phonevalue);
                                $fetchphone = mysqli_fetch_assoc($phonelink);
                                echo $fetchphone['phone']
                                ?>">    

                            <input type="passoword" name="password" id="input" value="<?php
                                $passwordvalue = "SELECT * from admin where phone = '".$_SESSION['values3']."'";
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
</body>
</html>