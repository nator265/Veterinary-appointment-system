<?php
session_start();
include('../connect.php');
include('../functions.php');

if(!isset($_SESSION['name'])){
    header('location:../login.php');
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
        SET approved = 'Approved' 
        WHERE ap_id = $approvedPhone";
    $removelink = mysqli_query($conn, $removeqry);
    header('location:appointments.php');    
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
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="check-appointments.css">
    <title>Appointments</title>
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

        <!-- thi is the modal for the appointments registration -->

        <div class="column2">
          
            <!-- 2.appointmets tab -->
            <div class="main-appointments-container" id="main-appointments-container">
                <div class="theader">
                    <div class="tbuttons">
                        <h1 style="color:white">Receipts</h1>
                    </div>
                    
                </div>
                
                <div class="table-container">
                <div class="table" id="recents2">
                        <table id="myTable">
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
                            </tr>
                            <div class="recents-tab">
                                <?php
                                    // retrieve data for the user matching the phone number
                                    // if($fetch_rest2['phone'] == )
                                    // retrieving data from the database for the user to see
                                    $currentDate = date('Y-m-d'); // Get the current date
                                    $currentTime = date('H:i:s', strtotime('-30 minutes'));
                                    
                                    $query = "SELECT * FROM appointments WHERE bill_status = 'paid' ORDER BY ap_date ASC";

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
                                            $select = "SELECT * FROM diagnosis WHERE ap_id ='$ap_id'";
                                            $linkselect = mysqli_query($conn, $select);
                                            $fetchdiagid = mysqli_fetch_assoc($linkselect);
                                            $thediagid = $fetchdiagid['diag_id'];
                                            $kaid = $row["animal_id"];
                                        ?>
                                        
                                        <tr onclick="window.location.href='receipt.php?pre=<?php echo $kaid; ?>&diag_id=<?php echo $thediagid?>';" style="cursor: pointer;" onmouseover="this.style.backgroundColor='#CCCCCC';" onmouseout="this.style.backgroundColor='transparent';">
                                        <td><?php echo $row["fullname"] ?></td>
                                        <td><?php echo $row["animal"] ?></td>
                                        <td><?php echo $row["ap_type"] ?></td>
                                        <td><?php echo $row["ap_time"] ?> </td>
                                        <td><?php echo $ap_date2?></td>
                                        <!-- <td style="z-index:2"><a href="check-off.php?checked=<?php echo $row['ap_id'] ?>"> <button class="edit">Attended</button></a></td> -->
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
    function myFunction() {
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("search");
  filter = input.value.toUpperCase();
  table = document.getElementById("myTable");
  tr = table.getElementsByTagName("tr");
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[0];
    if (td) {
      txtValue = td.textContent || td.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }       
  }
}
    
        
    </script>
</body>
</html>