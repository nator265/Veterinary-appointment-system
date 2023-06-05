<?php
session_start();
include('../../connect.php');
include('../../functions.php');

if(!isset($_SESSION['name'])){
    header('location:../../login.php');
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
                    <span id='link'> Dashboard <img src="images/dashboard.png" alt="" height="20px"></span>
                </div>
                <div class="link">
                    <a href="appointments.php"><span id='link'> Appointments <img src="images/appointments.png" alt="" height="20px"></a>
                </div>
                <div class="link">
                    <a href="check-appointments.php"><span id='link'> Checkoff Appointments <img src="images/total.png" alt="" height="20px"></span> </a>
                </div>
                <div class="link">
                    <a href="notifications.php"><span id='link'> Notifications <img src="images/notifications.png" alt="" height="20px"></span> </a>
                </div>
                <div class="link">
                    <a href="../logout.php" style="text-decoration: none; color: white">
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
                <div class="dashboard" id="dashboard"> 
                    <div class="top">
                        <a href="appointments.php" class="appointments-container" id="link2">
                            <div class="appointments">
                                <div class="count-container">
                                    <div class="count-info">
                                        Appointments
                                    </div>
                                    <div class="count">
                                        <div class="fig">
                                            <?php

                                                $field = "SELECT field from doctors where phone = '".$_SESSION['phone']."'";
                                                $fieldlink = mysqli_query($conn, $field);
                                                $field2 = mysqli_fetch_assoc($fieldlink);                               
                                                $_SESSION['field3'] =  $field2["field"];
                                                // showing the number of appointments that the doctor has
                                                $count1 = "SELECT * FROM appointments Where session_expiry = 'pending' and field = '".$_SESSION['field3']."'";
                                                $countlink = mysqli_query($conn, $count1);
                                                $count = mysqli_num_rows($countlink);
                                                echo $count;
        
                                            ?>
                                        </div>
                                        <div class="recimage">
                                            <img src="images/appoint.png" alt="appointments" height="150px" style="padding-top:10px" id="image1">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <div class="notifications-container" id="link2">
                            <a href="notifications.php">
                                <div class="notifications">
                                    <div class="count-container">
                                        <div class="count-info">
                                            Notifications
                                        </div>
                                    <!-- thi is the number badge for the counter -->
                                    <div class="count">
                                        <div class="fig">
                                            <?php
                                                $count1 = "SELECT * FROM notifications Where reciever = '" .$_SESSION['phone']."'";
                                                $countlink = mysqli_query($conn, $count1);
                                                $count = mysqli_num_rows($countlink);
                                                echo $count;
                                            ?>
                                        </div>
                                        <div class="recimage">
                                            <img src="images/sent.png" alt="Sent" height="135px" style="padding-top:10px" id="image3">
                                        </div>
                                    </div>
                                </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="bottom">
                        <div class="bottomcontainer">
                            <div class="textforbottom">
                                Your Next appointment is at 
                            </div>
                            <div class="nextapp">
                                <?php
                                    $field = "SELECT field from doctors where phone = '".$_SESSION['phone']."'";
                                    $fieldlink = mysqli_query($conn, $field);
                                    $field2 = mysqli_fetch_assoc($fieldlink);                               
                                    $_SESSION['field3'] =  $field2["field"];
                                    // showing the number of appointments that the doctor has
                                    $count1 = "SELECT * FROM appointments Where session_expiry = 'pending' and approved = 'approved' and field = '".$_SESSION['field3']."'";
                                    $countlink = mysqli_query($conn, $count1);
                                    if(mysqli_num_rows($countlink) == 0){
                                        echo 'N/A';
                                    }else{
                                        $fetchtime = mysqli_fetch_assoc($countlink);
                                        $thistime = $fetchtime['ap_time'];
                                        echo $thistime;
                                    }
                                ?>
                            </div>
                            <div class="nextdate">
                            <?php
                                    $field = "SELECT field from doctors where phone = '".$_SESSION['phone']."'";
                                    $fieldlink = mysqli_query($conn, $field);
                                    $field2 = mysqli_fetch_assoc($fieldlink);                               
                                    $_SESSION['field3'] =  $field2["field"];
                                    // showing the number of appointments that the doctor has
                                    $count1 = "SELECT * FROM appointments Where session_expiry = 'pending' and approved = 'approved' and field = '".$_SESSION['field3']."'";
                                    $countlink = mysqli_query($conn, $count1);
                                    if(mysqli_num_rows($countlink) == 0){
                                        echo 'N/A';
                                    }else{
                                        $fetchdate = mysqli_fetch_assoc($countlink);
                                        $thisdate = $fetchdate['ap_date'];
                                        echo $thisdate;
                                    }
                                ?>
                            </div>
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
        $(function(){
            $("#dashboard").animate({opacity:'1', transform: 'translate("0px, 0px")'}, 1500, 'swing');
            $('#dashboard').css({"animation":"my-animation 2s forwards"});
        });
        
    </script>
</body>
</html>