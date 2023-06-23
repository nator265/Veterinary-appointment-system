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
    <link rel="stylesheet" href="add-profile.css">
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
                    <a href="profiles.php"><span id='link'> Profiles <img src="images/user-small.png" alt="" height="20px"></span></a>
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
            <div class="greetings-container">
                <span class="greetings" id="greetings"></span>
                <?php 
                    // this is to call the name of the user with the session variable
                   
                    echo ucwords($_SESSION['name']) . '.';
                ?> 
            </div>

            <!-- 1.Dashboard -->
            <div class="main-dashboard-container" id="main-dashboard-container">
                <div class="dashboard" id="dashboard" style="padding-left: 80px;"> 
                    <a href="doctors.php" class="appointments-container" id="link2">
                        <div class="appointments" onclick="document.getElementById('add-modal').style.display='block'">
                            <div class="count-container">
                                <div class="count-info">
                                    Doctors
                                </div>
                                <div class="count">
                                    <div class="fig">
                                        <?php
                                            $count1 = "SELECT * FROM doctors";
                                            $countlink = mysqli_query($conn, $count1);
                                            $count = mysqli_num_rows($countlink);
                                            echo $count;
                                        ?>
                                    </div>
                                    <div class="recimage">
                                    <img src="images/doctor.png" alt="edit doctor" height="150px" style='padding-top:10px;' id="image1">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                    <div class="notifications-container" id="link2">
                        <a href="accountants.php">
                            <div class="notifications">
                                <div class="count-container">
                                    <div class="count-info">
                                        Accountants
                                    </div>
                                <!-- thi is the number badge for the counter -->
                                <div class="count">
                                    <div class="fig">
                                        <?php
                                            $count1 = "SELECT * FROM accountant";
                                            $countlink = mysqli_query($conn, $count1);
                                            $count = mysqli_num_rows($countlink);
                                            echo $count;
                                        ?>
                                    </div>
                                    <div class="recimage">
                                        <img src="images/accountant.png" alt="edit accountant" height="150px" style='padding-top:10px;' id="image2">
                                    </div>
                                </div>
                            </div>
                            </div>
                        </a>
                    </div>
                    <div class="notifications-container" id="link2">
                        <a href="my-profile.php">
                            <div class="notifications">
                                <div class="count-container">
                                    <div class="count-info">
                                        My Profile
                                    </div>
                                <!-- thi is the number badge for the counter -->
                                <div class="count">
                                    <div class="fig">
                                        <?php
                                            $count1 = "SELECT * FROM admin";
                                            $countlink = mysqli_query($conn, $count1);
                                            $count = mysqli_num_rows($countlink);
                                            echo $count;
                                        ?>
                                    </div>
                                    <div class="recimage">
                                        <img src="images/admin.png" alt="edit my profile" height="150px" style='padding-top:10px;' id="image3">
                                    </div>
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

        // to add an animation effect
        $(function(){
            $("#dashboard").animate({opacity:'1', transform: 'translate("0px, 0px")'}, 1500, 'swing');
            $('#dashboard').css({"animation":"my-animation 2s forwards"});
        });
    </script>
</body>
</html>