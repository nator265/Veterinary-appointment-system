<?php
session_start();
include('../connect.php');
include('../functions.php');

if(!isset($_SESSION['name'])){
    header('location:../login.php');
}

if (isset($_GET['pre'])) {
    $_SESSION['preanimalid2'] = $_GET['pre'];
}
if (isset($_GET['diag_id'])) {
    $_SESSION['prediag_id'] = $_GET['diag_id'];
}
if(isset($_POST['submit'])){
    // Retrieve the form data
    $reportedSigns = $_POST['reported-signs'];
    $diagnosis = $_POST['diagnosis'];
    $services = $_POST['services'];
    $selectedservices = implode(",",$services);
    $medicine = $_POST['medicine'];
    $selectedmedicine = implode(",", $medicine);
    $dosage = $_POST['dosage'];

    $animal_id = $_SESSION['idforanimal'];
  
    $retrievename = "SELECT * FROM animal_details WHERE animal_id = '$animal_id'";
    $linkretrieve = mysqli_query($conn, $retrievename);
    $fetchname = mysqli_fetch_assoc($linkretrieve);
    $fetchedname = $fetchname['name'];

    $total = 0;
     foreach ($medicine as $service) {
         $service = mysqli_real_escape_string($conn, $service); // Prevent SQL injection
         $query = "SELECT price FROM medicine WHERE medicine_name = '$service'";
         $result = mysqli_query($conn, $query);
 
         if ($row = mysqli_fetch_assoc($result)) {
             $serviceCost = intval($row['price']); // Convert to integer
             $medicineprice += $serviceCost;
         }
     }
    
    $query = "INSERT INTO diagnosis (reported_signs, diagnosis, animal_id, animal_name, service_provided, prescribed_meds, dosage, price, ap_id) VALUES ('$reportedSigns', '$diagnosis', '$animal_id', '$fetchedname', '$selectedservices', '$selectedmedicine', '$dosage', '$medicineprice', '".$_SESSION['p_id']."')";
    $result = mysqli_query($conn, $query);
    $p_id = $_SESSION['p_id'];
    $totalupdate = "UPDATE appointments
                    SET total = '$medicineprice'
                    WHERE ap_id = '$p_id'";
    $linktotalupdate = mysqli_query($conn, $totalupdate);

    $selectall = "SELECT diag_id from diagnosis WHERE reported_signs = '$reportedSigns' AND diagnosis = '$diagnosis' AND animal_id = '$animal_id' AND service_provided = '$selectedservices' AND prescribed_meds = '$selectedmedicine' and dosage = '$dosage'";
    $linkselectall = mysqli_query($conn, $selectall);
    $fetchdiagid = mysqli_fetch_assoc($linkselectall);
    $fetchedid = $fetchdiagid['diag_id'];
    $_SESSION['fetchedid'] = $fetchedid;
    header('location:prescription2.php');
}

if (isset($_POST['request'])) {
    if (empty($_SESSION['fetchedid'])) {
        echo "N/A";
    } else {
        $p_id = $_SESSION['p_id'];
        $update = "UPDATE appointments
                    SET bill_status = 'requesting_payment' WHERE ap_id = '$p_id'";
        $linkupdate = mysqli_query($conn, $update);

        // // sending the data to the user inform of a receipt
        // $insert = "INSERT INTO notifications values "

        header('location:prescription2success.php');
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
                                        $thisname = "SELECT * FROM users where phone = '".$_SESSION['phone']."'";
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
                                            $thisaddress = "SELECT * FROM users where phone = '".$_SESSION['phone']."'";
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
                                            $thisphone = "SELECT * FROM users where phone = '".$_SESSION['phone']."'";
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
                                        $animal_id = $_SESSION['preanimalid2'];
                                        $forname = "SELECT * FROM animal_details where animal_id = '$animal_id'";
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
                                        $animal_id = $_SESSION['preanimalid2'];
                                        $forage = "SELECT * FROM animal_details where animal_id = '$animal_id'";
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
                                        $animal_id = $_SESSION['preanimalid2'];
                                        $forbreed = "SELECT * FROM animal_details where animal_id = '$animal_id'";
                                        $linkbreed = mysqli_query($conn, $forage);
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
                                        $animal_id = $_SESSION['preanimalid2'];
                                        $forweight = "SELECT * FROM animal_details where animal_id = '$animal_id'";
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
                                        $animal_id = $_SESSION['preanimalid2'];
                                        $forgender = "SELECT * FROM animal_details where animal_id = '$animal_id'";
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
                        <div class="box3">
                            <div class="left3">
                                <div class="top3">
                                    Report Signs
                                </div>
                                <div class="bottom3">
                                    <?php
                                        $diag_id = $_SESSION['prediag_id'];
                                        $selectsigns = "SELECT * FROM diagnosis WHERE diag_id = '$diag_id'";
                                        $linkselectsigns = mysqli_query($conn, $selectsigns);
                                        $fetchsigns = mysqli_fetch_assoc($linkselectsigns);
                                        $signs = $fetchsigns['reported_signs'];
                                        echo $signs;
                                    ?>
                                </div>
                            </div>
                            <div class="right3">
                                <div class="top3">
                                    Diagnosis
                                </div>
                                <div class="bottom3">
                                    <?php
                                        $diag_id = $_SESSION['prediag_id'];
                                        $selectdiag = "SELECT * FROM diagnosis WHERE diag_id = '$diag_id'";
                                        $linkselectdiag = mysqli_query($conn, $selectdiag);
                                        $fetchdiag = mysqli_fetch_assoc($linkselectdiag);
                                        $diag = $fetchdiag['diagnosis'];
                                        echo $diag;
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="box3">
                            <div class="left3">
                                <div class="top3">
                                    Services Provided
                                </div>
                                <div class="bottom3">
                                    <?php
                                        $diag_id = $_SESSION['prediag_id'];
                                        $selectservices = "SELECT * FROM diagnosis WHERE diag_id = '$diag_id'";
                                        $linkselectservices = mysqli_query($conn, $selectservices);
                                        $fetchservices = mysqli_fetch_assoc($linkselectservices);
                                        $services = $fetchservices['service_provided'];
                                        echo $services;
                                    ?>
                                </div>
                            </div>
                            <div class="right3">
                                <div class="top3">
                                    Medicine Prescribed
                                </div>
                                <div class="bottom3">
                                    <?php
                                        $diag_id = $_SESSION['prediag_id'];
                                        $selectmeds = "SELECT * FROM diagnosis WHERE diag_id = '$diag_id'";
                                        $linkselectmeds = mysqli_query($conn, $selectmeds);
                                        $fetchmeds = mysqli_fetch_assoc($linkselectmeds);
                                        $meds = $fetchmeds['prescribed_meds'];
                                        echo $meds;
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="box3">
                            <div class="left3">
                                <div class="top3">
                                    Dosage
                                </div>
                                <div class="bottom3">
                                    <?php
                                        $diag_id = $_SESSION['prediag_id'];
                                        $selectDosage = "SELECT * FROM diagnosis WHERE diag_id = '$diag_id'";
                                        $linkselectDosage = mysqli_query($conn, $selectDosage);
                                        $fetchDosage = mysqli_fetch_assoc($linkselectDosage);
                                        $Dosage = $fetchDosage['prescribed_meds'];
                                        echo $Dosage;
                                    ?>
                                </div>
                            </div>
                            <div class="right3">
                                <div class="top3">
                                    Additional Notes
                                </div>
                                <div class="bottom3">
                                    You are welcome to visit our clinic whenever you need a service
                                </div>
                            </div>
                        </div>
                        <div class="box3">
                            <div class="left3">
                                <div class="top3">
                                    Price
                                </div>
                                <div class="bottom3">
                                    <?php
                                        $diag_id = $_SESSION['prediag_id'];
                                        $selectprice = "SELECT * FROM diagnosis WHERE diag_id = '$diag_id'";
                                        $linkselectprice = mysqli_query($conn, $selectprice);
                                        $fetchprice = mysqli_fetch_assoc($linkselectprice);
                                        $price = $fetchprice['price'];
                                        echo $price;
                                    ?>
                                </div>
                            </div>
                            <div class="right3">
                                <div class="top3">
                                    Bill Status
                                </div>
                                <div class="bottom3">
                                    PAID
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
           </div>
        </div>

        <form action="prescription2.php" method="POST">
            <!-- Modal -->
            <div id="modal2" class="modal2">
            <!-- Modal content -->
                <div class="modal-content">
                    <h2 style="text-align: center; margin: 10px 10px 30px 10px">Diagnose Patient</h2>
                    
                        <div class="form-input2">
                            <label for="reported-signs" class="label">Reported Signs:</label>
                            <textarea id="reported-signs" name="reported-signs" rows="5" placeholder="Enter reported signs..."></textarea>
                        </div>
                        <div class="form-input2">
                            <label for="diagnosis" class="label">Diagnosis:</label>
                            <textarea id="diagnosis" name="diagnosis" rows="5" placeholder="Enter diagnosis..."></textarea>
                        </div>
                        <div class="form-input2">
                            <div  style="display:flex; justify-content:center; padding:10px">
                                <div id="submitbtn" class="edit" id="btn" style="width:40%; display:flex; justify-content:center; align-items:center; padding-top:0px">Prescribe Medicine</div>
                            </div>
                        </div>
                </div>
            </div>   
            <div id="modal3" class="modal3">
                <div class="modal-content3">
                    <div class="columns">
                        <div class="columnleft">
                            <h2 style="padding:20px;">Service(s) Provided</h2>
                            <div class="checkbox-flex-container">
                                <?php
                                $query = "SELECT * FROM service_costs";
                                $result = mysqli_query($conn, $query);

                                if(mysqli_num_rows($result) > 0) {
                                $count = 0;

                                while($row = mysqli_fetch_assoc($result)) {
                                    // Break to a new line after every 3 checkboxes
                                    if($count % 3 == 0 && $count != 0) {
                                    echo "<br>";
                                    }

                                    $name = $row['servicename'];

                                    echo '<div class="checkbox-flex-item"><label><input type="checkbox" name="services[]" value="' . $name . '" style="margin-right:5px">' . $name . '</label></div>';

                                    $count++;
                                }
                                }
                                ?>
                            </div>
                        </div>
                        <div class="columnright">
                            <h2 style="padding:20px;">Medicine</h2>
                            <input type="text" id="medicineSearch" placeholder="Search Medicine" oninput="searchMedicine()">
                            <div class="checkbox-flex-container" id="medicineContainer">
                                <?php
                                    $medicineQuery = "SELECT * FROM medicine";
                                    $medicineResult = mysqli_query($conn, $medicineQuery);

                                    if(mysqli_num_rows($medicineResult) > 0) {
                                    while($medicineRow = mysqli_fetch_assoc($medicineResult)) {

                                        if($count % 3 == 0 && $count != 0) {
                                            echo "<br>";
                                            }
                                        $medicineName = $medicineRow['medicine_name'];

                                        echo '<div class="checkbox-flex-item medicine-item"><label><input type="checkbox" name="medicine[]" value="' . $medicineName . '" style="margin-right:5px">' . $medicineName . '</label></div>';
                                    }
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                    <label for="dosage">Dosage:</label>
                    <input type="text" id="dosage" name="dosage" required>
                    <div style="text-align: center; margin-top: 30px;">
                    <input type="submit" name="submit" value="Submit" class="edit" id="submitBtn">
                    </div>
                </div>
            </div>
        </form> 
        <!-- The modal -->
        <div id="myModal4" class="modal4">
        <!-- Modal content -->
            <div class="alert" id="alert">
                <div class="warning-container">
                    <div class="warning-header">
                        Send Request?
                    </div>
                    <div class="subtext">
                        Are you sure you want to send payment request for this appointment
                    </div>
                    <form action="prescription2.php" method="post">
                        <div class="buttonsection">

                            <input type="submit" class="edit2" value="Yes" name="request">

                            <a href="prescription2.php">
                                <input type="button" class="cancel2" value="No" name="no" id="noclearance">
                            </a>
                        </div>
                    </form>
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
<script>
  // Get the modal element
  var modal3 = document.getElementById('modal2');

  // Get the button that opens the modal
  var btn3 = document.getElementById('diagnose');
  var btn4 = document.getElementById('submitbtn');

  // When the user clicks the button, open the modal
  btn3.onclick = function() {
    modal3.style.display = 'flex';
  }
 

  // When the user clicks anywhere outside the modal, close it
  // When the user clicks anywhere outside the second modal, close it
    modal2.onclick = function(event) {
  // Check if the event target is not a child of the second modal
  if (event.target == this) {
    // Close the second modal
    this.style.display = 'none';
  }
}
</script>
<!-- JavaScript to handle the modal -->
<script>
// Function to open the modal
function openModal() {
  var modal = document.getElementById("myModal4");
  modal.style.display = "flex";
}

// Function to close the modal
function closeModal() {
  var modal = document.getElementById("myModal4");
  modal.style.display = "none";
}
</script>
<script>
  // Get the modal element
  var modal2 = document.getElementById('modal3');

  // Get the button that opens the modal
  var btn2 = document.getElementById('prescribe');

  // When the user clicks the button, open the modal
  btn2.onclick = function() {
    modal2.style.display = 'flex';
  }
  btn4.onclick = function() {
    modal2.style.display = 'flex';
  }

  // When the user clicks anywhere outside the modal, close it
  window.onclick = function(event) {
    if (event.target == modal2) {
      modal2.style.display = 'none';
    }
  }
</script>

</body>
</html>