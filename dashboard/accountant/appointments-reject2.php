<?php
session_start();
include('../../connect.php');
include('../../functions.php');

if(!isset($_SESSION['name'])){
    header('location:../../login.php');
}

if(isset($_GET['reject'])){
$_SESSION['haltid'] = $_GET['reject'];
}

if(isset($_GET['yes'])){
    $ap_id  = $_GET['yes'];
    $query = "UPDATE appointments
            SET bill_status = 'Halted'
            where ap_id = $ap_id ";
    $linkquery = mysqli_query($conn, $query);
    checkSQL($conn, $linkquery);   

    $insertqry = "SELECT * from appointments where ap_id = '$ap_id'";
    $linkinsert = mysqli_query($conn, $insertqry);
    $fetchphone = mysqli_fetch_assoc($linkinsert);
    $phone = $fetchphone['phone'];

    $insertqry2 = "INSERT into notifications(sender,title,message1,phone, reciever) values ('".$_SESSION['name']."', 'Payment','Your transaction has been halted an not paid for.','".$_SESSION['phone']."','$phone')";
    $insertlink2 = mysqli_query($conn, $insertqry2);
    checkSQL($conn, $insertlink2);

    Header('location:appointments.php');
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
    <link rel="stylesheet" href="appointments-reject2.css">
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
                <input type="text" id="search" onkeyup="myFunction()" placeholder="Search for names.." title="Type in a name">
                <img src="images/search.webp" height="30px" width="30px" alt=" search">
                </div>
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
                                        Paid
                                    </button>
                                </a>
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
                                    Session Status
                                </th>
                                <th>
                                    Amount
                                </th>
                                <th colspan="2" style="z-index:1">
                                   Actions
                                </th>
                            </tr>
                            <div class="approved-tab" style="display:none">
                                <?php
                                    // retrieve data for the user matching the phone number
                                    // if($fetch_rest2['phone'] == )
                                    // retrieving data from the database for the user to see
                                    $approved = "yes";

                                    $retrieve = "SELECT appointments.fullname, appointments.animal, appointments.ap_type, appointments.ap_id, appointments.ap_date, appointments.session_expiry, appointments.total, doctors.fullname as 'name'
                                        from appointments INNER JOIN doctors on appointments.field = doctors.field  where appointments.session_expiry = 'Attended' and bill_status = 'Not paid' ORDER BY appointments.ap_date asc";

                                    $link = mysqli_query($conn, $retrieve);
                                    checkSQL($conn, $link);
                                    $row = mysqli_num_rows($link);
                                    if (!$link){
                                        die("Invalid query: " .$conn->error);
                                    }
                                    // reading data contained in each row
                                    while($row = $link->fetch_assoc()){
                                            $ap_date2 = date("d-m-Y", strtotime($row["ap_date"]));
                                            // $ap_id = $row["ap_id"];
                                        ?>
                                        <tr>
                                        <td><?php echo $row["fullname"] ?></td>
                                        <td><?php echo $row["animal"] ?></td>
                                        <td><?php echo $row["name"] ?></td>
                                        <td><?php echo $row["ap_type"] ?></td>
                                        <td><?php echo $ap_date2 ?></td>
                                        <td><?php echo $row["session_expiry"] ?></td>
                                        <td><?php echo 'K'.number_format($row["total"]) ?></td>
                                        <td style="z-index:2"><a href="appointments-approve.php?approve=<?php echo $row['ap_id'] ?>"> <button class="action-buttons" id="approve-button"> Paid</button></a></td>
                                        <td style="z-index:2"><a href="appointments-reject2.php?reject=<?php echo $row['ap_id'] ?>"> <button class="action-buttons" id="reject-button">Halt</button></a></td>
                                        </tr>
                                        
                                    <?php } ?>
                            </div>        
                        </table>
                    </div>
                </div> 
                <div class="alert-container" id="target">
                    <div class="alert" id="alert">
                        <div class="warning-container">
                            <div class="warning-header">
                                Halt
                            </div>
                            <div class="subtext">
                                Are you sure you want to halt this transaction?
                            </div>
                            <form action="appointments.php" method="post">
                                    <div class="buttonsection">
                                        <a href="appointments-reject2.php?yes=<?php echo $_SESSION['haltid'] ?>">
                                            <input type="button" class="edit2" value="Yes" name="yes">
                                        </a>
                                        <a href="appointments.php">
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
            $(function(){
                $(".alert").css({"animation":"second-animation 1s forwards"});
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