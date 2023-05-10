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
    $reg = "INSERT INTO notifications(sender, title, message1, phone, reciever) VALUES ('".$_SESSION['name']."', 'The rejected appointment has been approved', '$message', '".$_SESSION['phone']."', '$phone')";
    $rest = mysqli_query($conn, $reg);
    checkSQL($conn, $rest);

    // to remove the appoitnment from the pending tab to the rejected tab.
    $removeqry = "UPDATE appointments 
        SET approved = 'approved' 
        WHERE ap_id = $approvedPhone";
    $removelink = mysqli_query($conn, $removeqry);
    header('location:appointments-rejected.php');        
}

if(isset($_POST['re-submit'])){
    $fullname = $_POST['fullname'];
    $field = $_POST['field'];
    $date = $_POST['ap_date'];
    $animal = $_POST['animal'];
    $ap_type = $_POST['ap_type'];
    $_SESSION['field2'] = $field;

    // converting the ap_type to string
    $allaptype = implode(", ", $ap_type);
    
    // inserting data into the appointments table in the database
    $update = "UPDATE appointments SET fullname = '$fullname', field = '$field', ap_date = '$date', animal = '$animal', ap_type = '$allaptype' where ap_id = '".$_SESSION['id']."' ";
    mysqli_query($conn, $update);
    // header('location:appointments.php');
    
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
    <link rel="stylesheet" href="appointments-rejected.css">
    <title>Appointments</title>
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
                    <span id='link'> Appointments </span>
                </div>
                <div class="link">
                    <a href="check-appointments.php"><span id='link'> Check-off appointment </span> </a>
                </div>
                <div class="link">
                    <a href="notifications.php"><span id='link'> Notifications </span> </a>
                </div>
                <div class="link">
                    <a href="../logout.php" style="text-decoration: none; color: white">
                        <button class="logout" id="bttn">Logout</button>
                    </a>
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
                                <a href="appointments-approved.php">
                                    <button class="sort-buttons" id="approved" name="approved">
                                        Approved
                                    </button>
                                </a>
                            </div>
                            <div class="rejected">
                                <button class="sort-buttons" id="rejected" name="rejected">
                                    Rejected
                                </button>
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
                                <th colspan="2" style="z-index:1">
                                    Actions
                                </th>
                            </tr>
                            <div class="recents-tab">
                                <?php
                                    // retrieve data for the user matching the phone number
                                    // if($fetch_rest2['phone'] == )
                                    // retrieving data from the database for the user to see
                                    $query = "SELECT * from appointments where field = '".$_SESSION['field3']."' and approved='rejected' and session_expiry='pending' ORDER BY ap_date asc";
                                        $approved_filter = mysqli_query($conn, $query);
                                        checkSQL($conn, $approved_filter);
                                        $row = mysqli_num_rows($approved_filter);
                                        if (!$approved_filter){
                                            die("Invalid query: " .$conn->error);
                                        }
                                        while($row = $approved_filter->fetch_assoc()){
                                           
                                            $ap_id = $row["ap_id"];
                                        ?>
                                        
                                        <tr>
                                        <td><?php echo $row["fullname"] ?></td>
                                        <td><?php echo $row["animal"] ?></td>
                                        <td><?php echo $row["ap_type"] ?></td>
                                        <td><?php echo $row['ap_date'] ?></td>
                                        <td style="z-index:2"><a href="appointments-approve-rejected.php?approve=<?php echo $row['ap_id']?>"><button class="action-buttons" id="approve-button" value="<?php echo $ap_id ?>">Approve</button></a>
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
                $(".create").css({"animation":"second-animation 1s forwards"});
                $(".table").css({"animation":"third-animation 1s forwards"});
                $(".recents-container").css({"animation":"slide-animation 0.5s forwards"});
                $(".approved").css({"animation":"slide-animation 1s forwards"});
                $(".rejected").css({"animation":"slide-animation2 1.5s forwards"});
            })
            
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