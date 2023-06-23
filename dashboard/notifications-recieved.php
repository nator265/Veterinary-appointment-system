<?php
session_start();
include('../connect.php');
include('../functions.php');

if(!isset($_SESSION['name'])){
    header('location:../login.php');
}

if(isset($_POST['submit'])){
    $message = $_POST['message'];
   
    $reg = "INSERT INTO notifications(sender, title, message1, phone, reciever) VALUES ('".$_SESSION['name']."', 'Replying to: ".$_SESSION['message-title']."', '$message', '".$_SESSION['phone']."', '".$_SESSION['phone2']."')";
                            
    $rest = mysqli_query($conn, $reg);
    
    checkSQL($conn, $rest);

    // delete the message after replying
    $delete = "DELETE FROM notifications where notifications_id = '".$_SESSION['notification_id']."'";
    $linkit = mysqli_query($conn, $delete);
    checkSQL($conn, $linkit);
    
    header('location: notifications-recieved.php');
}

if(isset($_GET['yes'])){
    $id = $_GET['yes'];
    $delete = "DELETE FROM notifications where notifications_id = $id ";
    mysqli_query($conn, $delete);
    header('location:notifications-recieved.php');
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
    <link rel="stylesheet" href="notification-style.css">
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

            <!-- this is the modal for the reply console -->
            <div class="modal-container" id="modal-container">
                <div class="modal">
                    <div class="close" onclick="document.getElementById('modal-container').style.display='none'">&times;</div>
                    <div class="form-container">
                        <div class="form-header">
                            <h1 style="text-align: center; color: white; font-weight:100; font-size:xx-large">
                                <?php
                                    echo "Replying to"," ", $_SESSION['reply-to'];
                                ?>
                            </h1>
                            <div class="messagecontainer">
                                <div class="messagebody">
                                    <?php
                                        echo $_SESSION['message-to-reply']
                                    ?>
                                </div>
                            </div>
                        </div>
                        <form action="notifications-recieved.php" method="POST">
                            <textarea type="text" name="message" id="fullname" placeholder="Type message here..." cols="67" rows="4" style="padding:10px" required></textarea>
                            <br>
                            <br>
                            <div class="bttn-container">
                                <input type="submit" value="Send" name="submit"  id="btn" style="font-size:large;">    
                            </div>
                        </form>
                    </div>
                </div>
            </div>
    
            <!-- this is the second column -->
            <div class="column2">
                <div class="greetings-container">
                    <a href="notifications.php" style="padding-right:20px; text-decoration:underline"><-- Previous page</a>
                </div>
                <div class="create">
                    <a href="notifications-sent.php">
                        <button class="create" id="bttn">
                            View Sent Messages 
                        </button>
                    </a>
                </div>
    
                <div class="main-appointments-container" id="main-appointments-container">

                <div class="table-container"> 
                    <div class="table">
                        <table>
                            <tr class="first-row">
                                <th style="padding-left:10px">
                                    From
                                </th>
                                <th>
                                    Title
                                </th>
                                <th>
                                    Time
                                </th>
                                <th colspan="2" style="z-index:2">
                                    Actions
                                </th>
                            </tr>
                            <?php
                                
                                // retrieve data for the user matching the phone number
                                // retrieving data from the database for the user to see
                                $retrieved = "SELECT * FROM notifications Where reciever = '" .$_SESSION['phone']."' ORDER BY time DESC";
                                $link = mysqli_query($conn, $retrieved);
                                checkSQL($conn, $link);
                                $row = mysqli_num_rows($link);
                                if (!$link){
                                    die("Invalid query: " .$conn->error);
                                }                  

                                // reading data contained in each row
                                while($row = $link->fetch_assoc()){
                                    ?>
                                    <tr>
                                    <td><?php echo $row["sender"] ?></td>
                                    <td><?php echo $row["title"] ?></td>
                                    <td><?php echo time_elapsed_string($row["time"]) ?></td>
                                    <td><a href="notifications-recieved-reply.php?reply=<?php echo $row['notifications_id'] ?>" class="edit">View</a></td>
                                    <td><a href="notifications-recieved-delete?delete=<?php echo $row['notifications_id']?>#" class="cancel" id="clearance">Delete</a></td>
                                    </tr>
                                    <?php } ?>                                    
                        </table>
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

             // the alert for the delete function


            $(function(){
                $(".create").css({"animation":"second-animation 1s forwards"});
                $(".table").css({"animation":"third-animation 1s forwards"});
            })
            
        </script>
    </body>
    </html>
</body>
</html>