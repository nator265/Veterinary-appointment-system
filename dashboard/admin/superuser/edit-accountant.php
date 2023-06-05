<?php
session_start();
include('../../../connect.php');
include('../../../functions.php');

if(!isset($_SESSION['name'])){
    header('location:../../../login.php');
}

if(isset($_POST['submit'])){
    $fullname = $_POST['fullname'];
    $field = $_POST['field'];
    $date = $_POST['ap_date'];
    $animal = $_POST['animal'];
    $ap_type = $_POST['ap_type'];
    $_SESSION['field2'] = $field;
    // converting the ap_type to string
    $allaptype = implode(", ", $ap_type);
    // inserting data into the appointments table in the database
    $reg = "INSERT INTO appointments(fullname, field, animal, ap_date, ap_type, phone) VALUES ('$fullname', '$field', '$animal', '$date', '$allaptype', '".$_SESSION['phone']."')";
                            
    $rest = mysqli_query($conn, $reg);
    
    checkSQL($conn, $rest);
}
if(isset($_POST['edit'])){

    $fullname = $_POST['fullname'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    
    // inserting data into the appointments table in the database
    $update = "UPDATE accountant SET address = '$address', password='$password', fullname = '$fullname', phone = '$phone' where phone = '".$_SESSION['values2']."' ";
    mysqli_query($conn, $update);
    // header('location:edit-doctor.php');   
}
// this is the test date function for the one i found on youtube
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
    <link rel="stylesheet" href="doctors.css">
    <title>Doctors</title>
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
                    <a href="profiles.php"><span id='link'> Profiles <img src="images/user-small.png" alt="" height="20px"></span></a>
                </div>
                <div class="link">
                    <a href="appointments.php"><span id='link'> Appointments  <img src="images/appointments.png" alt="" height="20px"></span></a>
                </div>
                <div class="link">
                    <a href="settings.php"><span id='link'> Settings <img src="images/settings.png" alt="" height="20px"></span> </a>
                </div>
                <div class="link">
                    <a href="../../logout.php" style="text-decoration: none; color: white">
                        <button class="logout" id="bttn">Logout</button>
                    </a>
                </div>
            </div>
        </div>

        <div class="column2">
            <div class="greetings-container" style="padding-right: 20px">
               <a href="edit-profiles.php" style="text-decoration:underline"> <-- Previous Page </a>
            </div>
          
            <!-- 2.appointmets tab -->
            <div class="main-appointments-container" id="main-appointments-container">
                 <div class="create">
                    <a href="add-accountant.php">
                        <button class="create" id="bttn" onclick="document.getElementById('modal-container').style.display='flex'" style="border-radius: 5px; font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif; font-weight: 100;"> Add an accountant </button>
                    </a>                
                </div>
                <div class="table-container"> 
                    <div class="table">
                        <table>
                            <tr class="first-row">
                                <th style="width:200px">
                                    Accountants Name
                                </th>
                                <th>
                                    Phone
                                </th>
                                <th>
                                    Address
                                </th>
                                <th>
                                    Date joined
                                </th>
                                <th colspan=2 style="z-index: 2;">
                                    Actions
                                </th>
                            </tr>
                            <div class="approved-tab">
                                <?php
                                    // retrieve data for the user matching the phone number
                                    // if($fetch_rest2['phone'] == )
                                    // retrieving data from the database for the user to see
                                    $retrieve = "SELECT * FROM accountant ORDER BY date_joined DESC";
                                    $link = mysqli_query($conn, $retrieve);
                                    checkSQL($conn, $link);
                                    $row = mysqli_num_rows($link);
                                    if (!$link){
                                        die("Invalid query: " .$conn->error);
                                    }
                                    // reading data contained in each row
                                    while($row = $link->fetch_assoc()){
                                            // $dateJ = date("Y-m-d H:i:s", strtotime($row["date_joined"]));
                                            $datenow = $row['date_joined'];
                                           $dateJ = explode('.', $datenow);
                                           
                                           
                                        ?>
                                        <tr>
                                        <td><?php echo $row["fullname"] ?></td>                                        
                                        <td><?php echo $row["phone"] ?></td>
                                        <td><?php echo $row["address"] ?></td>
                                        <td><?php echo time_elapsed_string($row["date_joined"]) ?></td>
                                        <td>
                                            <a href="edit-accountant2.php?edit=<?php echo $row['phone']?>">
                                                <button class="action-buttons" id="approve-button" name="reject">Edit</button>
                                            </a>
                                        </td>
                                        </tr>
                                        
                                    <?php } ?>
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

    // changing the dates form-container
    
        
    </script>
</body>
</html>