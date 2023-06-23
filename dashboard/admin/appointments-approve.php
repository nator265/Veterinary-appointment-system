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
?>

<DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="appointments-approve.css">
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
                                    $retrieve = "SELECT * FROM appointments where field = '".$_SESSION['field3']."' and approved='pending' ORDER BY ap_date desc";
                                    $link = mysqli_query($conn, $retrieve);
                                    checkSQL($conn, $link);
                                    $row = mysqli_num_rows($link);
                                    if (!$link){
                                        die("Invalid query: " .$conn->error);
                                    }
                                   
                                    // reading data contained in each row
                                    while($row = $link->fetch_assoc()){
                                            $ap_date2 = date("d-m-Y", strtotime($row["ap_date"]));
                                            $phone = $row["phone"];
                                        ?>
                                        
                                        <tr>
                                        <td><?php echo $row["fullname"] ?></td>
                                        <td><?php echo $row["animal"] ?></td>
                                        <td><?php echo $row["ap_type"] ?></td>
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
                <div class="alert-container" id="target">
                    <div class="alert" id="alert">
                        <div class="warning-container">
                            <div class="warning-header">
                                Approve
                            </div>
                            <div class="subtext">
                                Are you sure you want to approve this appointment
                            </div>
                            <form action="appointments.php" method="post">
                                    <div class="buttonsection">
                                        <a href="appointments.php?yes=<?php echo $_SESSION['thisap_id'] ?>">
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