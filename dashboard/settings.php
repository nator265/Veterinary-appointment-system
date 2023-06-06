<?php
session_start();
include('../connect.php');
include('../functions.php');

if(!isset($_SESSION['name'])){
    header('location:../login.php');
}

if(isset($_POST['submit'])){
    $fullname = $_POST['fullname'];
    $field = $_POST['field'];
    $date = $_POST['ap_date'];
    $animal = $_POST['animal'];
    $ap_type = $_POST['ap_type'];
    $_SESSION['field2'] = $field;
    // converting the ap_type to string
    $allaptype = implode(", ", $ap_type);

    // inserting data into the appointments table in the database
    $reg = "INSERT INTO appointments(fullname, field, animal, ap_date, ap_type, phone) VALUES ('$fullname', '$field', '$animal', '$date', '$allaptype', '".$_SESSION['phone']."')";
                            
    $rest = mysqli_query($conn, $reg);
    
    checkSQL($conn, $rest);
}

if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    $delete = "DELETE FROM appointments where ap_id = $id ";
    mysqli_query($conn, $delete);
    header('location:user.php');
}
?>

<DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style.css">
    <title>Dashboard</title>
</head>
<body>
   
    <div class="body">
        <div class="body-container">
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
                        <a href="index.php"><span class="link1"> Dashboard <img src="images/dashboard.png" alt="" height="20px"> </a>
                    </div>
                    <div class="link">
                        <a href="appointments.php"><span id='link'> Appointments <img src="images/appointments.png" alt="" height="20px"></span></a>
                    </div>
                    <div class="link">
                        <a href="notifications.php"><span id='link'> Notifications <img src="images/notifications.png" alt="" height="20px"></span> </a>
                    </div>
                    <div class="link">
                        <span id='link'> Settings <img src="images/settings.png" alt="" height="20px"></span>
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
    
                <!-- 1.Dashboard -->
                <div class="main-dashboard-container" id="main-dashboard-container">
                    <div class="dashboard" id="dashboard"> 
                        <a href="edit-profile.php" class="appointments-container" id="link2">
                            <div class="appointments">
                                <div class="count-container">
                                    <div class="count-info">
                                       Edit Profile
                                    </div>
                                    <div class="count">
                                       <div class="recimage">
                                            <img src="images/admin.png" alt="appointments" height="150px" style="padding-top:10px;" id="image1">
                                       </div>
                                    </div>
                                </div>
                            </div>
                        </a>
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

        // animate the dashboard
        $(function(){
            $("#dashboard").animate({opacity:'1', transform: 'translate("0px, 0px")'}, 1500, 'swing');
            $('#dashboard').css({"animation":"my-animation 2s forwards"});
        });
        
    </script>
</body>
</html>