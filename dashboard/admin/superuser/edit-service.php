<?php
session_start();
include('../../../connect.php');
include('../../../functions.php');

if(!isset($_SESSION['name'])){
    header('location:../../../login.php');
}
if(isset($_POST['edit'])){
    
    $servicename = $_POST['servicename'];
    $cost = $_POST['service_cost'];
    
    $s = "SELECT * FROM service_costs WHERE servicename = '$servicename' and service_cost = '$cost'";
    $result = mysqli_query($conn, $s);
    $num = mysqli_num_rows($result);

    if (empty($servicename) || empty($cost)) {
        header('location: add-service-blank.php');
    }else{
        if($num == 1 and $cost_id != $_SESSION['cost_id']){
        
            header('location: add-service-error.php');
       
         }
         else{
            $update = "UPDATE service_costs SET servicename = '$servicename', service_cost = '$cost' where cost_id = '".$_SESSION['cost_id']."' ";
            mysqli_query($conn, $update);
           
            header('location: add-service-success.php'); 
         }
    }
}
if(isset($_GET['edit'])){
    $_SESSION['cost_id'] = $_GET['edit'];
}
?>

<DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="add-accountant.css">
    <title>Edit Settings</title>
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
               <a href="edit-serrvice.php" style="text-decoration:underline"> <-- Previous Page </a>
            </div>
            <!-- the form that will allow the admin to add a doctor -->
            <div class="main-dashboard-container" id="main-dashboard-container">
                <div class="header">
                   <div class="pagetitle">EDIT SERVICES.</div>
                </div>
                <div class="anothercontainer">
                    <div class="form-container">
                        <div class="form">
                            <form action="edit-service.php" method="post">

                            <input type="text" name="servicename" id="input" value="<?php
                                $namevalue = "SELECT * from service_costs where cost_id = '".$_SESSION['cost_id']."'";
                                $namelink = mysqli_query($conn, $namevalue);
                                $fetchname = mysqli_fetch_assoc($namelink);
                                echo $fetchname['servicename']
                                ?>">

                            <input type="text" name="service_cost" id="input"value="<?php
                                $addressvalue = "SELECT * from service_costs where cost_id = '".$_SESSION['cost_id']."'";
                                $addresslink = mysqli_query($conn, $addressvalue);
                                $fetchaddress = mysqli_fetch_assoc($addresslink);
                                echo $fetchaddress['service_cost']
                                ?>">

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