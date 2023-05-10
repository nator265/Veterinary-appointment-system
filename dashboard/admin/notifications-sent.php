<?php
session_start();
include('../../connect.php');
include('../../functions.php');

if(!isset($_SESSION['name'])){
    header('location:../../login.php');
}

if(isset($_GET['yes'])){
    $id = $_GET['yes'];
    $delete = "DELETE FROM notifications where notifications_id = $id ";
    mysqli_query($conn, $delete);
    header('location: notifications-sent.php');
}
date_default_timezone_set("Africa/Harare");
function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}
?>

<DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="notifications.css">
    <title>Notifications</title>
</head>
<body>
     <!-- these are columns -->
     <div class="flex-container">
        
        <!-- this is a shadow that make the first column come out -->
        <div class="shadow"></div>
    
            <div class="column1">
                <div class="company-name-container">
                    <div class="company-name">
                        Veterinary
                    </div>
                </div>
                <div class="links-container">
                    <div class="link">
                        <a href="dashboard.php"> <span id='link'> Dashboard </span> </a>
                    </div>
                    <div class="link">
                        <a href="appointments.php"><span id='link'> Appointments </span></a>
                    </div>
                    <div class="link">
                        <a href="check-appointments.php"><span id='link'> Check-off appointment </span> </a>
                    </div>
                    <div class="link">
                       <a href="notifications.php"> <span id='link'> Notifications </span> </a> 
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
                <div class="create">
                    <a href="notifications-recieved.php">
                        <button class="create" id="bttn"> View Recieved Messages </button>
                    </a>
                </div>
    
                <div class="main-appointments-container" id="main-appointments-container">
                <div class="table-container"> 
                    <div class="table">
                        <table>
                            <tr class="first-row">
                                <th style="padding-left: 20px;">
                                    To
                                </th>
                                <th>
                                    Title
                                </th>
                                <th>
                                    Time
                                </th>
                                <th style="z-index:1" colspan="2">
                                    Actions
                                </th>
                            </tr>
                            <?php
                                
                                // retrieve data for the user matching the phone number
                                // retrieving data from the database for the user to see
                                $retrieve = "SELECT * FROM notifications Where phone = '".$_SESSION['phone']."' ORDER BY time desc";
                                $link = mysqli_query($conn, $retrieve);
                                checkSQL($conn, $link);
                                $row = mysqli_num_rows($link);
                                if (!$link){
                                    die("Invalid query: " .$conn->error);
                                }                  

                                // reading data contained in each row
                                while($row = $link->fetch_assoc()){
                                    ?>
                                    <tr id="srollfortr">
                                    <td style="padding-left: 15px";><?php
                                    $phonethis = $row['reciever'];
                                    $querythis = "SELECT * FROM users where phone = $phonethis";
                                    $querylink = mysqli_query($conn, $querythis);
                                    checkSQL($conn, $querylink);
                                    $fetchthisnanme = mysqli_fetch_assoc($querylink);
                                    $thisname = $fetchthisnanme['fullname'];
                                    echo $thisname ?></td>
                                    <td><?php echo $row["title"] ?></td>
                                    <td><?php echo time_elapsed_string($row["time"]) ?></td>
                                    <td style="z-index:2">
                                        <a href="notifications-sent-reply.php?reply=<?php echo $row['notifications_id'] ?>">
                                        <button class="edit">
                                            View
                                        </button></a></td>
                                    <td style="z-index:2;">
                                        <a href="notifications-sent-delete?delete=<?php echo $row['notifications_id']?>#" class="cancel" id="clearance">Delete</a>
                                    </td>
                                    </tr>
                                    <?php } ?>
            

                                    
                        </table>
                    </div>
                </div> 
            </div>
        <script>
            
            $(function(){
                $(".create").css({"animation":"second-animation 1s forwards"});
                $(".table").css({"animation":"third-animation 1s forwards"});
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