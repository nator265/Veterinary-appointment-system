<?php
session_start();
include('../connect.php');
include('../functions.php');

if(!isset($_SESSION['name'])){
    header('location:../login.php');
}

?>

<DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="namecards.css">
    <title>Notifications</title>
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
                <div class="greetings-container">
                    <span class="greetings" id="greetings"></span>
                    <?php 
                        // this is to call the name of the user with the session variable
                       
                        echo ucwords($_SESSION['name']) . '.';
                    ?> 
                </div>
    
                <div class="main-dashboard-container" id="main-dashboard-container">
                    <div class="dashboard"> 
                        <a href="notifications-sent.php" class="appointments-container" id="link2">
                            <div class="appointments">
                                <div class="count-container">
                                    <div class="count-info">
                                        Sent
                                    </div>
                                    <div class="count">
                                        <div class="fig">
                                            <?php
                                                $count1 = "SELECT * FROM notifications Where phone = '" .$_SESSION['phone']."'";
                                                $countlink = mysqli_query($conn, $count1);
                                                $count = mysqli_num_rows($countlink);
                                                echo $count;
                                            ?>
                                        </div>
                                        <div class="recimage">
                                            <img src="images/sent.png" alt="sent" height="130px" id="image1">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <div class="notifications-container" id="link2">
                            <a href="notifications-recieved.php">
                                <div class="notifications">
                                    <div class="count-container">
                                        <div class="count-info">
                                            Recieved
                                        </div>
                                        <!-- this is the number badge for the counter -->
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
                                                <img src="images/received.png" alt="received" height="140px" id="image3" style="padding-top:10px">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="notifications-container" id="link2">
                            <a href="receipts.php">
                                <div class="notifications">
                                    <div class="count-container">
                                        <div class="count-info">
                                            Receipts
                                        </div>
                                        <!-- this is the number badge for the counter -->
                                        <div class="count">
                                            <div class="fig">
                                                <?php
                                                    $count1 = "SELECT * FROM appointments Where bill_status = 'paid' AND phone = '" .$_SESSION['phone']."'";
                                                    $countlink = mysqli_query($conn, $count1);
                                                    $count = mysqli_num_rows($countlink);
                                                    echo $count;
                                                ?>
                                            </div>
                                            <div class="recimage">
                                                <img src="images/received.png" alt="received" height="140px" id="image3" style="padding-top:10px">
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

            // animate the dashboard
            $(function(){
                $(".dashboard").animate({opacity:'1', transform: 'translate("0px, 0px")'}, 1500, 'swing');
                $('.dashboard').css({"animation":"my-animation 2s forwards"});
            });
    
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
</body>
</html>