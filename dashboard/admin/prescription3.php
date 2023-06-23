<?php
session_start();
include('../../connect.php');
include('../../functions.php');

if(!isset($_SESSION['name'])){
    header('location:../../login.php');
}

if(isset($_GET['submit'])){
       $services = $_POST['services'];
       $selectedservices = implode(",",$services);
       $medicine = $_POST['medicine'];
       $selectedmedicine = implode(",", $medicine);

       $insert = "UPDATE diagnosis
                  SET service_provided = '$selectedservices', prescribed_meds = '$selectedmedicine'
                  WHERE ";
       $linkinsert = mysqli_query($conn, $insert);
       checkSQL($conn, $linkinsert);
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

if(isset($_GET['id']))(
    $_SESSION['animalphone'] = $_GET['id']
)
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
<body onload="openModal()">
   
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
                                    <?php
                                        $forname = "SELECT * FROM animal_details where phone = '".$_SESSION['preid']."'";
                                        $linkname = mysqli_query($conn, $forname);
                                        $fetchname = mysqli_fetch_assoc($linkname);
                                        $animalname = $fetchname['name'];
                                        echo $animalname;
                                    ?>
                                </div>
                            </div>
                            <div class="info">
                                <div class="holder">
                                    Age:
                                </div>
                                <div class="otherinfo">
                                    <?php
                                        $forage = "SELECT * FROM animal_details where phone = '".$_SESSION['preid']."'";
                                        $linkage = mysqli_query($conn, $forage);
                                        $fetchage = mysqli_fetch_assoc($linkage);
                                        $animalage = $fetchname['age'];
                                        echo $animalage;
                                    ?>
                                </div>
                            </div>
                            <div class="info">
                                <div class="holder">
                                    Breed:
                                </div>
                                <div class="otherinfo">
                                    <?php
                                        $forbreed = "SELECT * FROM animal_details where phone = '".$_SESSION['preid']."'";
                                        $linkbreed = mysqli_query($conn, $forbreed);
                                        $fetchbreed = mysqli_fetch_assoc($linkbreed);
                                        $animalbreed = $fetchname['breed'];
                                        echo $animalbreed;
                                    ?>
                                </div>
                            </div>
                            <div class="info">
                                <div class="holder">
                                    Weight:
                                </div>
                                <div class="otherinfo">
                                    <?php
                                        $forweight = "SELECT * FROM animal_details where phone = '".$_SESSION['preid']."'";
                                        $linkweight = mysqli_query($conn, $forweight);
                                        $fetchweight = mysqli_fetch_assoc($linkweight);
                                        $animalweight = $fetchname['weight'];
                                        echo $animalweight;
                                    ?>
                                </div>
                            </div>
                            <div class="info">
                                <div class="holder">
                                    Gender:
                                </div>
                                <div class="otherinfo">
                                    <?php
                                        $forgender = "SELECT * FROM animal_details where phone = '".$_SESSION['preid']."'";
                                        $linkgender = mysqli_query($conn, $forgender);
                                        $fetchgender = mysqli_fetch_assoc($linkgender);
                                        $animalgender = $fetchname['gender'];
                                        echo $animalgender;
                                    ?>
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
                                    <button  id="diagnose" id="btn" class="edit" title="Click to record patients signs and symptoms">Medical Diagnosis</button>
                                </div>
                                <div class="prescribe">
                                    <button id="btn" class="edit" title="Click here to prescribe medicine to patient" id="prescribe"> Prescribe Medicine</button>
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
       <!-- Modal HTML -->
       
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
        function searchMedicine() {
            var input, filter, container, checkboxes, i, label;
            input = document.getElementById("medicineSearch");
            filter = input.value.toUpperCase();
            container = document.getElementById("medicineContainer");
            checkboxes = container.getElementsByClassName("medicine-item");

            for (i = 0; i < checkboxes.length; i++) {
                label = checkboxes[i].getElementsByTagName("label")[0];
                if (label.innerHTML.toUpperCase().indexOf(filter) > -1) {
                checkboxes[i].style.display = "";
                } else {
                checkboxes[i].style.display = "none";
                }
            }
        }

        function closeModal() {
        document.getElementById("modal").style.display = "none";
        document.body.classList.remove("blur");
        }
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