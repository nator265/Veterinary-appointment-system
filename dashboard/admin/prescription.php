<?php
session_start();
include('../../connect.php');
include('../../functions.php');

if(!isset($_SESSION['name'])){
    header('location:../../login.php');
}
if(isset($_GET['ap_id'])){
    $_SESSION['p_id'] = $_GET['ap_id'];
}
if(isset($_GET['pre']))(
    $_SESSION['preid'] = $_GET['pre']
);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $name = $_POST['name'];
    $age = $_POST['age'];
    $breed = $_POST['breed'];
    $weight = $_POST['weight'];
    $gender = $_POST['gender'];

    $phone = $_SESSION['preid'];

    $query = "SELECT * FROM animal_details WHERE name = '$name' AND gender = '$gender' and phone = '$phone'";
    $result = mysqli_query($conn, $query);

    // Check if the query returned any rows
    if (mysqli_num_rows($result) > 0) {
        // Data already exists, redirect to prescription2.php
        $update = "UPDATE animal_details
                    SET name = '$name', age = '$age', breed = '$breed', weight = '$weight', gender = '$gender'
                    WHERE phone = '$phone' and name = '$name'  AND gender = '$gender'";
        $linkupdate = mysqli_query($conn, $update);

        $retrieve = "SELECT * FROM animal_details where phone = '$phone' and name = '$name' and age = '$age' and weight = '$weight' and gender = '$gender' and breed = '$breed'";
        $linkretrieve = mysqli_query($conn, $retrieve);
        $fetchid = mysqli_fetch_assoc($linkretrieve);
        $id = $fetchid['animal_id'];
        $_SESSION['idforanimal'] = $id;

        header("Location: prescription2.php");
        exit;
    }else{
        // Escape special characters to prevent SQL injection
        $name = mysqli_real_escape_string($conn, $name);
        $age = mysqli_real_escape_string($conn, $age);
        $breed = mysqli_real_escape_string($conn, $breed);
        $weight = mysqli_real_escape_string($conn, $weight);

        // Prepare and execute the SQL query
        $query = "INSERT INTO animal_details (name, age, breed, weight, gender, phone) VALUES ('$name', '$age', '$breed', '$weight', '$gender', '$phone')";
        $link = mysqli_query($conn, $query);
        checkSQL($conn, $link);

        $retrieve2 = "SELECT * FROM animal_details where phone = '$phone' and name = '$name' and age = '$age' and weight = '$weight' and gender = '$gender' and breed = '$breed'";
        $linkretrieve2 = mysqli_query($conn, $retrieve2);
        $fetchid2 = mysqli_fetch_assoc($linkretrieve2);
        $id2 = $fetchid['animal_id'];
        $_SESSION['idforanimal'] = $id;
        // Redirect to the prescription2.php page

        header("Location: prescription2.php");

        exit();
    }

    
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
    <link rel="stylesheet" href="prescription.css">
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
                <a href="appointments.php"><span id='link'> Appointments <img src="images/appointments.png" alt="" height="20px"> </a>
                </div>
                <div class="link">
                    <a href="check-appointments.php"> <span id='link'> Checkoff Appointments <img src="images/total.png" alt="" height="20px"></span> </a>
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
           <div class="container">
                <div class="left">
                    <div class="userdetails">
                        <div class="usercontainer">
                            <div class="ownername">
                                <div class="nametitle">
                                    Owner Name
                                </div>
                                <div class="name">
                                    <?php
                                        $thisname = "SELECT * FROM users where phone = '".$_SESSION['preid']."'";
                                        $linkname = mysqli_query($conn, $thisname);
                                        $fetchname = mysqli_fetch_assoc($linkname);
                                        $ownername = $fetchname['fullname'];
                                        echo $ownername;
                                    ?>
                                </div>
                            </div>
                            <div class="contacts">
                                <div class="addy">
                                    <div class="nametitle">
                                        Address
                                    </div>
                                    <div class="name">
                                        <?php
                                            $thisaddress = "SELECT * FROM users where phone = '".$_SESSION['preid']."'";
                                            $linkaddress = mysqli_query($conn, $thisaddress);
                                            $fetchaddress = mysqli_fetch_assoc($linkaddress);
                                            $owneraddress = $fetchaddress['address'];
                                            echo $owneraddress;
                                        ?>
                                    </div>
                                </div>
                                <div class="line">
                                    <div class="nametitle">
                                        Phone
                                    </div>
                                    <div class="name">
                                        <?php
                                            $thisphone = "SELECT * FROM users where phone = '".$_SESSION['preid']."'";
                                            $linkphone = mysqli_query($conn, $thisphone);
                                            $fetchphone = mysqli_fetch_assoc($linkphone);
                                            $ownerphone = $fetchaddress['phone'];
                                            echo $ownerphone;
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="petdetails">
                        <div class="petcontainer"> 
                            <div class="Patientcont">
                                <div class="patient">
                                    Patient Details
                                </div>
                            </div>                           
                            <div class="info">
                                <div class="holder">
                                    Patient Name/ID/Tag:
                                </div>
                                <div class="otherinfo">

                                </div>
                            </div>
                            <div class="info">
                                <div class="holder">
                                    Age:
                                </div>
                                <div class="otherinfo">

                                </div>
                            </div>
                            <div class="info">
                                <div class="holder">
                                    Breed:
                                </div>
                                <div class="otherinfo">

                                </div>
                            </div>
                            <div class="info">
                                <div class="holder">
                                    Weight:
                                </div>
                                <div class="otherinfo">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="right">
                    <div class="rightcont">
                        <div class="righttop">
                            <div class="righttoptop">
                                <div class="reason">
                                    <span style="font-weight:14px;">Came in for:</span> <br>
                                    <span style="font-size:20px; font-weight:600;"><?php
                                        $reason = "SELECT * FROM appointments WHERE phone = '".$_SESSION['preid']."'";
                                        $reasonlink = mysqli_query($conn, $reason);
                                        $fetchservices = mysqli_fetch_assoc($reasonlink);
                                        $reason = $fetchservices['ap_type'];
                                        echo $reason;
                                    ?></span>
                                </div>
                                <div class="past">
                                    View Past Records
                                </div>
                            </div>
                            <div class="righttopbottom">
                                <div class="meddiag">
                                    <button id="btn" class="edit" title="Click to record patients signs and symptoms">Medical Diagnosis</button>
                                </div>
                                <div class="prescribe">
                                    <button id="btn" class="edit" title="Click here to prescribe medicine to patient"> Prescribe Medicine</button>
                                </div>
                            </div>
                        </div>
                        <div class="rightbottom">
                            <div class="bottomtitle">
                                Medical Prescriptions
                                <hr style="margin:10px 40px 0px 40px">
                            </div>
                            <div class="bottombody">
                                <div class="bottomleft">
                                    <div class="bottommintitle">
                                        Service Delivered
                                    </div>
                                    <div class="bottomminbody">

                                    </div>
                                </div>
                                <div class="bottomright">
                                    <div class="bottommintitle">
                                        Medicine Prescribed
                                    </div> 
                                    <div class="bottomminbody">

                                    </div>
                                </div>
                            </div>
                            <div class="bottomfooter">
                                <input type="submit" name="submit" value="Request Payment" class="edit" style="width: 25%;">
                            </div>
                        </div>
                    </div>
                </div>
           </div>
        </div>
        <!-- The modal -->
        <div id="myModal" class="modal">
            <!-- Modal content -->
            <div class="modal-container">
                <h2 style="text-align: center; margin-bottom:10px;">Pet Details</h2>
                <form method="post" action="prescription.php">
                    <div class="form-input">
                        <label for="name">Name/ID/Tag:</label>
                        <input type="text" id="name" name="name" class="input" required>
                    </div>
                    <div class="form-input">
                        <label for="age">Age:</label>
                        <input type="text" id="age" name="age" class="input" required>
                    </div>
                    <div class="form-input">
                        <label for="breed">Breed:</label>
                        <input type="text" id="breed" name="breed" class="input" required>
                    </div>
                    <div class="form-input">
                        <label for="weight">Weight(kg):</label>
                        <input type="text" id="weight" name="weight" class="input" oninput="this.value = this.value.replace(/[^0-9.]/g, '')" required>
                    </div>
                    <div class="form-input">
                        <label for="gender">Gender:</label>
                        <input type="radio" id="male" name="gender" value="Male" required>
                        <label for="male">Male</label>
                        <input type="radio" id="female" name="gender" value="Female" required>
                        <label for="female">Female</label>
                    </div>
                    <div style="text-align:center; margin-top:30px">
                        <input type="submit" value="Submit" class="edit" id="btn">
                    </div>
                </form>
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
        // Function to open the modal
        function openModal() {
            document.getElementById("myModal").style.display = "block";
        }

        // Function to close the modal
        function closeModal() {
            document.getElementById("myModal").style.display = "none";
        }

        // Open the modal when the page loads
        window.onload = function() {
            openModal();
        };

        // Close the modal when clicking outside of it
        window.onclick = function(event) {
            if (event.target == document.getElementById("myModal")) {
                closeModal();
            }
        }
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