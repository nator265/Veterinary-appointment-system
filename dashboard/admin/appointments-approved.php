<?php
session_start();
include('../../connect.php');
include('../../functions.php');

if(!isset($_SESSION['name'])){
    header('location:../../login.php');
}

if(isset($_GET['approve'])){
    $_SESSION['phone2'] = $_GET['approve'];
    $phone2 = $_SESSION['phone2'];
    // to remove the appoitnment from the pending tab to the rejected tab.
    $removeqry = "UPDATE appointments 
        SET approved = 'approved' 
        WHERE phone = '$phone2'" ;
    $removelink = mysqli_query($conn, $removeqry);
    checkSQL($conn, $removelink);
    header('location:appointments-approved.php');

    // if(isset($_POST['submit'])){
    //     $message = $_POST['message'];       
    //     $reg = "INSERT INTO notifications(sender, title, message1, phone, reciever) VALUES ('".$_SESSION['name']."', 'Appointment has been rejected', '$message', '".$_SESSION['phone']."', '".$_SESSION['phone2']."')";
    //     $rest = mysqli_query($conn, $reg);
    //     checkSQL($conn, $rest);
    // }
}
if(isset($_GET['reject'])){
    $_SESSION['ap_id'] = $_GET['reject'];
    $ap_id = $_SESSION['phone2'];
    // to remove the appoitnment from the pending tab to the rejected tab.
    $removeqry = "UPDATE appointments 
        SET approved = 'rejected' 
        WHERE ap_id = '$ap_id'" ;
    $removelink = mysqli_query($conn, $removeqry);
    checkSQL($conn, $removelink);

    if(isset($_POST['submit'])){
        $message = $_POST['message'];       
        $reg = "INSERT INTO notifications(sender, title, message1, phone, reciever) VALUES ('".$_SESSION['name']."', 'Appointment has been rejected', '$message', '".$_SESSION['phone']."', '".$_SESSION['phone2']."')";
        $rest = mysqli_query($conn, $reg);
        checkSQL($conn, $rest);
    }
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
?>

<DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="appointments-approved.css">
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
                            Book An Appointment.
                        </h1>
                    </div>
                    <form action="appointments.php" method="POST" onsubmit="return validateForm(this);">
                        <input type="text" name="fullname" id="fullname" placeholder="Owner Name(Fullname)" required>
                        <br>
                        <br>
                        <span style="color:white;"> Select Animal Type</span>
                        <br>
                        <div class="type">
                            <select name="field" id="field" required>
                                <option value="pet">Pet</option>
                                <option value="livestock">Livestock</option>
                            </select>                        
                            <input type="text" name="animal" id="animal" placeholder="Pet e.g. Dog | Livestock e.g. Cow" required>
                        </div>
                        <br>
                        <input type="date" name="ap_date" id="date" required>
                        <br>
                        <div style="margin-bottom: 5px;" id="msg">Select Service:</div>
                        <input type="checkbox" class="checkboxes" name="ap_type[]" id="checkbox" value="Vaccination"> Vaccination <br>
                        <input type="checkbox" class="checkboxes" name="ap_type[]" id="checkbox" value="Check up"> Check up <br>
                        <input type="checkbox" class="checkboxes" name="ap_type[]" id="checkbox" value="Diet"> Diet<br>
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
                                <a href="appointments.php">
                                    <button class="sort-buttons" id="recents" name="recents">
                                        Recents
                                    </button>
                                </a>
                            </div>
                            <div class="approved">
                                <button class="sort-buttons" id="approved" name="approved">
                                    Approved
                                </button>
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
                                    Date
                                </th>
                                <!-- <th>
                                    Approved
                                </th>-->
                                <th style="z-index:1">
                                    Actions
                                </th>
                            </tr>
                            <div class="recents-tab">
                                <?php
                                    // retrieve data for the user matching the phone number
                                    // if($fetch_rest2['phone'] == )
                                    // retrieving data from the database for the user to see
                                    $query = "SELECT * from appointments where field = '".$_SESSION['field3']."' and approved='approved' and session_expiry = 'pending' ORDER BY ap_date asc, ap_time ASC";
                                        $approved_filter = mysqli_query($conn, $query);
                                        checkSQL($conn, $approved_filter);
                                        $row = mysqli_num_rows($approved_filter);
                                        if (!$approved_filter){
                                            die("Invalid query: " .$conn->error);
                                        }
                                        while($row = $approved_filter->fetch_assoc()){
                                            $dateString = $row["ap_date"]; // Your date in YYYY-MM-DD format
                                            $date = strtotime($dateString); // Convert the string to a Unix timestamp
                                            $ap_date2 = date("j F Y", $date); // Format the date
                                            $ap_id = $row["ap_id"];
                                        ?>
                                        
                                        <tr>
                                        <td><?php echo $row["fullname"] ?></td>
                                        <td><?php echo $row["animal"] ?></td>
                                        <td><?php echo $row["ap_type"] ?></td>
                                        <td><?php echo $ap_date2 ?></td>
                                        <td style="z-index:2"><a href="appointments-reject3.php?reject=<?php echo $row['ap_id']?>"><button class="action-buttons" id="reject-button" name="reject">Reject</button></a>
                                        </tr>
                                    <?php }
                                ?>
                            </div>
                        </table>
                    </div>
                </div>
                <!-- <div class="alert-container" id="target">
                    <div class="alert" id="alert">
                        <div class="warning-container">
                            <div class="warning-header">
                                DELETE
                            </div>
                            <div class="subtext">
                                Are you sure you want to delete the notification?
                            </div>
                            <form action="appointments.php" method="post">
                                    <div class="buttonsection">
                                        <?php
                                        $retrieve = "SELECT * FROM notifications Where phone = '" .$_SESSION['phone']."'";
                                        $link = mysqli_query($conn, $retrieve);
                                        checkSQL($conn, $link);
                                        ?>
                                        <a href="appointments.php?yes=<?php echo $_SESSION['this-id'] ?>">
                                            <input type="button" class="edit2" value="Yes" name="yes">
                                        </a>
                                        <a href="appointments.php">
                                            <input type="button" class="cancel2" value="No" name="no" id="noclearance">
                                        </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>  -->
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
    
    $(function(){
                $(".table").css({"animation":"third-animation2 1s forwards"});
                $(".recents-container").css({"animation":"slide-animation2 0.5s forwards"});
                $(".approved").css({"animation":"slide-animation 1s forwards"});
                $(".rejected").css({"animation":"slide-animation2 1.5s forwards"});
            })
    </script>
</body>
</html>