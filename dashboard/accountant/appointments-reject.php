<?php
session_start();
include('../../connect.php');
include('../../functions.php');

if(!isset($_SESSION['name'])){
    header('location:../../login.php');
}

if(isset($_GET['reject'])){
    $_SESSION['thisap_id'] = $_GET['reject'];
}

if(isset($_GET['yes'])){
    $ap_id  = $_GET['yes'];
    $query = "UPDATE appointments
            SET bill_status = 'Halted'
            where ap_id = $ap_id ";
    $linkquery = mysqli_query($conn, $query);
    checkSQL($conn, $linkquery);   
    Header('location:appointments-approved.php');

    $insertqry = "SELECT * from appointments where ap_id = '$ap_id'";
    $linkinsert = mysqli_query($conn, $insertqry);
    $fetchphone = mysqli_fetch_assoc($linkinsert);
    $phone = $fetchphone['phone'];

    $insertqry2 = "INSERT into notifications(sender,title,message1,phone, reciever) values ('".$_SESSION['name']."', 'transaction has been reversed!','Your transaction has been reversed by the accountant, reply to the accountant to know more.','".$_SESSION['phone']."','$phone')";
    $insertlink2 = mysqli_query($conn, $insertqry2);
    checkSQL($conn, $insertlink2);
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
    <link rel="stylesheet" href="appointments-approved2.css">
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
                    <a href="check-appointments.php"><span id='link'> Total Transactions </span> </a>
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
                <div class="search-container">
                    <input type="text" id="search" onkeyup="myFunctions()" placeholder="Search for names.." title="Type in a name">
                    <img src="images/search.webp" height="30px" width="30px" alt=" search">
                </div>
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
                                    Paid
                                </button>
                            </div>
                            <div class="rejected">
                                <a href="appointments-rejected.php">
                                    <button class="sort-buttons" id="rejected" name="rejected">
                                        Halted Payments
                                    </button>
                                </a>
                            </div>
                        </div>
                    </div>
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
                                    Date
                                </th>
                                <th>
                                    Session Status
                                </th>
                                <th>
                                    Total
                                </th>
                                <th colspan="2" style="z-index:1">
                                   Actions
                                </th>
                            </tr>
                            <div class="recents-tab">
                                <?php
                                    // retrieve data for the user matching the phone number
                                    // if($fetch_rest2['phone'] == )
                                    // retrieving data from the database for the user to see
                                    $query = "SELECT * from appointments where bill_status = 'Paid' and session_expiry = 'Attended' ORDER BY ap_date asc";
                                        $approved_filter = mysqli_query($conn, $query);
                                        checkSQL($conn, $approved_filter);
                                        $row = mysqli_num_rows($approved_filter);
                                        if (!$approved_filter){
                                            die("Invalid query: " .$conn->error);
                                        }
                                        while($row = $approved_filter->fetch_assoc()){
                                            $ap_date2 = time_elapsed_string($row["ap_date"]);
                                            $ap_id = $row["ap_id"];
                                        ?>
                                        
                                        <tr>
                                        <td><?php echo $row["fullname"] ?></td>
                                        <td><?php echo $row["animal"] ?></td>
                                        <td><?php echo $row["ap_type"] ?></td>
                                        <td><?php echo $row['ap_date']?></td>
                                        <td><?php echo $row["session_expiry"] ?></td>
                                        <td><?php echo "K".number_format($row["total"]) ?></td>
                                        <td style="z-index:2"><a href="appointments-reject.php?reject=<?php echo $row['ap_id'] ?>"> <button class="action-buttons" id="reject-button">Reverse</button></a></td>
                                        </tr>
                                    <?php }
                                ?>
                            </div>
                        </table>
                    </div>
                </div>
                <div class="alert-container" id="target">
                    <div class="alert" id="alert">
                        <div class="warning-container">
                            <div class="warning-header">
                                Reverse
                            </div>
                            <div class="subtext">
                                Are you sure you want to reverse this transaction?
                            </div>
                            <form action="appointments.php" method="post">
                                    <div class="buttonsection">
                                        <a href="appointments-reject.php?yes=<?php echo $_SESSION['thisap_id'] ?>">
                                            <input type="button" class="edit2" value="Yes" name="yes">
                                        </a>
                                        <a href="appointments-approved.php">
                                            <input type="button" class="cancel2" value="No" name="no" id="noclearance">
                                        </a>
                                </div>
                            </form>
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
                $(".alert").css({"animation":"second-animation 1s forwards"});
            });
    
    function myFunctions() {
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