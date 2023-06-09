<?php
session_start();
include('../../connect.php');
include('../../functions.php');

if(isset($_POST['submit'])){
    if($_SESSION['selectedYear'] = date('Y')){
        $_SESSION['selectedYear'] = $_POST['selectedYear'];
    }
    $_SESSION['selectedYear'] = $_POST['selectedYear'];
    header('location:check-appointments.php');
}
if(empty($_POST['selectedYear'])){
    $_SESSION['selectedYear'] = date('Y');
}else{
    $_SESSION['selectedYear'] = $_POST['selectedYear'];
}
if(!isset($_SESSION['name'])){
    header('location:../../login.php');
}
if(isset($_GET['selectedmonth'])){
    $_SESSION['monthz'] = $_GET['selectedmonth'];  
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
    <link rel="stylesheet" href="monthly.css">
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
                    <a href="dashboard.php"> <span id='link'> Dashboard <img src="images/dashboard.png" alt="" height="20px"></span> </a>
                </div>
                <div class="link">
                <a href="appointments.php"><span id='link'> Appointments <img src="images/appointments.png" alt="" height="20px"></span> </a>
                </div>
                <div class="link">
                    <a href="check-appointments.php"> <span id='link'> Total Transactions <img src="images/total.png" alt="" height="20px"></span> </a>
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

        <div class="column2">
            <div class="header">
                <div class="pagetitle" style="margin-right:50px; margin-left:60px">
                    <?php
                        $figure = "SELECT * from total_transactions where month1 = '".$_SESSION['monthz']."'";
                        $linkfigure = mysqli_query($conn, $figure);
                        $total = 0;
                        while($row = mysqli_fetch_assoc($linkfigure)) {
                            $total += $row['total'];
                        }
                    ?>
                    <div class="toppest">
                        Total for <?php echo $_SESSION['monthz']?>
                    </div>
                    <div class="bottomest">
                        K<?php echo number_format($total)?>
                    </div>
                </div>
                <div class="search-container" style="float:right; margin-right:25px">
                <input type="text" id="search" onkeyup="myFunction()" placeholder="Search for names.." title="Type in a name">
                <img src="images/search.webp" height="30px" width="30px" alt=" search">
                </div>
            </div>
            <div class="table-container"> 
                <div class="table" >
                    <table id="myTable">
                        <tr class="first-row">
                            <th>
                                Owner
                            </th>
                            <th>
                                Animal Type
                            </th>
                            <th>
                                Doctor
                            </th>
                            <th>
                                Appointment Type
                            </th> 
                            <th>
                                Date
                            </th>
                            <th>
                                Total
                            </th>
                            <th>
                                Session Status
                            </th>
                        </tr>
                        <div class="approved-tab" style="display:none">
                            <?php
                                // retrieve data for the user matching the phone number
                                // if($fetch_rest2['phone'] == )
                                // retrieving data from the database for the user to see
                                $approved = "yes";

                                $retrieve = "SELECT appointments.fullname, appointments.animal, appointments.ap_type, appointments.ap_id, appointments.ap_date, appointments.session_expiry, appointments.bill_status, appointments.total, doctors.fullname AS 'name'
                                FROM appointments
                                INNER JOIN doctors ON appointments.field = doctors.field
                                WHERE appointments.session_expiry = 'attended' AND appointments.bill_status = 'Paid' AND MONTH(appointments.ap_date) = MONTH(STR_TO_DATE('" . $_SESSION['monthz'] . "', '%M')) -- Compare month only
                                ORDER BY appointments.ap_date ASC, appointments.ap_time ASC";
                                
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
                                    ?>
                                    <tr>
                                    <td><?php echo $row["fullname"] ?></td>
                                    <td><?php echo $row["animal"] ?></td>
                                    <td><?php echo $row["name"] ?></td>
                                    <td><?php echo $row["ap_type"] ?></td>
                                    <td><?php echo $ap_date2 ?></td>
                                    <td><?php echo "K". number_format($row["total"]);?></td>
                                    <td><?php echo $row["bill_status"] ?></td>
                                    <!-- <td><button class="action-buttons" id="approve-button" value="<?php echo $ap_id ?>" onclick="approve(this.value)">Approve</a></button>
                                    <td><button class="action-buttons" id="reject-button" name="reject">Reject</a></button> -->
                                    </tr>
                                    
                                <?php } ?>
                        </div>        
                    </table>
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
            var selectedYear; // Variable to store the selected value
            
            var yearDropdown = document.getElementById('yearDropdown');
            
            yearDropdown.addEventListener('change', function() {
                selectedYear = this.value; // Update the selectedYear variable with the new value
                
                // Send the selectedYear value to the server using AJAX
                // Or submit a form to send the value to the server
                // Here, I'm using AJAX as an example
                
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'process_year.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    // Response received from the server
                    console.log(xhr.responseText); // Optional: Print the server response to the console
                }
                };
                xhr.send('selectedYear=' + selectedYear);
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