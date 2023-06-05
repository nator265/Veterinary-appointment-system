<?php
session_start();
include('../../connect.php');
include('../../functions.php');

if(!isset($_SESSION['name'])){
    header('location:../../login.php');
}

if(isset($_GET['yes'])){
    $_SESSION['ap_id'] = $_GET['yes'];
    $approvedPhone = $_SESSION['ap_id'];

     // sending the user a notification the appointment has been approved.
    $query="SELECT * from appointments where ap_id = '$approvedPhone'";
    $linkquery = mysqli_query($conn, $query);
    checkSQL($conn, $linkquery);
    $phoneassoc = mysqli_fetch_assoc($linkquery);
    $phone = $phoneassoc['phone']; 
    $message = 'We will reserve the date for you, thank you!';
    $reg = "INSERT INTO notifications(sender, title, message1, phone, reciever) VALUES ('".$_SESSION['name']."', 'Appointment has been approved', '$message', '".$_SESSION['phone']."', '$phone')";
    $rest = mysqli_query($conn, $reg);
    checkSQL($conn, $rest);

    // to remove the appoitnment from the pending tab to the rejected tab.
    $removeqry = "UPDATE appointments 
        SET approved = 'approved' 
        WHERE ap_id = $approvedPhone";
    $removelink = mysqli_query($conn, $removeqry);
    header('location:appointments.php');    
}

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
function sendNotification($userId, $message, $phone) {
    include('../../connect.php');
   

    // Escape the message to prevent SQL injection
    $escapedMessage = mysqli_real_escape_string($conn, $message);

    // Insert the notification into the notifications table
    $insertQuery = "INSERT INTO notifications (sender, message1, title, phone, reciever) VALUES ('GSV appointment sytem', '$escapedMessage', 'Appointment Expiry', '123', '$phone')";
    $result = mysqli_query($conn, $insertQuery);

    // Check if the insert was successful
    if (!$result) {
        // Handle database insert error
        // You can display an error message or log the error
        echo "Failed to insert notification: " . mysqli_error($conn);
        return;
    }

    // Close the database connection
    mysqli_close($conn);
}
$currentDate = date('Y-m-d'); // Get the current date

// Retrieve appointments with the appointment date in the past and not already expired
$query = "UPDATE appointments SET session_expiry = 'expired' WHERE ap_date < '$currentDate' AND session_expiry <> 'expired'";
$result = mysqli_query($conn, $query);

if ($result) {
    $numExpiredAppointments = mysqli_affected_rows($conn);
   
    // Retrieve the details of the expired appointments
    $expiredQuery = "SELECT fullname, ap_id, phone FROM appointments WHERE ap_date < '$currentDate' AND session_expiry = 'expired'";
    $expiredResult = mysqli_query($conn, $expiredQuery);

    while ($row = mysqli_fetch_assoc($expiredResult)) {
        $fullname = $row['fullname'];
        $ap_id = $row['ap_id'];
        $phone = $row['phone'];

        // Send notification to the user
        sendNotification($fullname, "Your appointment has expired. Please reschedule.", $phone);

    }
} else {
    echo "Failed to update expired appointments: " . mysqli_error($conn);
}
?>

<DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="appointments.css">
    <title>Appointments</title>
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
                    <a href="dashboard.php"><span id='link'> Dashboard <img src="images/dashboard.png" alt="" height="20px"></span></a>
                </div>
                <div class="link">
                    <span id='link'> Appointments <img src="images/appointments.png" alt="" height="20px">
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

        <!-- thi is the modal for the appointments registration -->
        <div class="modal-container" id="modal-container">
            <div class="modal">
                <a href="appointments.php">
                    <div class="close" onclick="document.getElementById('modal-container').style.display='none'">&times;</div>
                </a>
                <div class="form-container">
                    <div class="form-header">
                        <h1 style="text-align: center; color: white;">
                            Why Am I Being Rejected?
                        </h1>
                    </div>
                    <form action="appointments.php" method="POST">
                        <textarea type="text" name="message" id="fullname" placeholder="Due to..." cols="67" rows="4" style="padding:10px" required></textarea>
                        <br>
                        <br>
                        <div class="bttn-container">
                            <input type="submit" value="Submit" name="submit"  id="btn">    
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="column2">
            <div class="greetings-container">
                <span class="greetings" id="greetings"></span>
                <?php 
                    // this is to call the name of the user with the session variable
                   
                    echo ucwords($_SESSION['name']) . '.';
                ?> 
            </div>
          
            <!-- 2.appointmets tab -->
            <div class="main-appointments-container" id="main-appointments-container">
                <div class="table-container"> 
                    <div class="sort-container">
                        <div class="sort">
                            <div class="recents-container">
                                <button class="sort-buttons" id="recents" name="recents">
                                    Recents
                                </button>
                            </div>
                            <div class="approved">
                                <a href="appointments-approved.php">
                                    <button class="sort-buttons" id="approved" name="approved">
                                        Approved
                                    </button>
                                </a>
                            </div>
                            <div class="rejected">
                                <a href="appointments-rejected.php">
                                    <button class="sort-buttons" id="rejected" name="rejected">
                                        Rejected
                                    </button>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="table" id="recents2">
                        <table>
                            <tr class="first-row">
                                <th>
                                    Owner
                                </th>
                                <th>
                                    Animal Type
                                </th>
                                <th>
                                    Appointment Type
                                </th>
                                <th>
                                    Time
                                </th>
                                <th>
                                    Date
                                </th>
                                <!-- <th>
                                    Approved
                                </th>-->
                                <th colspan="2" style="z-index:1">
                                    Actions
                                </th>
                            </tr>
                            <div class="recents-tab">
                                <?php
                                    // retrieve data for the user matching the phone number
                                    // if($fetch_rest2['phone'] == )
                                    // retrieving data from the database for the user to see
                                    $retrieve = "SELECT * FROM appointments where field = '".$_SESSION['field3']."' and approved='pending' and session_expiry = 'pending' ORDER BY ap_date ASC, ap_time ASC";
                                    $link = mysqli_query($conn, $retrieve);
                                    checkSQL($conn, $link);
                                    $row = mysqli_num_rows($link);
                                    if (!$link){
                                        die("Invalid query: " .$conn->error);
                                    }
                                   
                                    // reading data contained in each row
                                    while($row = $link->fetch_assoc()){
                                            $dateString = $row["ap_date"]; // Your date in YYYY-MM-DD format
                                            $date = strtotime($dateString); // Convert the string to a Unix timestamp
                                            $ap_date2 = date("j F Y", $date); // Format the date
                                            $phone = $row["phone"];
                                        ?>
                                        
                                        <tr>
                                        <td><?php echo $row["fullname"] ?></td>
                                        <td><?php echo $row["animal"] ?></td>
                                        <td><?php echo $row["ap_type"] ?></td>
                                        <td><?php echo $row["ap_time"] ?></td>
                                        <td><?php echo $ap_date2 ?></td>
                                        <td style="z-index:2"><a href="appointments-approve.php?approve=<?php echo $row['ap_id'] ?>"> <button class="edit"> Approve</button></a></td>
                                        <td style="z-index:2"><a href="appointments-reject2.php?reject=<?php echo $row['ap_id'] ?>"> <button class="cancel">Reject</button></a></td>
                                        </tr>
                                    <?php }
                                ?>
                            </div>
                        </table>
                    </div>
                </div> 
            </div>
        </div>
        
        <script src="jquery.js"></script>
        <script>
            $(function(){
                $(".table").css({"animation":"third-animation 1s forwards"});
                $(".recents-container").css({"animation":"slide-animation2 0.5s forwards"});
                $(".approved").css({"animation":"slide-animation 1s forwards"});
                $(".rejected").css({"animation":"slide-animation 1.5s forwards"});
            });
        </script>
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

        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth()+1; //January is 0 so need to add 1 to make it 1!
        var yyyy = today.getFullYear();
        if(dd<10){
        dd='0'+dd
        } 
        if(mm<10){
        mm='0'+mm
        } 

        today = yyyy+'-'+mm+'-'+dd;
        document.getElementById("date").setAttribute("min", today);

         // checkbox validation
        function validateForm(form) {

        var ap_type = document.getElementsByName("ap_type[]");

        var checked_ap_type = 0;
        
        for (var i = 0; i < ap_type.length; i++) {
            if (ap_type[i].checked) {
                checked_ap_type++;
            }
        }

  
        if (checked_ap_type == 0) {
            document.getElementById("msg").innerHTML = "Service is required";
            document.getElementById('msg').style.color="red";
            return false;
        }
        return true;
    }
    
        
    </script>
</body>
</html>