<?php
session_start();
include('../../../connect.php');
include('../../../functions.php');

if(!isset($_SESSION['name'])){
    header('location:../../../login.php');
}

?>

<DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="calendar.css">
    <script language="javascript" type="text/javascript" >
</script>
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
                    <span id='link'> Dashboard <img src="images/dashboard.png" alt="" height="20px">
                </div>
                <div class="link">
                    <a href="profiles.php"><span id='link'> Profiles <img src="images/user-small.png" alt="" height="20px"></span></a>
                </div>
                <div class="link">
                    <a href="appointments.php"><span id='link'> Appointments <img src="images/appointments.png" alt="" height="20px"></span></a>
                </div>
                <div class="link">
                    <a href="settings.php"><span id='link'> Settings <img src="images/settings.png" alt="" height="20px"></span> </a>
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
            <div class="greetings-container" style="padding-right: 20px;">
                <span class="greetings" id="greetings"></span>
                <?php 
                    // this is to call the name of the user with the session variable
                   
                    echo ucwords($_SESSION['name']) . '.';
                ?> 
            </div>

            <!-- 1.Dashboard -->
            <div class="main-dashboard-container" id="main-dashboard-container">
                <div class="dashboard" id="dashboard"> 
                    <a href="appointments.php" class="appointments-container" id="link2">
                        <div class="appointments">
                            <div class="count-container">
                                <div class="count-info">
                                    Appointments
                                </div>
                                <div class="count">
                                    <div class="fig">
                                        <?php
                                            $appointments = "SELECT * from appointments";
                                            $link_appointments = mysqli_query($conn, $appointments);
                                            $appointments_num = mysqli_num_rows($link_appointments);
                                            echo $appointments_num;
                                        ?>
                                    </div>
                                   <div class="recimage">
                                    <img src="images/appoint.png" alt="Appointments" height="150px" style='padding-top:10px;' id="image1">
                                   </div>
                                </div>
                            </div>
                        </div>
                    </a>
                    
                    <div class="notifications-container" id="link2">
                        <a href="profiles.php">
                            <div class="notifications">
                                <div class="count-container">
                                    <div class="count-info">
                                        Profiles
                                    </div>
                                <!-- thi is the number badge for the counter -->
                                <div class="count">
                                   <img src="images/user-icon.png" alt="profiles" height="150px" style="padding-top:20px" id="image2">
                                </div>
                            </div>
                            </div>
                        </a>
                    </div>
                    <div class="notifications-container" id="link2">
                        <a href="settings.php">
                            <div class="notifications">
                                <div class="count-container">
                                    <div class="count-info">
                                        Settings
                                    </div>
                                <!-- thi is the number badge for the counter -->
                                <div class="count">
                                    <img src="images/settings.png" height="120px" width="100px" alt="Settings" style="padding-top: 20px;" id="image3">
                                </div>
                            </div>
                            </div>
                        </a>
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

        $(function(){
            $("#dashboard").animate({opacity:'1', transform: 'translate("0px, 0px")'}, 1500, 'swing');
            $('#dashboard').css({"animation":"my-animation 2s forwards"});
        });
        
    </script>
</body>
</html>