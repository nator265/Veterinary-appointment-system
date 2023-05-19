<?php
session_start();
include('../../connect.php');
include('../../functions.php');

if(!isset($_SESSION['name'])){
    header('location:../../login.php');
}

if(isset($_GET['approve'])){
    $_SESSION['thisap_id'] = $_GET['approve'];
}
if(isset($_GET['yes'])){
    $ap_id  = $_GET['yes'];
    $query = "UPDATE appointments
            SET bill_status = 'Paid'
            where ap_id = $ap_id ";
    $linkquery = mysqli_query($conn, $query);
    checkSQL($conn, $linkquery);  

    $insertqry = "SELECT * from appointments where ap_id = '$ap_id'";
    $linkinsert = mysqli_query($conn, $insertqry);
    $fetchphone = mysqli_fetch_assoc($linkinsert);
    $phone = $fetchphone['phone'];

    $insertqry2 = "INSERT into notifications(sender,title,message1,phone, reciever) values ('".$_SESSION['name']."', 'Transaction has been paid for!','Your transaction has been Paid for successfully.','".$_SESSION['phone']."','$phone')";
    $insertlink2 = mysqli_query($conn, $insertqry2);
    checkSQL($conn, $insertlink2);
    Header('location:appointments-rejected.php'); 

    
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
                                <a href="appointments.php">
                                    <button class="sort-buttons" id="recents" name="recents">
                                        Recents
                                    </button>
                                </a>
                            </div>
                            <div class="approved">
                                <a href="appointments-approved.php">
                                    <button class="sort-buttons" id="approved" name="approved">
                                        Paid
                                    </button>
                                </a>
                            </div>
                            <div class="rejected">
                                <button class="sort-buttons" id="rejected" name="rejected">
                                    Halted Payments
                                </button>
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
                                    Amount
                                </th>
                                <th colspan="2" style="z-index:1">
                                   Actions
                                </th>
                            </tr>
                            <div class="recents-tab">
                                <?php
                                    // retrieving data from the database for the user to see
                                    $query = "SELECT * from appointments where bill_status = 'Halted' and session_expiry='Attended' ORDER BY ap_date asc";
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
                                        <td><?php echo $row["session_expiry"] ?></td>
                                        <td><?php echo "K".number_format($row["total"]) ?></td>
                                        <td style="z-index:2"><a href="appointments-approve2.php?reject=<?php echo $row['ap_id'] ?>"> <button class="action-buttons" id="reject-button">Halt</button></a></td>
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
                                Paid
                            </div>
                            <div class="subtext">
                                Has this appointment been paid for?
                            </div>
                            <form action="appointments.php" method="post">
                                    <div class="buttonsection">
                                        <a href="appointments-approve2.php?yes=<?php echo $_SESSION['thisap_id'] ?>">
                                            <input type="button" class="edit2" value="Yes" name="yes">
                                        </a>
                                        <a href="appointments-rejected.php">
                                            <input type="button" class="cancel2" value="No" name="no" id="noclearance">
                                        </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div> 
            </div>
        </div>
        
        <script src="jquery.js"></script>
        <script>
            $(function(){
                $(".alert").css({"animation":"second-animation 1s forwards"});
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